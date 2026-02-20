<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Kelas Saya</h2>
            <p class="text-gray-500">Akses materi pembelajaran dan kuis Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php if (empty($subjects)): ?>
                <div class="col-span-full card p-8 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <p class="text-gray-500 font-medium">Belum ada mata pelajaran untuk kelas Anda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($subjects as $subject): ?>
                    <div class="card p-6 border-t-4 border-[#00ABE4]">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    <?php echo $subject['subject_name']; ?>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    <?php echo $subject['major_name']; ?> - <?php echo $subject['class_name']; ?>
                                </p>
                            </div>
                            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-[#00ABE4]">
                                <i class="fas fa-book-reader text-xl"></i>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                <?php echo strtoupper(substr($subject['teacher_name'], 0, 1)); ?>
                            </div>
                            <div class="text-sm">
                                <p class="font-medium text-gray-700">
                                    <?php echo $subject['teacher_name']; ?>
                                </p>
                                <p class="text-xs text-gray-500 italic">Mata Pelajaran & Pengajar</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Progress Bar -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500">Penyelesaian Kursus</span>
                                    <span class="font-bold text-[#00ABE4]">0%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-[#00ABE4] h-1.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <a href="/EDUTEN2/student/classroom/<?php echo $subject['id']; ?>"
                            class="block w-full text-center mt-6 py-3 bg-[#00ABE4] text-white font-bold rounded-xl hover:bg-[#008ebf] transition-all">
                            Masuk Kelas
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
