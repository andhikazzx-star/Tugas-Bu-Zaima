<?php

namespace App\Models;

use App\Core\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    public function getProgress($user_id, $material_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id AND material_id = :material_id");
        $stmt->execute(['user_id' => $user_id, 'material_id' => $material_id]);
        return $stmt->fetch();
    }

    public function markCompleted($user_id, $material_id)
    {
        $existing = $this->getProgress($user_id, $material_id);
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'completed' WHERE user_id = :user_id AND material_id = :material_id");
            return $stmt->execute(['user_id' => $user_id, 'material_id' => $material_id]);
        } else {
            return $this->create([
                'user_id' => $user_id,
                'material_id' => $material_id,
                'status' => 'completed'
            ]);
        }
    }

    public function getCompletedByAssignment($user_id, $assignment_id)
    {
        $sql = "SELECT sp.* FROM student_progress sp 
                JOIN materials m ON sp.material_id = m.id 
                WHERE sp.user_id = :user_id 
                AND m.subject_assignment_id = :assignment_id 
                AND sp.status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'assignment_id' => $assignment_id]);
        return $stmt->fetchAll();
    }
}
