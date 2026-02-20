<?php

namespace App\Models;

use App\Core\Model;

class SubjectAssignment extends Model
{
    protected $table = 'subject_assignments';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id'
    ];

    public function getByTeacherWithDetails($teacher_id)
    {
        $sql = "SELECT sa.*, s.name as subject_name, c.name as class_name, m.name as major_name
                FROM subject_assignments sa
                JOIN subjects s ON sa.subject_id = s.id
                JOIN classes c ON sa.class_id = c.id
                JOIN majors m ON c.major_id = m.id
                WHERE sa.teacher_id = :teacher_id AND sa.deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['teacher_id' => $teacher_id]);
        return $stmt->fetchAll();
    }

    public function getByStudentWithDetails($student_id)
    {
        $sql = "SELECT sa.*, s.name as subject_name, u.full_name as teacher_name, c.name as class_name, m.name as major_name
                FROM subject_assignments sa
                JOIN subjects s ON sa.subject_id = s.id
                JOIN users u ON sa.teacher_id = u.id
                JOIN classes c ON sa.class_id = c.id
                JOIN majors m ON c.major_id = m.id
                JOIN students_classes sc ON c.id = sc.class_id
                WHERE sc.user_id = :student_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    public function findWithDetails($id)
    {
        $sql = "SELECT sa.*, s.name as subject_name, u.full_name as teacher_name, c.name as class_name, m.name as major_name
                FROM subject_assignments sa
                JOIN subjects s ON sa.subject_id = s.id
                JOIN users u ON sa.teacher_id = u.id
                JOIN classes c ON sa.class_id = c.id
                JOIN majors m ON c.major_id = m.id
                WHERE sa.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
