<?php

namespace App\Models;

use App\Core\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    protected $fillable = [
        'material_id',
        'title',
        'passing_score',
        'opened_at'
    ];

    public function getByMaterial($material_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE material_id = :material_id AND deleted_at IS NULL");
        $stmt->execute(['material_id' => $material_id]);
        return $stmt->fetchAll();
    }
}
