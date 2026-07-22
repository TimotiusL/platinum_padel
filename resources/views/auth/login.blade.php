<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Platinum Padel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/platinum-auth.css">
</head>
<body>
  <div class="auth-wrap">
    <div class="brand-row">
      <a class="word" href="{{ route('home') }}">PLATINUM <b>PADEL</b></a>
    </div>
    <div class="auth-card">
      <div class="eyebrow">Selamat Datang Kembali</div>
      <h1>Sign In</h1>
      <p class="lead">Masuk untuk melihat statistik dan riwayat turnamenmu.</p>

      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-error">
          @foreach ($errors->all() as $error)
            &bull; {{ $error }}<br>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>
        <div class="field">
          <label for="password">Kata Sandi</label>
          <input id="password" type="password" name="password" placeholder="Kata sandi" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn">Sign In</button>
      </form>

      <div class="switch-line">Belum punya akun? <a href="{{ route('register') }}">Register di sini</a></div>
    </div>
  </div>
</body>
</html>
