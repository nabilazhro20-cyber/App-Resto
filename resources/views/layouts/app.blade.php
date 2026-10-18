<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Resto Admin')</title>

    <link rel="stylesheet"
          href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <style>
        :root {
            --nav-height: 56px;
            --sidebar-width: 250px;
        }

        body {
            background: #f4f6f9;
        }

        .app-navbar {
            height: var(--nav-height);
            z-index: 1040;
        }

        .app-sidebar {
            position: fixed;
            top: var(--nav-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: #212529;
            overflow-y: auto;
            transition: transform .2s ease;
            z-index: 1030;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--nav-height) + 24px) 24px 24px;
            transition: margin-left .2s ease;
        }

        body.sidebar-hidden .app-sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-hidden .main-content {
            margin-left: 0;
        }

        .sidebar-link {
            display: block;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: .75rem 1rem;
            border-radius: .5rem;
            margin: .25rem .75rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255,255,255,.12);
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            body.sidebar-mobile-open .app-sidebar {
                transform: translateX(0);
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <main class="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/vuejs/vue.global.js') }}"></script>

    <script>
        const btnSidebar = document.getElementById('btnSidebar');

        btnSidebar.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                document.body.classList.toggle('sidebar-mobile-open');
            } else {
                document.body.classList.toggle('sidebar-hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
