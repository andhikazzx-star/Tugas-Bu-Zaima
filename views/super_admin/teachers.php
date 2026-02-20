<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Guru</h2>
                <p class="text-gray-500">Kelola semua akun guru dan akses mereka.</p>
            </div>
            <button onclick="openModal('addTeacherModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Guru</span>
            </button>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pengguna
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Login
                                Terakhir
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Mengajar Di
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($teachers as $teacher): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-full flex items-center justify-center text-[#00ABE4] font-bold overflow-hidden bg-blue-50">
                                            <?php if (!empty($teacher['profile_image'])): ?>
                                                <img src="/EDUTEN2/public/uploads/profiles/<?php echo $teacher['profile_image']; ?>"
                                                    alt="<?php echo $teacher['full_name']; ?>"
                                                    class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo strtoupper(substr($teacher['full_name'], 0, 1)); ?>
                                            <?php endif; ?>
                                        </div>
                                        <span class="ml-3 font-medium text-gray-800">
                                            <?php echo $teacher['full_name']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    <?php echo $teacher['username']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $teacher['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                        <?php echo ucfirst($teacher['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    <?php echo $teacher['last_login'] ?? '-'; ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <?php if (!empty($teacher['class_list'])): ?>
                                            <?php foreach (explode(', ', $teacher['class_list']) as $cls): ?>
                                                <span
                                                    class="px-2 py-0.5 bg-blue-50 text-[#00ABE4] rounded text-[10px] font-bold"><?php echo $cls; ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Belum ada kelas</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick='editTeacher(<?php echo json_encode($teacher); ?>)'
                                        class="text-blue-500 hover:text-blue-700 p-2 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="/EDUTEN2/admin/teachers/delete/<?php echo $teacher['id']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus guru ini?')"
                                        class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div id="addTeacherModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tambah Guru Baru</h3>
                <button onclick="closeModal('addTeacherModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addTeacherForm" action="/EDUTEN2/admin/teachers/store" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="full_name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengguna <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="guru@eduten.com"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="profile_image" accept="image/*"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max. 2MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi <span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal('addTeacherModal')"
                        class="px-4 py-2 text-gray-600 font-medium hover:text-gray-800 transitions-colors">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf] transition-all">Tambah
                        Guru</button>
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

    function editTeacher(teacher) {
        document.getElementById('editTeacherForm').action = "/EDUTEN2/admin/teachers/update/" + teacher.id;
        document.getElementById('edit_full_name').value = teacher.full_name;
        document.getElementById('edit_username').value = teacher.username;
        document.getElementById('edit_email').value = teacher.email;
        document.getElementById('edit_status').value = teacher.status;
        openModal('editTeacherModal');
    }

    // Modal click outside logic
    window.onclick = function (event) {
        if (event.target.classList.contains('bg-opacity-50')) {
            event.target.classList.add('hidden');
            event.target.classList.remove('flex');
        }
    }
</script>

<!-- Edit Teacher Modal -->
<div id="editTeacherModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Guru</h3>
            <button onclick="closeModal('editTeacherModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editTeacherForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?php \App\Core\Helper::csrfField(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="full_name" id="edit_full_name" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengguna</label>
                <input type="text" name="username" id="edit_username" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="edit_email" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="edit_status"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                 <input type="file" name="profile_image" accept="image/*"
                       class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                 <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max. 2MB)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi (Kosongkan jika tidak ingin
                    diubah)</label>
                <input type="password" name="password"
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