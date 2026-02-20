<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Mengajar</h2>
                <p class="text-gray-500">Tentukan mata pelajaran dan kelas yang Anda tinggali.</p>
            </div>
            <button onclick="document.getElementById('addAssignmentModal').classList.remove('hidden')"
                class="bg-[#00ABE4] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#008ebf] transition-all shadow-lg shadow-[#00ABE4]/20 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Pengaturan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($assignments)): ?>
                <div class="md:col-span-2 lg:col-span-3 card p-12 text-center bg-gray-50 border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pengaturan</h3>
                    <p class="text-gray-500 max-w-xs mx-auto">Silakan tambahkan mata pelajaran dan kelas yang Anda ajar untuk mulai mengelola materi.</p>
                </div>
            <?php else: ?>
                <?php foreach ($assignments as $assignment): ?>
                    <div class="card p-6 border border-gray-100 hover:border-[#00ABE4] transition-all group relative">
                        <div class="absolute top-4 right-4">
                            <a href="/EDUTEN2/teacher/assignments/delete/<?php echo $assignment['id']; ?>" 
                               onclick="return confirm('Hapus pengaturan mengajar ini? Semua materi terkait mungkin tidak lagi terlihat di kelas ini.')"
                               class="text-gray-300 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-[#00ABE4] rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#00ABE4] group-hover:text-white transition-all">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 mb-1"><?php echo $assignment['subject_name']; ?></h4>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <i class="fas fa-school mr-2 text-xs"></i>
                            <span>Kelas: <?php echo $assignment['class_name']; ?></span>
                        </div>
                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                            <a href="/EDUTEN2/teacher/materials" class="text-xs font-bold text-[#00ABE4] hover:underline">Kelola Materi</a>
                            <span class="px-2 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-full">AKTIF</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal Tambah Pengaturan -->
<div id="addAssignmentModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-slide-up">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Tambah Pengaturan Mengajar</h3>
                <button onclick="document.getElementById('addAssignmentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="/EDUTEN2/teacher/assignments/store" method="POST" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Mata Pelajaran</label>
                    <select name="subject_id" required class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:border-[#00ABE4] outline-none transition-all appearance-none bg-gray-50">
                        <option value="">Pilih Mata Pelajaran...</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['name']; ?> (<?php echo $s['code']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kelas (Bisa pilih lebih dari satu)</label>
                    <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1 custom-scrollbar">
                        <?php foreach ($classes as $c): ?>
                            <label class="flex items-center p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all select-none group">
                                <input type="checkbox" name="class_ids[]" value="<?php echo $c['id']; ?>" class="peer hidden">
                                <div class="w-5 h-5 border-2 border-gray-200 rounded-lg flex items-center justify-center peer-checked:border-[#00ABE4] peer-checked:bg-[#00ABE4] mr-3 transition-all">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-gray-800 peer-checked:text-[#00ABE4]"><?php echo $c['name']; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-[#00ABE4] text-white font-bold rounded-xl hover:bg-[#008ebf] transition-all shadow-lg shadow-[#00ABE4]/20">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
