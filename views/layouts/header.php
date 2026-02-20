<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?? 'EDUTEN LMS'; ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E9F1FA;
        }

        .sidebar {
            background-color: #FFFFFF;
        }

        .nav-link.active {
            background-color: #00ABE4;
            color: #FFFFFF;
        }

        .nav-link:not(.active):hover {
            background-color: #F3F4F6;
        }

        .card {
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }

        @media (max-width: 768px) {
            .sidebar-mobile-active {
                transform: translateX(0) !important;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 45;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('sidebar-mobile-active');
            overlay.classList.toggle('active');
        }
    </script>
</head>

<body class="min-h-screen">
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="flex">