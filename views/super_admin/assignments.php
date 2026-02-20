<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Penugasan Pengajar</h2>
                <p class="text-gray-500">Tentukan guru mana yang mengajar mata pelajaran apa di kelas mana.</p>
            </div>
            <button onclick="openModal('addAssignmentModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-link"></i>
                <span>Tambah Penugasan</span>
            </button>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Mapel</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($assignments as $a): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <?php echo $a['teacher_name']; ?>
                                </td>
                                <td class="px-6 py-4 text-[#00ABE4] font-bold">
                                    <?php echo $a['subject_name']; ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">
                                    <?php echo $a['class_name']; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="/EDUTEN2/admin/assignments/delete/<?php echo $a['id']; ?>"
                                        onclick="return confirm('Hapus penugasan ini?')"
                                        class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="addAssignmentModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Assign Pengajar</h3>
                <button onclick="closeModal('addAssignmentModal')" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
            <form action="/EDUTEN2/admin/assignments/store" method="POST" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Guru</label>
                    <select name="teacher_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?php echo $t['id']; ?>">
                                <?php echo $t['full_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Mata Pelajaran</label>
                    <select name="subject_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo $s['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas (Bisa Pilih
                        Beberapa)</label>
                    <div
                        class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 border border-gray-200 rounded-xl bg-gray-50/50">
                        <?php foreach ($classes as $c): ?>
                            <label
                                class="flex items-center space-x-2 text-sm cursor-pointer hover:text-[#00ABE4] transition-colors group">
                                <input type="checkbox" name="class_ids[]" value="<?php echo $c['id']; ?>"
                                    class="w-4 h-4 rounded border-gray-300 text-[#00ABE4] focus:ring-[#00ABE4]">
                                <span class="text-gray-600 group-hover:text-gray-900"><?php echo $c['name']; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">* Guru akan mengajar mata pelajaran tersebut di
                        semua kelas yang dicentang.</p>
                </div>
                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('addAssignmentModal')"
                        class="px-4 py-2 text-gray-600 font-medium">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg">Assign</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
