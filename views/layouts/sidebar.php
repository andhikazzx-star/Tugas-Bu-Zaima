<?php
$role = $_SESSION['role'] ?? '';
$uri = $_SERVER['REQUEST_URI'];
?>
<aside class="w-64 h-screen sidebar fixed left-0 top-0 border-r border-gray-100 flex flex-col z-50">
    <div class="p-6 shrink-0">
        <h1 class="text-2xl font-bold text-[#00ABE4] tracking-tight">EDUTEN</h1>
    </div>

    <div class="flex-1 overflow-y-auto px-4 space-y-2 py-2 custom-scrollbar">
        <nav class="space-y-2">
            <a href="/EDUTEN2/dashboard"
                class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'dashboard') !== false ? 'active' : ''; ?>">
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'dashboard') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                    <i class="fas fa-home text-sm"></i>
                </div>
                <span class="font-semibold text-sm">Beranda</span>
            </a>

            <?php if ($role === 'super_admin'): ?>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen</span>
                </div>
                <a href="/EDUTEN2/admin/teachers"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'teachers') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'teachers') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-chalkboard-teacher text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Data Guru</span>
                </a>
                <a href="/EDUTEN2/admin/students"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'students') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'students') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Data Siswa</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akademik</span>
                </div>
                <a href="/EDUTEN2/admin/majors"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'majors') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'majors') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-graduation-cap text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Jurusan</span>
                </a>
                <a href="/EDUTEN2/admin/subjects"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'subjects') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'subjects') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-book text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Mata Pelajaran</span>
                </a>
                <a href="/EDUTEN2/admin/classes"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'classes') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'classes') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-school text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Kelas</span>
                </a>
                <a href="/EDUTEN2/admin/assignments"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'assignments') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'assignments') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-link text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Penugasan Guru</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem</span>
                </div>
                <a href="/EDUTEN2/admin/audit-logs"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'audit-logs') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'audit-logs') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Log Audit</span>
                </a>
            <?php elseif ($role === 'teacher'): ?>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akademik</span>
                </div>
                <a href="/EDUTEN2/teacher/materials"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'materials') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'materials') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-book text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Materi</span>
                </a>
                <a href="/EDUTEN2/teacher/quizzes"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'quizzes') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'quizzes') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-tasks text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Kuis</span>
                </a>

                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem</span>
                </div>
                <a href="/EDUTEN2/teacher/assignments"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo strpos($uri, 'assignments') !== false ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo strpos($uri, 'assignments') !== false ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Pengaturan Mengajar</span>
                </a>
            <?php elseif ($role === 'student'): ?>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akademik</span>
                </div>
                <a href="/EDUTEN2/student/classes"
                    class="flex items-center px-4 py-3 text-gray-600 rounded-xl nav-link transition-all <?php echo (strpos($uri, 'classes') !== false || strpos($uri, 'classroom') !== false) ? 'active' : ''; ?>">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 <?php echo (strpos($uri, 'classes') !== false || strpos($uri, 'classroom') !== false) ? 'bg-white/10 text-white' : 'bg-gray-50'; ?>">
                        <i class="fas fa-book-reader text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Mata Pelajaran</span>
                </a>
            <?php endif; ?>
        </nav>
        <div class="h-20"></div> <!-- Spacer for absolute bottom or just padding -->
    </div>

    <div class="p-4 border-t border-gray-50 shrink-0 space-y-2">
        <a href="/EDUTEN2/profile"
            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-all group <?php echo strpos($uri, 'profile') !== false ? 'active bg-gray-50 text-[#00ABE4]' : ''; ?>">
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-gray-50 group-hover:bg-white group-hover:shadow-sm transition-all">
                <i class="fas fa-user-circle text-xs"></i>
            </div>
            <span class="font-semibold text-sm">Edit Akun</span>
        </a>

        <a href="/EDUTEN2/logout"
            class="flex items-center px-4 py-3 text-red-500 hover:bg-red-50/50 rounded-xl transition-all group">
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-red-50 group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-sign-out-alt text-xs"></i>
            </div>
            <span class="font-bold text-sm">Keluar</span>
        </a>
    </div>
</aside>