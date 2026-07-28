<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            background:#0c1e17;
            color:#f2ecdd;
            font-family:'Inter',sans-serif;
            margin:0;
            padding:20px;
        }

        .container{
            max-width:1200px;
            margin:0 auto;
        }

        header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:20px 0;
            border-bottom:1px solid rgba(201,167,102,.22);
        }

        h1{
            color:#c9a766;
        }

        .btn-logout{
            color:#c97b6b;
            text-decoration:none;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
            gap:20px;
            margin-top:30px;
        }

        .card{
            background:#173a2e;
            padding:24px;
            border-radius:12px;
            border:1px solid rgba(201,167,102,.22);
            text-align:center;
        }

        .stat{
            font-size:32px;
            font-weight:700;
            margin-bottom:8px;
        }

        .card a{
            display:inline-block;
            margin-top:15px;
            color:#c9a766;
            text-decoration:none;
            font-weight:600;
        }

        .disabled{
            color:#888 !important;
            cursor:default;
            pointer-events:none;
        }

        .quick-box{
            margin-top:40px;
            padding:20px;
            background:#173a2e;
            border-radius:12px;
        }

        .quick-btn{
            display:inline-block;
            background:#c9a766;
            color:#0c1e17;
            padding:10px 18px;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
            margin-right:10px;
            margin-top:10px;
        }

    </style>
</head>

<body>

<div class="container">

<header>
    <h1>⚡ Admin Dashboard</h1>

    <a href="{{ url('/admin/logout') }}" class="btn-logout">
        Logout
    </a>
</header>


<div class="grid">

    <div class="card">
        <div class="stat">{{ \App\Models\Player::count() }}</div>
        <div>Players</div>

        <a href="{{ route('players.index') }}">
            Manage
        </a>
    </div>

    <div class="card">
        <div class="stat">{{ \App\Models\Tournament::count() }}</div>
        <div>Tournaments</div>

        <a href="{{ route('tournaments.index') }}">
            Manage
        </a>
    </div>

    <div class="card">
        <div class="stat">{{ \App\Models\Team::count() }}</div>
        <div>Teams</div>

        <a href="#" class="disabled">
            Coming Soon
        </a>
    </div>

    <div class="card">
        <div class="stat">{{ \App\Models\MatchTournament::count() }}</div>
        <div>Matches</div>

        <a href="#" class="disabled">
            Coming Soon
        </a>
    </div>

</div>


<div class="quick-box">

    <h3>Quick Actions</h3>

    <a href="{{ route('players.create') }}" class="quick-btn">
        ➕ Add Player
    </a>

    <a href="{{ route('tournaments.create') }}" class="quick-btn">
        🏆 Create Tournament
    </a>

</div>

</div>

</body>
</html>