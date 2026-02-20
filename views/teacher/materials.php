<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Materi Pembelajaran</h2>
                <p class="text-gray-500">Kelola silabus kelas dan sumber daya akademik Anda.</p>
            </div>
            <button onclick="openModal('addMaterialModal')"
                class="flex items-center justify-center space-x-2 px-6 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-[#008ebf] transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Materi</span>
            </button>
        </div>

        <?php if (\App\Core\Session::get('success')): ?>
            <div
                class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span><?php echo \App\Core\Session::get('success');
                    \App\Core\Session::remove('success'); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::get('error')): ?>
            <div
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo \App\Core\Session::get('error');
                    \App\Core\Session::remove('error'); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filter & Search -->
        <div class="card p-4 mb-8">
            <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" placeholder="Cari judul materi..."
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

        <div class="grid grid-cols-1 gap-6">
            <?php foreach ($materials as $material): ?>
                <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl flex items-center justify-center <?php
                        echo $material['type'] === 'video' ? 'bg-red-50 text-red-500' :
                            ($material['type'] === 'file' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500');
                        ?>">
                            <i class="fas <?php
                            echo $material['type'] === 'video' ? 'fa-play-circle' :
                                ($material['type'] === 'file' ? 'fa-file-pdf' : 'fa-align-left');
                            ?> text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">
                                <?php echo $material['title']; ?>
                            </h4>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-xs font-bold text-[#00ABE4] uppercase">
                                    <?php echo $material['class_name']; ?>
                                </span>
                                <span class="text-xs text-gray-400">Urutan:
                                    <?php echo $material['order_index']; ?>
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 capitalize">
                                    <?php echo $material['type']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <?php if ($material['type'] !== 'text' && !empty($material['file_path'])): ?>
                            <a href="/EDUTEN2/uploads/materials/<?php echo $material['file_path']; ?>" target="_blank"
                                class="text-blue-500 hover:bg-blue-50 px-3 py-1 rounded text-xs font-bold transition-all flex items-center">
                                <i class="fas fa-download mr-1"></i> Unduh
                            </a>
                        <?php endif; ?>

                        <a href="/EDUTEN2/teacher/materials/delete/<?php echo $material['id']; ?>"
                            onclick="return confirm('Hapus materi ini?')"
                            class="text-gray-400 hover:text-red-500 transition-colors p-2"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Materi Pembelajaran Baru</h3>
                <button onclick="closeModal('addMaterialModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm" action="/EDUTEN2/teacher/materials" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                <?php \App\Core\Helper::csrfField(); ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg text-xs mb-4">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]/20 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Tujuan <span
                                class="text-red-500">*</span></label>
                        <select name="assignment_id" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                            <option value="">Pilih Kelas/Mapel</option>
                            <?php foreach ($assignments as $a): ?>
                                <option value="<?php echo $a['id']; ?>"><?php echo $a['class_name']; ?> -
                                    <?php echo $a['subject_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Materi <span
                                class="text-red-500">*</span></label>
                        <select name="type" id="materialType" required onchange="updateUploadFields(this.value)"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none">
                            <option value="text">Teks/Artikel</option>
                            <option value="file">Upload Dokumen (PDF/Doc)</option>
                            <option value="video">Upload Video</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Dibuka (Opsional)</label>
                    <input type="datetime-local" name="opened_at"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#00ABE4] outline-none">
                    <p class="text-[10px] text-gray-400 mt-1 italic">* Kosongkan jika ingin langsung dibuka.</p>
                </div>

                <div id="fileUploadContainer" class="hidden">
                    <label id="uploadLabel" class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                    <input type="file" name="material_file" id="fileInput"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#00ABE4] hover:file:bg-blue-100">
                    <p id="sizeLimitText" class="text-[10px] text-gray-400 mt-1 italic"></p>
                </div>

                <div id="textContainer">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten / Ringkasan</label>
                    <textarea name="content" rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-[#00ABE4]"
                        placeholder="Masukkan teks materi atau deskripsi file..."></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal('addMaterialModal')"
                        class="text-gray-600 font-medium">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#00ABE4] text-white font-semibold rounded-lg hover:bg-[#008ebf] shadow-md transition-all">Simpan
                        Materi</button>
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

    function updateUploadFields(type) {
        const container = document.getElementById('fileUploadContainer');
        const label = document.getElementById('uploadLabel');
        const sizeLimit = document.getElementById('sizeLimitText');
        const fileInput = document.getElementById('fileInput');

        if (type === 'text') {
            container.classList.add('hidden');
            fileInput.required = false;
        } else {
            container.classList.remove('hidden');
            fileInput.required = true;
            if (type === 'file') {
                label.innerText = 'Upload Dokumen (PDF, Word, dll)';
                sizeLimit.innerText = '* Ukuran maksimal file: 3MB';
            } else if (type === 'video') {
                label.innerText = 'Upload Video (MP4, MKV, dll)';
                sizeLimit.innerText = '* Ukuran maksimal video: 15MB';
            }
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>