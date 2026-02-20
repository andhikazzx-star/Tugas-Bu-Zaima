<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Halo Guru,
                <?php echo $_SESSION['full_name']; ?>!
            </h2>
            <p class="text-gray-500">Pantau kemajuan siswa Anda dan kelola kelas Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($assignments as $assignment): ?>
                <div class="card p-6 bg-gradient-to-br from-[#00ABE4] to-[#008ebf] text-white">
                    <p class="text-sm opacity-80 font-medium"><?php echo $assignment['subject_name']; ?></p>
                    <p class="text-2xl font-bold mt-1"><?php echo $assignment['class_name']; ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xs font-semibold px-2 py-1 bg-white/20 rounded-lg">
                            <?php echo $assignment['major_name']; ?>
                        </span>
                        <a href="/EDUTEN2/teacher/materials?assignment=<?php echo $assignment['id']; ?>"
                            class="text-white hover:underline text-xs font-bold">Kelola Konten <i
                                class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="card p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-6">Siswa Butuh Perhatian</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold">
                                JD</div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">John Doe (XI-RPL-1)</p>
                                <p class="text-xs text-red-600 font-medium italic underline cursor-pointer">Skor di
                                    bawah
                                    KKM (65/75)</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-6">Tenggat Mendatang</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-xl flex items-center space-x-4 border-l-4 border-blue-400">
                        <div class="text-center w-12">
                            <p class="text-xs font-bold text-gray-400 uppercase">Feb</p>
                            <p class="text-xl font-bold text-gray-800">12</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Kuis Pemrograman PHP</p>
                            <p class="text-xs text-gray-500">Dijadwalkan jam 10:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
