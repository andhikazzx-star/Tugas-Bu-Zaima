<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Log Audit Sistem</h2>
                <p class="text-gray-500">Riwayat semua tindakan penting yang dilakukan dalam sistem.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="window.location.href='/EDUTEN2/admin/audit-logs'"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card p-6 mb-8">
            <form action="/EDUTEN2/admin/audit-logs" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama / Username</label>
                    <input type="text" name="name" value="<?php echo $filters['name'] ?? ''; ?>"
                        placeholder="Cari pengguna..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-100 focus:border-[#00ABE4] outline-none text-sm transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tindakan</label>
                    <input type="text" name="action" value="<?php echo $filters['action'] ?? ''; ?>"
                        placeholder="Misal: LOGIN, CREATE_..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-100 focus:border-[#00ABE4] outline-none text-sm transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal</label>
                    <input type="date" name="date" value="<?php echo $filters['date'] ?? ''; ?>"
                        class="w-full px-4 py-2 rounded-lg border border-gray-100 focus:border-[#00ABE4] outline-none text-sm transition-all">
                </div>
                <button type="submit"
                    class="bg-[#00ABE4] text-white font-bold py-2 rounded-lg hover:bg-[#008ebf] transition-all text-sm">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </form>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tindakan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Detail</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat IP
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                    <?php echo $log['created_at']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700">
                                            <?php echo $log['full_name'] ?? ($log['username'] ?? 'Sistem'); ?>
                                        </span>
                                        <?php if (isset($log['username'])): ?>
                                            <span class="text-[10px] text-gray-400">@<?php echo $log['username']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-blue-100 text-blue-700">
                                        <?php echo $log['action']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $log['details']; ?>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 font-mono">
                                    <?php echo $log['ip_address']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">Menampilkan <?php echo count($logs); ?> log yang ditemukan</p>
                <div class="flex space-x-2">
                    <button
                        class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-400 cursor-not-allowed">Sebelumnya</button>
                    <button
                        class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Berikutnya</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>