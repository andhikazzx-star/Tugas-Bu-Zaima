<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Jurusan</h2>
                <p class="text-gray-500">Kelola jurusan dan departemen akademik.</p>
            </div>
            <button onclick="openModal('addMajorModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Jurusan</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($majors as $major): ?>
                <div class="card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-[#00ABE4]">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick='editMajor(<?php echo json_encode($major); ?>)'
                                    class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="/EDUTEN2/admin/majors/delete/<?php echo $major['id']; ?>"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')"
                                    class="text-gray-400 hover:text-red-500 transition-colors"><i
                                        class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">
                            <?php echo $major['name']; ?>
                        </h3>
                        <p class="text-sm text-gray-400 font-medium">CODE:
                            <?php echo $major['code']; ?>
                        </p>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-gray-50 pt-4">
                        <div class="text-center">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Siswa</p>
                            <p class="text-lg font-bold text-gray-800">
                                <?php echo $major['student_count']; ?>
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Kelas</p>
                            <p class="text-lg font-bold text-gray-800">
                                <?php echo $major['class_count']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Major Modal -->
    <div id="addMajorModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tambah Jurusan Baru</h3>
                <button onclick="closeModal('addMajorModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMajorForm" action="/EDUTEN2/admin/majors/store" method="POST" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="misal: Rekayasa Perangkat Lunak"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="misal: RPL"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal('addMajorModal')"
                        class="px-4 py-2 text-gray-600 font-medium hover:text-gray-800 transitions-colors">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf] transition-all">Tambah
                        Jurusan</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function editMajor(major) {
        document.getElementById('editMajorForm').action = "/EDUTEN2/admin/majors/update/" + major.id;
        document.getElementById('edit_major_name').value = major.name;
        document.getElementById('edit_major_code').value = major.code;
        openModal('editMajorModal');
    }
</script>

<!-- Edit Major Modal -->
<div id="editMajorModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Jurusan</h3>
            <button onclick="closeModal('editMajorModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editMajorForm" action="" method="POST" class="p-6 space-y-4">
            <?php \App\Core\Helper::csrfField(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan</label>
                <input type="text" name="name" id="edit_major_name" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan</label>
                <input type="text" name="code" id="edit_major_code" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
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
