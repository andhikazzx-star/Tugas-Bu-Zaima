<?php
// Session is already started by App::run()

require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/sidebar.php';
?>

<main class="flex-1 md:ml-64 min-h-screen">
    <?php require_once __DIR__ . '/layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil
                <?php echo ucfirst($user['role'] ?? 'Pengguna'); ?>
            </h2>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="/EDUTEN2/profile/update" method="POST" enctype="multipart/form-data"
                    class="p-8 space-y-8">
                    <?php \App\Core\Helper::csrfField(); ?>

                    <div class="flex flex-col md:flex-row gap-8 items-start">

                        <!-- ================= PROFILE IMAGE ================= -->
                        <div class="w-full md:w-1/3 flex flex-col items-center space-y-4">
                            <div class="relative group">
                                <div
                                    class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                                    <?php if (!empty($user['profile_image'] ?? null)): ?>
                                        <img src="/EDUTEN2/public/uploads/profiles/<?php echo $user['profile_image']; ?>"
                                            class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-blue-50 text-[#00ABE4]">
                                            <i class="fas fa-user text-5xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <label for="profile_image"
                                    class="absolute bottom-2 right-2 bg-[#00ABE4] text-white p-2 rounded-full cursor-pointer">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="hidden"
                                    accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>

                        <!-- ================= FORM ================= -->
                        <div class="w-full md:w-2/3 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- NAMA -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" name="full_name" value="<?php echo $user['full_name'] ?? ''; ?>"
                                        required class="w-full px-4 py-2 rounded-lg border border-gray-200">
                                </div>

                                <!-- USERNAME -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Pengguna
                                    </label>
                                    <input type="text" name="username" value="<?php echo $user['username'] ?? ''; ?>"
                                        required class="w-full px-4 py-2 rounded-lg border border-gray-200">
                                </div>

                                <!-- EMAIL -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>"
                                        required class="w-full px-4 py-2 rounded-lg border border-gray-200">
                                </div>

                                <!-- PHONE -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon
                                    </label>
                                    <input type="text" name="phone_number"
                                        value="<?php echo $user['phone_number'] ?? ''; ?>"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-200">
                                </div>
                            </div>

                            <!-- ================= PASSWORD ================= -->
                            <div class="border-t border-gray-100 pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ganti Kata Sandi</h3>

                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Kata Sandi Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" id="new_password"
                                            class="w-full px-4 py-2 rounded-lg border border-gray-200">
                                        <button type="button" onclick="togglePassword('new_password')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="px-8 py-3 bg-[#00ABE4] text-white font-semibold rounded-xl">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const container = input.previousElementSibling.previousElementSibling;
                let img = container.querySelector('img');

                if (!img) {
                    container.innerHTML = '';
                    img = document.createElement('img');
                    img.className = 'w-full h-full object-cover';
                    container.appendChild(img);
                }
                img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>