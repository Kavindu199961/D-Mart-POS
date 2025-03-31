<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    
    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


</head>


<!-- Sidebar Navigation -->
<div class="navigation">
    <ul>
         <li>
            <a href="#">
                <img src="{{ asset('img/logo.jpg') }}" alt="Mobile Shop Logo" class="logo" style="width: 280px; height: 100px;">
            </a>
        </li>

        <li class="{{ Request::routeIs('billing.index') ? 'hovered' : '' }}">
            <a href="{{ route('billing.index') }}" class="stock-link">
                <span class="icon">
                    <ion-icon name="grid-outline"></ion-icon> <!-- Dashboard Icon -->
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li class="{{ Request::routeIs('stock.index') ? 'hovered' : '' }}">
            <a href="{{ route('stock.index') }}" class="stock-link">
                <span class="icon">
                    <ion-icon name="cube-outline"></ion-icon> <!-- Updated Icon -->
                </span>
                <span class="title">Stock</span>
            </a>
        </li>

        <li class="{{ Request::routeIs('invoices.index') ? 'hovered' : '' }}">
            <a href="{{ route('invoices.index') }}" class="stock-link">
                <span class="icon">
                    <ion-icon name="document-text-outline"></ion-icon>
                </span>
                <span class="title">Invoices</span>
            </a>
        </li>

        <li class="{{ Request::routeIs('report.index') ? 'hovered' : '' }}">
            <a href="{{ route('report.index') }}" class="stock-link">
                <span class="icon">
                    <ion-icon name="cash-outline"></ion-icon>
                </span>
                <span class="title">Todays Sales</span>
            </a>
        </li>

        <li class="{{ Request::routeIs('admin.profits.index') ? 'hovered' : '' }}">
            <a href="{{ route('admin.profits.index') }}" class="stock-link">
                <span class="icon">
                    <ion-icon name="trending-up-outline"></ion-icon>
                </span>
                <span class="title">Profits</span>
            </a>
        </li>


        <li>
            <a href="{{ route('admin.logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                <span class="title">Sign Out</span>
            </a>
            <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" style="display: none;">
                @csrf
            </form>
        </li>
        
    </ul>
</div>

<!-- Main Content -->
<div class="main">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>

    <!-- Happy new year msg-->
    
    @if (now()->format('m') == 3) <!-- Check if the current month is April -->
    <div class="new-year-animation text-center mt-4">
        <h1 class="text-danger fw-bold new-year-title">
            🎉 Happy New Year! 🎊
        </h1>
        <p class="text-secondary new-year-message">
            Wishing you success and happiness. D-Mart! ✨
        </p>
    </div>

    <style>
        .new-year-animation {
            position: relative;
            text-align: center;
            animation: fadeIn 2s ease-in-out, bounce 1.5s infinite alternate;
        }

        .new-year-title {
            font-size: 2.5rem;
            text-transform: uppercase;
            font-weight: bold;
            color: #ff4757;
        }

        .new-year-message {
            font-size: 1.3rem;
            font-weight: 500;
            color: #57606f;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-15px);
            }
        }
    </style>
@endif



    <main>
        <div class="container mt-4">
            @yield('content')

            @yield('styles')
        </div>
    </main>
    <footer class="text-center mt-4 p-3">
        <small>Powered by <strong>CeylonGIT</strong></small>
    </footer>
</div>

<!-- JavaScript for Toggle Menu -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
   
    // Menu Toggle Script
    let toggle = document.querySelector(".toggle");
    let navigation = document.querySelector(".navigation");
    let main = document.querySelector(".main");

    toggle.onclick = function () {
        navigation.classList.toggle("active");
        main.classList.toggle("active");
    };
</script>

<!-- IonIcons Script -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


</body>
</html>
