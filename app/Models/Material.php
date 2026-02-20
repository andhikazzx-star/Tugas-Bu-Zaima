<?php

namespace App\Models;

use App\Core\Model;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
        'subject_assignment_id',
        'title',
        'content',
        'type',
        'file_path',
        'video_url',
        'order_index',
        'opened_at'
    ];

    public function getByAssignment($assignment_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE subject_assignment_id = :assignment_id AND deleted_at IS NULL ORDER BY order_index ASC");
        $stmt->execute(['assignment_id' => $assignment_id]);
        return $stmt->fetchAll();
    }
}
