<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Mata Pelajaran</h2>
                <p class="text-gray-500">Kelola semua daftar mata pelajaran dalam sistem.</p>
            </div>
            <button onclick="openModal('addSubjectModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Mapel</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($subjects as $subject): ?>
                <div class="card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-[#00ABE4]">
                                <i class="fas fa-book text-xl"></i>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick='editSubject(<?php echo json_encode($subject); ?>)'
                                    class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="/EDUTEN2/admin/subjects/delete/<?php echo $subject['id']; ?>"
                                    onclick="return confirm('Hapus mata pelajaran ini?')"
                                    class="text-gray-400 hover:text-red-500 transition-colors"><i
                                        class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800"><?php echo $subject['name']; ?></h3>
                        <p class="text-sm text-gray-400 font-medium">KODE: <?php echo $subject['code']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Subject Modal -->
    <div id="addSubjectModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tambah Mata Pelajaran</h3>
                <button onclick="closeModal('addSubjectModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="/EDUTEN2/admin/subjects/store" method="POST" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                    <input type="text" name="name" required placeholder="misal: Pemrograman Web"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mapel</label>
                    <input type="text" name="code" required placeholder="misal: WEB"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                </div>
                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('addSubjectModal')"
                        class="px-4 py-2 text-gray-600 font-medium">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
    function editSubject(subject) {
        document.getElementById('editSubjectForm').action = "/EDUTEN2/admin/subjects/update/" + subject.id;
        document.getElementById('edit_subject_name').value = subject.name;
        document.getElementById('edit_subject_code').value = subject.code;
        openModal('editSubjectModal');
    }
</script>

<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Ubah Mata Pelajaran</h3>
            <button onclick="closeModal('editSubjectModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editSubjectForm" action="" method="POST" class="p-6 space-y-4">
            <?php \App\Core\Helper::csrfField(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="name" id="edit_subject_name" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mapel</label>
                <input type="text" name="code" id="edit_subject_code" required
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
