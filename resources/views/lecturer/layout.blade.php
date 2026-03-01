<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Giảng viên')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, #6B7BD9 0%, #6B4B9D 59%, #6B4B9D 100%);
            color: #2d2d2d;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Menu Bar - Modern */
        .menu-bar {
            background: linear-gradient(90deg, #6B7BD9 0%, #6B4B9D 100%);
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            box-shadow: 0 2px 12px rgba(41, 33, 70, 0.15);
            z-index: 1000;
        }

        .menu-bar-logo {
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.15));
        }

        .menu-bar-menu {
            display: flex;
            gap: 20px;
            flex: 1;
            font-weight: 500;
        }

        .menu-item-top {
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            padding: 6px 12px;
            transition: all 0.2s;
            border-radius: 4px;
            user-select: none;
        }

        .menu-item-top:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .menu-bar-user {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-name-top {
            font-size: 13px;
            font-weight: 500;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            font-size: 16px;
        }

        .user-avatar:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Main Layout */
        .wimp-container {
            display: flex;
            flex: 1;
            margin: 6px;
            gap: 8px;
        }

        /* Navigation Panel */
        .nav-panel {
            width: 220px;
            background: linear-gradient(180deg, #6B7BD9 0%, #6B4B9D 59%, #6B4B9D 100%);
            border-radius: 8px;
            padding: 16px 12px;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(41, 33, 70, 0.15);
            display: flex;
            flex-direction: column;
        }

        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-title {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .nav-item-wimp {
            padding: 10px 12px;
            margin: 2px 0;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 6px;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-item-wimp i {
            width: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .nav-item-wimp:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .nav-item-wimp.active {
            background: linear-gradient(90deg, #8595F6 0%, #b99ce9 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .nav-item-wimp.active i {
            color: white;
        }

        .nav-logout {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .nav-logout button {
            width: 100%;
            background: linear-gradient(90deg, #d881cb 0%, #c86fb8 100%);
            border: none;
            color: white !important;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }

        .nav-logout button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Content Wrapper */
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f6f3ff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(41, 33, 70, 0.1);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .main-content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
        }

        .content-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d2d2d;
            margin: 0;
        }

        .content-info {
            font-size: 13px;
            color: #8f8a9e;
        }

        .content-info strong {
            color: #2d2d2d;
            font-weight: 600;
        }

        /* Status Bar */
        .status-bar {
            background: linear-gradient(90deg, rgba(133, 149, 246, 0.1), rgba(185, 156, 233, 0.1));
            border-top: 1px solid rgba(107, 123, 217, 0.15);
            padding: 8px 20px;
            display: flex;
            gap: 20px;
            font-size: 12px;
            color: #6973a3;
            border-radius: 0 0 8px 8px;
        }

        .status-bar-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-bar-item i {
            color: #6B4B9D;
        }

        /* Cards */
        .card {
            background: white;
            border: 1px solid #ece8f6;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(41, 33, 70, 0.08);
            padding: 16px;
            margin-bottom: 16px;
        }

        .card-header {
            background: linear-gradient(90deg, #8595F6 0%, #b99ce9 100%);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 16px;
            margin: -16px -16px 16px -16px;
            font-weight: 600;
            font-size: 14px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            border-left: 4px solid;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert.success {
            background: #ebf8f1;
            border-color: #1f7a46;
            color: #23683f;
        }

        .alert.error {
            background: #fff0f0;
            border-color: #b93131;
            color: #7d1d1d;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: white;
        }

        th {
            background: linear-gradient(90deg, #f0ecfa, #f0ecfa);
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ece8f6;
            color: #6e6785;
            font-size: 12px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ebe7f5;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #faf8ff;
        }

        tbody tr.selected {
            background: linear-gradient(90deg, #8595F6 0%, #b99ce9 100%);
            color: white;
        }

        /* Buttons */
        .btn-wimp {
            background: linear-gradient(135deg, #8595F6 0%, #b99ce9 100%);
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 12px;
            cursor: pointer;
            color: white;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(107, 123, 217, 0.2);
        }

        .btn-wimp:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 123, 217, 0.3);
        }

        .btn-wimp:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(107, 123, 217, 0.2);
        }

        /* Badge */
        .badge {
            display: inline-block;
            background: #ece9ff;
            border: 1px solid #d9cff5;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            color: #6B4B9D;
            font-weight: 600;
        }

        .badge.ok {
            background: #e8f7ef;
            border-color: #bce3cc;
            color: #1f7a46;
        }

        .badge.warn {
            background: #fff3db;
            border-color: #f3d0a2;
            color: #9a6100;
        }

        /* Form Container Styles */
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #F6F3FF;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 8px 0;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: white;
            color: #1e293b;
            border: 1.5px solid #cbd5e0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial, sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #6B4B9D;
            box-shadow: 0 0 0 3px rgba(107, 75, 157, 0.1);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
        }

        .form-row {
            display: grid;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.cols-2 {
            grid-template-columns: 1fr 1fr;
        }

        .form-row.cols-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #F6F3FF;
        }

        .btn-submit {
            background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #5a6ac8 0%, #5a3a8c 100%);
            box-shadow: 0 4px 12px rgba(107, 75, 157, 0.3);
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .form-hint {
            background: #F6F3FF;
            border-left: 4px solid #6B4B9D;
            padding: 12px 16px;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            margin: 20px 0;
        }

        .form-hint strong {
            color: #6B4B9D;
        }

        @media (max-width: 900px) {
            .nav-panel {
                width: 180px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }
        }

        @media (max-width: 600px) {
            .wimp-container {
                flex-direction: column;
            }

            .nav-panel {
                width: 100%;
                max-height: 200px;
            }

            .menu-bar-menu {
                display: none;
            }

            .menu-bar {
                flex-wrap: wrap;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Menu Bar -->
    <div class="menu-bar">
        <div class="menu-bar-logo">
            <i class="fas fa-graduation-cap"></i>
            <span>Hệ Thống Quản Lý Tín Chỉ</span>
        </div>
        <div class="menu-bar-menu">
            <span class="menu-item-top">Tệp</span>
            <span class="menu-item-top">Chỉnh sửa</span>
            <span class="menu-item-top">Xem</span>
            <span class="menu-item-top">Công cụ</span>
            <span class="menu-item-top">Trợ giúp</span>
        </div>
        <div class="menu-bar-user">
            <span class="user-name-top">{{ auth()->user()->name }}</span>
            <a href="{{ route('lecturer.profile') }}" class="user-avatar" title="Hồ sơ cá nhân">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="wimp-container">
        <!-- Navigation Panel -->
        <div class="nav-panel">
            <div class="nav-section">
                <div class="nav-section-title">CHỨC NĂNG</div>
                <a href="{{ route('lecturer.dashboard') }}" class="nav-item-wimp {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    Thời khóa biểu
                </a>
                <a href="{{ route('lecturer.classes') }}" class="nav-item-wimp {{ request()->routeIs('lecturer.classes*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Lớp giảng dạy
                </a>
                <a href="{{ route('lecturer.notifications') }}" class="nav-item-wimp {{ request()->routeIs('lecturer.notifications') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    Thông báo
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">TÀI KHOẢN</div>
                <a href="{{ route('lecturer.profile') }}" class="nav-item-wimp {{ request()->routeIs('lecturer.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    Hồ sơ cá nhân
                </a>
                <a href="{{ route('lecturer.password.change') }}" class="nav-item-wimp {{ request()->routeIs('lecturer.password.change') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    Đổi mật khẩu
                </a>
            </div>

            <div class="nav-logout">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Main Content -->
            <div class="main-content">
                <div class="main-content-header">
                    <div>
                        <h1 class="content-title">@yield('title', 'Giảng viên')</h1>
                        <div class="content-info">
                            <strong>{{ auth()->user()->name }}</strong> • {{ auth()->user()->code }}
                        </div>
                    </div>
                </div>

                @if(session('status'))
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')
            </div>

            <!-- Status Bar -->
            <div class="status-bar">
                <div class="status-bar-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Sẵn sàng</span>
                </div>
                <div class="status-bar-item" style="margin-left: auto;">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update time in status bar
        setInterval(function() {
            var now = new Date();
            var time = now.getHours().toString().padStart(2, '0') + ':' +
                       now.getMinutes().toString().padStart(2, '0') + ':' +
                       now.getSeconds().toString().padStart(2, '0');
            document.getElementById('current-time').textContent = time;
        }, 1000);

        // Direct Manipulation - Row Selection
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'TR' && e.target.parentElement.tagName === 'TBODY') {
                document.querySelectorAll('tbody tr.selected').forEach(row => {
                    row.classList.remove('selected');
                });
                e.target.classList.add('selected');
            }
        });
    </script>
    @yield('scripts')
</body>

</html>