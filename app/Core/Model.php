<?php

namespace App\Core;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected $db;
    protected $table;

    /**
     * Kolom yang boleh di-insert / update
     * Wajib di-override di model turunan (User, Subject, dll)
     */
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE deleted_at IS NULL 
             ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $data = $this->filterFillable($data);

        // Remove null values so DB defaults are used for nullable columns
        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data = $this->filterFillable($data);

        if (empty($data)) {
            return false;
        }

        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");

        $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "UPDATE {$this->table} 
                SET deleted_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function getConnection()
    {
        return $this->db;
    }

    /**
     * FILTER DATA BERDASARKAN FILLABLE
     */
    protected function filterFillable(array $data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }
}
