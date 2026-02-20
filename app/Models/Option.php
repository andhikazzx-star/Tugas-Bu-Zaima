<?php

namespace App\Models;

use App\Core\Model;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct'
    ];

    public function getByQuestion($question_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE question_id = :question_id");
        $stmt->execute(['question_id' => $question_id]);
        return $stmt->fetchAll();
    }

    public function deleteByQuestion($question_id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE question_id = :question_id");
        return $stmt->execute(['question_id' => $question_id]);
    }
}
