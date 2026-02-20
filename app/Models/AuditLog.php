<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public function getRecent($limit = 5)
    {
        $sql = "SELECT al.*, u.username FROM audit_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function filter($name = null, $action = null, $date = null)
    {
        $sql = "SELECT al.*, u.username, u.full_name FROM audit_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($name)) {
            $sql .= " AND (u.username LIKE :name OR u.full_name LIKE :name)";
            $params['name'] = "%$name%";
        }

        if (!empty($action)) {
            $sql .= " AND al.action LIKE :action";
            $params['action'] = "%$action%";
        }

        if (!empty($date)) {
            $sql .= " AND DATE(al.created_at) = :date";
            $params['date'] = $date;
        }

        $sql .= " ORDER BY al.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
