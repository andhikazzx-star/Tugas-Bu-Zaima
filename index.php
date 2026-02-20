<?php

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Core\App;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\TeacherController;
use App\Controllers\StudentController;

$router = new Router();

// Auth Routes
$router->get('/', [AuthController::class, 'index']);
$router->get('/login', [AuthController::class, 'index']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'storeRegister']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/profile', [AuthController::class, 'profile']);
$router->post('/profile/update', [AuthController::class, 'updateProfile']);

// Dashboard Routes
$router->get('/dashboard', [AuthController::class, 'dashboard']);
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/teacher/dashboard', [TeacherController::class, 'dashboard']);
$router->get('/student/dashboard', [StudentController::class, 'dashboard']);

// Admin Routes
$router->get('/admin/teachers', [AdminController::class, 'teachers']);
$router->post('/admin/teachers/store', [AdminController::class, 'storeTeacher']);
$router->post('/admin/teachers/update/{id}', [AdminController::class, 'updateTeacher']);
$router->get('/admin/teachers/delete/{id}', [AdminController::class, 'deleteTeacher']);
$router->get('/admin/classes', [AdminController::class, 'classes']);
$router->post('/admin/classes/store', [AdminController::class, 'storeClass']);
$router->post('/admin/classes/update/{id}', [AdminController::class, 'updateClass']);
$router->get('/admin/classes/delete/{id}', [AdminController::class, 'deleteClass']);
$router->get('/admin/majors', [AdminController::class, 'majors']);
$router->post('/admin/majors/store', [AdminController::class, 'storeMajor']);
$router->post('/admin/majors/update/{id}', [AdminController::class, 'updateMajor']);
$router->get('/admin/majors/delete/{id}', [AdminController::class, 'deleteMajor']);
$router->get('/admin/subjects', [AdminController::class, 'subjects']);
$router->post('/admin/subjects/store', [AdminController::class, 'storeSubject']);
$router->post('/admin/subjects/update/{id}', [AdminController::class, 'updateSubject']);
$router->get('/admin/subjects/delete/{id}', [AdminController::class, 'deleteSubject']);
$router->get('/admin/assignments', [AdminController::class, 'assignments']);
$router->post('/admin/assignments/store', [AdminController::class, 'storeAssignment']);
$router->get('/admin/assignments/delete/{id}', [AdminController::class, 'deleteAssignment']);
$router->get('/admin/students', [AdminController::class, 'students']);
$router->post('/admin/students/store', [AdminController::class, 'storeStudent']);
$router->post('/admin/students/update/{id}', [AdminController::class, 'updateStudent']);
$router->get('/admin/students/delete/{id}', [AdminController::class, 'deleteStudent']);
$router->get('/admin/audit-logs', [AdminController::class, 'auditLogs']);

// Teacher Routes
$router->get('/teacher/dashboard', [TeacherController::class, 'dashboard']);
$router->get('/teacher/materials', [TeacherController::class, 'materials']);
$router->post('/teacher/materials', [TeacherController::class, 'storeMaterial']);
$router->post('/teacher/materials/store', [TeacherController::class, 'storeMaterial']);
$router->get('/teacher/materials/delete/{id}', [TeacherController::class, 'deleteMaterial']);
$router->get('/teacher/quizzes', [TeacherController::class, 'quizzes']);
$router->post('/teacher/quizzes/store', [TeacherController::class, 'storeQuiz']);
$router->get('/teacher/quizzes/delete/{id}', [TeacherController::class, 'deleteQuiz']);
$router->get('/teacher/quizzes/questions/{id}', [TeacherController::class, 'quizQuestions']);
$router->post('/teacher/quizzes/questions/store', [TeacherController::class, 'storeQuestion']);
$router->get('/teacher/quizzes/questions/delete/{id}', [TeacherController::class, 'deleteQuestion']);
$router->get('/teacher/assignments', [TeacherController::class, 'assignments']);
$router->post('/teacher/assignments/store', [TeacherController::class, 'storeAssignment']);
$router->get('/teacher/assignments/delete/{id}', [TeacherController::class, 'deleteAssignment']);
$router->get('/teacher/quizzes/report/{id}', [TeacherController::class, 'quizReport']);

// Student Routes
$router->get('/student/classes', [StudentController::class, 'classes']);
$router->get('/student/classroom/{id}', [StudentController::class, 'classroom']);
$router->get('/student/material/{id}', [StudentController::class, 'viewMaterial']);
$router->get('/student/quiz/{id}', [StudentController::class, 'viewQuiz']);
$router->post('/student/quiz/submit', [StudentController::class, 'submitQuiz']);

$app = new App($router);
$app->run();
