<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex items-center space-x-4 mb-8">
            <a href="/EDUTEN2/student/dashboard"
                class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-[#00ABE4] transition-all">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <?php echo $assignment['subject_name']; ?>
                </h2>
                <p class="text-gray-500">
                    <?php echo $assignment['class_name']; ?> •
                    <?php echo $assignment['teacher_name']; ?>
                </p>
            </div>
        </div>

        <!-- Material List -->
        <div class="lg:col-span-2 space-y-6">
            <?php if (empty($materials)): ?>
                <div class="card p-8 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <p class="text-gray-500">Materi belum tersedia untuk mata pelajaran ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($materials as $index => $material): ?>
                    <div
                        class="card overflow-hidden <?php echo ($material['is_locked'] || $material['is_time_locked']) ? 'opacity-60 grayscale' : ''; ?>">
                        <div class="p-6 flex items-center justify-between border-b border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="h-12 w-12 <?php echo ($material['is_locked'] || $material['is_time_locked']) ? 'bg-gray-100 text-gray-400' : 'bg-blue-50 text-[#00ABE4]'; ?> rounded-xl flex items-center justify-center font-bold">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">
                                        <?php echo $material['title']; ?>
                                    </h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                            <i class="far fa-file-alt mr-1"></i> <?php echo strtoupper($material['type']); ?>
                                        </span>
                                        <?php if ($material['status'] === 'completed'): ?>
                                            <span class="text-[10px] font-bold text-green-500 uppercase tracking-wider">
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                            </span>
                                        <?php elseif ($material['is_time_locked']): ?>
                                            <span class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">
                                                <i class="far fa-calendar-alt mr-1"></i> Dibuka:
                                                <?php echo date('d M, H:i', strtotime($material['opened_at'])); ?>
                                            </span>
                                        <?php elseif ($material['is_locked']): ?>
                                            <span class="text-[10px] font-bold text-red-400 uppercase tracking-wider">
                                                <i class="fas fa-lock mr-1"></i> Terkunci
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!$material['is_locked'] && !$material['is_time_locked']): ?>
                                <a href="/EDUTEN2/student/material/<?php echo $material['id']; ?>"
                                    class="px-6 py-2 bg-[#00ABE4] text-white font-bold rounded-lg hover:bg-[#008ebf] transition-all text-sm">
                                    <?php echo $material['status'] === 'completed' ? 'Lihat Kembali' : 'Pelajari'; ?>
                                </a>
                            <?php else: ?>
                                <i class="fas fa-lock text-gray-300 mr-4"></i>
                            <?php endif; ?>
                        </div>

                        <!-- Quiz Section within Material Card -->
                        <?php if ($material['quiz']): ?>
                            <div class="px-6 py-4 bg-gray-50/50 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center">
                                        <i class="fas fa-tasks text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-700"><?php echo $material['quiz']['title']; ?></p>
                                        <p class="text-[10px] text-gray-500">Skor Minimal:
                                            <?php echo $material['quiz']['passing_score']; ?>
                                        </p>
                                    </div>
                                </div>

                                <?php if (!$material['is_locked'] && $material['status'] === 'completed' && !$material['quiz']['is_time_locked']): ?>
                                    <div class="flex items-center space-x-4">
                                        <?php if (isset($material['quiz_attempt'])): ?>
                                            <div class="text-right mr-2">
                                                <p
                                                    class="text-[10px] font-bold uppercase tracking-wider <?php echo $material['quiz_attempt']['status'] === 'passed' ? 'text-green-500' : 'text-red-500'; ?>">
                                                    <?php echo $material['quiz_attempt']['status'] === 'passed' ? 'LULUS' : 'GAGAL'; ?>
                                                </p>
                                                <p class="text-sm font-black text-gray-800">
                                                    <?php echo $material['quiz_attempt']['score']; ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <a href="/EDUTEN2/student/quiz/<?php echo $material['quiz']['id']; ?>?assignment_id=<?php echo $assignment['id']; ?>"
                                            class="px-4 py-1.5 <?php echo isset($material['quiz_attempt']) && $material['quiz_attempt']['status'] === 'passed' ? 'bg-gray-100 text-gray-500' : 'bg-orange-500 text-white'; ?> font-bold rounded-lg hover:opacity-90 transition-all text-xs">
                                            <?php echo isset($material['quiz_attempt']) ? 'Coba Lagi' : 'Kerjakan Kuis'; ?>
                                        </a>
                                    </div>
                                <?php elseif ($material['quiz']['is_time_locked']): ?>
                                    <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest"><i
                                            class="far fa-calendar-alt mr-1"></i> Dibuka:
                                        <?php echo date('d M, H:i', strtotime($material['quiz']['opened_at'])); ?></span>
                                <?php elseif ($material['is_locked'] || $material['status'] !== 'completed'): ?>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><i
                                            class="fas fa-lock mr-1"></i> Terkunci</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Subject Stats Sidebar -->
        <div class="space-y-6">
            <!-- Lecturer Card -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-tie text-[#00ABE4] mr-2"></i> Pengajar
                </h3>
                <div class="flex items-center space-x-4">
                    <div
                        class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center text-[#00ABE4] font-bold text-xl">
                        <?php echo strtoupper(substr($assignment['teacher_name'], 0, 1)); ?>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 leading-none mb-1">
                            <?php echo $assignment['teacher_name']; ?>
                        </p>
                        <p class="text-[11px] text-gray-400 uppercase font-bold tracking-wider">Guru Mata Pelajaran</p>
                    </div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-green-500 mr-2"></i> Statistik Saya
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Materi Selesai</span>
                        <span class="text-sm font-bold text-gray-800">
                            <?php echo $stats['completed']; ?> / <?php echo $stats['total']; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Kuis Lulus</span>
                        <span class="text-sm font-bold text-gray-800">
                            <?php echo $stats['passed_quizzes']; ?> / <?php echo $stats['total']; ?>
                        </span>
                    </div>
                    <div class="pt-2">
                        <?php $perc = $stats['total'] > 0 ? ($stats['completed'] / $stats['total']) * 100 : 0; ?>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Progress Belajar</span>
                            <span class="text-[10px] font-bold text-[#00ABE4]"><?php echo round($perc); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#00ABE4] h-2 rounded-full transition-all duration-1000"
                                style="width: <?php echo $perc; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guidance Card -->
            <div class="card p-6 bg-gradient-to-br from-[#00ABE4] to-[#008ebf] text-white">
                <h3 class="font-bold mb-2">Penting!</h3>
                <p class="text-xs text-white/80 leading-relaxed">
                    Selesaikan materi dan kuis secara berurutan untuk membuka akses ke materi selanjutnya. Selamat
                    belajar!
                </p>
            </div>
        </div>
    </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>