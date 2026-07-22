/* ============================= DATA ============================= */
const ICONS = {
  calendar:`<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="16" rx="1.5"/><path d="M3 9h18M8 3v4M16 3v4"/></svg>`,
  pin:`<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.3"/></svg>`,
  court:`<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="16" rx="1"/><path d="M12 4v16M3 12h18"/></svg>`,
  crown:`<svg width="10" height="10" viewBox="0 0 24 24" fill="var(--bg-deepest)"><path d="M3 8l4 3 5-6 5 6 4-3-2 10H5L3 8z"/></svg>`,
  back:`<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>`,
  chev:`<svg class="chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>`,
  check:`<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--win)" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>`,
  trophy:`<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4z"/><path d="M7 5H4a3 3 0 0 0 3 5M17 5h3a3 3 0 0 1-3 5"/></svg>`,
};

const PLAYERS = [
  {id:'p1', name:'Pemain 1', loc:'Jakarta Selatan, DKI Jakarta', titles:2},
  {id:'p2', name:'Pemain 2', loc:'', titles:1},
  {id:'p3', name:'Pemain 3', loc:'', titles:1},
  {id:'p4', name:'Pemain 4', loc:'', titles:1},
  {id:'p5', name:'Pemain 5', loc:'', titles:1},
  {id:'p6', name:'Pemain 6', loc:'Kota Bandung, Jawa Barat', titles:1},
  {id:'p7', name:'Pemain 7', loc:'Jakarta Selatan, DKI Jakarta', titles:1},
  {id:'p8', name:'Pemain 8', loc:'Jakarta Selatan, DKI Jakarta', titles:1},
  {id:'p9', name:'Pemain 9', loc:'', titles:1},
  {id:'p10', name:'Pemain 10', loc:'', titles:1},
  {id:'p11', name:'Pemain 11', loc:'', titles:1},
  {id:'p12', name:'Pemain 12', loc:'', titles:1},
  {id:'p13', name:'Pemain 13', loc:'', titles:1},
  {id:'p14', name:'Pemain 14', loc:'', titles:1},
];
PLAYERS.forEach((p,i)=>p.rank=i+1);

const PLAYER_PROFILE = {
  name:'Alexander Wibowo', main:12, menang:10, winrate:83, juara:2,
  years:[{year:2026, main:12, menang:10, kalah:2, juara:2}],
  history:[
    {name:'PLATINUM GRAND OPENING PADEL TOURNAMENT', date:'Sabtu, 19 Juli 2026', result:'WINNER', icon:'trophy'},
    {name:'THE GREAT CONTEST INVITATIONAL', date:'Minggu, 28 Juni 2026', result:'SEMI FINAL', icon:'court'},
  ]
};

function mkTeam(a,b){ return [a,b]; }

const GROUP_TEAMS = {
  A:[
    {code:'A1', players:mkTeam('Farrel Suhandi','Kelvin Susanto')},
    {code:'A2', players:mkTeam('Alexander Wibowo','Marcus Halim')},
    {code:'A3', players:mkTeam('Reza Hartono','Vincent Tanuwijaya')},
    {code:'A4', players:mkTeam('Dimas Prasetyo','Yusuf Kamal')},
  ],
  B:[
    {code:'B1', players:mkTeam('Christopher Nathan','Imran Wicaksono')},
    {code:'B2', players:mkTeam('Hadi Mawaddah','Nasirudin Ahmad')},
    {code:'B3', players:mkTeam('Taslim Wirawan','Hatta Akhmat')},
    {code:'B4', players:mkTeam('Bagas Nugroho','Ilham Fadillah')},
  ],
};

function buildGroupMatches(group, startHour){
  const teams=GROUP_TEAMS[group], matches=[];
  let slot=0;
  for(let i=0;i<teams.length;i++){
    for(let j=i+1;j<teams.length;j++){
      const mins=(startHour*60)+(slot*30), hh=String(Math.floor(mins/60)).padStart(2,'0'), mm=String(mins%60).padStart(2,'0');
      matches.push({
        t1:teams[i].players, code1:teams[i].code, s1:'-',
        t2:teams[j].players, code2:teams[j].code, s2:'-',
        time:`Sabtu, 19 Juli 2026 pukul ${hh}.${mm}`, winner:0
      });
      slot++;
    }
  }
  return matches;
}

const GROUPS = { A:buildGroupMatches('A',8), B:buildGroupMatches('B',11) };

const R16 = [
  {t1:mkTeam('Alexander Wibowo','Marcus Halim'), s1:6, t2:mkTeam('Rafi Setiawan','Gilang Permana'), s2:1, time:'Sabtu, 19 Juli 2026 pukul 14.00', winner:1},
  {t1:mkTeam('Christopher Nathan','Imran Wicaksono'), s1:6, t2:mkTeam('Yudha Pratama','Bayu Aditya'), s2:4, time:'Sabtu, 19 Juli 2026 pukul 14.30', winner:1},
];
const QF = [
  {t1:mkTeam('Alexander Wibowo','Marcus Halim'), s1:6, t2:mkTeam('Christopher Nathan','Imran Wicaksono'), s2:2, time:'Sabtu, 19 Juli 2026 pukul 16.00', winner:1},
];
const SF = [
  {t1:mkTeam('Alexander Wibowo','Marcus Halim'), s1:6, t2:mkTeam('Reza Hartono','Vincent Tanuwijaya'), s2:3, time:'Minggu, 20 Juli 2026 pukul 09.00', winner:1},
];
const FINAL = [
  {t1:mkTeam('Alexander Wibowo','Marcus Halim'), s1:6, t2:mkTeam('Taslim Wirawan','Hatta Akhmat'), s2:2, time:'Minggu, 20 Juli 2026 pukul 15.00', winner:1},
];

const MATCH_DETAIL = {
  status:'SELESAI', lap:'LAP 2', stage:'GROUP STAGE',
  t1:mkTeam('Farrel Suhandi','Kelvin Susanto'), s1:2,
  t2:mkTeam('Alexander Wibowo','Marcus Halim'), s2:6,
  winner:2,
  tournament:'PLATINUM GRAND OPENING PADEL TOURNAMENT · PLATINUM PADEL COURT',
  history:[
    {score:'2-6', time:'08.58.12'},{score:'2-5', time:'08.51.40'},{score:'1-5', time:'08.47.02'},
    {score:'1-4', time:'08.41.18'},{score:'1-3', time:'08.36.55'},{score:'0-3', time:'08.31.09'},
  ]
};

const CATEGORIES = [
  {id:'rookie-men', label:'Rookie', tier:'Men'},
  {id:'bronze-men', label:'Bronze', tier:'Men'},
  {id:'upper-women', label:'Upper Beginner', tier:'Women'},
  {id:'open-men', label:'Open', tier:'Men'},
  {id:'open-women', label:'Open', tier:'Women'},
];

const TOURNAMENTS = [
  {
    id:'grand-opening',
    badge:'PLATINUM SOCIETAS',
    name:'Platinum Grand Opening Padel Tournament',
    dateRange:'18 Jul 2026 – 20 Jul 2026',
    venue:'Platinum Padel Court',
    venueSub:'Senayan City Lt.3',
    location:'Jakarta Selatan, DKI Jakarta',
    prize:'150.000.000++',
    status:'ongoing',
    poster:'linear-gradient(160deg,#173a2e,#0c1e17)',
    tags:['Rookie · Men','Bronze · Men','Upper Beginner · Women','Open · Men','Open · Women'],
  },
  {
    id:'great-contest',
    badge:'PLATINUM SOCIETAS',
    name:'The Great Contest Invitational',
    dateRange:'22 Aug 2026 – 23 Aug 2026',
    venue:'Platinum Padel Court',
    venueSub:'Senayan City Lt.3',
    location:'Jakarta Selatan, DKI Jakarta',
    prize:'80.000.000++',
    status:'upcoming',
    poster:'linear-gradient(160deg,#1c4335,#0c1e17)',
    tags:['Open · Men','Open · Women','Bronze · Mixed'],
  },
];

const HISTORY = [
  {badge:'PLATINISTA', name:'Bounce & Crest Vol. 02', date:'12 Jun 2026 – 13 Jun 2026', venue:'The Crest Court, Kota Bandung', prize:'20.000.000', tags:['Upper Beginner · Men','Rookie · Women','Bronze · Men']},
  {badge:'CONNECTING UP', name:'Moslem Padel Weekend', date:'6 Jun 2026 – 6 Jun 2026', venue:'Padel Garden, Malang', prize:'—', tags:['Upper Beginner · Mixed','Open · Men']},
  {badge:'PLATINUM SOCIETAS', name:'Platinum Mini Tournament Vol.1', date:'14 May 2026 – 14 May 2026', venue:'Sidoarjo', prize:'10.000.000', tags:['Rookie · Men','Rookie · Women']},
];

const FINALISTS = [
  {initials:'AW', name:'Alexander Wibowo', tname:'Bounce & Crest Vol. 02'},
  {initials:'BK', name:'Bianca Kartadinata', tname:'Bounce & Crest Vol. 02'},
  {initials:'TW', name:'Taslim Wirawan', tname:'Bounce & Crest Vol. 02'},
  {initials:'GS', name:'Gabriela Salim', tname:'Bounce & Crest Vol. 02'},
  {initials:'AS', name:'Angelica Suherman', tname:'Bounce & Crest Vol. 02'},
  {initials:'MH', name:'Marcus Halim', tname:'Bounce & Crest Vol. 02'},
];

/* ============================= HELPERS ============================= */
function initials(name){ return name.split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase(); }
function esc(s){ return s; }

function matchCard(m, cat, lap){
  return `
  <div class="m-card">
    <div class="m-top"><span class="cat">${cat||'MEN · GROUP STAGE'}</span><span class="lap">${lap||'LAP 2'}</span></div>
    <div class="m-team ${m.winner===1?'winner':''}">
      <div class="names">${m.code1?`<span class="team-code">${m.code1}</span>`:""}${m.t1[0]}<br>${m.t1[1]}</div>
      <div class="score">${m.s1}</div>
    </div>
    <div class="m-team ${m.winner===2?'winner':''}">
      <div class="names">${m.code2?`<span class="team-code">${m.code2}</span>`:""}${m.t2[0]}<br>${m.t2[1]}</div>
      <div class="score">${m.s2}</div>
    </div>
    <div class="m-divider"></div>
    <div class="m-meta"><span>1 SET · FIRST6 · GP</span><span>${ICONS.check}</span></div>
    <div style="padding:0 18px 14px; font-family:var(--ui); font-size:11px; color:var(--cream-faint);">${m.time}</div>
    <button class="m-detail-btn" onclick="location.hash='#/match/x'">See Match Detail</button>
  </div>`;
}

/* ============================= VIEWS ============================= */
function viewHome(){
  return `
  <div class="invite-home">
    <section class="invite-hero invite-section">
      <div class="invite-grid" aria-hidden="true"></div>
      <div class="invite-ball one" aria-hidden="true"></div>
      <div class="invite-ball two" aria-hidden="true"></div>

      <div class="invite-copy invite-reveal">
        <p class="invite-eyebrow">PLATINUM PADEL PRESENTS</p>
        <h1 class="invite-heading">Where every point<br><em>becomes a moment.</em></h1>
        <p class="invite-description">Sebuah perayaan padel yang mempertemukan energi, kompetisi, dan komunitas dalam pengalaman tiga hari yang dirancang secara eksklusif.</p>
        <div class="invite-actions">
          <button class="invite-primary" type="button" data-invite-scroll="invite-event">
            <span>Lihat Detail</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
          <a class="invite-secondary" href="#/tournaments">Lihat Tournaments</a>
        </div>
      </div>

      <div class="invite-date-card invite-reveal">
        <span class="invite-date-number">28</span>
        <div><span>AUGUST</span><strong>2026</strong></div>
        <small>UNTIL 30 AUGUST</small>
      </div>
      <div class="invite-scroll" aria-hidden="true"><span>SCROLL TO EXPLORE</span><i></i></div>
    </section>

    <section class="invite-countdown-section invite-section" id="invite-event">
      <div class="invite-section-heading invite-reveal">
        <div><p class="invite-eyebrow">SAVE THE DATE</p><h2 class="invite-heading">The countdown<br><em>has begun.</em></h2></div>
      </div>
      <div class="invite-countdown invite-reveal" aria-label="Hitung mundur acara">
        <div><strong id="inviteDays">00</strong><span>Days</span></div>
        <div><strong id="inviteHours">00</strong><span>Hours</span></div>
        <div><strong id="inviteMinutes">00</strong><span>Minutes</span></div>
        <div><strong id="inviteSeconds">00</strong><span>Seconds</span></div>
      </div>
      <div class="invite-event-summary invite-reveal">
        <div class="invite-summary-index">01</div>
        <div class="invite-summary-content">
          <span>THE EVENT</span><h3>Platinum Padel</h3>
          <p>Hadir dan jadilah bagian dari atmosfer kompetitif yang sportif, modern, dan penuh kebersamaan. Registrasi peserta dimulai pukul 06.00 WIB.</p>
        </div>
        <div class="invite-summary-meta">
          <div><span>Date</span><strong>28–30 August 2026</strong></div>
          <div><span>Registration</span><strong>06.00 WIB</strong></div>
          <div><span>Venue</span><strong>Platinum Padel</strong></div>
        </div>
      </div>
    </section>

    <section class="invite-schedule-section invite-section" id="invite-schedule">
      <div class="invite-section-heading invite-reveal">
        <div><p class="invite-eyebrow">EVENT RUNDOWN</p><h2 class="invite-heading">Three days.<br><em>One unforgettable court.</em></h2></div>
      </div>
      <div class="invite-timeline">
        <article class="invite-timeline-item invite-reveal"><span class="invite-timeline-day">DAY 01</span><div class="invite-timeline-date">28 AUG</div><div class="invite-timeline-info"><h3>Opening, Registration & Group Stage</h3><p>Registrasi peserta mulai pukul 06.00 WIB, technical briefing, pertandingan pembuka, dan rangkaian fase grup.</p></div><span class="invite-timeline-time">06.00 WIB</span></article>
        <article class="invite-timeline-item invite-reveal"><span class="invite-timeline-day">DAY 02</span><div class="invite-timeline-date">29 AUG</div><div class="invite-timeline-info"><h3>Knockout Stage</h3><p>Pertandingan eliminasi menuju babak semifinal dan final.</p></div><span class="invite-timeline-time">Schedule TBA</span></article>
        <article class="invite-timeline-item invite-reveal"><span class="invite-timeline-day">DAY 03</span><div class="invite-timeline-date">30 AUG</div><div class="invite-timeline-info"><h3>Final & Celebration</h3><p>Partai puncak, awarding ceremony, dan penutupan acara.</p></div><span class="invite-timeline-time">Schedule TBA</span></article>
      </div>
    </section>

    <section class="invite-venue-section" id="invite-venue">
      <div class="invite-venue-visual invite-reveal">
        <div class="invite-court" aria-hidden="true"><div class="invite-court-line invite-court-outer"></div><div class="invite-court-line invite-court-middle"></div><div class="invite-court-line invite-court-left"></div><div class="invite-court-line invite-court-right"></div><span class="invite-court-ball"></span></div>
      </div>
      <div class="invite-venue-copy invite-reveal">
        <p class="invite-eyebrow">THE VENUE</p><h2 class="invite-heading">Platinum<br><em>Padel.</em></h2>
        <p>Lokasi resmi pertandingan Platinum Padel. Alamat lengkap dan tautan Google Maps dapat ditambahkan setelah data lokasi tersedia.</p>
        <div class="invite-venue-details"><div><span>Location</span><strong>Platinum Padel</strong></div><div><span>Address</span><strong>Alamat akan diperbarui</strong></div></div>
        <span class="invite-secondary invite-disabled">Google Maps segera tersedia</span>
      </div>
    </section>

    <section class="invite-closing invite-section">
      <div class="invite-closing-copy invite-reveal">
        <p class="invite-eyebrow">SEE YOU ON COURT</p><h2 class="invite-heading">Bring your game.<br><em>Leave your legacy.</em></h2>
        <p>28–30 Agustus 2026 · Registrasi pukul 06.00 WIB</p>
        <button class="invite-primary" type="button" data-invite-scroll="top"><span>Kembali ke Atas</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg></button>
      </div>
    </section>
    <footer class="invite-footer"><div class="invite-footer-brand"><span>PP</span><strong>PLATINUM PADEL</strong></div><p>Digital Invitation · 2026</p><p>Sportsmanship · Community · Excellence</p></footer>
  </div>`;
}

function tournamentCard(t){
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
      <div class="tags">${t.tags.slice(0,3).map(tag=>`<span class="tag">${tag}</span>`).join('')}</div>
    </div>
  </a>`;
}

function viewTournaments(){
  return `
  <div style="padding-top:40px;"></div>
  <section>
    <div class="section-head">
      <div><div class="eyebrow">Currently Live</div><h2>Tournaments</h2></div>
    </div>
    <div class="t-grid">${TOURNAMENTS.map(tournamentCard).join('')}</div>
  </section>

  <section class="section">
    <div class="section-head">
      <div><div class="eyebrow">Latest Champions</div><h2>Finalist Pool</h2></div>
      <a class="see-all" href="#/players">Lihat Semua Finalis →</a>
    </div>
    <div class="f-grid">
      ${FINALISTS.map(f=>`
        <div class="f-card">
          <div class="avatar">${f.initials}<span class="crown">${ICONS.crown}</span></div>
          <div class="pname">${f.name}</div>
          <div class="pill">${ICONS.trophy} Juara</div>
          <div class="tname">${f.tname}</div>
        </div>`).join('')}
    </div>
  </section>

  <section class="section">
    <div class="section-head">
      <div><div class="eyebrow">Archive</div><h2>History</h2></div>
      <span class="see-all">${HISTORY.length} Turnamen</span>
    </div>
    <div class="h-grid">
      ${HISTORY.map(h=>`
        <div class="t-card">
          <div class="poster" style="background:linear-gradient(160deg,#173a2e,#0c1e17);">
            <span class="badge">${h.badge}</span><span class="status finished">Finished</span>
          </div>
          <div class="body">
            <div class="name">${h.name}</div>
            <div class="meta"><div class="meta-row">${ICONS.calendar} ${h.date}</div><div class="meta-row">${ICONS.court} ${h.venue}</div></div>
            <div class="tags">${h.tags.map(t=>`<span class="tag">${t}</span>`).join('')}</div>
          </div>
        </div>`).join('')}
    </div>
  </section>`;
}

let currentCat = 'open-men';
let currentTab = 'results';

function viewTournamentDetail(id){
  const t = TOURNAMENTS.find(x=>x.id===id) || TOURNAMENTS[0];
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
    </div>
  </div>

  <div style="display:flex; align-items:center; justify-content:space-between; margin-top:34px;">
    <div class="eyebrow dim">Pilih Kategori</div>
  </div>
  <div class="cat-row" id="catRow">
    ${CATEGORIES.map(c=>`<button class="cat-btn ${c.id===currentCat?'active':''}" data-cat="${c.id}">${ICONS.trophy} ${c.label} <span class="t">— ${c.tier}</span></button>`).join('')}
  </div>

  <div class="tab-row" id="tabRow">
    ${['fixture','results','leaderboard','bracket'].map(tb=>`<button class="tab-btn ${tb===currentTab?'active':''}" data-tab="${tb}">${tb}</button>`).join('')}
  </div>

  <div id="tabContent">${renderTabContent()}</div>
  `;
}

function renderTabContent(){
  if(currentTab==='results') return renderResults();
  if(currentTab==='fixture') return renderResults(true);
  if(currentTab==='leaderboard') return renderMiniLeaderboard();
  if(currentTab==='bracket') return renderBracket();
  return '';
}

function renderResults(isFixture){
  return `
  <div class="acc open" data-acc="group">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)">
      <span>Group Stage</span>
      <span class="count">${isFixture?'12 Match':'12 Match'} ${ICONS.chev}</span>
    </div>
    <div class="acc-body">
      ${Object.keys(GROUPS).map(g=>`
        <div class="sub-acc ${g==='A'?'open':''}">
          <div class="sub-head" onclick="toggleSubAcc(this.parentElement)">Group ${g} <span style="color:var(--cream-faint); font-weight:400;">${GROUPS[g].length} match ${ICONS.chev}</span></div>
          <div class="sub-body">
            ${GROUPS[g].map(m=>matchCard(m)).join('')}
          </div>
        </div>`).join('')}
    </div>
  </div>
  <div class="acc" data-acc="r16">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Round of 16</span><span class="count">2 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${R16.map(m=>matchCard(m,'MEN · ROUND OF 16','LAP 3')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="qf">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Quarter Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${QF.map(m=>matchCard(m,'MEN · QUARTER FINAL','LAP 4')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="sf">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Semi Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${SF.map(m=>matchCard(m,'MEN · SEMI FINAL','LAP 5')).join('')}</div></div>
  </div>
  <div class="acc" data-acc="final">
    <div class="acc-head" onclick="toggleAcc(this.parentElement)"><span>Final</span><span class="count">1 Match ${ICONS.chev}</span></div>
    <div class="acc-body"><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">${FINAL.map(m=>matchCard(m,'MEN · FINAL','LAP 6')).join('')}</div></div>
  </div>
  `;
}

function renderMiniLeaderboard(){
  return `<div class="p-grid">${PLAYERS.slice(0,8).map((p,i)=>playerRow(p,i)).join('')}</div>`;
}

function bracketColumn(title, matches){
  return `<div style="flex:1; min-width:220px;">
    <div class="eyebrow dim" style="margin-bottom:14px; text-align:center;">${title}</div>
    <div style="display:flex; flex-direction:column; gap:36px; justify-content:center; height:100%;">
      ${matches.map(m=>`
      <div class="m-card" style="max-width:260px; margin:0 auto;">
        <div class="m-team ${m.winner===1?'winner':''}"><div class="names" style="font-size:14px;">${m.t1[0]}<br>${m.t1[1]}</div><div class="score" style="font-size:18px;">${m.s1}</div></div>
        <div class="m-divider"></div>
        <div class="m-team ${m.winner===2?'winner':''}"><div class="names" style="font-size:14px;">${m.t2[0]}<br>${m.t2[1]}</div><div class="score" style="font-size:18px;">${m.s2}</div></div>
      </div>`).join('')}
    </div>
  </div>`;
}

function renderBracket(){
  return `<div style="overflow-x:auto; padding-bottom:20px;">
    <div style="display:flex; gap:30px; min-width:900px;">
      ${bracketColumn('Round of 16', R16)}
      ${bracketColumn('Quarter Final', QF)}
      ${bracketColumn('Semi Final', SF)}
      ${bracketColumn('Final', FINAL)}
    </div>
  </div>`;
}

function viewMatchDetail(){
  const m = MATCH_DETAIL;
  const total = m.s1+m.s2;
  const pct1 = Math.round(m.s1/total*100), pct2=100-pct1;
  return `
  <a class="back-link" href="#/tournament/grand-opening">${ICONS.back} Kembali ke Detail Turnamen</a>
  <div class="md-hero">
    <div class="md-status"><span style="color:var(--gold);">${m.status}</span><span>·</span><span>${m.lap}</span><span>·</span><span>${m.stage}</span></div>
    <div class="md-score-row">
      <div class="md-side">
        <div class="md-avatars"><div class="avatar">${initials(m.t1[0])}</div><div class="avatar">${initials(m.t1[1])}</div></div>
        <div class="nm">${m.t1[0]}<br>${m.t1[1]}</div>
      </div>
      <div class="md-score"><span class="${m.winner===1?'bright':'dim'}">${m.s1}</span><span style="color:var(--cream-faint); font-size:28px;">–</span><span class="${m.winner===2?'bright':'dim'}">${m.s2}</span></div>
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
    ${m.history.map(h=>`<div class="history-item"><span class="score">${h.score} <span style="font-family:var(--ui); font-size:10px; color:var(--cream-faint); text-transform:uppercase; margin-left:8px;">Set 1</span></span><span class="time">Pembaruan Skor · ${h.time}</span></div>`).join('')}
  </div>
  `;
}

function playerRow(p,i){
  const rankClass = p.rank<=3 ? `rank-${p.rank}` : '';
  const rankTop = p.rank<=3 ? 'top' : '';
  return `
  <a class="p-row ${rankClass}" href="#/player/${p.id}">
    <div class="p-rank ${rankTop}">${p.rank}</div>
    <div class="avatar">${initials(p.name)}</div>
    <div class="p-info">
      <div class="nm">${p.name}</div>
      <div class="loc">${p.loc? ICONS.pin+' '+p.loc : '—'}</div>
    </div>
    <div class="p-titles">${ICONS.trophy} ${p.titles} Titles</div>
  </a>`;
}

function teamGroupCard(team){
  const searchText = `${team.code} ${team.players.join(' ')}`.toLowerCase();
  return `
  <article class="team-group-card" data-team-search="${searchText}">
    <div class="team-code"><small>Team</small><strong>${team.code}</strong></div>
    <div class="team-player-list">
      ${team.players.map((name,index)=>`
        <div class="team-player-item">
          <div class="avatar">${initials(name)}</div>
          <div class="team-player-copy">
            <div class="nm">${name}</div>
            <div class="role">Pemain ${index+1}</div>
          </div>
        </div>`).join('')}
    </div>
    <div class="team-player-count">2 Pemain</div>
  </article>`;
}

function groupRosterSection(group){
  return `
  <section class="group-roster-section" data-roster-group="${group}">
    <div class="group-roster-head">
      <h2>Group ${group}</h2>
      <span>${GROUP_TEAMS[group].length} Team · 8 Pemain</span>
    </div>
    <div class="team-group-grid">
      ${GROUP_TEAMS[group].map(teamGroupCard).join('')}
    </div>
  </section>`;
}

function viewPlayers(){
  return `
  <div style="padding-top:40px;"></div>
  <div class="lb-head">
    <div class="lb-title">
      <div class="eyebrow">The Roster</div>
      <h1>Player Grouping</h1>
      <div class="eyebrow dim" style="margin-top:8px;">A1–A4 dan B1–B4 · Setiap team berisi 2 pemain</div>
    </div>
    <input class="search-box" id="groupSearch" placeholder="Cari kode atau nama..." autocomplete="off">
  </div>
  ${groupRosterSection('A')}
  ${groupRosterSection('B')}
  <div class="group-search-empty" id="groupSearchEmpty">Team atau nama pemain tidak ditemukan.</div>
  `;
}

function viewPlayerProfile(){
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
    ${p.years.map(y=>`
      <div class="year-card">
        <div class="year-top"><span>${y.year}</span><span class="wr">${y.menang}/${y.main} menang</span></div>
        <div class="bar"><div class="bar-fill" style="width:${Math.round(y.menang/y.main*100)}%;"></div></div>
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
      ${p.history.map(h=>`
        <div class="history-item">
          <span>${h.name}<br><span class="time">${h.date}</span></span>
          <span class="pill">${h.result}</span>
        </div>`).join('')}
    </div>
  </div>
  `;
}

/* ============================= ROUTER ============================= */
const app = document.getElementById('app');

function toggleAcc(el){
  const wasOpen = el.classList.contains('open');
  document.querySelectorAll('.acc').forEach(a=>a.classList.remove('open'));
  if(!wasOpen) el.classList.add('open');
}
function toggleSubAcc(el){
  const wasOpen = el.classList.contains('open');
  el.parentElement.querySelectorAll('.sub-acc').forEach(a=>a.classList.remove('open'));
  if(!wasOpen) el.classList.add('open');
}


let homeCountdownTimer = null;

function attachHomeHandlers(){
  const eventTime = new Date('2026-08-28T06:00:00+07:00').getTime();
  const pad = value => String(Math.max(0,value)).padStart(2,'0');
  const updateCountdown = ()=>{
    const distance = eventTime - Date.now();
    const ids = {
      inviteDays: Math.floor(Math.max(0,distance) / 86400000),
      inviteHours: Math.floor((Math.max(0,distance) % 86400000) / 3600000),
      inviteMinutes: Math.floor((Math.max(0,distance) % 3600000) / 60000),
      inviteSeconds: Math.floor((Math.max(0,distance) % 60000) / 1000)
    };
    Object.entries(ids).forEach(([id,value])=>{ const el=document.getElementById(id); if(el) el.textContent=pad(value); });
  };
  updateCountdown();
  homeCountdownTimer = window.setInterval(updateCountdown,1000);

  document.querySelectorAll('[data-invite-scroll]').forEach(button=>{
    button.addEventListener('click',()=>{
      const target = button.dataset.inviteScroll;
      if(target==='top') window.scrollTo({top:0,behavior:'smooth'});
      else document.getElementById(target)?.scrollIntoView({behavior:'smooth',block:'start'});
    });
  });

  const reveals = document.querySelectorAll('.invite-reveal');
  if('IntersectionObserver' in window){
    const observer = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{ if(entry.isIntersecting){ entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
    },{threshold:.1});
    reveals.forEach(item=>observer.observe(item));
  } else {
    reveals.forEach(item=>item.classList.add('is-visible'));
  }
}

function attachTournamentHandlers(){
  document.querySelectorAll('#catRow .cat-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      currentCat = btn.dataset.cat;
      document.querySelectorAll('#catRow .cat-btn').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
  document.querySelectorAll('#tabRow .tab-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      currentTab = btn.dataset.tab;
      document.querySelectorAll('#tabRow .tab-btn').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('tabContent').innerHTML = renderTabContent();
    });
  });
}

function attachPlayersHandlers(){
  const search = document.getElementById('groupSearch');
  if(!search) return;
  search.addEventListener('input', ()=>{
    const keyword = search.value.trim().toLowerCase();
    let visibleCount = 0;
    document.querySelectorAll('[data-team-search]').forEach(card=>{
      const visible = !keyword || card.dataset.teamSearch.includes(keyword);
      card.style.display = visible ? '' : 'none';
      if(visible) visibleCount++;
    });
    document.querySelectorAll('[data-roster-group]').forEach(section=>{
      const hasVisibleCard = Array.from(section.querySelectorAll('[data-team-search]')).some(card=>card.style.display !== 'none');
      section.style.display = hasVisibleCard ? '' : 'none';
    });
    const empty = document.getElementById('groupSearchEmpty');
    if(empty) empty.style.display = visibleCount ? 'none' : 'block';
  });
}

function updateNav(route){
  document.querySelectorAll('#navLeft a').forEach(a=>{
    a.classList.toggle('active', a.dataset.route===route);
  });
}

function router(){
  if(homeCountdownTimer){ window.clearInterval(homeCountdownTimer); homeCountdownTimer=null; }
  const hash = location.hash || '#/';
  const isHome = (hash === '#/' || hash === '');
  app.classList.toggle('home-page', isHome);
  window.scrollTo(0,0);
  if(isHome){
    app.innerHTML = viewHome(); updateNav('home');
    attachHomeHandlers();
  } else if(hash === '#/tournaments'){
    app.innerHTML = viewTournaments(); updateNav('tournaments');
  } else if(hash.startsWith('#/tournament/')){
    const id = hash.split('/')[2];
    app.innerHTML = viewTournamentDetail(id); updateNav('tournaments');
    attachTournamentHandlers();
  } else if(hash.startsWith('#/match/')){
    app.innerHTML = viewMatchDetail(); updateNav('tournaments');
  } else if(hash === '#/players'){
    app.innerHTML = viewPlayers(); updateNav('players');
    attachPlayersHandlers();
  } else if(hash.startsWith('#/player/')){
    app.innerHTML = viewPlayerProfile(); updateNav('players');
  } else {
    app.innerHTML = `<div class="empty">Halaman tidak ditemukan.</div>`; updateNav('');
  }
}

window.addEventListener('hashchange', router);
router();
