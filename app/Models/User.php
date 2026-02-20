<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'users';

    /**
     * KOLOM YANG BOLEH DI INSERT / UPDATE
     * HARUS 100% SAMA DENGAN KOLOM DATABASE
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'phone_number',   // ✅ FIX: sekarang nomor telepon akan tersimpan
        'password',
        'role',
        'profile_image'
    ];

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE email = :email AND deleted_at IS NULL"
        );
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE username = :username AND deleted_at IS NULL"
        );
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    /**
     * CREATE USER
     */
    public function create($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash(
                $data['password'],
                PASSWORD_ARGON2ID
            );
        }

        return parent::create($data);
    }

    public function findAllByRole($role)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE role = :role
             AND deleted_at IS NULL
             ORDER BY created_at DESC"
        );
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }

    /**
     * UPDATE USER / UPDATE PROFILE
     */
    public function update($id, $data)
    {
        // Password opsional (edit profil)
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash(
                $data['password'],
                PASSWORD_ARGON2ID
            );
        } else {
            unset($data['password']);
        }

        return parent::update($id, $data);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
