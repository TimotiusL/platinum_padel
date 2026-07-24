<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #0c1e17; color: #f2ecdd; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid rgba(201,167,102,0.22); }
        h1 { color: #c9a766; }
        .btn-logout { color: #c97b6b; text-decoration: none; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 20px; margin-top: 30px; }
        .card { background: #173a2e; padding: 24px; border-radius: 12px; border: 1px solid rgba(201,167,102,0.22); text-align: center; }
        .card a { color: #c9a766; text-decoration: none; display: block; margin-top: 12px; }
        .stat { font-size: 32px; font-weight: 700; color: #f2ecdd; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>⚡ Admin Dashboard</h1>
            <a href="/admin/logout" class="btn-logout">Logout</a>
        </header>

        <div class="grid">
            <div class="card">
                <div class="stat">{{ \App\Models\Player::count() }}</div>
                <div>Players</div>
                <a href="#">Manage</a>
            </div>
            <div class="card">
                <div class="stat">{{ \App\Models\Team::count() }}</div>
                <div>Teams</div>
                <a href="#">Manage</a>
            </div>
            <div class="card">
                <div class="stat">{{ \App\Models\MatchTournament::count() }}</div>
                <div>Matches</div>
                <a href="#">Manage</a>
            </div>
            <div class="card">
                <div class="stat">{{ \App\Models\Tournament::count() }}</div>
                <div>Tournaments</div>
                <a href="#">Manage</a>
            </div>
        </div>

        <div style="margin-top:40px;padding:20px;background:#173a2e;border-radius:12px;">
            <h3>Quick Actions</h3>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;">
                <a href="#" style="background:#c9a766;color:#0c1e17;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">➕ Add Player</a>
                <a href="#" style="background:#c9a766;color:#0c1e17;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">🏸 Add Match</a>
                <a href="#" style="background:#c9a766;color:#0c1e17;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">🏆 Create Tournament</a>
            </div>
        </div>
    </div>
</body>
</html>