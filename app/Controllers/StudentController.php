<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\SubjectAssignment;
use App\Core\Session;

class StudentController extends Controller
{
    protected $assignmentModel;

    public function __construct()
    {
        if (!Session::get('user_id') || Session::get('role') !== 'student') {
            $this->redirect('/EDUTEN2/login');
        }
        $this->assignmentModel = new SubjectAssignment();
    }

    public function dashboard()
    {
        $student_id = Session::get('user_id');
        $subjects = $this->assignmentModel->getByStudentWithDetails($student_id);

        $this->view('student/dashboard', [
            'title' => 'Beranda Siswa - EDUTEN',
            'subjects' => $subjects
        ]);
    }

    public function classes()
    {
        // Re-using the same logic for now as 'My Classes' view in translation
        // but now it dynamic based on assignments
        $student_id = Session::get('user_id');
        $subjects = $this->assignmentModel->getByStudentWithDetails($student_id);

        $this->view('student/classes', [
            'title' => 'Mata Pelajaran Saya - EDUTEN',
            'subjects' => $subjects
        ]);
    }

    public function classroom($params)
    {
        $student_id = Session::get('user_id');
        $assignment_id = $params['id'];
        $assignment = $this->assignmentModel->findWithDetails($assignment_id);

        if (!$assignment) {
            $this->redirect('/EDUTEN2/student/dashboard');
        }

        $materialModel = new \App\Models\Material();
        $quizModel = new \App\Models\Quiz();
        $progressModel = new \App\Models\StudentProgress();
        $attemptModel = new \App\Models\QuizAttempt();

        $materials = $materialModel->getByAssignment($assignment_id);

        // Attach quizzes and progress to each material
        $completedCount = 0;
        $passedQuizzesCount = 0;

        $now = date('Y-m-d H:i:s');

        foreach ($materials as $index => &$m) {
            // Get quiz for this material (assuming 1 quiz per material for now)
            $quizzes = $quizModel->getByMaterial($m['id']);
            $m['quiz'] = !empty($quizzes) ? $quizzes[0] : null;

            // Get student progress
            $progress = $progressModel->getProgress($student_id, $m['id']);
            $m['status'] = $progress ? $progress['status'] : 'unlocked';

            if ($m['status'] === 'completed') {
                $completedCount++;
            }

            // Get quiz attempt if exists
            if ($m['quiz']) {
                $attempt = $attemptModel->getLatestAttempt($student_id, $m['quiz']['id']);
                $m['quiz_attempt'] = $attempt;
                if ($attempt && $attempt['status'] === 'passed') {
                    $passedQuizzesCount++;
                }

                // TIME LOGIC FOR QUIZ:
                $m['quiz']['is_time_locked'] = ($m['quiz']['opened_at'] && $m['quiz']['opened_at'] > $now);
            }

            // TIME LOGIC FOR MATERIAL:
            $m['is_time_locked'] = ($m['opened_at'] && $m['opened_at'] > $now);

            // SEQUENCE LOGIC:
            // Material 1 is always unlocked.
            // Material N is unlocked only if Material N-1 is completed AND its quiz is passed (if any).
            if ($index === 0) {
                $m['is_locked'] = false;
            } else {
                $prevMaterial = $materials[$index - 1];
                $m['is_locked'] = ($prevMaterial['status'] !== 'completed');

                // If previous material had a quiz, it must be passed too
                if (!$m['is_locked'] && $prevMaterial['quiz']) {
                    $m['is_locked'] = (!isset($prevMaterial['quiz_attempt']) || $prevMaterial['quiz_attempt']['status'] !== 'passed');
                }
            }
        }

        $this->view('student/classroom', [
            'title' => $assignment['subject_name'] . ' - Ruang Belajar',
            'assignment' => $assignment,
            'materials' => $materials,
            'stats' => [
                'completed' => $completedCount,
                'passed_quizzes' => $passedQuizzesCount,
                'total' => count($materials)
            ]
        ]);
    }
    public function viewMaterial($params)
    {
        $student_id = Session::get('user_id');
        $material_id = $params['id'];

        $materialModel = new \App\Models\Material();
        $material = $materialModel->find($material_id);

        if (!$material || ($material['opened_at'] && $material['opened_at'] > date('Y-m-d H:i:s'))) {
            $this->redirect('/EDUTEN2/student/dashboard');
        }

        // Mark as completed when viewed
        $progressModel = new \App\Models\StudentProgress();
        $progressModel->markCompleted($student_id, $material_id);

        $this->view('student/material_view', [
            'title' => $material['title'] . ' - Ruang Belajar',
            'material' => $material
        ]);
    }

    public function viewQuiz($params)
    {
        $student_id = Session::get('user_id');
        $quiz_id = $params['id'];

        $quizModel = new \App\Models\Quiz();
        $quiz = $quizModel->find($quiz_id);

        if (!$quiz || ($quiz['opened_at'] && $quiz['opened_at'] > date('Y-m-d H:i:s'))) {
            $this->redirect('/EDUTEN2/student/dashboard');
        }

        $questionModel = new \App\Models\Question();
        $questions = $questionModel->getByQuizWithDetails($quiz_id);

        $this->view('student/quiz_view', [
            'title' => 'Mengerjakan Kuis: ' . $quiz['title'],
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }

    public function submitQuiz()
    {
        $student_id = Session::get('user_id');
        $quiz_id = $_POST['quiz_id'];
        $answers = $_POST['answers'] ?? [];

        $quizModel = new \App\Models\Quiz();
        $quiz = $quizModel->find($quiz_id);

        $questionModel = new \App\Models\Question();
        $questions = $questionModel->getByQuizWithDetails($quiz_id);

        $score = 0;
        $totalPoints = 0;

        foreach ($questions as $q) {
            $totalPoints += $q['point'];
            if (isset($answers[$q['id']])) {
                foreach ($q['options'] as $opt) {
                    if ($opt['id'] == $answers[$q['id']] && $opt['is_correct']) {
                        $score += $q['point'];
                        break;
                    }
                }
            }
        }

        $finalScore = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $status = $finalScore >= $quiz['passing_score'] ? 'passed' : 'failed';

        $attemptModel = new \App\Models\QuizAttempt();
        $attemptModel->create([
            'user_id' => $student_id,
            'quiz_id' => $quiz_id,
            'score' => $finalScore,
            'status' => $status
        ]);

        $this->redirect('/EDUTEN2/student/classroom/' . $_POST['assignment_id']);
    }
}
