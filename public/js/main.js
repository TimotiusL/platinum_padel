function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function firstInitial(name) {
    const value = String(name || '').trim();
    return value ? value.charAt(0).toUpperCase() : '?';
}

function renderLoggedOut() {
    const authArea = document.getElementById('authArea');
    if (!authArea) return;
    authArea.innerHTML = `
    <a href="register.php"><button class="nav-cta">Register</button></a>
    <a href="login.php"><button class="signin">Sign In</button></a>
  `;
}

function renderLoggedIn(user) {
    const authArea = document.getElementById('authArea');
    if (!authArea) return;

    const name = escapeHtml(user.name);
    const email = escapeHtml(user.email);
    const initial = escapeHtml(firstInitial(user.name));

    authArea.innerHTML = `
    <details class="profile-menu">
      <summary class="profile-trigger" aria-label="Buka menu profil">
        <span class="profile-avatar">${initial}</span>
        <span class="profile-copy">
          <strong>${name}</strong>
          <small>Sudah login</small>
        </span>
        <span class="profile-chevron">▼</span>
      </summary>
      <div class="profile-dropdown">
        <div class="signed-label">Signed in as</div>
        <div class="signed-name">${name}</div>
        <div class="signed-email">${email}</div>
        <div class="profile-actions">
          <a class="profile-link" href="dashboard.php">Profil</a>
          <a class="profile-link logout" href="logout.php">Sign Out</a>
        </div>
      </div>
    </details>
  `;

    const params = new URLSearchParams(window.location.search);
    if (params.get('welcome') === '1') {
        const toastArea = document.getElementById('loginToastArea');
        if (toastArea) {
            toastArea.innerHTML = `
        <div class="login-toast">
          <b>Login berhasil.</b><br>Selamat datang, ${name}!
        </div>
      `;
            setTimeout(() => {
                const toast = toastArea.querySelector('.login-toast');
                if (toast) toast.remove();
            }, 4500);
        }

        params.delete('welcome');
        const cleanQuery = params.toString();
        const cleanUrl =
            window.location.pathname +
            (cleanQuery ? '?' + cleanQuery : '') +
            (window.location.hash || '#/');
        window.history.replaceState({}, '', cleanUrl);
    }
}

async function loadLoginStatus() {
    try {
        const response = await fetch('auth-status.php', {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }

        const data = await response.json();
        if (data.logged_in && data.user) {
            renderLoggedIn(data.user);
        } else {
            renderLoggedOut();
        }
    } catch (error) {
        console.error('Gagal membaca status login:', error);
        renderLoggedOut();
    }
}

document.addEventListener('DOMContentLoaded', loadLoginStatus);

/* ============================= DATA ============================= */
const ICONS = {
    calendar: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="16" rx="1.5"/><path d="M3 9h18M8 3v4M16 3v4"/></svg>`,
    pin: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.3"/></svg>`,
    court: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="16" rx="1"/><path d="M12 4v16M3 12h18"/></svg>`,
    crown: `<svg width="10" height="10" viewBox="0 0 24 24" fill="var(--bg-deepest)"><path d="M3 8l4 3 5-6 5 6 4-3-2 10H5L3 8z"/></svg>`,
    back: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>`,
    chev: `<svg class="chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>`,
    check: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--win)" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>`,
    trophy: `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4z"/><path d="M7 5H4a3 3 0 0 0 3 5M17 5h3a3 3 0 0 1-3 5"/></svg>`,
};

const PLAYERS = [
    { id: 'p1', name: 'Pemain 1', loc: 'Jakarta Selatan, DKI Jakarta', titles: 2 },
    { id: 'p2', name: 'Pemain 2', loc: '', titles: 1 },
    { id: 'p3', name: 'Pemain 3', loc: '', titles: 1 },
    { id: 'p4', name: 'Pemain 4', loc: '', titles: 1 },
    { id: 'p5', name: 'Pemain 5', loc: '', titles: 1 },
    { id: 'p6', name: 'Pemain 6', loc: 'Kota Bandung, Jawa Barat', titles: 1 },
    { id: 'p7', name: 'Pemain 7', loc: 'Jakarta Selatan, DKI Jakarta', titles: 1 },
    { id: 'p8', name: 'Pemain 8', loc: 'Jakarta Selatan, DKI Jakarta', titles: 1 },
    { id: 'p9', name: 'Pemain 9', loc: '', titles: 1 },
    { id: 'p10', name: 'Pemain 10', loc: '', titles: 1 },
    { id: 'p11', name: 'Pemain 11', loc: '', titles: 1 },
    { id: 'p12', name: 'Pemain 12', loc: '', titles: 1 },
    { id: 'p13', name: 'Pemain 13', loc: '', titles: 1 },
    { id: 'p14', name: 'Pemain 14', loc: '', titles: 1 },
];
PLAYERS.forEach((p, i) => p.rank = i + 1);

const PLAYER_PROFILE = {
    name: 'Alexander Wibowo', main: 12, menang: 10, winrate: 83, juara: 2,
    years: [{ year: 2026, main: 12, menang: 10, kalah: 2, juara: 2 }],
    history: [
        { name: 'PLATINUM GRAND OPENING PADEL TOURNAMENT', date: 'Sabtu, 19 Juli 2026', result: 'WINNER', icon: 'trophy' },
        { name: 'THE GREAT CONTEST INVITATIONAL', date: 'Minggu, 28 Juni 2026', result: 'SEMI FINAL', icon: 'court' },
    ]
};

function mkTeam(a, b) { return [a, b]; }

const GROUPS = {
    A: [
        { t1: mkTeam('Farrel Suhandi', 'Kelvin Susanto'), s1: 2, t2: mkTeam('Alexander Wibowo', 'Marcus Halim'), s2: 6, time: 'Sabtu, 19 Juli 2026 pukul 08.30', winner: 2 },
        { t1: mkTeam('Reza Hartono', 'Vincent Tanuwijaya'), s1: 6, t2: mkTeam('Dimas Prasetyo', 'Yusuf Kamal'), s2: 3, time: 'Sabtu, 19 Juli 2026 pukul 09.00', winner: 1 },
        { t1: mkTeam('Alexander Wibowo', 'Marcus Halim'), s1: 6, t2: mkTeam('Dimas Prasetyo', 'Yusuf Kamal'), s2: 2, time: 'Sabtu, 19 Juli 2026 pukul 09.30', winner: 1 },
    ],
    B: [
        { t1: mkTeam('Christopher Nathan', 'Imran Wicaksono'), s1: 6, t2: mkTeam('Hadi Mawaddah', 'Nasirudin Ahmad'), s2: 1, time: 'Sabtu, 19 Juli 2026 pukul 10.00', winner: 1 },
        { t1: mkTeam('Taslim Wirawan', 'Hatta Akhmat'), s1: 4, t2: mkTeam('Bagas Nugroho', 'Ilham Fadillah'), s2: 6, time: 'Sabtu, 19 Juli 2026 pukul 10.30', winner: 2 },
        { t1: mkTeam('Christopher Nathan', 'Imran Wicaksono'), s1: 6, t2: mkTeam('Bagas Nugroho', 'Ilham Fadillah'), s2: 3, time: 'Sabtu, 19 Juli 2026 pukul 11.00', winner: 1 },
    ],
};

const R16 = [
    { t1: mkTeam('Alexander Wibowo', 'Marcus Halim'), s1: 6, t2: mkTeam('Rafi Setiawan', 'Gilang Permana'), s2: 1, time: 'Sabtu, 19 Juli 2026 pukul 14.00', winner: 1 },
    { t1: mkTeam('Christopher Nathan', 'Imran Wicaksono'), s1: 6, t2: mkTeam('Yudha Pratama', 'Bayu Aditya'), s2: 4, time: 'Sabtu, 19 Juli 2026 pukul 14.30', winner: 1 },
];
const QF = [
    { t1: mkTeam('Alexander Wibowo', 'Marcus Halim'), s1: 6, t2: mkTeam('Christopher Nathan', 'Imran Wicaksono'), s2: 2, time: 'Sabtu, 19 Juli 2026 pukul 16.00', winner: 1 },
];
const SF = [
    { t1: mkTeam('Alexander Wibowo', 'Marcus Halim'), s1: 6, t2: mkTeam('Reza Hartono', 'Vincent Tanuwijaya'), s2: 3, time: 'Minggu, 20 Juli 2026 pukul 09.00', winner: 1 },
];
const FINAL = [
    { t1: mkTeam('Alexander Wibowo', 'Marcus Halim'), s1: 6, t2: mkTeam('Taslim Wirawan', 'Hatta Akhmat'), s2: 2, time: 'Minggu, 20 Juli 2026 pukul 15.00', winner: 1 },
];

const MATCH_DETAIL = {
    status: 'SELESAI', lap: 'LAP 2', stage: 'GROUP STAGE',
    t1: mkTeam('Farrel Suhandi', 'Kelvin Susanto'), s1: 2,
    t2: mkTeam('Alexander Wibowo', 'Marcus Halim'), s2: 6,
    winner: 2,
    tournament: 'PLATINUM GRAND OPENING PADEL TOURNAMENT · PLATINUM PADEL COURT',
    history: [
        { score: '2-6', time: '08.58.12' }, { score: '2-5', time: '08.51.40' }, { score: '1-5', time: '08.47.02' },
        { score: '1-4', time: '08.41.18' }, { score: '1-3', time: '08.36.55' }, { score: '0-3', time: '08.31.09' },
    ]
};

const CATEGORIES = [
    { id: 'rookie-men', label: 'Rookie', tier: 'Men' },
    { id: 'bronze-men', label: 'Bronze', tier: 'Men' },
    { id: 'upper-women', label: 'Upper Beginner', tier: 'Women' },
    { id: 'open-men', label: 'Open', tier: 'Men' },
    { id: 'open-women', label: 'Open', tier: 'Women' },
];

const TOURNAMENTS = [
    {
        id: 'grand-opening',
        badge: 'PLATINUM SOCIETAS',
        name: 'Platinum Grand Opening Padel Tournament',
        dateRange: '18 Jul 2026 – 20 Jul 2026',
        venue: 'Platinum Padel Court',
        venueSub: 'Senayan City Lt.3',
        location: 'Jakarta Selatan, DKI Jakarta',
        prize: '150.000.000++',
        status: 'ongoing',
        poster: 'linear-gradient(160deg,#173a2e,#0c1e17)',
        tags: ['Rookie · Men', 'Bronze · Men', 'Upper Beginner · Women', 'Open · Men', 'Open · Women'],
    },
    {
        id: 'great-contest',
        badge: 'PLATINUM SOCIETAS',
        name: 'The Great Contest Invitational',
        dateRange: '22 Aug 2026 – 23 Aug 2026',
        venue: 'Platinum Padel Court',
        venueSub: 'Senayan City Lt.3',
        location: 'Jakarta Selatan, DKI Jakarta',
        prize: '80.000.000++',
        status: 'upcoming',
        poster: 'linear-gradient(160deg,#1c4335,#0c1e17)',
        tags: ['Open · Men', 'Open · Women', 'Bronze · Mixed'],
    },
];

const HISTORY = [
    { badge: 'PLATINISTA', name: 'Bounce & Crest Vol. 02', date: '12 Jun 2026 – 13 Jun 2026', venue: 'The Crest Court, Kota Bandung', prize: '20.000.000', tags: ['Upper Beginner · Men', 'Rookie · Women', 'Bronze · Men'] },
    { badge: 'CONNECTING UP', name: 'Moslem Padel Weekend', date: '6 Jun 2026 – 6 Jun 2026', venue: 'Padel Garden, Malang', prize: '—', tags: ['Upper Beginner · Mixed', 'Open · Men'] },
    { badge: 'PLATINUM SOCIETAS', name: 'Platinum Mini Tournament Vol.1', date: '14 May 2026 – 14 May 2026', venue: 'Sidoarjo', prize: '10.000.000', tags: ['Rookie · Men', 'Rookie · Women'] },
];

const FINALISTS = [
    { initials: 'AW', name: 'Alexander Wibowo', tname: 'Bounce & Crest Vol. 02' },
    { initials: 'BK', name: 'Bianca Kartadinata', tname: 'Bounce & Crest Vol. 02' },
    { initials: 'TW', name: 'Taslim Wirawan', tname: 'Bounce & Crest Vol. 02' },
    { initials: 'GS', name: 'Gabriela Salim', tname: 'Bounce & Crest Vol. 02' },
    { initials: 'AS', name: 'Angelica Suherman', tname: 'Bounce & Crest Vol. 02' },
    { initials: 'MH', name: 'Marcus Halim', tname: 'Bounce & Crest Vol. 02' },
];

/* ============================= HELPERS ============================= */
function initials(name) { return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase(); }
function esc(s) { return s; }

function matchCard(m, cat, lap) {
    return `
  <div class="m-card">
    <div class="m-top"><span class="cat">${cat || 'MEN · GROUP STAGE'}</span><span class="lap">${lap || 'LAP 2'}</span></div>
    <div class="m-team ${m.winner === 1 ? 'winner' : ''}">
      <div class="names">${m.t1[0]}<br>${m.t1[1]}</div>
      <div class="score">${m.s1}</div>
    </div>
    <div class="m-team ${m.winner === 2 ? 'winner' : ''}">
      <div class="names">${m.t2[0]}<br>${m.t2[1]}</div>
      <div class="score">${m.s2}</div>
    </div>
    <div class="m-divider"></div>
    <div class="m-meta"><span>1 SET · FIRST6 · GP</span><span>${ICONS.check}</span></div>
    <div style="padding:0 18px 14px; font-family:var(--ui); font-size:11px; color:var(--cream-faint);">${m.time}</div>
    <button class="m-detail-btn" onclick="location.hash='#/match/x'">See Match Detail</button>
  </div>`;
}

/* ============================= VIEWS ============================= */
function viewHome() {
    return `
  
  `;
}

function tournamentCard(t) {
    return `
  <a href="#/tournament/${t.id}" class="t-card" style="display:flex;">
    <div class="poster" style="background:${t.poster}">
      <span class="status ${t.status}">${t.status}</span>
      <span class="badge">${t.badge}</span>
    </div>
    <div class="body">
      <div class="name">${t.name}</div>
      <div class="meta">
        <div class="meta-row">${ICONS.calendar} ${t.dateRange}</div>
        <div class="meta-row">${ICONS.court} ${t.venue} — ${t.venueSub}</div>
        <div class="meta-row">${ICONS.pin} ${t.location}</div>
      </div>
      <div class="tags">${t.tags.slice(0, 3).map(tag => `<span class="tag">${tag}</span>`).join('')}</div>
    </div>
  </a>`;
}

function viewTournaments() {
    return `
  <div style="padding-top:40px;"></div>
  <div class="section-head">
    <div>
      <div class="eyebrow">All Events</div>
      <h2>Tournaments</h2>
    </div>
  </div>
  <div class="t-grid">${TOURNAMENTS.map(tournamentCard).join('')}</div>
  <div class="section">
    <div class="section-head">
      <div><div class="eyebrow">Archive</div><h2>History</h2></div>
    </div>
    <div class="h-grid">
      ${HISTORY.map(h => `
        <div class="t-card">
          <div class="poster" style="background:linear-gradient(160deg,#173a2e,#0c1e17);">
            <span class="badge">${h.badge}</span><span class="status finished">Finished</span>
          </div>
          <div class="body">
            <div class="name">${h.name}</div>
            <div class="meta"><div class="meta-row">${ICONS.calendar} ${h.date}</div><div class="meta-row">${ICONS.court} ${h.venue}</div></div>
            <div class="tags">${h.tags.map(t => `<span class="tag">${t}</span>`).join('')}</div>
          </div>
        </div>`).join('')}
    </div>
  </div>`;
}

let currentCat = 'open-men';
let currentTab = 'results';

function viewTournamentDetail(id) {
    const t = TOURNAMENTS.find(x => x.id === id) || TOURNAMENTS[0];
    return `
  <a class="back-link" href="#/tournaments">${ICONS.back} Kembali ke Tournaments</a>
  <div class="t-banner">
    <div class="poster-lg" style="background:${t.poster}"></div>
    <div class="info">
      <div class="badge">| ${t.badge}</div>
      <h1>${t.name}</h1>
      <div class="row">${ICONS.calendar} ${t.dateRange}</div>
      <div class="row">${ICONS.court} ${t.venue}<br></div>
      <div class="row">${ICONS.pin} ${t.location}</div>
      <button class="cta-gold" style="width:fit-content; margin-top:10px;" onclick="location.hash='#/register/${t.id}'">Daftar Sekarang &nbsp;→</button>
    </div>
  </div>

  <div style="display:flex; align-items:center; justify-content:space-between; margin-top:34px;">
    <div class="eyebrow dim">Pilih Kategori</div>
  </div>
  <div class="cat-row" id="catRow">
    ${CATEGORIES.map(c => `<button class="cat-btn ${c.id === currentCat ? 'active' : ''}" data-cat="${c.id}">${ICONS.trophy} ${c.label} <span class="t">— ${c.tier}</span></button>`).join('')}
  </div>

  <div class="tab-row" id="tabRow">
    ${['fixture', 'results', 'leaderboard', 'bracket'].map(tb => `<button class="tab-btn ${tb === currentTab ? 'active' : ''}" data-tab="${tb}">${tb}</button>`).join('')}
  </div>

  <div id="tabContent">${renderTabContent()}</div>
  `;
}

function renderTabContent() {
    if (currentTab === 'results') return renderResults();
    if (currentTab === 'fixture') return renderResults(true);
    if (currentTab === 'leaderboard') return renderMiniLeaderboard();
    if (currentTab === 'bracket') return renderBracket();
    return '';
}

function renderResults(isFixture) {
    return `
  <div class="acc open" data-acc="group">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)">
      <span>Group Stage</span>
      <span class="count">${isFixture ? '12 Match' : '12 Match'} ${ICONS.chev}</span>
    </div>
    <div class="acc-body">
      ${Object.keys(GROUPS).map(g => `
        <div class="sub-acc ${g === 'A' ? 'open' : ''}">
          <div class="sub-head" onclick="toggleSubAcc(this.parentElement)">Group ${g} <span style="color:var(--cream-faint); font-weight:400;">${GROUPS[g].length} match ${ICONS.chev}</span></div>
          <div class="sub-body">
            ${GROUPS[g].map(m => matchCard(m)).join('')}
          </div>
        </div>`).join('')}
    </div>
  </div>
  <div class="acc" data-acc="r16">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Round of 16</span><span class="count">2 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${R16.map(m => matchCard(m, 'MEN · ROUND OF 16', 'LAP 3')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="qf">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Quarter Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${QF.map(m => matchCard(m, 'MEN · QUARTER FINAL', 'LAP 4')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="sf">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Semi Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${SF.map(m => matchCard(m, 'MEN · SEMI FINAL', 'LAP 5')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="final">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${FINAL.map(m => matchCard(m, 'MEN · FINAL', 'LAP 6')).join('')}</div></div>
  </div>
  `;
}

function renderMiniLeaderboard() {
    return `<div class="p-grid">${PLAYERS.slice(0, 8).map((p, i) => playerRow(p, i)).join('')}</div>`;
}

function bracketColumn(title, matches) {
    return `<div style="flex:1; min-width:220px;">
    <div class="eyebrow dim" style="margin-bottom:14px; text-align:center;">${title}</div>
    <div style="display:flex; flex-direction:column; gap:36px; justify-content:center; height:100%;">
      ${matches.map(m => `
      <div class="m-card" style="max-width:260px; margin:0 auto;">
        <div class="m-team ${m.winner === 1 ? 'winner' : ''}"><div class="names" style="font-size:14px;">${m.t1[0]}<br>${m.t1[1]}</div><div class="score" style="font-size:18px;">${m.s1}</div></div>
        <div class="m-divider"></div>
        <div class="m-team ${m.winner === 2 ? 'winner' : ''}"><div class="names" style="font-size:14px;">${m.t2[0]}<br>${m.t2[1]}</div><div class="score" style="font-size:18px;">${m.s2}</div></div>
      </div>`).join('')}
    </div>
  </div>`;
}

function renderBracket() {
    return `<div style="overflow-x:auto; padding-bottom:20px;">
    <div style="display:flex; gap:30px; min-width:900px;">
      ${bracketColumn('Round of 16', R16)}
      ${bracketColumn('Quarter Final', QF)}
      ${bracketColumn('Semi Final', SF)}
      ${bracketColumn('Final', FINAL)}
    </div>
  </div>`;
}

function viewMatchDetail() {
    const m = MATCH_DETAIL;
    const total = m.s1 + m.s2;
    const pct1 = Math.round(m.s1 / total * 100), pct2 = 100 - pct1;
    return `
  <a class="back-link" href="#/tournament/grand-opening">${ICONS.back} Kembali ke Detail Turnamen</a>
  <div class="md-hero">
    <div class="md-status"><span style="color:var(--gold);">${m.status}</span><span>·</span><span>${m.lap}</span><span>·</span><span>${m.stage}</span></div>
    <div class="md-score-row">
      <div class="md-side">
        <div class="md-avatars"><div class="avatar">${initials(m.t1[0])}</div><div class="avatar">${initials(m.t1[1])}</div></div>
        <div class="nm">${m.t1[0]}<br>${m.t1[1]}</div>
      </div>
      <div class="md-score"><span class="${m.winner === 1 ? 'bright' : 'dim'}">${m.s1}</span><span style="color:var(--cream-faint); font-size:28px;">–</span><span class="${m.winner === 2 ? 'bright' : 'dim'}">${m.s2}</span></div>
      <div class="md-side">
        <div class="md-avatars"><div class="avatar">${initials(m.t2[0])}</div><div class="avatar">${initials(m.t2[1])}</div></div>
        <div class="nm">${m.t2[0]}<br>${m.t2[1]}</div>
        <div class="winner-tag">Pemenang</div>
      </div>
    </div>
    <div class="md-sub">${m.tournament}</div>
  </div>

  <div class="stat-card">
    <div class="stat-title">Statistik</div>
    <div class="stat-names">
      <span><span class="stat-dot" style="background:var(--cream-faint);"></span>${m.t1[0].split(' ')[0]} / ${m.t1[1].split(' ')[0]}</span>
      <span>${m.t2[0].split(' ')[0]} / ${m.t2[1].split(' ')[0]}<span class="stat-dot" style="background:var(--gold); margin-left:7px;"></span></span>
    </div>
    <div class="eyebrow dim" style="text-align:center; margin-bottom:10px;">Total Game</div>
    <div class="bar-wrap">
      <div class="bar-num">${m.s1}</div>
      <div class="bar"><div class="bar-fill" style="width:${pct1}%; background:var(--cream-faint);"></div><div class="bar-fill" style="width:${pct2}%;"></div></div>
      <div class="bar-num">${m.s2}</div>
    </div>
  </div>

  <div class="history-card">
    <div class="eyebrow dim" style="padding:18px 22px 0;">Riwayat Skor</div>
    ${m.history.map(h => `<div class="history-item"><span class="score">${h.score} <span style="font-family:var(--ui); font-size:10px; color:var(--cream-faint); text-transform:uppercase; margin-left:8px;">Set 1</span></span><span class="time">Pembaruan Skor · ${h.time}</span></div>`).join('')}
  </div>
  `;
}

let genderTab = 'MEN';

function playerRow(p, i) {
    const rankClass = p.rank <= 3 ? `rank-${p.rank}` : '';
    const rankTop = p.rank <= 3 ? 'top' : '';
    return `
  <a class="p-row ${rankClass}" href="#/player/${p.id}">
    <div class="p-rank ${rankTop}">${p.rank}</div>
    <div class="avatar">${initials(p.name)}</div>
    <div class="p-info">
      <div class="nm">${p.name}</div>
      <div class="loc">${p.loc ? ICONS.pin + ' ' + p.loc : '—'}</div>
    </div>
    <div class="p-titles">${ICONS.trophy} ${p.titles} Titles</div>
  </a>`;
}

function viewPlayers() {
    return `
  <div style="padding-top:40px;"></div>
  <div class="lb-head">
    <div class="lb-title">
      <div class="eyebrow">The Roster</div>
      <h1>All Players</h1>
    </div>
    <input class="search-box" placeholder="Search...">
  </div>
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:26px; flex-wrap:wrap; gap:16px;">
    <div class="toggle-row">
      <button class="toggle-btn ${genderTab === 'MEN' ? 'active' : ''}" data-gender="MEN">Men</button>
      <button class="toggle-btn ${genderTab === 'WOMEN' ? 'active' : ''}" data-gender="WOMEN">Women</button>
    </div>
    <div class="eyebrow dim">${ICONS.trophy} Lingkup Peringkat — Seluruh Indonesia</div>
  </div>
  <div class="p-grid" id="pGrid">${PLAYERS.map((p, i) => playerRow(p, i)).join('')}</div>
  `;
}

function viewPlayerProfile() {
    const p = PLAYER_PROFILE;
    return `
  <a class="back-link" href="#/players">${ICONS.back} Kembali ke Leaderboard</a>
  <div class="prof-banner">
    <div class="avatar">${initials(p.name)}</div>
    <h1>${p.name}</h1>
  </div>
  <div class="stat-boxes">
    <div class="stat-box"><div class="num">${p.main}</div><div class="lbl">Main</div></div>
    <div class="stat-box"><div class="num">${p.menang}</div><div class="lbl">Menang</div></div>
    <div class="stat-box"><div class="num">${p.winrate}%</div><div class="lbl">Win Rate</div></div>
    <div class="stat-box"><div class="num">${ICONS.trophy} ${p.juara}</div><div class="lbl">Juara</div></div>
  </div>

  <div class="section" style="margin-top:44px;">
    <div class="eyebrow" style="margin-bottom:16px;">${ICONS.calendar} Statistik Per Tahun</div>
    ${p.years.map(y => `
      <div class="year-card">
        <div class="year-top"><span>${y.year}</span><span class="wr">${y.menang}/${y.main} menang</span></div>
        <div class="bar"><div class="bar-fill" style="width:${Math.round(y.menang / y.main * 100)}%;"></div></div>
        <div class="year-bottom">
          <span>${y.main} main</span>
          <span class="legend-dot"><i style="background:var(--win);"></i>${y.menang} menang</span>
          <span class="legend-dot"><i style="background:var(--lose);"></i>${y.kalah} kalah</span>
          <span style="color:var(--gold-light);">${ICONS.trophy} ${y.juara} juara</span>
        </div>
      </div>`).join('')}
  </div>

  <div class="section" style="margin-top:44px;">
    <div class="eyebrow" style="margin-bottom:16px;">${ICONS.trophy} Riwayat Turnamen</div>
    <div class="history-card">
      ${p.history.map(h => `
        <div class="history-item">
          <span>${h.name}<br><span class="time">${h.date}</span></span>
          <span class="pill">${h.result}</span>
        </div>`).join('')}
    </div>
  </div>
  `;
}

function viewRegister(preselectId) {
    const t = TOURNAMENTS.find(x => x.id === preselectId);
    return `
  <div style="padding-top:40px;"></div>
  <div class="eyebrow">Bergabung dengan Platinum Societas</div>
  <div class="reg-wrap" style="margin-top:14px;">
    <div class="reg-card">
      <div id="regFormArea">
        <h1>Formulir Pendaftaran</h1>
        <p class="lead">Isi data di bawah untuk mendaftarkan tim Anda ke turnamen pilihan.</p>
        <form id="regForm" novalidate>
          <div class="field-row">
            <div class="field" data-field="name">
              <label>Nama Lengkap <span class="req">*</span></label>
              <input type="text" name="name" placeholder="cth. Alexander Wibowo">
              <div class="field-error">Nama lengkap wajib diisi.</div>
            </div>
            <div class="field" data-field="partner">
              <label>Nama Partner <span class="req">*</span></label>
              <input type="text" name="partner" placeholder="cth. Marcus Halim">
              <div class="field-error">Nama partner wajib diisi.</div>
            </div>
          </div>
          <div class="field-row">
            <div class="field" data-field="email">
              <label>Email <span class="req">*</span></label>
              <input type="email" name="email" placeholder="nama@email.com">
              <div class="field-error">Masukkan alamat email yang valid.</div>
            </div>
            <div class="field" data-field="phone">
              <label>No. WhatsApp <span class="req">*</span></label>
              <input type="text" name="phone" placeholder="08xx xxxx xxxx">
              <div class="field-error">Nomor WhatsApp wajib diisi.</div>
            </div>
          </div>
          <div class="field" data-field="tournament">
            <label>Turnamen <span class="req">*</span></label>
            <select name="tournament">
              <option value="">Pilih turnamen</option>
              ${TOURNAMENTS.map(tt => `<option value="${tt.id}" ${tt.id === preselectId ? 'selected' : ''}>${tt.name}</option>`).join('')}
            </select>
            <div class="field-error">Pilih turnamen yang ingin diikuti.</div>
          </div>
          <div class="field" data-field="category">
            <label>Kategori <span class="req">*</span></label>
            <select name="category">
              <option value="">Pilih kategori</option>
              ${CATEGORIES.map(c => `<option value="${c.id}">${c.label} — ${c.tier}</option>`).join('')}
            </select>
            <div class="field-error">Pilih kategori yang ingin diikuti.</div>
          </div>
          <div class="field" data-field="city">
            <label>Kota Domisili</label>
            <input type="text" name="city" placeholder="cth. Jakarta Selatan, DKI Jakarta">
          </div>
          <button type="submit" class="reg-submit" id="regSubmitBtn">Kirim Pendaftaran</button>
        </form>
      </div>
    </div>
    <div class="reg-side">
      <div class="eyebrow dim">Perlu Diketahui</div>
      <div class="reg-note">Data pendaftaran disimpan pada penyimpanan bersama artefak ini, sehingga <b>dapat dilihat oleh pengguna lain</b> yang membuka halaman ini. Jangan sertakan data sensitif selain yang diminta.</div>
      <div class="eyebrow dim">Pendaftar Terbaru</div>
      <div class="recent-list" id="recentList"><div class="recent-loading">Memuat pendaftar terbaru…</div></div>
    </div>
  </div>
  `;
}

const FIELD_VALIDATORS = {
    name: v => v.trim().length > 1,
    partner: v => v.trim().length > 1,
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    phone: v => v.trim().replace(/[^0-9]/g, '').length >= 8,
    tournament: v => !!v,
    category: v => !!v,
};

function attachRegisterHandlers() {
    loadRecentRegistrations();
    const form = document.getElementById('regForm');
    if (!form) return;
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(form).entries());
        let valid = true;
        Object.keys(FIELD_VALIDATORS).forEach(key => {
            const fieldEl = form.querySelector(`[data-field="${key}"]`);
            const ok = FIELD_VALIDATORS[key](data[key] || '');
            fieldEl.classList.toggle('invalid', !ok);
            if (!ok) valid = false;
        });
        if (!valid) return;

        const btn = document.getElementById('regSubmitBtn');
        btn.disabled = true; btn.textContent = 'Menyimpan…';

        const id = Date.now() + '-' + Math.random().toString(36).slice(2, 7);
        const tName = (TOURNAMENTS.find(t => t.id === data.tournament) || {}).name || data.tournament;
        const catLabel = (CATEGORIES.find(c => c.id === data.category) || {}).label || data.category;
        const record = { name: data.name.trim(), partner: data.partner.trim(), email: data.email.trim(), phone: data.phone.trim(), city: (data.city || '').trim(), tournament: data.tournament, tournamentName: tName, category: data.category, categoryLabel: catLabel, submittedAt: new Date().toISOString() };

        try {
            const result = await window.storage.set('reg:' + id, JSON.stringify(record), true);
            if (!result) throw new Error('empty result');
            showRegisterSuccess(record);
        } catch (err) {
            btn.disabled = false; btn.textContent = 'Kirim Pendaftaran';
            alert('Pendaftaran gagal disimpan. Silakan coba lagi.');
            console.error('Registration storage error:', err);
        }
    });
}

function showRegisterSuccess(record) {
    const area = document.getElementById('regFormArea');
    if (!area) return;
    area.innerHTML = `
    <div class="reg-success">
      <div class="check-big">${ICONS.check}</div>
      <h2>Pendaftaran Terkirim</h2>
      <p>Terima kasih, <b style="color:var(--cream);">${record.name} / ${record.partner}</b>. Tim Anda terdaftar pada<br>${record.tournamentName} — ${record.categoryLabel}.</p>
      <button class="cta-gold" onclick="location.hash='#/tournaments'">Lihat Turnamen &nbsp;→</button>
      <button class="signin" style="margin-left:12px;" onclick="rerenderRegisterForm()">Daftar Tim Lain</button>
    </div>`;
    loadRecentRegistrations();
}

let lastRegisterTournamentId = undefined;
function rerenderRegisterForm() {
    renderRegisterPage(lastRegisterTournamentId);
}
function renderRegisterPage(preselectId) {
    lastRegisterTournamentId = preselectId;
    app.innerHTML = viewRegister(preselectId);
    updateNav('');
    attachRegisterHandlers();
}

async function loadRecentRegistrations() {
    const list = document.getElementById('recentList');
    if (!list) return;
    try {
        const keys = await window.storage.list('reg:', true);
        if (!keys || !keys.keys || keys.keys.length === 0) {
            list.innerHTML = `<div class="recent-empty">Belum ada pendaftar. Jadilah yang pertama!</div>`;
            return;
        }
        const sorted = keys.keys.slice().sort().reverse().slice(0, 5);
        const items = [];
        for (const k of sorted) {
            try {
                const r = await window.storage.get(k, true);
                if (r && r.value) items.push(JSON.parse(r.value));
            } catch (e) { /* skip missing/broken key */ }
        }
        if (items.length === 0) { list.innerHTML = `<div class="recent-empty">Belum ada pendaftar. Jadilah yang pertama!</div>`; return; }
        list.innerHTML = items.map(r => `
      <div class="recent-item">
        <div class="avatar">${initials(r.name || '??')}</div>
        <div>
          <div class="nm">${r.name} &amp; ${r.partner}</div>
          <div class="sub">${r.categoryLabel || ''} · ${r.tournamentName || ''}</div>
        </div>
      </div>`).join('');
    } catch (err) {
        list.innerHTML = `<div class="recent-empty">Gagal memuat data pendaftar.</div>`;
        console.error('Load registrations error:', err);
    }
}

/* ============================= ROUTER ============================= */

function toggleAcc(el) {
    const wasOpen = el.classList.contains('open');
    document.querySelectorAll('.acc').forEach(a => a.classList.remove('open'));
    if (!wasOpen) el.classList.add('open');
}
function toggleSubAcc(el) {
    const wasOpen = el.classList.contains('open');
    el.parentElement.querySelectorAll('.sub-acc').forEach(a => a.classList.remove('open'));
    if (!wasOpen) el.classList.add('open');
}

function attachTournamentHandlers() {
    document.querySelectorAll('#catRow .cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentCat = btn.dataset.cat;
            document.querySelectorAll('#catRow .cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
    document.querySelectorAll('#tabRow .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentTab = btn.dataset.tab;
            document.querySelectorAll('#tabRow .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tabContent').innerHTML = renderTabContent();
        });
    });
}

function attachPlayersHandlers() {
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            genderTab = btn.dataset.gender;
            document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
}
