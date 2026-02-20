<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen pb-20">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="mb-8">
            <a href="/EDUTEN2/teacher/quizzes"
                class="text-sm text-[#00ABE4] font-semibold hover:underline flex items-center mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kuis
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        <?php echo $quiz['title']; ?>
                    </h2>
                    <p class="text-gray-500">Kelola pertanyaan pilihan ganda dan essay untuk kuis ini.</p>
                </div>
                <button onclick="openModal('addQuestionModal')"
                    class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pertanyaan</span>
                </button>
            </div>
        </div>

        <div class="space-y-6">
            <?php if (empty($questions)): ?>
                <div class="card p-12 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <div
                        class="h-16 w-16 bg-blue-50 rounded-full flex items-center justify-center text-[#00ABE4] mx-auto mb-4">
                        <i class="fas fa-question text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pertanyaan</h3>
                    <p class="text-gray-500">Mulai buat pertanyaan untuk kuis ini agar siswa bisa mengerjakannya.</p>
                </div>
            <?php else: ?>
                <?php foreach ($questions as $index => $q): ?>
                    <div
                        class="card p-6 border-l-4 <?php echo $q['type'] === 'mcq' ? 'border-blue-400' : 'border-purple-400'; ?>">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-3">
                                <span
                                    class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-gray-600">
                                    <?php echo $index + 1; ?>
                                </span>
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?php echo $q['type'] === 'mcq' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'; ?>">
                                    <?php echo $q['type'] === 'mcq' ? 'Pilihan Ganda' : 'Essay'; ?>
                                </span>
                                <span class="text-xs font-bold text-gray-400">
                                    <?php echo $q['point']; ?> POIN
                                </span>
                            </div>
                            <a href="/EDUTEN2/teacher/quizzes/questions/delete/<?php echo $q['id']; ?>"
                                onclick="return confirm('Hapus pertanyaan ini?')"
                                class="text-red-400 hover:text-red-600 transition-colors p-2">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>

                        <div class="text-gray-800 font-medium text-lg mb-4">
                            <?php echo nl2br($q['question_text']); ?>
                        </div>

                        <?php if ($q['type'] === 'mcq' && isset($q['options'])): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php foreach ($q['options'] as $opt): ?>
                                    <div
                                        class="px-4 py-3 rounded-xl border <?php echo $opt['is_correct'] ? 'bg-green-50 border-green-200 text-green-700 font-semibold' : 'bg-gray-50 border-gray-100 text-gray-600'; ?> flex items-center">
                                        <span class="mr-3">
                                            <?php echo $opt['is_correct'] ? '<i class="fas fa-check-circle"></i>' : '<i class="far fa-circle"></i>'; ?>
                                        </span>
                                        <?php echo $opt['option_text']; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Question Modal -->
    <div id="addQuestionModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all shadow-2xl">
            <div
                class="px-8 py-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-bold text-gray-800">Tambah Pertanyaan</h3>
                <button onclick="closeModal('addQuestionModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="/EDUTEN2/teacher/quizzes/questions/store" method="POST" class="p-8 space-y-6">
                <?php \App\Core\Helper::csrfField(); ?>
                <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-md">Tipe
                            Pertanyaan</label>
                        <select name="type" id="questionType" onchange="toggleOptions(this.value)"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#00ABE4] bg-gray-50 font-medium">
                            <option value="mcq">Pilihan Ganda</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-md">Poin</label>
                        <input type="number" name="point" value="10"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#00ABE4] bg-gray-50 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-md">Teks
                        Pertanyaan</label>
                    <textarea name="question_text" rows="4" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#00ABE4] bg-gray-50"
                        placeholder="Tuliskan pertanyaan Anda di sini..."></textarea>
                </div>

                <div id="mcqOptions" class="space-y-4 pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-md">Opsi
                        Jawaban & Jawaban Benar</label>

                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="flex items-center space-x-4 group">
                            <div class="flex-1">
                                <input type="text" name="options[]" placeholder="Opsi <?php echo chr(65 + $i); ?>"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#00ABE4] bg-gray-50 group-hover:bg-white transition-all">
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="correct_option" value="<?php echo $i; ?>" <?php echo $i === 0 ? 'checked' : ''; ?> class="hidden peer">
                                <div
                                    class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-300 peer-checked:border-green-500 peer-checked:text-green-500 transition-all">
                                    <i class="fas fa-check"></i>
                                </div>
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeModal('addQuestionModal')"
                        class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700">Batal</button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#00ABE4] text-white font-bold rounded-xl shadow-lg shadow-blue-100 hover:shadow-blue-200 transition-all">Simpan
                        Pertanyaan</button>
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

    function toggleOptions(type) {
        const mcqBox = document.getElementById('mcqOptions');
        if (type === 'essay') {
            mcqBox.classList.add('hidden');
        } else {
            mcqBox.classList.remove('hidden');
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
