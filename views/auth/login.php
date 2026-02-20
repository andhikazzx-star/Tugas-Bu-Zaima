<?php use App\Core\Session; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EDUTEN</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-[#00ABE4]">EDUTEN</h1>
            <p class="text-gray-500 mt-2">
                Selamat datang kembali! Silakan masuk ke akun Anda.
            </p>
        </div>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <p class="text-red-700 text-sm">
                    <?php echo $error; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="login" method="POST">

            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo Session::generateCsrfToken(); ?>">

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="Masukkan email Anda"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200
                           focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]
                           focus:ring-opacity-20 outline-none transition-all">
            </div>

            <!-- Password -->
            <div class="mb-8">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi
                </label>

                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-200
                               focus:border-[#00ABE4] focus:ring-2 focus:ring-[#00ABE4]
                               focus:ring-opacity-20 outline-none transition-all">

                    <!-- Eye Button -->
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center
                               text-gray-400 hover:text-[#00ABE4]">

                        <!-- Eye Icon -->
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                   c4.478 0 8.268 2.943 9.542 7
                                   -1.274 4.057-5.064 7-9.542 7
                                   -4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full btn-primary text-white font-semibold
                       py-3 rounded-lg shadow-lg shadow-blue-200">
                Masuk
            </button>

            <!-- Register -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Belum memiliki akun?
                    <a href="register"
                        class="text-[#00ABE4] font-semibold hover:underline">
                        Daftar sebagai Siswa
                    </a>
                </p>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            &copy; <?php echo date('Y'); ?> EDUTEN Learning Management System
        </div>
    </div>

    <!-- Toggle Password Script -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19
                           c-4.478 0-8.268-2.943-9.543-7
                           a9.956 9.956 0 012.645-4.033M6.18 6.18
                           A9.956 9.956 0 0112 5
                           c4.478 0 8.268 2.943 9.543 7
                           a9.958 9.958 0 01-4.043 5.132M15 12
                           a3 3 0 00-3-3M3 3l18 18" />
                `;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                           c4.478 0 8.268 2.943 9.542 7
                           -1.274 4.057-5.064 7-9.542 7
                           -4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>

</html>
