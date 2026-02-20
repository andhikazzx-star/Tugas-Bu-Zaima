<?php

namespace App\Models;

use App\Core\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';

    public function getLatestAttempt($user_id, $quiz_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id AND quiz_id = :quiz_id ORDER BY created_at DESC LIMIT 1");
        $stmt->execute(['user_id' => $user_id, 'quiz_id' => $quiz_id]);
        return $stmt->fetch();
    }

    public function getPassedByAssignment($user_id, $assignment_id)
    {
        $sql = "SELECT qa.* FROM quiz_attempts qa 
                JOIN quizzes q ON qa.quiz_id = q.id 
                JOIN materials m ON q.material_id = m.id 
                WHERE qa.user_id = :user_id 
                AND m.subject_assignment_id = :assignment_id 
                AND qa.status = 'passed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'assignment_id' => $assignment_id]);
        return $stmt->fetchAll();
    }
}
