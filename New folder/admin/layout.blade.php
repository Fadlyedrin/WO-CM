<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Elegant Weddings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            color: #a3aed1;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
            border-top-right-radius: 24px;
            border-bottom-right-radius: 24px;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.02);
        }

        .sidebar-brand {
            padding: 30px 20px;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0px;
            color: #2b3674;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand i {
            color: #4318ff;
        }

        .sidebar-nav {
            padding: 10px 0;
            list-style: none;
            margin: 0;
        }
        
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #a3aed1;
            padding: 15px 25px 5px;
            letter-spacing: 1px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: #a3aed1;
            padding: 12px 20px;
            margin: 0 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(67, 24, 255, 0.05);
            color: #4318ff;
            border-left: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: transparent;
            padding: 25px 30px 10px;
            box-shadow: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .content-area {
            padding: 10px 30px 30px;
            flex-grow: 1;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
            background-color: #ffffff;
        }

        .card-header {
            background: #fff;
            border-bottom: none;
            padding: 24px 24px 10px;
            font-weight: 700;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .btn-primary {
            background-color: #4318ff;
            border-color: #4318ff;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 16px;
        }
        
        .btn-primary:hover {
            background-color: #3311db;
            border-color: #3311db;
        }

        .table th {
            background-color: transparent;
            font-weight: 600;
            text-transform: capitalize;
            font-size: 0.85rem;
            color: #a3aed1;
            border-bottom: 1px solid #f4f7fe;
        }

        .ck-editor__editable_inline {
            min-height: 300px;
        }

        /* ===== ADMIN RESPONSIVE ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }
        .sidebar-overlay.show { display: block; }
        .sidebar-toggle-btn {
            display: none;
            background: #fff;
            border: none;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #2b3674;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                z-index: 200;
                transition: transform 0.3s ease;
                border-radius: 0 24px 24px 0;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            .topbar {
                left: 0 !important;
                width: 100% !important;
            }
            .sidebar-toggle-btn {
                display: flex;
            }
            .content-area {
                padding: 1rem !important;
            }
        }
        @media (max-width: 576px) {
            .topbar { padding: 0.5rem 1rem !important; }
            .card { border-radius: 12px !important; }
            table { font-size: 0.82rem; }
            .btn-sm { font-size: 0.75rem; padding: 0.3rem 0.6rem; }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div class="sidebar" id="adminSidebar">
        <a href="{{ route('home') }}" class="sidebar-brand"><i class="bi bi-grid-fill"></i> Admin Sanggar</a>
        <ul class="sidebar-nav">
            <li class="nav-label">Main Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}"
                    href="{{ route('admin.calendar') }}"><i class="bi bi-calendar4"></i> Calendar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}"><i class="bi bi-cart"></i> Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"
                    href="{{ route('admin.packages.index') }}"><i class="bi bi-box"></i> Packages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
                    href="{{ route('admin.galleries.index') }}"><i class="bi bi-image"></i> Gallery</a>
            </li>
            <li class="nav-label">Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.promos.*') ? 'active' : '' }}"
                    href="{{ route('admin.promos.index') }}"><i class="bi bi-percent"></i> Promos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}"><i class="bi bi-person"></i> Users</a>
            </li>
            <li class="nav-label">Support</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}"
                    href="{{ route('admin.inquiries.index') }}">
                    <i class="bi bi-chat-dots"></i> Inquiries
                    @php $unreadCount = \App\Models\Inquiry::where('is_read', false)->count(); @endphp
                    @if ($unreadCount > 0)
                        <span class="badge bg-danger ms-auto rounded-pill">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" onclick="openSidebar()" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-white shadow-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" style="border: none; background: #fff;">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 25px; height: 25px; font-size: 0.8rem;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px;">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}">View Site</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-area">
            @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ addslashes(session("success")) }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
                });
            </script>
            @endif
            @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ addslashes(session("error")) }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true });
                });
            </script>
            @endif
            @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errList = @json($errors->all());
                    const errHtml = errList.map(e => `<li style="text-align:left;">${e}</li>`).join('');
                    Swal.fire({ icon: 'warning', title: 'Periksa Formulir Anda', html: `<ul style="padding-left:1.2rem;margin:0;">${errHtml}</ul>`, confirmButtonText: 'Mengerti', confirmButtonColor: '#4318ff' });
                });
            </script>
            @endif

            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.1.0/"
            }
        }
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph,
            List
        } from 'ckeditor5';

        window.initCKEditor = function(textarea) {
            ClassicEditor
                .create(textarea, {
                    plugins: [Essentials, Bold, Italic, Font, Paragraph, List],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        };

        const textareas = document.querySelectorAll('.ckeditor-field');
        textareas.forEach(textarea => {
            window.initCKEditor(textarea);
        });
    </script>
    @stack('scripts')
    <script>
        function openSidebar() {
            document.getElementById('adminSidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('show');
        }
        function closeSidebar() {
            document.getElementById('adminSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
    </script>
</body>

</html>
