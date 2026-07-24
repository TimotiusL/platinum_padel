<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Padel</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/platinum.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;600;700;800&family=Parisienne&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header>
        <div class="header-inner">
            <div class="nav-left">
                <a href="#/" data-route="home" class="active">Home</a>
                <a href="#/tournaments" data-route="tournaments">Tournaments</a>
                <a href="#/players" data-route="players">Players</a>
            </div>
            <div class="brand">
                <svg class="mono" viewBox="0 0 24 24" fill="none" stroke="#c9a766" stroke-width="1.5">
                    <rect x="3" y="2" width="18" height="20" rx="2"/>
                    <path d="M3 8h18M12 8v12M3 18h18"/>
                </svg>
                <span class="word"><b>PLATINUM</b> PADEL</span>
            </div>
            <!-- 👇 ADMIN DIHAPUS DARI SINI -->
            <div class="nav-right">
                <!-- Kosong, atau bisa tambahkan social media / info lain -->
            </div>
        </div>
    </header>

    <main id="app">
        <div class="loading">Loading...</div>
    </main>

    <footer>
        <p><b>PLATINUM PADEL</b> &middot; Sportsmanship &middot; Community &middot; Excellence</p>
        <p style="margin-top:6px;font-size:10px;">&copy; 2026 Platinum Padel. All rights reserved.</p>
        
        <!-- Hidden admin link - double tap logo to show -->
        <div class="admin-hidden" style="display:none;">
            <a href="/admin/login" style="color:transparent;font-size:1px;">Admin Login</a>
        </div>
    </footer>

    <script>
        // Double tap logo untuk akses admin
        let adminTapCount = 0;
        document.querySelector('.brand').addEventListener('dblclick', function(e) {
            adminTapCount++;
            if (adminTapCount >= 2) {
                document.querySelector('.admin-hidden').style.display = 'block';
                adminTapCount = 0;
                // Auto hide after 5 seconds
                setTimeout(() => {
                    document.querySelector('.admin-hidden').style.display = 'none';
                }, 5000);
            }
            setTimeout(() => { adminTapCount = 0; }, 2000);
        });
    </script>

    <script src="{{ asset('js/platinum.js') }}"></script>
</body>
</html>