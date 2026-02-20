<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Halo,
                <?php echo $_SESSION['full_name']; ?>!
            </h2>
            <p class="text-gray-500">Terus semangat! Selesaikan pembelajaranmu hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php if (empty($subjects)): ?>
                <div class="col-span-full card p-8 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <p class="text-gray-500 font-medium">Belum ada mata pelajaran yang ditugaskan untuk kelas Anda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($subjects as $subject): ?>
                    <div class="card p-6 bg-white relative overflow-hidden group">
                        <div class="flex flex-col h-full">
                            <div class="flex justify-between items-start mb-4">
                                <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center text-[#00ABE4]">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <span
                                    class="px-2 py-1 bg-blue-50 text-[#00ABE4] text-[10px] font-bold rounded italic uppercase tracking-wider">
                                    <?php echo $subject['class_name']; ?>
                                </span>
                            </div>
                            <h3 class="font-bold text-gray-800 text-lg group-hover:text-[#00ABE4] transition-colors">
                                <?php echo $subject['subject_name']; ?>
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Guru: <?php echo $subject['teacher_name']; ?></p>

                            <div class="mt-6">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-gray-500">Kemajuan Belajar</span>
                                    <span class="font-bold text-gray-800">0%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#00ABE4] h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end">
                            <a href="/EDUTEN2/student/classroom/<?php echo $subject['id']; ?>"
                                class="text-[#00ABE4] font-bold text-sm hover:underline">Masuk Kelas <i
                                    class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div
                class="card p-6 border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="h-12 w-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="text-gray-400 text-sm">Gabung kelas baru menggunakan kode kelas</p>
                <button
                    class="mt-4 px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50">Gabung
                    Kelas</button>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
