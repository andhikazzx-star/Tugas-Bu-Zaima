<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
    <div class="flex items-center">
        <!-- Mobile menu button -->
        <button onclick="toggleSidebar()"
            class="md:hidden text-gray-600 mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <div class="flex items-center space-x-6">
        <div class="flex items-center space-x-4">
            <a href="/EDUTEN2/profile" class="flex items-center space-x-3 cursor-pointer group">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-[#00ABE4] transition-colors">
                        <?php echo $_SESSION['full_name']; ?>
                    </p>
                    <p class="text-xs text-gray-500 capitalize">
                        <?php echo str_replace('_', ' ', $_SESSION['role']); ?>
                    </p>
                </div>
                <div
                    class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-[#00ABE4] border-2 border-white shadow-sm overflow-hidden">
                    <?php if (!empty($_SESSION['profile_image'])): ?>
                        <img src="/EDUTEN2/public/uploads/profiles/<?php echo $_SESSION['profile_image']; ?>" alt="Profile"
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
            </a>

            <div class="h-8 w-px bg-gray-100 mx-2"></div>

            <a href="/EDUTEN2/logout"
                class="h-10 w-10 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm group"
                title="Keluar">
                <i class="fas fa-sign-out-alt text-sm transition-transform group-hover:translate-x-0.5"></i>
            </a>
        </div>
    </div>
</header>