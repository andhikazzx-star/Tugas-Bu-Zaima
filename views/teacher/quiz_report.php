<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex items-center space-x-4 mb-8">
            <a href="/EDUTEN2/teacher/quizzes"
                class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-[#00ABE4] transition-all">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Nilai Kuis</h2>
                <p class="text-gray-500">
                    <?php echo $quiz['title']; ?> •
                    <?php echo $quiz['class_name']; ?> -
                    <?php echo $quiz['subject_name']; ?>
                </p>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Daftar Mahasiswa / Siswa</h3>
                <div class="text-xs font-bold text-gray-400 uppercase">
                    KKM: <span class="text-[#00ABE4] ml-1">
                        <?php echo $quiz['passing_score']; ?>
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Skor Terakhir</th>
                            <th class="px-6 py-4 text-center">Hasil</th>
                            <th class="px-6 py-4 text-right">Tanggal Mengerjakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($students as $index => $s): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo $index + 1; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400">
                                            <?php echo strtoupper(substr($s['full_name'], 0, 1)); ?>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">
                                            <?php echo $s['full_name']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo $s['username']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($s['attempt']): ?>
                                        <span
                                            class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">SUDAH</span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 py-1 bg-gray-50 text-gray-400 text-[10px] font-bold rounded-full">BELUM</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($s['attempt']): ?>
                                        <?php
                                        $isPassing = $s['attempt']['score'] >= $quiz['passing_score'];
                                        $colorClass = $isPassing ? 'bg-green-500 text-white shadow-green-100' : 'bg-red-500 text-white shadow-red-100';
                                        ?>
                                        <div
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-black text-sm shadow-lg <?php echo $colorClass; ?>">
                                            <?php echo round($s['attempt']['score']); ?>
                                        </div>
                                    <?php else: ?>
                                        <div
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-300 font-bold text-sm">
                                            -
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($s['attempt']): ?>
                                        <span
                                            class="text-xs font-bold <?php echo $isPassing ? 'text-green-500' : 'text-red-500'; ?>">
                                            <?php echo $isPassing ? 'LULUS' : 'GAGAL'; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-300">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-gray-400">
                                    <?php echo $s['attempt'] ? date('d/m/Y H:i', strtotime($s['attempt']['created_at'])) : '-'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($students)): ?>
                <div class="p-12 text-center">
                    <p class="text-gray-400 italic">Belum ada siswa yang terdaftar di kelas ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>