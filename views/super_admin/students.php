<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Siswa</h2>
                <p class="text-gray-500">Kelola semua daftar siswa dalam sistem.</p>
            </div>
            <button onclick="openModal('addStudentModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-user-plus"></i>
                <span>Tambah Siswa</span>
            </button>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email &
                                Username</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas &
                                Jurusan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($students as $student): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 bg-blue-50 rounded-full flex items-center justify-center text-[#00ABE4] font-bold overflow-hidden">
                                            <?php if (!empty($student['profile_image'])): ?>
                                                <img src="/EDUTEN2/public/uploads/profiles/<?php echo $student['profile_image']; ?>"
                                                    alt="<?php echo $student['full_name']; ?>"
                                                    class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-800">
                                                <?php echo $student['full_name']; ?>
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                <?php echo $student['phone_number'] ?? '-'; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">
                                        <?php echo $student['email']; ?>
                                    </p>
                                    <p class="text-xs text-gray-400">@
                                        <?php echo $student['username']; ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($student['class_name']): ?>
                                        <span class="px-2 py-1 bg-blue-50 text-[#00ABE4] rounded text-xs font-bold">
                                            <?php echo $student['class_name']; ?>
                                        </span>
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            <?php echo $student['major_name']; ?>
                                        </p>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic text-sm">Belum ada kelas</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $student['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                        <?php echo ucfirst($student['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick='editStudent(<?php echo json_encode($student); ?>)'
                                        class="text-blue-500 hover:text-blue-700 p-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="/EDUTEN2/admin/students/delete/<?php echo $student['id']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')"
                                        class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tambah Siswa Baru</h3>
                <button onclick="closeModal('addStudentModal')" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
            <form action="/EDUTEN2/admin/students/store" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                    </div>
                </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
            <input type="file" name="profile_image" accept="image/*"
                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max. 2MB)</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                <input type="text" name="phone_number" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <select name="class_id" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?php echo $c['id']; ?>">
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
        </div>
        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf]">Simpan
                Siswa</button>
        </div>
        </form>
    </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Ubah Data Siswa</h3>
                <button onclick="closeModal('editStudentModal')" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
            <form id="editStudentForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" id="edit_full_name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="edit_username" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                        <input type="text" name="phone_number" id="edit_phone_number" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                        <select name="class_id" id="edit_class_id" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?php echo $c['id']; ?>">
                                    <?php echo $c['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
</main>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
    function editStudent(student) {
        document.getElementById('editStudentForm').action = "/EDUTEN2/admin/students/update/" + student.id;
        document.getElementById('edit_full_name').value = student.full_name;
        document.getElementById('edit_username').value = student.username;
        document.getElementById('edit_email').value = student.email;
        document.getElementById('edit_phone_number').value = student.phone_number || '';
        document.getElementById('edit_class_id').value = student.class_id;
        document.getElementById('edit_status').value = student.status;
        openModal('editStudentModal');
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>