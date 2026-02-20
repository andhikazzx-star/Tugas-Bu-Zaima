<?php

require_once __DIR__ . '/app/Config/Config.php';

use App\Config\Config;

function seed()
{
    try {
        $dsn = "mysql:host=" . Config::DB_HOST . ";port=" . Config::DB_PORT . ";charset=utf8mb4";
        $pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        echo "Initializing database...\n";

        // Multi-query handling for schema
        $sql = file_get_contents(__DIR__ . '/database.sql');
        $pdo->exec($sql);

        echo "Database schema initialized.\n";

        $pdo->exec("USE " . Config::DB_NAME);

        // 1. Create Users (Super Admin, Teachers, Students)
        $passwordHash = password_hash('edutengg', PASSWORD_ARGON2ID);

        $users = [
            ['full_name' => 'Super Admin', 'email' => 'admin@eduten.local', 'username' => 'admin', 'role' => 'super_admin'],
            ['full_name' => 'Budi Susanto', 'email' => 'budi@teacher.local', 'username' => 'budi', 'role' => 'teacher'],
            ['full_name' => 'Siti Aminah', 'email' => 'siti@teacher.local', 'username' => 'siti', 'role' => 'teacher'],
            ['full_name' => 'Andi Wijaya', 'email' => 'andi@teacher.local', 'username' => 'andi', 'role' => 'teacher'],
            ['full_name' => 'John Student', 'email' => 'john@student.local', 'username' => 'john', 'role' => 'student'],
            ['full_name' => 'Jane Student', 'email' => 'jane@student.local', 'username' => 'jane', 'role' => 'student'],
        ];

        $userStmt = $pdo->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
        $userIds = [];
        foreach ($users as $u) {
            $userStmt->execute([$u['full_name'], $u['email'], $u['username'], $passwordHash, $u['role']]);
            $userIds[$u['username']] = $pdo->lastInsertId();
        }

        // 2. Create Majors
        $majors = [
            ['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL'],
            ['name' => 'Teknik Komputer Jaringan', 'code' => 'TKJ'],
        ];
        $majorStmt = $pdo->prepare("INSERT INTO majors (name, code) VALUES (?, ?)");
        $majorIds = [];
        foreach ($majors as $m) {
            $majorStmt->execute([$m['name'], $m['code']]);
            $majorIds[$m['code']] = $pdo->lastInsertId();
        }

        // 3. Create Classes
        $classes = [
            ['name' => 'XI-RPL-1', 'major_code' => 'RPL'],
            ['name' => 'XI-TKJ-1', 'major_code' => 'TKJ'],
        ];
        $classStmt = $pdo->prepare("INSERT INTO classes (name, major_id) VALUES (?, ?)");
        $classIds = [];
        foreach ($classes as $c) {
            $classStmt->execute([$c['name'], $majorIds[$c['major_code']]]);
            $classIds[$c['name']] = $pdo->lastInsertId();
        }

        // 4. Enroll Students
        $enrollStmt = $pdo->prepare("INSERT INTO students_classes (user_id, class_id) VALUES (?, ?)");
        $enrollStmt->execute([$userIds['john'], $classIds['XI-RPL-1']]);
        $enrollStmt->execute([$userIds['jane'], $classIds['XI-TKJ-1']]);

        // 5. Create Subjects
        $subjects = [
            ['name' => 'Pemrograman Web', 'code' => 'WEB'],
            ['name' => 'Basis Data', 'code' => 'DB'],
            ['name' => 'Infrastruktur Jaringan', 'code' => 'NET'],
        ];
        $subjStmt = $pdo->prepare("INSERT INTO subjects (name, code) VALUES (?, ?)");
        $subjIds = [];
        foreach ($subjects as $s) {
            $subjStmt->execute([$s['name'], $s['code']]);
            $subjIds[$s['code']] = $pdo->lastInsertId();
        }

        // 6. Subject Assignments (Teaching Contexts)
        $assignments = [
            ['teacher' => 'budi', 'subject' => 'WEB', 'class' => 'XI-RPL-1'], // Budi teaches Web to RPL
            ['teacher' => 'siti', 'subject' => 'DB', 'class' => 'XI-RPL-1'],  // Siti teaches DB to RPL
            ['teacher' => 'andi', 'subject' => 'NET', 'class' => 'XI-TKJ-1'], // Andi teaches Net to TKJ
        ];
        $assignStmt = $pdo->prepare("INSERT INTO subject_assignments (teacher_id, subject_id, class_id) VALUES (?, ?, ?)");
        $assignIds = [];
        foreach ($assignments as $a) {
            $assignStmt->execute([$userIds[$a['teacher']], $subjIds[$a['subject']], $classIds[$a['class']]]);
            $assignIds[] = $pdo->lastInsertId();
        }

        // 7. Materials & Quizzes for Budi's Web Class
        $matStmt = $pdo->prepare("INSERT INTO materials (subject_assignment_id, title, content, type, order_index) VALUES (?, ?, ?, ?, ?)");
        $matStmt->execute([$assignIds[0], 'Pengenalan HTML', 'Konten HTML dasar...', 'text', 1]);
        $matId1 = $pdo->lastInsertId();

        $quizStmt = $pdo->prepare("INSERT INTO quizzes (material_id, title, passing_score) VALUES (?, ?, ?)");
        $quizStmt->execute([$matId1, 'Kuis HTML Dasar', 75]);

        $matStmt->execute([$assignIds[0], 'Dasar-dasar CSS', 'Konten CSS dasar...', 'text', 2]);
        $matId2 = $pdo->lastInsertId();
        $quizStmt->execute([$matId2, 'Kuis CSS Dasar', 75]);

        // 8. Materials for Siti's DB Class
        $matStmt->execute([$assignIds[1], 'Pengenalan SQL', 'Konten SQL dasar...', 'text', 1]);
        $matId3 = $pdo->lastInsertId();
        $quizStmt->execute([$matId3, 'Kuis SQL Dasar', 80]);

        echo "Seeding completed successfully.\n";

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage() . "\n");
    }
}

seed();
