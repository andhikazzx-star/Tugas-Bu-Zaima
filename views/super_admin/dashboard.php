<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang,
                <?php echo $_SESSION['full_name']; ?>!
            </h2>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card p-6 flex items-center space-x-4">
                <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center text-[#00ABE4]">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Guru</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $stats['teachers']; ?></p>
                </div>
            </div>

            <div class="card p-6 flex items-center space-x-4">
                <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Siswa</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $stats['students']; ?></p>
                </div>
            </div>

            <div class="card p-6 flex items-center space-x-4">
                <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-school text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Kelas</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $stats['classes']; ?></p>
                </div>
            </div>

            <div class="card p-6 flex items-center space-x-4">
                <div class="h-12 w-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                    <i class="fas fa-book-open text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Jurusan</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $stats['majors']; ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-gray-800 text-lg">Ikhtisar Penggunaan Sistem</h3>
                    <button class="text-[#00ABE4] text-sm font-semibold hover:underline">Lihat Semua</button>
                </div>
                <div
                    class="h-64 flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                    <p class="text-gray-400">[ Placeholder Grafik ]</p>
                </div>
            </div>

            <!-- Audit Logs Summary -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-6">Log Audit Terbaru</h3>
                <div class="space-y-4">
                    <?php if (empty($recentLogs)): ?>
                        <p class="text-sm text-gray-400 italic">Belum ada aktivitas.</p>
                    <?php else: ?>
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                                    <i class="fas fa-history text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800"><?php echo $log['action']; ?></p>
                                    <p class="text-xs text-gray-500"><?php echo $log['created_at']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
