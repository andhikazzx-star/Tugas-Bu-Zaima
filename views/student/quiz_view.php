<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen bg-gray-50/50">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <button onclick="if(confirm('Batalkan pengerjaan kuis?')) history.back()"
                        class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 transition-all shadow-sm">
                        <i class="fas fa-times"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <?php echo $quiz['title']; ?>
                        </h2>
                        <p class="text-sm text-gray-500">Skor Minimal Kelulusan: <span
                                class="font-bold text-orange-500">
                                <?php echo $quiz['passing_score']; ?>
                            </span></p>
                    </div>
                </div>

                <div
                    class="hidden md:flex items-center space-x-3 bg-white px-6 py-2 rounded-2xl shadow-sm border border-gray-100">
                    <i class="far fa-clock text-[#00ABE4]"></i>
                    <span class="font-bold text-gray-700" id="timer">--:--</span>
                </div>
            </div>

            <form action="/EDUTEN2/student/quiz/submit" method="POST" id="quizForm">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                <input type="hidden" name="assignment_id" value="<?php echo $_GET['assignment_id'] ?? ''; ?>">

                <div class="space-y-6">
                    <?php if (empty($questions)): ?>
                        <div class="card p-12 text-center">
                            <i class="fas fa-exclamation-circle text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Kuis ini belum memiliki pertanyaan.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($questions as $index => $q): ?>
                            <div class="card p-8 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="shrink-0 h-10 w-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center font-bold text-lg">
                                        <?php echo $index + 1; ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-lg text-gray-800 font-medium leading-relaxed mb-6">
                                            <?php echo nl2br($q['question_text']); ?>
                                        </p>

                                        <?php if ($q['type'] === 'mcq'): ?>
                                            <div class="grid grid-cols-1 gap-3">
                                                <?php foreach ($q['options'] as $opt): ?>
                                                    <label
                                                        class="relative flex items-center p-4 border border-gray-100 rounded-2xl cursor-pointer hover:bg-blue-50/50 hover:border-blue-100 transition-all group">
                                                        <input type="radio" name="answers[<?php echo $q['id']; ?>]"
                                                            value="<?php echo $opt['id']; ?>" class="peer hidden" required>
                                                        <div
                                                            class="w-5 h-5 border-2 border-gray-200 rounded-full flex items-center justify-center peer-checked:border-[#00ABE4] peer-checked:bg-[#00ABE4] transition-all mr-4">
                                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                                        </div>
                                                        <span
                                                            class="text-gray-700 font-medium peer-checked:text-[#00ABE4] transition-colors">
                                                            <?php echo $opt['option_text']; ?>
                                                        </span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <textarea name="answers[<?php echo $q['id']; ?>]"
                                                class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#00ABE4]/20 outline-none transition-all placeholder:text-gray-400"
                                                rows="4" placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="pt-8 pb-12 flex justify-center">
                            <button type="submit"
                                class="group relative px-12 py-4 bg-[#00ABE4] text-white font-black uppercase tracking-widest rounded-2xl hover:bg-[#008ebf] transition-all shadow-xl shadow-[#00ABE4]/20">
                                Kirim Jawaban
                                <i class="fas fa-paper-plane ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Simple visual timer only
    let seconds = 0;
    setInterval(() => {
        seconds++;
        let m = Math.floor(seconds / 60).toString().padStart(2, '0');
        let s = (seconds % 60).toString().padStart(2, '0');
        document.getElementById('timer').innerText = m + ':' + s;
    }, 1000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>