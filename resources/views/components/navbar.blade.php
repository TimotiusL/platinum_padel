<header>
  <div class="header-inner">
    <div class="nav-left" id="navLeft">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('tournaments.index') }}">Tournaments</a>
      <a href="{{ route('players.index') }}">Players</a>
    </div>
    <div class="brand" onclick="window.location='{{ route('home') }}'" style="cursor:pointer;">
      <svg class="mono" viewBox="0 0 60 60" fill="none">
        <line x1="30" y1="6" x2="30" y2="54" stroke="var(--gold)" stroke-width="1.4"/>
        <path d="M14 12h7c4 0 6.5 2.4 6.5 6s-2.5 6-6.5 6h-3v8h-4V12zm4 3.2v5.6h2.6c2 0 3-1 3-2.8s-1-2.8-3-2.8H18z" fill="var(--gold)" transform="translate(-4,4)"/>
        <path d="M14 12h7c4 0 6.5 2.4 6.5 6s-2.5 6-6.5 6h-3v8h-4V12zm4 3.2v5.6h2.6c2 0 3-1 3-2.8s-1-2.8-3-2.8H18z" fill="var(--gold)" transform="translate(20,4) scale(-1,1) translate(-38,0)"/>
        <line x1="12" y1="22" x2="26" y2="22" stroke="var(--gold)" stroke-width="1.2"/>
      </svg>
      <div class="word">PLATINUM <b>PADEL</b></div>
    </div>
    <div class="nav-right" id="authArea">
      <a href="{{ route('register') }}"><button class="nav-cta">Register</button></a>
      <a href="{{ route('login') }}"><button class="signin">Sign In</button></a>
    </div>
  </div>
</header>