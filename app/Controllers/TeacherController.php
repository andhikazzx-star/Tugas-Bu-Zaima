<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\SubjectAssignment;
use App\Models\Question;
use App\Models\Option;
use App\Core\Session;

class TeacherController extends Controller
{
    protected $materialModel;
    protected $quizModel;
    protected $assignmentModel;
    protected $questionModel;
    protected $optionModel;
    protected $userModel;

    public function __construct()
    {
        if (!Session::get('user_id') || Session::get('role') !== 'teacher') {
            $this->redirect('/EDUTEN2/login');
        }
        $this->materialModel = new Material();
        $this->quizModel = new Quiz();
        $this->assignmentModel = new SubjectAssignment();
        $this->questionModel = new Question();
        $this->optionModel = new Option();
        $this->userModel = new User();
    }

    public function dashboard()
    {
        $teacher_id = Session::get('user_id');
        $assignments = $this->assignmentModel->getByTeacherWithDetails($teacher_id);

        $this->view('teacher/dashboard', [
            'title' => 'Dashboard Guru - EDUTEN',
            'assignments' => $assignments
        ]);
    }

    public function materials()
    {
        $teacher_id = Session::get('user_id');
        $search = $_GET['search'] ?? null;
        $class_id = $_GET['class_id'] ?? null;

        $assignments = $this->assignmentModel->getByTeacherWithDetails($teacher_id);

        $allMaterials = [];
        foreach ($assignments as $a) {
            // Filter by class_id if provided
            if ($class_id && $a['class_id'] != $class_id)
                continue;

            $mats = $this->materialModel->getByAssignment($a['id']);
            foreach ($mats as $m) {
                // Filter by search string if provided
                if ($search && stripos($m['title'], $search) === false)
                    continue;

                $m['subject_name'] = $a['subject_name'];
                $m['class_name'] = $a['class_name'];
                $allMaterials[] = $m;
            }
        }

        $this->view('teacher/materials', [
            'title' => 'Materi Pembelajaran - EDUTEN',
            'materials' => $allMaterials,
            'assignments' => $assignments,
            'filters' => [
                'search' => $search,
                'class_id' => $class_id
            ]
        ]);
    }

    public function storeMaterial()
    {
        // CSRF Validation
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::set('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            $this->redirect('/EDUTEN2/teacher/materials');
            return;
        }

        // Validate required fields
        if (empty($_POST['title']) || empty($_POST['assignment_id']) || empty($_POST['type'])) {
            Session::set('error', 'Semua field wajib harus diisi.');
            $this->redirect('/EDUTEN2/teacher/materials');
            return;
        }

        $type = $_POST['type'];
        $filePath = null;

        // Handle File Upload
        if ($type !== 'text' && isset($_FILES['material_file']) && $_FILES['material_file']['error'] === 0) {
            $file = $_FILES['material_file'];
            $fileSize = $file['size'];

            // Validation: 3MB for file, 15MB for video
            $maxSize = ($type === 'file') ? 3 * 1024 * 1024 : 15 * 1024 * 1024;

            if ($fileSize > $maxSize) {
                Session::set('error', "Ukuran file terlalu besar. Maksimal " . ($maxSize / (1024 * 1024)) . "MB.");
                $this->redirect('/EDUTEN2/teacher/materials');
                return;
            }

            // Prepare Upload Directory
            $uploadDir = __DIR__ . '/../../uploads/materials/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate Unique Name
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('mat_', true) . '.' . $extension;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $filePath = $fileName;
            } else {
                Session::set('error', "Gagal mengunggah file.");
                $this->redirect('/EDUTEN2/teacher/materials');
                return;
            }
        } elseif ($type !== 'text') {
            Session::set('error', 'File harus diunggah untuk tipe materi ini.');
            $this->redirect('/EDUTEN2/teacher/materials');
            return;
        }

        $data = [
            'subject_assignment_id' => $_POST['assignment_id'],
            'title' => $_POST['title'],
            'content' => $_POST['content'] ?? '',
            'type' => $type,
            'file_path' => $filePath,
            'order_index' => $_POST['order_index'] ?? 0,
            'opened_at' => !empty($_POST['opened_at']) ? $_POST['opened_at'] : null
        ];

        try {
            $result = $this->materialModel->create($data);

            if ($result) {
                \App\Core\Helper::log('CREATE_MATERIAL', 'Added material: ' . $data['title'] . ($filePath ? " with file $filePath" : ""));
                Session::set('success', 'Materi berhasil ditambahkan!');
            } else {
                Session::set('error', 'Gagal menyimpan materi. Silakan coba lagi.');
            }
        } catch (\PDOException $e) {
            error_log("Material save error: " . $e->getMessage());
            Session::set('error', 'Gagal menyimpan ke database: ' . $e->getMessage());
        } catch (\Exception $e) {
            error_log("Material save error: " . $e->getMessage());
            Session::set('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect('/EDUTEN2/teacher/materials');
    }

    public function deleteMaterial($params)
    {
        $id = $params['id'];
        if ($this->materialModel->delete($id)) {
            \App\Core\Helper::log('DELETE_MATERIAL', 'Deleted material ID: ' . $id);
            $this->redirect('/EDUTEN2/teacher/materials');
        }
    }

    public function quizzes()
    {
        $teacher_id = Session::get('user_id');
        $search = $_GET['search'] ?? null;
        $class_id = $_GET['class_id'] ?? null;

        $assignments = $this->assignmentModel->getByTeacherWithDetails($teacher_id);

        $allQuizzes = [];
        $allMaterialsForTeacher = [];
        foreach ($assignments as $a) {
            $mats = $this->materialModel->getByAssignment($a['id']);
            foreach ($mats as $m) {
                $m['subject_name'] = $a['subject_name'];
                $m['class_name'] = $a['class_name'];
                $allMaterialsForTeacher[] = $m;

                // Filter by class_id if provided (for quizzes)
                if ($class_id && $a['class_id'] != $class_id)
                    continue;

                $quizList = $this->quizModel->getByMaterial($m['id']);
                foreach ($quizList as $q) {
                    // Filter by search string if provided
                    if ($search && stripos($q['title'], $search) === false)
                        continue;

                    $q['material_title'] = $m['title'];
                    $q['subject_name'] = $a['subject_name'];
                    $q['class_name'] = $a['class_name'];
                    $allQuizzes[] = $q;
                }
            }
        }

        $this->view('teacher/quizzes', [
            'title' => 'Manajemen Kuis - EDUTEN',
            'quizzes' => $allQuizzes,
            'materials' => $allMaterialsForTeacher,
            'assignments' => $assignments, // Added for class filter dropdown
            'filters' => [
                'search' => $search,
                'class_id' => $class_id
            ]
        ]);
    }

    public function storeQuiz()
    {
        $data = [
            'material_id' => $_POST['material_id'],
            'title' => $_POST['title'],
            'passing_score' => $_POST['passing_score'],
            'opened_at' => !empty($_POST['opened_at']) ? $_POST['opened_at'] : null
        ];

        if ($this->quizModel->create($data)) {
            \App\Core\Helper::log('CREATE_QUIZ', 'Added quiz: ' . $data['title']);
            $this->redirect('/EDUTEN2/teacher/quizzes');
        }
    }

    public function deleteQuiz($params)
    {
        $id = $params['id'];
        if ($this->quizModel->delete($id)) {
            \App\Core\Helper::log('DELETE_QUIZ', 'Deleted quiz ID: ' . $id);
            $this->redirect('/EDUTEN2/teacher/quizzes');
        }
    }

    public function quizQuestions($params)
    {
        $quizId = $params['id'];
        $quiz = $this->quizModel->find($quizId);

        if (!$quiz) {
            $this->redirect('/EDUTEN2/teacher/quizzes');
        }

        $questions = $this->questionModel->getByQuiz($quizId);
        foreach ($questions as &$q) {
            if ($q['type'] === 'mcq') {
                $q['options'] = $this->optionModel->getByQuestion($q['id']);
            }
        }

        $this->view('teacher/quiz_questions', [
            'title' => 'Kelola Pertanyaan - ' . $quiz['title'],
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }

    public function storeQuestion()
    {
        $quizId = $_POST['quiz_id'];
        $data = [
            'quiz_id' => $quizId,
            'question_text' => $_POST['question_text'],
            'type' => $_POST['type'],
            'point' => $_POST['point'] ?? 10
        ];

        if ($this->questionModel->create($data)) {
            $questionId = $this->questionModel->lastInsertId();

            if ($data['type'] === 'mcq' && isset($_POST['options'])) {
                foreach ($_POST['options'] as $index => $text) {
                    $isCorrect = ($index == $_POST['correct_option']) ? 1 : 0;
                    $this->optionModel->create([
                        'question_id' => $questionId,
                        'option_text' => $text,
                        'is_correct' => $isCorrect
                    ]);
                }
            }

            \App\Core\Helper::log('CREATE_QUESTION', 'Added question to quiz ID: ' . $quizId);
            $this->redirect('/EDUTEN2/teacher/quizzes/questions/' . $quizId);
        }
    }

    public function deleteQuestion($params)
    {
        $id = $params['id'];
        $question = $this->questionModel->find($id);
        if ($question) {
            $quizId = $question['quiz_id'];
            if ($this->questionModel->delete($id)) {
                \App\Core\Helper::log('DELETE_QUESTION', 'Deleted question ID: ' . $id);
                $this->redirect('/EDUTEN2/teacher/quizzes/questions/' . $quizId);
            }
        }
        $this->redirect('/EDUTEN2/teacher/quizzes');
    }
    public function assignments()
    {
        $teacher_id = Session::get('user_id');

        $sql = "SELECT sa.*, s.name as subject_name, c.name as class_name 
                FROM subject_assignments sa 
                JOIN subjects s ON sa.subject_id = s.id 
                JOIN classes c ON sa.class_id = c.id 
                WHERE sa.teacher_id = :teacher_id AND sa.deleted_at IS NULL";
        $stmt = $this->assignmentModel->getConnection()->prepare($sql);
        $stmt->execute(['teacher_id' => $teacher_id]);
        $myAssignments = $stmt->fetchAll();

        $subjectModel = new \App\Models\Subject();
        $classModel = new \App\Models\ClassModel();

        $allSubjects = $subjectModel->all();
        $allClasses = $classModel->all();

        $this->view('teacher/assignments', [
            'title' => 'Pengaturan Mengajar - EDUTEN',
            'assignments' => $myAssignments,
            'subjects' => $allSubjects,
            'classes' => $allClasses
        ]);
    }

    public function storeAssignment()
    {
        $teacher_id = Session::get('user_id');
        $subject_id = $_POST['subject_id'];
        $class_ids = $_POST['class_ids'] ?? [];

        $success = 0;
        foreach ($class_ids as $class_id) {
            $data = [
                'teacher_id' => $teacher_id,
                'subject_id' => $subject_id,
                'class_id' => $class_id
            ];

            $sqlCheck = "SELECT id FROM subject_assignments WHERE teacher_id = ? AND subject_id = ? AND class_id = ? AND deleted_at IS NULL";
            $stmt = $this->assignmentModel->getConnection()->prepare($sqlCheck);
            $stmt->execute([$teacher_id, $subject_id, $class_id]);

            if (!$stmt->fetch()) {
                if ($this->assignmentModel->create($data)) {
                    $success++;
                }
            }
        }

        if ($success > 0) {
            \App\Core\Helper::log('TEACHER_SELF_ASSIGN', "Teacher assigned themselves to $success classes");
        }
        $this->redirect('/EDUTEN2/teacher/assignments');
    }

    public function deleteAssignment($params)
    {
        $id = $params['id'];
        $teacher_id = Session::get('user_id');

        $sqlCheck = "SELECT id FROM subject_assignments WHERE id = ? AND teacher_id = ?";
        $stmt = $this->assignmentModel->getConnection()->prepare($sqlCheck);
        $stmt->execute([$id, $teacher_id]);

        if ($stmt->fetch()) {
            $sql = "UPDATE subject_assignments SET deleted_at = NOW() WHERE id = ?";
            $this->assignmentModel->getConnection()->prepare($sql)->execute([$id]);
            \App\Core\Helper::log('TEACHER_SELF_UNASSIGN', "Teacher removed assignment ID: $id");
        }

        $this->redirect('/EDUTEN2/teacher/assignments');
    }

    public function quizReport($params)
    {
        $quiz_id = $params['id'];

        // 1. Get Quiz Details with Class & Subject info
        $sqlQuiz = "SELECT q.*, m.title as material_title, c.name as class_name, s.name as subject_name, c.id as class_id
                    FROM quizzes q
                    JOIN materials m ON q.material_id = m.id
                    JOIN subject_assignments sa ON m.subject_assignment_id = sa.id
                    JOIN classes c ON sa.class_id = c.id
                    JOIN subjects s ON sa.subject_id = s.id
                    WHERE q.id = ? AND q.deleted_at IS NULL";
        $stmtQuiz = $this->quizModel->getConnection()->prepare($sqlQuiz);
        $stmtQuiz->execute([$quiz_id]);
        $quiz = $stmtQuiz->fetch();

        if (!$quiz) {
            $this->redirect('/EDUTEN2/teacher/quizzes');
        }

        // 2. Get all students in this class
        $sqlStudents = "SELECT u.id, u.full_name, u.username
                        FROM users u
                        JOIN students_classes sc ON u.id = sc.user_id
                        WHERE sc.class_id = ? AND u.role = 'student' AND u.deleted_at IS NULL
                        ORDER BY u.full_name ASC";
        $stmtStudents = $this->userModel->getConnection()->prepare($sqlStudents);
        $stmtStudents->execute([$quiz['class_id']]);
        $students = $stmtStudents->fetchAll();

        // 3. Get best attempt (max score) for all students for this quiz
        $sqlAttempts = "SELECT user_id, MAX(score) as score, MAX(created_at) as created_at
                        FROM quiz_attempts 
                        WHERE quiz_id = ? 
                        GROUP BY user_id";
        $stmtAttempts = $this->quizModel->getConnection()->prepare($sqlAttempts);
        $stmtAttempts->execute([$quiz_id]);
        $attempts = $stmtAttempts->fetchAll();

        // 4. Map attempts to students
        $attemptsMap = [];
        foreach ($attempts as $atp) {
            $attemptsMap[$atp['user_id']] = $atp;
        }

        foreach ($students as &$student) {
            $student['attempt'] = $attemptsMap[$student['id']] ?? null;
        }

        $this->view('teacher/quiz_report', [
            'title' => 'Rekap Nilai: ' . $quiz['title'],
            'quiz' => $quiz,
            'students' => $students
        ]);
    }
}
