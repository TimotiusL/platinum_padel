<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Platinum Padel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/platinum-auth.css">
</head>
<body>
  <div class="auth-wrap">
    <div class="brand-row">
      <a class="word" href="{{ route('home') }}">PLATINUM <b>PADEL</b></a>
    </div>
    <div class="auth-card dashboard-card">
      <div class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
      @if (request()->boolean('welcome'))
        <div class="alert alert-success">Akun berhasil dibuat. Selamat bergabung, {{ auth()->user()->name }}!</div>
      @endif
      <div class="eyebrow" style="justify-content:center; display:flex;">Signed in as</div>
      <h1>{{ auth()->user()->name }}</h1>
      <p class="lead">{{ auth()->user()->email }}</p>
      <a class="btn-outline" href="{{ route('home') }}" style="margin-top:10px;">Kembali ke Home</a>
      <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
        @csrf
        <button class="btn-outline" type="submit">Sign Out</button>
      </form>
    </div>
  </div>
</body>
</html>
