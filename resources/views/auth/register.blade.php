<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Platinum Padel</title>
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
      <div class="eyebrow">Bergabung dengan Platinum Societas</div>
      <h1>Buat Akun</h1>
      <p class="lead">Daftar untuk mengikuti turnamen dan melacak riwayat pertandinganmu.</p>

      @if ($errors->any())
        <div class="alert alert-error">
          @foreach ($errors->all() as $error)
            &bull; {{ $error }}<br>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <div class="field">
          <label for="name">Nama Lengkap</label>
          <input id="name" type="text" name="name" placeholder="cth. Alexander Wibowo" value="{{ old('name') }}" required autofocus autocomplete="name">
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="username">
        </div>
        <div class="field-row">
          <div class="field">
            <label for="phone">No. WhatsApp</label>
            <input id="phone" type="text" name="phone" placeholder="08xx xxxx xxxx" value="{{ old('phone') }}" required autocomplete="tel">
          </div>
          <div class="field">
            <label for="city">Kota Domisili</label>
            <input id="city" type="text" name="city" placeholder="cth. Jakarta Selatan" value="{{ old('city') }}" autocomplete="address-level2">
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="password">Kata Sandi</label>
            <input id="password" type="password" name="password" placeholder="Minimal 6 karakter" required autocomplete="new-password">
          </div>
          <div class="field">
            <label for="password_confirmation">Konfirmasi Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required autocomplete="new-password">
          </div>
        </div>
        <button type="submit" class="btn">Buat Akun</button>
      </form>

      <div class="switch-line">Sudah punya akun? <a href="{{ route('login') }}">Sign In di sini</a></div>
    </div>
  </div>
</body>
</html>
