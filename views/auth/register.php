<?php use App\Core\Session; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - EDUTEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E9F1FA;
        }

        .btn-primary {
            background-color: #00ABE4;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #008ebf;
            transform: translateY(-1px);
        }

        .form-input {
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #00ABE4;
            box-shadow: 0 0 0 3px rgba(0, 171, 228, 0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 py-12">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 bg-[#00ABE4] p-8 text-white flex flex-col justify-center">
                <h2 class="text-3xl font-bold mb-4">Mari Bergabung!</h2>
                <p class="text-blue-100 mb-8">Daftarkan dirimu untuk mulai belajar di platform EDUTEN.</p>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-blue-200"></i>
                        <span class="text-sm">Akses Materi Lengkap</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-blue-200"></i>
                        <span class="text-sm">Kuis Interaktif</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-blue-200"></i>
                        <span class="text-sm">Pantau Kemajuanmu</span>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3 p-10">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Registrasi Siswa</h1>
                    <a href="login" class="text-sm text-[#00ABE4] font-semibold hover:underline">Sudah punya akun?</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="register" method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo Session::generateCsrfToken(); ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama
                                Lengkap</label>
                            <input type="text" name="full_name" required placeholder="Jaka Tarub"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Username</label>
                            <input type="text" name="username" required placeholder="jaka_tarub"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" name="email" required placeholder="jaka@example.com"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No.
                                Telepon</label>
                            <input type="tel" name="phone_number" required placeholder="0812..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih
                                Kelas</label>
                            <select name="class_id" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input bg-white">
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>">
                                        <?php echo $class['name']; ?> -
                                        <?php echo $class['major_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kata
                            Sandi</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none form-input pr-10">
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full btn-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-100 mt-4">
                        Daftar Sekarang
                    </button>
                </form>

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
                </script>
                </form>
            </div>
        </div>
    </div>
</body>

</html>