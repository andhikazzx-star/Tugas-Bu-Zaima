<?php

namespace App\Models;

use App\Core\Model;

class Major extends Model
{
    protected $table = 'majors';

    protected $fillable = [
        'name',
        'code'
    ];

    public function getAllWithStats()
    {
        $sql = "SELECT m.*, 
                (SELECT COUNT(*) FROM classes c WHERE c.major_id = m.id AND c.deleted_at IS NULL) as class_count,
                (SELECT COUNT(*) FROM students_classes sc 
                 JOIN classes c ON sc.class_id = c.id 
                 WHERE c.major_id = m.id AND c.deleted_at IS NULL) as student_count
                FROM majors m 
                WHERE m.deleted_at IS NULL 
                ORDER BY m.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
