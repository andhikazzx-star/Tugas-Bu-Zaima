<?php

namespace App\Core;

use App\Core\Database;

class Helper
{
    public static function log($action, $details = null)
    {
        $db = Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, user_agent) VALUES (:user_id, :action, :details, :ip, :ua)");
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip' => $ip,
            'ua' => $ua
        ]);
    }

    public static function csrfField()
    {
        $token = Session::generateCsrfToken();
        echo "<input type='hidden' name='csrf_token' value='$token'>";
    }

    public static function uploadFile($file, $destination, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'])
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes)) {
            return false;
        }

        // 2MB limit
        if ($file['size'] > 2 * 1024 * 1024) {
            return false;
        }

        $fileName = uniqid() . '.' . $extension;
        $targetPath = $destination . '/' . $fileName;

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }
}
