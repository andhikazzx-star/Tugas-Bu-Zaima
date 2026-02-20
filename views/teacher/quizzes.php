<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Kuis</h2>
                <p class="text-gray-500">Buat dan beri nilai penilaian untuk siswa Anda.</p>
            </div>
            <button onclick="openModal('addQuizModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Buat Kuis</span>
            </button>
        </div>

        <!-- Filter & Search -->
        <div class="card p-4 mb-8">
            <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" placeholder="Cari judul kuis..."
                        value="<?php echo $filters['search']; ?>"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-100 focus:border-[#00ABE4] outline-none transition-all">
                </div>
                <div class="w-full md:w-64">
                    <select name="class_id" onchange="this.form.submit()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-100 appearance-none bg-gray-50 font-semibold text-gray-700 outline-none">
                        <option value="">Semua Kelas</option>
                        <?php
                        $classesSeen = [];
                        foreach ($assignments as $a):
                            if (in_array($a['class_id'], $classesSeen))
                                continue;
                            $classesSeen[] = $a['class_id'];
                            ?>
                            <option value="<?php echo $a['class_id']; ?>" <?php echo $filters['class_id'] == $a['class_id'] ? 'selected' : ''; ?>>
                                <?php echo $a['class_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit"
                    class="hidden md:block px-6 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-black transition-all">
                    Filter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (empty($quizzes)): ?>
                <div class="col-span-full card p-8 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <p class="text-gray-500 font-medium">Belum ada kuis yang dibuat.</p>
                </div>
            <?php else: ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="card p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="h-10 w-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 font-bold">
                                Q
                            </div>
                            <span class="px-2 py-1 bg-blue-50 text-[#00ABE4] text-[10px] font-bold rounded">
                                <?php echo $quiz['class_name']; ?>
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg"><?php echo $quiz['title']; ?></h3>
                        <p class="text-sm text-gray-500 mt-1"><?php echo $quiz['material_title']; ?></p>
                        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">KKM</p>
                                <p class="text-sm font-bold text-gray-700"><?php echo $quiz['passing_score']; ?></p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="/EDUTEN2/teacher/quizzes/questions/<?php echo $quiz['id']; ?>"
                                    class="text-blue-500 hover:bg-blue-50 px-3 py-1 rounded text-xs font-bold transition-all flex items-center">
                                    <i class="fas fa-list-ul mr-1"></i> Pertanyaan
                                </a>
                                <a href="/EDUTEN2/teacher/quizzes/report/<?php echo $quiz['id']; ?>"
                                    class="text-green-500 hover:bg-green-50 px-3 py-1 rounded text-xs font-bold transition-all flex items-center">
                                    <i class="fas fa-poll-h mr-1"></i> Rekap Nilai
                                </a>
                                <a href="/EDUTEN2/teacher/quizzes/delete/<?php echo $quiz['id']; ?>"
                                    onclick="return confirm('Hapus kuis ini?')" class="text-red-500 hover:text-red-700 p-2"><i
                                        class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Quiz Modal -->
    <div id="addQuizModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Kuis Baru</h3>
                <button onclick="closeModal('addQuizModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addQuizForm" action="/EDUTEN2/teacher/quizzes/store" method="POST" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hubungkan dengan Materi <span
                            class="text-red-500">*</span></label>
                    <select name="material_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                        <option value="">Pilih Materi...</option>
                        <?php foreach ($materials as $m): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo $m['class_name']; ?> -
                                <?php echo $m['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kuis <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skor Kelulusan (KKM) <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="passing_score" value="75" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Dibuka (Opsional)</label>
                    <input type="datetime-local" name="opened_at"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] outline-none">
                    <p class="text-[10px] text-gray-400 mt-1 italic">* Kosongkan jika ingin langsung dibuka.</p>
                </div>
                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal('addQuizModal')"
                        class="text-gray-600 font-medium">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf]">Buat
                        Kuis</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>