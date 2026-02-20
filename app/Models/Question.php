<?php

namespace App\Models;

use App\Core\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'point'
    ];

    public function getByQuiz($quiz_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE quiz_id = :quiz_id AND deleted_at IS NULL ORDER BY created_at ASC");
        $stmt->execute(['quiz_id' => $quiz_id]);
        return $stmt->fetchAll();
    }

    public function getByQuizWithDetails($quiz_id)
    {
        $questions = $this->getByQuiz($quiz_id);
        $optionModel = new \App\Models\Option();

        foreach ($questions as &$q) {
            $q['options'] = $optionModel->getByQuestion($q['id']);
        }

        return $questions;
    }
}
