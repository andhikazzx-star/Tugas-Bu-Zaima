<?php

namespace App\Models;

use App\Core\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'major_id'
    ];

    public function getAllWithDetails()
    {
        $sql = "SELECT c.*, m.name as major_name, u.full_name as teacher_name,
                (SELECT COUNT(*) FROM students_classes sc WHERE sc.class_id = c.id) as student_count
                FROM classes c
                LEFT JOIN majors m ON c.major_id = m.id
                LEFT JOIN class_teachers ct ON c.id = ct.class_id
                LEFT JOIN users u ON ct.user_id = u.id
                WHERE c.deleted_at IS NULL
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
