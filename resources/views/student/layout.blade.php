<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sinh viên')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, #6B7BD9 0%, #6B4B9D 59%, #6B4B9D 100%);
            color: #2d2d2d;
        }

        .student-shell {
            width: 100%;
            min-height: 100vh;
            padding: 6px;
        }

        .student-shell-inner {
            display: grid;
            grid-template-columns: 230px 1fr;
            min-height: calc(100vh - 12px);
            gap: 8px;
            max-width: none;
            margin: 0;
        }

        .sidebar {
            background: linear-gradient(180deg, #6B7BD9 0%, #6B4B9D 59%, #6B4B9D 100%);
            border-radius: 8px;
            padding: 20px 14px 14px;
            display: flex;
            flex-direction: column;
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .08);
        }

        .sidebar-brand {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .16);
        }

        .sidebar-brand .logo {
            font-size: 34px;
            line-height: 1;
            margin-bottom: 8px;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .25));
        }

        .sidebar-brand .name {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .6px;
        }

        .sidebar-brand .sub {
            font-size: 10px;
            opacity: .88;
            margin-top: 4px;
            letter-spacing: .4px;
        }

        .sidebar nav {
            display: grid;
            gap: 7px;
            padding: 8px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .06);
        }

        .nav-item,
        .sidebar button.nav-item {
            width: 100%;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, .95);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }

        .nav-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .16);
            font-size: 14px;
            flex-shrink: 0;
        }

        .nav-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-item:hover,
        .sidebar button.nav-item:hover {
            background: rgba(255, 255, 255, .22);
            transform: translateX(2px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, #8595F6 0%, #b99ce9 100%);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .18);
        }

        .nav-item.active::before {
            content: "";
            position: absolute;
            left: -8px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .9);
        }

        .nav-item.active .nav-icon {
            background: rgba(255, 255, 255, .28);
        }

        .logout-wrap {
            margin-top: auto;
            padding-top: 14px;
        }

        .logout-btn {
            background: linear-gradient(90deg, #d881cb 0%, #c86fb8 100%) !important;
            color: #fff !important;
            justify-content: center;
            font-weight: 600;
        }

        .logout-btn .nav-icon {
            background: rgba(255, 255, 255, .24);
        }

        .main {
            background: #f6f3ff;
            border-radius: 8px;
            padding: 18px;
        }

        .topbar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .profile-quick-link {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            background: #6B4B9D;
            box-shadow: 0 2px 8px rgba(60, 45, 97, .2);
            transition: all .2s;
            font-size: 16px;
        }

        .profile-quick-link:hover {
            background: #55337d;
            transform: translateY(-1px);
        }

        .search-box {
            width: min(100%, 360px);
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            padding: 11px 16px;
            border-radius: 8px;
            background: #c9d2ff;
            color: #4b4b4b;
            font-size: 13px;
        }

        .search-box input::placeholder {
            color: #6973a3;
        }

        .content {
            background: #f6f3ff;
            border-radius: 6px;
            min-height: calc(100vh - 180px);
        }

        .grid {
            display: grid;
            gap: 14px;
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }

        @media (min-width: 960px) {
            .grid-2 {
                grid-template-columns: 1.2fr .9fr;
            }
        }

        .card {
            background: #fff;
            border: 1px solid #ece8f6;
            border-radius: 6px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(41, 33, 70, .1);
            margin-bottom: 14px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            background: #6B4B9D;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }

        .btn:hover {
            background: #55337d;
            transform: translateY(-1px);
        }

        .btn.danger {
            background: #d9534f;
        }

        .btn.danger:hover {
            background: #bf3f3a;
        }

        .status {
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 10px;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .status.success {
            background: #ebf8f1;
            border-color: #bce3cc;
            color: #23683f;
        }

        .status.error {
            background: #fff0f0;
            border-color: #f3c1c1;
            color: #9b2c2c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ebe7f5;
            vertical-align: top;
        }

        th {
            background: #f8f6fc;
            color: #6e6785;
            font-weight: 600;
            font-size: 12px;
        }

        tbody tr:hover {
            background: #faf8ff;
        }

        .badge {
            display: inline-block;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.info {
            background: #ece9ff;
            color: #6B4B9D;
        }

        .badge.ok {
            background: #e8f7ef;
            color: #1f7a46;
        }

        .badge.warn {
            background: #fff3db;
            color: #9a6100;
        }

        .badge.danger {
            background: #ffe9e9;
            color: #b93131;
        }

        .muted {
            color: #8f8a9e;
            font-size: 12px;
        }

        .toast {
            position: fixed;
            right: 16px;
            bottom: 16px;
            background: #3b2a59;
            color: #fff;
            padding: 12px 16px;
            border-radius: 6px;
            min-width: 220px;
            opacity: 0;
            transform: translateY(10px);
            transition: all .3s;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .2);
            z-index: 99;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 900px) {
            .student-shell-inner {
                grid-template-columns: 1fr;
            }

            .sidebar {
                min-height: auto;
            }

            .logout-wrap {
                margin-top: 8px;
            }

            table thead {
                display: none;
            }

            table tr {
                display: block;
                border: 1px solid #ebe7f5;
                border-radius: 6px;
                margin-bottom: 10px;
                padding: 8px;
                background: #fff;
            }

            table td {
                display: block;
                border-bottom: none;
                padding: 6px 0;
            }
        }
    </style>
</head>

<body>
    <div class="student-shell">
        <div class="student-shell-inner">
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <div class="logo">🎓</div>
                    <div class="name">ĐẠI HỌC ABC</div>
                    <div class="sub">CỔNG SINH VIÊN</div>
                </div>

                <nav>
                    <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Trang chủ</span>
                    </a>
                    <a href="{{ route('student.profile.show') }}" class="nav-item {{ request()->routeIs('student.profile.show') ? 'active' : '' }}">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Hồ sơ cá nhân</span>
                    </a>
                    <a href="{{ route('student.offerings') }}" class="nav-item {{ request()->routeIs('student.offerings') || request()->routeIs('student.my') ? 'active' : '' }}">
                        <span class="nav-icon">🗓️</span>
                        <span class="nav-text">Đăng ký trực tuyến</span>
                    </a>
                    <a href="{{ route('student.timetable') }}" class="nav-item {{ request()->routeIs('student.timetable') ? 'active' : '' }}">
                        <span class="nav-icon">🧾</span>
                        <span class="nav-text">Thời khóa biểu</span>
                    </a>
                    <a href="{{ route('student.notifications') }}" class="nav-item {{ request()->routeIs('student.notifications') ? 'active' : '' }}">
                        <span class="nav-icon">🔔</span>
                        <span class="nav-text">Tin tức</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">📘</span>
                        <span class="nav-text">Hướng dẫn sử dụng</span>
                    </a>
                </nav>

                <div class="logout-wrap">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-item logout-btn">
                            <span class="nav-icon">↩</span>
                            <span class="nav-text">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="main">
                <div class="topbar">
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm thông tin" />
                    </div>
                    <a href="{{ route('student.profile.show') }}" class="profile-quick-link" title="Hồ sơ cá nhân" aria-label="Hồ sơ cá nhân">👤</a>
                </div>

                <section class="content">
                    @if(session('status'))
                    <div class="status success">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="status error">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </section>
            </main>
        </div>
    </div>
    <div id="toast" class="toast" role="status"></div>
    <script>
        (function() {
            var t = document.getElementById('toast');
            var msg = @json(session('status') ?? session('error'));
            if (msg) {
                t.textContent = msg;
                t.classList.add('show');
                setTimeout(function() {
                    t.classList.remove('show');
                }, 2600);
            }
        })();
    </script>
</body>

</html>