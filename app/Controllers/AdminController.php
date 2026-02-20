<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Major;
use App\Models\ClassModel;
use App\Models\AuditLog;
use App\Core\Session;

class AdminController extends Controller
{
    protected $userModel;
    protected $majorModel;
    protected $classModel;
    protected $auditModel;
    protected $subjectModel;
    protected $assignmentModel;

    public function __construct()
    {
        if (!Session::get('user_id') || Session::get('role') !== 'super_admin') {
            $this->redirect('/EDUTEN2/login');
        }
        $this->userModel = new User();
        $this->majorModel = new Major();
        $this->classModel = new ClassModel();
        $this->auditModel = new AuditLog();
        $this->subjectModel = new \App\Models\Subject();
        $this->assignmentModel = new \App\Models\SubjectAssignment();
    }

    public function dashboard()
    {
        $stats = [
            'teachers' => count($this->userModel->findAllByRole('teacher')),
            'students' => count($this->userModel->findAllByRole('student')),
            'classes' => count($this->classModel->all()),
            'majors' => count($this->majorModel->all())
        ];

        $recentLogs = $this->auditModel->getRecent(5);

        $this->view('super_admin/dashboard', [
            'title' => 'Beranda Admin - EDUTEN',
            'stats' => $stats,
            'recentLogs' => $recentLogs
        ]);
    }

    public function teachers()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "AND (u.full_name LIKE :search OR u.username LIKE :search OR u.email LIKE :search)" : "";

        // Fetch teachers with their assigned classes summary
        $sql = "SELECT u.*, 
                (SELECT GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') 
                 FROM subject_assignments sa 
                 JOIN classes c ON sa.class_id = c.id 
                 WHERE sa.teacher_id = u.id AND sa.deleted_at IS NULL) as class_list
                FROM users u 
                WHERE u.role = 'teacher' AND u.deleted_at IS NULL $searchSql
                ORDER BY u.created_at DESC";
        $stmt = $this->userModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $teachers = $stmt->fetchAll();

        $this->view('super_admin/teachers', [
            'title' => 'Manajemen Guru - EDUTEN',
            'teachers' => $teachers,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeTeacher()
    {
        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role' => 'teacher',
            'email' => $_POST['email']
        ];

        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles';
            $fileName = \App\Core\Helper::uploadFile($_FILES['profile_image'], $uploadDir);

            if ($fileName) {
                $data['profile_image'] = $fileName;
            }
        }

        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles';
            $fileName = \App\Core\Helper::uploadFile($_FILES['profile_image'], $uploadDir);

            if ($fileName) {
                $data['profile_image'] = $fileName;
            }
        }

        if ($this->userModel->findByEmail($data['email']) || $this->userModel->findByUsername($data['username'])) {
            $this->redirect('/EDUTEN2/admin/teachers');
            return;
        }

        if ($this->userModel->create($data)) {
            \App\Core\Helper::log('CREATE_TEACHER', 'Added teacher: ' . $data['full_name']);
        }
        $this->redirect('/EDUTEN2/admin/teachers');
    }

    public function updateTeacher($params)
    {
        $id = $params['id'];
        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'status' => $_POST['status']
        ];

        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles';
            $fileName = \App\Core\Helper::uploadFile($_FILES['profile_image'], $uploadDir);

            if ($fileName) {
                $data['profile_image'] = $fileName;
            }
        }

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($this->userModel->update($id, $data)) {
            \App\Core\Helper::log('UPDATE_TEACHER', 'Updated teacher ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/teachers');
        }
    }

    public function majors()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "AND (m.name LIKE :search OR m.code LIKE :search)" : "";

        $sql = "SELECT m.*, 
                (SELECT COUNT(*) FROM classes c WHERE c.major_id = m.id AND c.deleted_at IS NULL) as class_count,
                (SELECT COUNT(*) FROM students_classes sc 
                 JOIN classes c ON sc.class_id = c.id 
                 WHERE c.major_id = m.id AND c.deleted_at IS NULL) as student_count
                FROM majors m 
                WHERE m.deleted_at IS NULL $searchSql
                ORDER BY m.created_at DESC";
        $stmt = $this->majorModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $majors = $stmt->fetchAll();

        $this->view('super_admin/majors', [
            'title' => 'Manajemen Jurusan - EDUTEN',
            'majors' => $majors,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeMajor()
    {
        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code']
        ];

        if ($this->majorModel->create($data)) {
            \App\Core\Helper::log('CREATE_MAJOR', 'Added major: ' . $data['name']);
            $this->redirect('/EDUTEN2/admin/majors');
        }
    }

    public function updateMajor($params)
    {
        $id = $params['id'];
        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code']
        ];

        if ($this->majorModel->update($id, $data)) {
            \App\Core\Helper::log('UPDATE_MAJOR', 'Updated major ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/majors');
        }
    }

    public function classes()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "AND (c.name LIKE :search OR m.name LIKE :search OR u.full_name LIKE :search)" : "";

        $sql = "SELECT c.*, m.name as major_name, u.full_name as teacher_name,
                (SELECT COUNT(*) FROM students_classes sc WHERE sc.class_id = c.id) as student_count
                FROM classes c
                LEFT JOIN majors m ON c.major_id = m.id
                LEFT JOIN class_teachers ct ON c.id = ct.class_id
                LEFT JOIN users u ON ct.user_id = u.id
                WHERE c.deleted_at IS NULL $searchSql
                ORDER BY c.created_at DESC";
        $stmt = $this->classModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $classes = $stmt->fetchAll();

        $majors = $this->majorModel->all();
        $teachers = $this->userModel->findAllByRole('teacher');

        $this->view('super_admin/classes', [
            'title' => 'Manajemen Kelas - EDUTEN',
            'classes' => $classes,
            'majors' => $majors,
            'teachers' => $teachers,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeClass()
    {
        $data = [
            'name' => $_POST['name'],
            'major_id' => $_POST['major_id']
        ];

        if ($this->classModel->create($data)) {
            $class_id = $this->classModel->lastInsertId();
            // Assign teacher if provided
            if (!empty($_POST['teacher_id'])) {
                $sql = "INSERT INTO class_teachers (class_id, user_id) VALUES (?, ?)";
                $stmt = $this->classModel->getConnection()->prepare($sql);
                $stmt->execute([$class_id, $_POST['teacher_id']]);
            }
            \App\Core\Helper::log('CREATE_CLASS', 'Added class: ' . $data['name']);
            $this->redirect('/EDUTEN2/admin/classes');
        }
    }

    public function updateClass($params)
    {
        $id = $params['id'];
        $data = [
            'name' => $_POST['name'],
            'major_id' => $_POST['major_id']
        ];

        if ($this->classModel->update($id, $data)) {
            // Update teacher assignment
            if (!empty($_POST['teacher_id'])) {
                // Remove old assignments
                $sqlDelete = "DELETE FROM class_teachers WHERE class_id = ?";
                $this->classModel->getConnection()->prepare($sqlDelete)->execute([$id]);

                // Add new
                $sqlInsert = "INSERT INTO class_teachers (class_id, user_id) VALUES (?, ?)";
                $this->classModel->getConnection()->prepare($sqlInsert)->execute([$id, $_POST['teacher_id']]);
            }
            \App\Core\Helper::log('UPDATE_CLASS', 'Updated class ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/classes');
        }
    }

    public function subjects()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "WHERE s.deleted_at IS NULL AND (s.name LIKE :search OR s.code LIKE :search)" : "WHERE s.deleted_at IS NULL";

        $sql = "SELECT s.* FROM subjects s $searchSql ORDER BY s.name ASC";
        $stmt = $this->subjectModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $subjects = $stmt->fetchAll();

        $this->view('super_admin/subjects', [
            'title' => 'Manajemen Mata Pelajaran - EDUTEN',
            'subjects' => $subjects,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeSubject()
    {
        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code']
        ];
        if ($this->subjectModel->create($data)) {
            \App\Core\Helper::log('CREATE_SUBJECT', 'Added subject: ' . $data['name']);
            $this->redirect('/EDUTEN2/admin/subjects');
        }
    }

    public function updateSubject($params)
    {
        $id = $params['id'];
        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code']
        ];

        if ($this->subjectModel->update($id, $data)) {
            \App\Core\Helper::log('UPDATE_SUBJECT', 'Updated subject ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/subjects');
        }
    }

    public function deleteSubject($params)
    {
        $id = $params['id'];
        if ($this->subjectModel->delete($id)) {
            \App\Core\Helper::log('DELETE_SUBJECT', 'Deleted subject ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/subjects');
        }
    }

    public function assignments()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "WHERE u.full_name LIKE :search OR s.name LIKE :search OR c.name LIKE :search" : "";

        // Let's get more detailed ones for the list
        $sql = "SELECT sa.*, u.full_name as teacher_name, s.name as subject_name, c.name as class_name 
                FROM subject_assignments sa 
                JOIN users u ON sa.teacher_id = u.id 
                JOIN subjects s ON sa.subject_id = s.id 
                JOIN classes c ON sa.class_id = c.id
                $searchSql";
        $stmt = $this->assignmentModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $assignmentsDetailed = $stmt->fetchAll();

        $teachers = $this->userModel->findAllByRole('teacher');
        $subjects = $this->subjectModel->all();
        $classes = $this->classModel->all();

        $this->view('super_admin/assignments', [
            'title' => 'Penugasan Guru - EDUTEN',
            'assignments' => $assignmentsDetailed,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'classes' => $classes,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeAssignment()
    {
        $teacher_id = $_POST['teacher_id'];
        $subject_id = $_POST['subject_id'];
        $class_ids = $_POST['class_ids'] ?? [];

        $success = 0;
        foreach ($class_ids as $class_id) {
            $data = [
                'teacher_id' => $teacher_id,
                'subject_id' => $subject_id,
                'class_id' => $class_id
            ];
            // Check if already exists to avoid unique constraint error
            $sqlCheck = "SELECT id FROM subject_assignments WHERE teacher_id = ? AND subject_id = ? AND class_id = ?";
            $stmt = $this->assignmentModel->getConnection()->prepare($sqlCheck);
            $stmt->execute([$teacher_id, $subject_id, $class_id]);

            if (!$stmt->fetch()) {
                if ($this->assignmentModel->create($data)) {
                    $success++;
                }
            }
        }

        if ($success > 0) {
            \App\Core\Helper::log('CREATE_ASSIGNMENT', "Added $success new teacher assignments");
        }
        $this->redirect('/EDUTEN2/admin/assignments');
    }

    public function deleteAssignment($params)
    {
        $id = $params['id'];
        // Manual delete since it's a pivot-style table without deleted_at usually 
        // but our seed created it without deleted_at. Let's check schema.
        $sql = "DELETE FROM subject_assignments WHERE id = ?";
        $stmt = $this->assignmentModel->getConnection()->prepare($sql);
        $stmt->execute([$id]);

        \App\Core\Helper::log('DELETE_ASSIGNMENT', 'Deleted assignment ID: ' . $id);
        $this->redirect('/EDUTEN2/admin/assignments');
    }

    public function deleteTeacher($params)
    {
        $id = $params['id'];
        if ($this->userModel->delete($id)) {
            \App\Core\Helper::log('DELETE_TEACHER', 'Deleted teacher ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/teachers');
        }
    }

    public function deleteMajor($params)
    {
        $id = $params['id'];
        if ($this->majorModel->delete($id)) {
            \App\Core\Helper::log('DELETE_MAJOR', 'Deleted major ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/majors');
        }
    }

    public function deleteClass($params)
    {
        $id = $params['id'];
        if ($this->classModel->delete($id)) {
            \App\Core\Helper::log('DELETE_CLASS', 'Deleted class ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/classes');
        }
    }

    public function students()
    {
        $search = $_GET['search'] ?? null;
        $searchSql = $search ? "AND (u.full_name LIKE :search OR u.username LIKE :search OR u.email LIKE :search OR c.name LIKE :search OR m.name LIKE :search)" : "";

        $sql = "SELECT u.*, c.name as class_name, m.name as major_name, c.id as class_id 
                FROM users u 
                LEFT JOIN students_classes sc ON u.id = sc.user_id 
                LEFT JOIN classes c ON sc.class_id = c.id 
                LEFT JOIN majors m ON c.major_id = m.id 
                WHERE u.role = 'student' AND u.deleted_at IS NULL $searchSql
                ORDER BY u.created_at DESC";
        $stmt = $this->userModel->getConnection()->prepare($sql);
        if ($search) {
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt->execute();
        }
        $students = $stmt->fetchAll();

        $classes = $this->classModel->getAllWithDetails();

        $this->view('super_admin/students', [
            'title' => 'Manajemen Siswa - EDUTEN',
            'students' => $students,
            'classes' => $classes,
            'filters' => ['search' => $search]
        ]);
    }

    public function storeStudent()
    {
        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'phone_number' => $_POST['phone_number'],
            'password' => $_POST['password'],
            'role' => 'student',
            'status' => 'active'
        ];

        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles';
            $fileName = \App\Core\Helper::uploadFile($_FILES['profile_image'], $uploadDir);

            if ($fileName) {
                $data['profile_image'] = $fileName;
            }
        }

        if ($this->userModel->findByEmail($data['email']) || $this->userModel->findByUsername($data['username'])) {
            $this->redirect('/EDUTEN2/admin/students');
            return;
        }

        if ($this->userModel->create($data)) {
            $user_id = $this->userModel->lastInsertId();

            // Link to class
            $sql = "INSERT INTO students_classes (user_id, class_id) VALUES (?, ?)";
            $stmt = $this->userModel->getConnection()->prepare($sql);
            $stmt->execute([$user_id, $_POST['class_id']]);

            \App\Core\Helper::log('CREATE_STUDENT', 'Added student: ' . $data['full_name']);
        }
        $this->redirect('/EDUTEN2/admin/students');
    }

    public function updateStudent($params)
    {
        $id = $params['id'];
        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'phone_number' => $_POST['phone_number'],
            'status' => $_POST['status']
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles';
            $fileName = \App\Core\Helper::uploadFile($_FILES['profile_image'], $uploadDir);

            if ($fileName) {
                $data['profile_image'] = $fileName;
            }
        }

        if ($this->userModel->update($id, $data)) {
            // Update class link
            if (!empty($_POST['class_id'])) {
                $sqlDelete = "DELETE FROM students_classes WHERE user_id = ?";
                $this->userModel->getConnection()->prepare($sqlDelete)->execute([$id]);

                $sqlInsert = "INSERT INTO students_classes (user_id, class_id) VALUES (?, ?)";
                $this->userModel->getConnection()->prepare($sqlInsert)->execute([$id, $_POST['class_id']]);
            }

            \App\Core\Helper::log('UPDATE_STUDENT', 'Updated student ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/students');
        }
    }

    public function deleteStudent($params)
    {
        $id = $params['id'];
        if ($this->userModel->delete($id)) {
            \App\Core\Helper::log('DELETE_STUDENT', 'Deleted student ID: ' . $id);
            $this->redirect('/EDUTEN2/admin/students');
        }
    }

    public function auditLogs()
    {
        $name = $_GET['name'] ?? null;
        $action = $_GET['action'] ?? null;
        $date = $_GET['date'] ?? null;

        $logs = $this->auditModel->filter($name, $action, $date);

        $this->view('super_admin/audit_logs', [
            'title' => 'Log Audit - EDUTEN',
            'logs' => $logs,
            'filters' => [
                'name' => $name,
                'action' => $action,
                'date' => $date
            ]
        ]);
    }
}
