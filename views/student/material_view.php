<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex items-center space-x-4 mb-8">
            <button onclick="history.back()"
                class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-[#00ABE4] transition-all">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <?php echo $material['title']; ?>
                </h2>
                <p class="text-gray-500">Materi Pembelajaran</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="card p-8">
                    <?php if ($material['type'] === 'video'): ?>
                        <div class="aspect-video bg-black rounded-xl mb-6 overflow-hidden">
                            <!-- Simple Video Integration -->
                            <iframe class="w-full h-full" src="<?php echo $material['video_url']; ?>" frameborder="0"
                                allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>

                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        <?php echo nl2br($material['content']); ?>
                    </div>

                    <?php if ($material['type'] === 'file' && !empty($material['file_path'])): ?>
                        <div
                            class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 text-[#00ABE4] rounded-lg flex items-center justify-center text-xl">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">Dokumen Materi</p>
                                    <p class="text-xs text-gray-400">Silakan unduh untuk dipelajari secara luar jaringan.
                                    </p>
                                </div>
                            </div>
                            <a href="/EDUTEN2/uploads/materials/<?php echo basename($material['file_path']); ?>" download
                                class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50 transition-all">
                                Unduh File
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-8 flex justify-end">
                    <button onclick="history.back()"
                        class="px-8 py-3 bg-[#00ABE4] text-white font-bold rounded-xl hover:shadow-lg hover:shadow-[#00ABE4]/20 transition-all">
                        Selesai Membaca
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Ringkasan</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span>Selesaikan materi ini untuk membuka kuis.</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                            <span>Anda dapat melihat kembali materi ini kapan saja.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>