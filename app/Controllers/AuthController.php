<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Session;

class AuthController extends Controller
{
    protected $userModel;
    protected $classModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->classModel = new \App\Models\ClassModel();
    }

    public function register()
    {
        $classes = $this->classModel->getAllWithDetails();
        $this->view('auth/register', ['classes' => $classes]);
    }

    public function storeRegister()
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::validateCsrfToken($csrfToken)) {
            $classes = $this->classModel->getAllWithDetails();
            return $this->view('auth/register', ['error' => 'Invalid CSRF token', 'classes' => $classes]);
        }

        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'phone_number' => $_POST['phone_number'],
            'password' => $_POST['password'],
            'role' => 'student',
            'status' => 'active'
        ];

        if ($this->userModel->findByEmail($data['email'])) {
            $classes = $this->classModel->getAllWithDetails();
            return $this->view('auth/register', ['error' => 'Email sudah terdaftar.', 'classes' => $classes]);
        }

        if ($this->userModel->findByUsername($data['username'])) {
            $classes = $this->classModel->getAllWithDetails();
            return $this->view('auth/register', ['error' => 'Username sudah digunakan.', 'classes' => $classes]);
        }

        if ($this->userModel->create($data)) {
            $user_id = $this->userModel->lastInsertId();

            // Link to class
            $sql = "INSERT INTO students_classes (user_id, class_id) VALUES (?, ?)";
            $stmt = $this->userModel->getConnection()->prepare($sql);
            $stmt->execute([$user_id, $_POST['class_id']]);

            \App\Core\Helper::log('STUDENT_REGISTER', 'Student registered: ' . $data['full_name']);

            // Auto login after register
            Session::set('user_id', $user_id);
            Session::set('email', $data['email']);
            Session::set('full_name', $data['full_name']);
            Session::set('role', 'student');

            $this->redirect('/EDUTEN2/student/dashboard');
        } else {
            $classes = $this->classModel->getAllWithDetails();
            $this->view('auth/register', ['error' => 'Pendaftaran gagal. Silakan coba lagi.', 'classes' => $classes]);
        }
    }

    public function index()
    {
        if (Session::get('user_id')) {
            $this->redirect('/EDUTEN2/dashboard');
        }
        $this->view('auth/login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? '';

        if (!Session::validateCsrfToken($csrfToken)) {
            return $this->view('auth/login', ['error' => 'Invalid CSRF token']);
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            if (isset($user['can_login']) && $user['can_login'] == 0) {
                return $this->view('auth/login', ['error' => 'Akun Anda dinonaktifkan.']);
            }

            Session::set('user_id', $user['id']);
            Session::set('email', $user['email']);
            Session::set('full_name', $user['full_name']);
            Session::set('role', $user['role']);
            Session::set('profile_image', $user['profile_image']); // Store profile image

            $this->redirect('/EDUTEN2/dashboard');
        }

        $this->view('auth/login', ['error' => 'Email atau kata sandi salah.']);
    }

    public function logout()
    {
        Session::destroy();
        $this->redirect('/EDUTEN2/login');
    }

    public function dashboard()
    {
        if (!Session::get('user_id')) {
            $this->redirect('/EDUTEN2/login');
        }

        $role = Session::get('role');

        switch ($role) {
            case 'super_admin':
                $this->redirect('/EDUTEN2/admin/dashboard');
                break;
            case 'teacher':
                $this->redirect('/EDUTEN2/teacher/dashboard');
                break;
            case 'student':
                $this->redirect('/EDUTEN2/student/dashboard');
                break;
            default:
                $this->redirect('/EDUTEN2/login');
        }
    }

    public function profile()
    {
        if (!Session::get('user_id')) {
            $this->redirect('/EDUTEN2/login');
        }

        $user = $this->userModel->find(Session::get('user_id'));
        $this->view('profile', ['user' => $user]);
    }

    public function updateProfile()
    {
        if (!Session::get('user_id')) {
            $this->redirect('/EDUTEN2/login');
        }

        $id = Session::get('user_id');
        $user = $this->userModel->find($id);

        $data = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'username' => $_POST['username'] ?? $user['username'], // Keep existing if not provided (though form should have it)
            'phone_number' => $_POST['phone_number'] ?? $user['phone_number']
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
                // Update session
                Session::set('profile_image', $fileName);
            }
        }

        if ($this->userModel->update($id, $data)) {
            Session::set('full_name', $data['full_name']);
            Session::set('email', $data['email']);

            \App\Core\Helper::log('UPDATE_PROFILE', 'User updated profile: ' . $id);
            $this->redirect('/EDUTEN2/profile');
        } else {
            // Handle error (maybe redirect back with error)
            $this->redirect('/EDUTEN2/profile');
        }
    }
}
