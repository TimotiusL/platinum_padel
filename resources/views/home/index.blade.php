<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Platinum Padel Tournament</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Parisienne&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/platinum.css">

</head>
<body>

<header>
  <div class="header-inner">
    <div class="nav-left" id="navLeft">
      <a href="#/" data-route="home">Home</a>
      <a href="#/tournaments" data-route="tournaments">Tournaments</a>
      <a href="#/players" data-route="players">Players</a>
    </div>
    <div class="brand" onclick="location.hash='#/'" style="cursor:pointer;">
      <svg class="mono" viewBox="0 0 60 60" fill="none">
        <line x1="30" y1="6" x2="30" y2="54" stroke="var(--gold)" stroke-width="1.4"/>
        <path d="M14 12h7c4 0 6.5 2.4 6.5 6s-2.5 6-6.5 6h-3v8h-4V12zm4 3.2v5.6h2.6c2 0 3-1 3-2.8s-1-2.8-3-2.8H18z" fill="var(--gold)" transform="translate(-4,4)"/>
        <path d="M14 12h7c4 0 6.5 2.4 6.5 6s-2.5 6-6.5 6h-3v8h-4V12zm4 3.2v5.6h2.6c2 0 3-1 3-2.8s-1-2.8-3-2.8H18z" fill="var(--gold)" transform="translate(20,4) scale(-1,1) translate(-38,0)"/>
        <line x1="12" y1="22" x2="26" y2="22" stroke="var(--gold)" stroke-width="1.2"/>
      </svg>
      <div class="word">PLATINUM <b>PADEL</b></div>
    </div>
    <div class="nav-right" aria-hidden="true"></div>
  </div>
</header>

<main id="app"></main>

<footer>
  &copy; 2026 <b>Platinum Padel</b>. Hak Cipta Dilindungi. · The Great Contest
</footer>

<script src="/js/platinum.js" defer></script>

</body>
</html>
