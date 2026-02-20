<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Kelas</h2>
                <p class="text-gray-500">Kelola semua kelompok kelas dan guru yang ditugaskan.</p>
            </div>
            <button onclick="openModal('addClassModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Kelas</span>
            </button>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kelas
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($classes as $class): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#00ABE4]">
                                        <?php echo $class['name']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    <?php echo $class['major_name']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center text-[10px] text-[#00ABE4] font-bold">
                                            <?php echo substr($class['teacher_name'] ?? 'B', 0, 1); ?>
                                        </div>
                                        <span class="text-sm text-gray-700">
                                            <?php echo $class['teacher_name'] ?? 'Belum ditugaskan'; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    <?php echo $class['student_count']; ?> anggota
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick='editClass(<?php echo json_encode($class); ?>)'
                                        class="text-blue-500 hover:text-blue-700 p-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="/EDUTEN2/admin/classes/delete/<?php echo $class['id']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')"
                                        class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div id="addClassModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tambah Kelas Baru</h3>
                <button onclick="closeModal('addClassModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addClassForm" action="/EDUTEN2/admin/classes/store" method="POST" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="misal: XI-RPL-1"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jurusan <span
                            class="text-red-500">*</span></label>
                    <select name="major_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                        <option value="">Pilih jurusan...</option>
                        <?php foreach ($majors as $major): ?>
                            <option value="<?php echo $major['id']; ?>"><?php echo $major['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tugaskan Guru <span
                            class="text-red-500">*</span></label>
                    <select name="teacher_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                        <option value="">Pilih guru...</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['full_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal('addClassModal')"
                        class="px-4 py-2 text-gray-600 font-medium hover:text-gray-800 transitions-colors">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf] transition-all">Tambah
                        Kelas</button>
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
    function editClass(cls) {
        document.getElementById('editClassForm').action = "/EDUTEN2/admin/classes/update/" + cls.id;
        document.getElementById('edit_class_name').value = cls.name;
        document.getElementById('edit_major_id').value = cls.major_id;
        // Search teacher ID if possible, but some classes might not have one assigned via model yet.
        // For now let's just preset class name and major.
        openModal('editClassModal');
    }
</script>

<!-- Edit Class Modal -->
<div id="editClassModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Kelas</h3>
            <button onclick="closeModal('editClassModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editClassForm" action="" method="POST" class="p-6 space-y-4">
            <?php \App\Core\Helper::csrfField(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input type="text" name="name" id="edit_class_name" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                <select name="major_id" id="edit_major_id" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                    <?php foreach ($majors as $m): ?>
                        <option value="<?php echo $m['id']; ?>"><?php echo $m['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pengajar Utama</label>
                <select name="teacher_id" id="edit_teacher_id"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                    <option value="">-- Tanpa Pengajar --</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo $t['full_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf]">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
