/* ============================= CONFIG ============================= */
const API_URL = "/api/v1";

/* ============================= DATA (will be populated from API) ============================= */
let LEADERBOARD = [];
let PLAYERS = [];
let TOURNAMENTS = [];
let GROUPS = {}; // roster teams, used by Players page
let GROUP_MATCHES = {};
let CATEGORY_DATA = {};
let CURRENT_TOURNAMENT = null;
let tournamentLiveTimer = null;
let R16 = [];
let QF = [];
let SF = [];
let FINAL = [];
let FINALISTS = [];
let HISTORY = [];
let PLAYER_PROFILE = {};
let CURRENT_TOURNAMENT_ID = 1; // default tournament

/* ============================= ICONS (TETAP SAMA) ============================= */
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

/* ============================= CATEGORIES (TETAP SAMA) ============================= */
let CATEGORIES = [];

let currentCat = null;
let currentTab = "results";

/* ============================= API HELPER ============================= */
async function fetchAPI(endpoint) {
    try {
        const response = await fetch(`${API_URL}${endpoint}`);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error("API Error:", error);
        return null;
    }
}

async function loadLeaderboard(categoryId = currentCat) {
    const query = categoryId ? `?category_id=${encodeURIComponent(categoryId)}` : "";
    const data = await fetchAPI(`/leaderboard${query}`);

    if (data?.leaderboard) {
        LEADERBOARD = data.leaderboard;
        console.log("✅ Leaderboard loaded:", LEADERBOARD.length);
    }
}

async function loadData() {
    console.log("🔄 Loading data from API...");

    try {
        // LOAD PARALLEL (lebih cepat)
        const [playersData, tournamentsData, historyData, leadersData] =
            await Promise.all([
                fetchAPI("/players?limit=20"),
                fetchAPI("/tournaments?status=ongoing,upcoming&limit=10"),
                fetchAPI("/tournaments/history?limit=5"),
                fetchAPI("/players/leaders?limit=6"),
            ]);

        // 1. Players
        if (playersData?.players) {
            PLAYERS = playersData.players;
            console.log("✅ Players loaded:", PLAYERS.length);
        }

        // 2. Tournaments
        if (tournamentsData?.tournaments) {
            TOURNAMENTS = tournamentsData.tournaments;
            console.log("✅ Tournaments loaded:", TOURNAMENTS.length);
        }

        // 3. History
        if (historyData?.history) {
            HISTORY = historyData.history;
            console.log("✅ History loaded:", HISTORY.length);
        }

        // 4. Finalists
        if (leadersData?.players) {
            FINALISTS = leadersData.players.map((p) => ({
                initials: getInitials(p.name),
                name: p.name,
                tname: p.titles > 0 ? `${p.titles}x Champion` : "Player",
            }));
            console.log("✅ Finalists loaded:", FINALISTS.length);
        }

        // 5. Load tournament detail (hanya jika ada tournament)
        if (TOURNAMENTS.length > 0) {
            const activeTournament =
                TOURNAMENTS.find((t) => t.status === "ongoing") ||
                TOURNAMENTS[0];
            if (activeTournament) {
                const tournamentDetail = await fetchAPI(
                    `/tournaments/${activeTournament.id}?with=teams,matches`,
                );
                if (tournamentDetail) {
                    const teams = tournamentDetail.teams || {};
                    GROUPS = {};
                    Object.keys(teams).forEach((groupCode) => {
                        GROUPS[groupCode] = teams[groupCode].map((team) => ({
                            code: team.code,
                            players: team.players || [
                                team.player1?.name || "?",
                                team.player2?.name || "?",
                            ],
                        }));
                    });

                    const matches = tournamentDetail.matches || {};
                    R16 = matches.r16 || [];
                    QF = matches.qf || [];
                    SF = matches.sf || [];
                    FINAL = matches.final || [];
                    console.log("✅ Tournament detail loaded");
                }
            }
        }

        console.log("✅ All data loaded!");
    } catch (error) {
        console.error("❌ Error loading data:", error);
    }
}

/* ============================= HELPERS ============================= */
function getInitials(name) {
    if (!name) return "??";
    const parts = name.split(" ");
    return parts
        .slice(0, 2)
        .map((w) => w[0])
        .join("")
        .toUpperCase();
}

function formatDateRange(start, end) {
    if (!start) return "TBD";
    try {
        const s = new Date(start);
        const e = new Date(end);
        return `${s.toLocaleDateString("id-ID", { day: "numeric", month: "short", year: "numeric" })} – ${e.toLocaleDateString("id-ID", { day: "numeric", month: "short", year: "numeric" })}`;
    } catch {
        return start;
    }
}

function formatDate(date) {
    if (!date) return "TBD";
    try {
        const d = new Date(date);
        return d.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    } catch {
        return date;
    }
}

function formatTime(date) {
    if (!date) return "TBD";
    try {
        const d = new Date(date);
        return d.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch {
        return date;
    }
}

function mkTeam(a, b) {
    return [a, b];
}

function esc(s) {
    return s || "";
}

/* ============================= MATCH CARD ============================= */
function matchCard(m, cat, lap) {
    if (!m) return "";

    const team1Names = m.team1
        ? [m.team1.player1?.name || "?", m.team1.player2?.name || "?"]
        : ["?", "?"];
    const team2Names = m.team2
        ? [m.team2.player1?.name || "?", m.team2.player2?.name || "?"]
        : ["?", "?"];

    const team1Code = m.team1?.code || "";
    const team2Code = m.team2?.code || "";
    const isScheduled = m.status === "scheduled";
    const s1 = isScheduled ? "-" : (m.score1 ?? 0);
    const s2 = isScheduled ? "-" : (m.score2 ?? 0);
    const winner = m.winner_id || 0;

    return `
    <div class="m-card">
        <div class="m-top">
            <span class="cat">${cat || "GROUP STAGE"}</span>
            <span class="lap">${lap || m.court || "COURT TBD"}</span>
        </div>
        <div class="m-team ${winner === m.team1_id ? "winner" : ""}">
            <div class="names">
                ${team1Code ? `<span class="team-code">${team1Code}</span>` : ""}
                ${team1Names[0]}<br>${team1Names[1]}
            </div>
            <div class="score">${s1}</div>
        </div>
        <div class="m-team ${winner === m.team2_id ? "winner" : ""}">
            <div class="names">
                ${team2Code ? `<span class="team-code">${team2Code}</span>` : ""}
                ${team2Names[0]}<br>${team2Names[1]}
            </div>
            <div class="score">${s2}</div>
        </div>
        <div class="m-divider"></div>
        <div class="m-meta">
            <span>${String(m.status || "scheduled").toUpperCase()} · ${String(m.round || "group").toUpperCase()}</span>
            <span>${m.status === "finished" ? ICONS.check : ""}</span>
        </div>
        <div style="padding:0 18px 14px; font-family:var(--ui); font-size:11px; color:var(--cream-faint);">
            ${m.scheduled_at ? formatDate(m.scheduled_at) + " pukul " + formatTime(m.scheduled_at) : "Jadwal belum ditentukan"}
        </div>
        <button class="m-detail-btn" onclick="location.hash='#/match/${m.id}'">See Match Detail</button>
    </div>`;
}

/* ============================= TOURNAMENT CARD ============================= */
function tournamentCard(t) {
    if (!t) return "";
    const tags = t.tags || [];
    const poster = t.poster || "linear-gradient(160deg,#173a2e,#0c1e17)";

    return `
    <a href="#/tournament/${t.id}" class="t-card" style="display:flex;">
        <div class="poster" style="background:${poster}">
            <span class="status ${t.status || "upcoming"}">${t.status || "upcoming"}</span>
            <span class="badge">${t.badge || "PLATINUM SOCIETAS"}</span>
        </div>
        <div class="body">
            <div class="name">${t.name}</div>
            <div class="meta">
                <div class="meta-row">${ICONS.calendar} ${formatDateRange(t.start_date, t.end_date)}</div>
                <div class="meta-row">${ICONS.court} ${t.venue} ${t.venue_sub ? "— " + t.venue_sub : ""}</div>
                <div class="meta-row">${ICONS.pin} ${t.location}</div>
            </div>
            <div class="tags">${tags
                .slice(0, 3)
                .map((tag) => `<span class="tag">${tag}</span>`)
                .join("")}</div>
        </div>
    </a>`;
}

/* ============================= VIEWS ============================= */

function viewHome() {
    // HOME PAGE - TETAP SAMA PERSIS SEPERTI ORIGINAL
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

function viewTournaments() {
    if (TOURNAMENTS.length === 0) {
        return `<div class="empty">⏳ Loading tournaments...</div>`;
    }

    return `
    <div style="padding-top:40px;"></div>
    <section>
        <div class="section-head">
            <div><div class="eyebrow">Currently Live</div><h2>Tournaments</h2></div>
        </div>
        <div class="t-grid">${TOURNAMENTS.map(tournamentCard).join("")}</div>
    </section>

    <section class="section">
        <div class="section-head">
            <div><div class="eyebrow">Latest Champions</div><h2>Finalist Pool</h2></div>
            <a class="see-all" href="#/players">Lihat Semua Finalis →</a>
        </div>
        <div class="f-grid">
            ${FINALISTS.map(
                (f) => `
                <div class="f-card">
                    <div class="avatar">${f.initials}<span class="crown">${ICONS.crown}</span></div>
                    <div class="pname">${f.name}</div>
                    <div class="pill">${ICONS.trophy} Juara</div>
                    <div class="tname">${f.tname}</div>
                </div>`,
            ).join("")}
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <div><div class="eyebrow">Archive</div><h2>History</h2></div>
            <span class="see-all">${HISTORY.length} Turnamen</span>
        </div>
        <div class="h-grid">
            ${HISTORY.map(
                (h) => `
                <div class="t-card">
                    <div class="poster" style="background:linear-gradient(160deg,#173a2e,#0c1e17);">
                        <span class="badge">${h.badge || "PLATINUM SOCIETAS"}</span>
                        <span class="status finished">Finished</span>
                    </div>
                    <div class="body">
                        <div class="name">${h.name}</div>
                        <div class="meta">
                            <div class="meta-row">${ICONS.calendar} ${formatDateRange(h.start_date, h.end_date)}</div>
                            <div class="meta-row">${ICONS.court} ${h.venue}</div>
                        </div>
                        <div class="tags">${(h.tags || []).map((t) => `<span class="tag">${t}</span>`).join("")}</div>
                    </div>
                </div>`,
            ).join("")}
        </div>
    </section>`;
}

function viewTournamentDetail(id) {
    const t = CURRENT_TOURNAMENT ||
        TOURNAMENTS.find((x) => x.id == id) ||
        TOURNAMENTS[0] ||
        { name: "Tournament", badge: "PLATINUM" };

    return `
    <a class="back-link" href="#/tournaments">${ICONS.back} Kembali ke Tournaments</a>
    <div class="t-banner">
        <div class="poster-lg" style="background:${t.poster || "linear-gradient(160deg,#173a2e,#0c1e17)"}"></div>
        <div class="info">
            <div class="badge">| ${t.badge || "PLATINUM PADEL"}</div>
            <h1>${t.name || t.title || "Tournament"}</h1>
            <div class="row">${ICONS.calendar} ${formatDateRange(t.start_date, t.end_date)}</div>
            <div class="row">${ICONS.court} ${t.venue || "Platinum Padel Court"}</div>
            <div class="row">${ICONS.pin} ${t.location || "TBD"}</div>
        </div>
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:34px;">
        <div class="eyebrow dim">Pilih Kategori</div>
    </div>
    <div class="cat-row" id="catRow">
        ${CATEGORIES.length
            ? CATEGORIES.map((c) => `<button class="cat-btn ${String(c.id) === String(currentCat) ? "active" : ""}" data-cat="${c.id}">${ICONS.trophy} ${c.label} <span class="t">— ${c.tier}</span></button>`).join("")
            : `<div class="empty">Belum ada kategori.</div>`}
    </div>

    <div class="tab-row" id="tabRow">
        ${["fixture", "results", "leaderboard", "bracket"].map((tb) => `<button class="tab-btn ${tb === currentTab ? "active" : ""}" data-tab="${tb}">${tb}</button>`).join("")}
    </div>

    <div id="tabContent">${renderTabContent()}</div>
    `;
}

function renderTabContent() {
    if (currentTab === "results") return renderResults();
    if (currentTab === "fixture") return renderResults(true);
    if (currentTab === "leaderboard") return renderMiniLeaderboard();
    if (currentTab === "bracket") return renderBracket();
    return "";
}

function renderResults(isFixture = false) {
    const includeMatch = (match) =>
        isFixture ? match.status !== "finished" : match.status === "finished";

    const groupMatches = {};
    Object.keys(GROUP_MATCHES).forEach((group) => {
        const filtered = (GROUP_MATCHES[group] || []).filter(includeMatch);
        if (filtered.length) groupMatches[group] = filtered;
    });

    const stages = {
        r16: R16.filter(includeMatch),
        qf: QF.filter(includeMatch),
        sf: SF.filter(includeMatch),
        final: FINAL.filter(includeMatch),
    };

    const groupCount = Object.values(groupMatches).reduce((total, list) => total + list.length, 0);
    const total = groupCount + Object.values(stages).reduce((sum, list) => sum + list.length, 0);

    if (total === 0) {
        return `<div class="empty">${isFixture ? "Belum ada fixture aktif." : "Belum ada hasil pertandingan."}</div>`;
    }

    const categoryName = CATEGORIES.find((category) => String(category.id) === String(currentCat))?.label || "CATEGORY";

    return `
    <div class="acc open" data-acc="group">
        <div class="acc-head" onclick="toggleAcc(this.parentElement)">
            <span>Group Stage</span>
            <span class="count">${groupCount} Match ${ICONS.chev}</span>
        </div>
        <div class="acc-body">
            ${Object.keys(groupMatches).map((group, index) => `
                <div class="sub-acc ${index === 0 ? "open" : ""}">
                    <div class="sub-head" onclick="toggleSubAcc(this.parentElement)">
                        Group ${group}
                        <span style="color:var(--cream-faint); font-weight:400;">
                            ${groupMatches[group].length} match ${ICONS.chev}
                        </span>
                    </div>
                    <div class="sub-body">
                        ${groupMatches[group].map((match) => matchCard(match, `${categoryName.toUpperCase()} · GROUP ${group}`, match.court || "COURT")).join("")}
                    </div>
                </div>
            `).join("") || `<div class="empty">Tidak ada match group pada tab ini.</div>`}
        </div>
    </div>

    ${[
        ["r16", "Round of 16", "ROUND OF 16"],
        ["qf", "Quarter Final", "QUARTER FINAL"],
        ["sf", "Semi Final", "SEMI FINAL"],
        ["final", "Final", "FINAL"],
    ].map(([key, title, label]) => `
        <div class="acc" data-acc="${key}">
            <div class="acc-head" onclick="toggleAcc(this.parentElement)">
                <span>${title}</span>
                <span class="count">${stages[key].length} Match ${ICONS.chev}</span>
            </div>
            <div class="acc-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">
                    ${stages[key].map((match) => matchCard(match, `${categoryName.toUpperCase()} · ${label}`, match.court || "COURT")).join("") || `<div class="empty">Belum ada match.</div>`}
                </div>
            </div>
        </div>
    `).join("")}
    `;
}

function renderMiniLeaderboard() {
    if (LEADERBOARD.length === 0) {
        return `<div class="empty">Belum ada leaderboard.</div>`;
    }

    return `
        <div class="p-grid">
            ${LEADERBOARD.map((team, index) => `
                <div class="p-row">
                    <div class="p-rank ${index < 3 ? "top" : ""}">${index + 1}</div>
                    <div class="p-info">
                        <div class="nm">${team.team_code || team.team_name}</div>
                        <div class="loc">${(team.players || []).join("<br>")}</div>
                        <div style="margin-top:7px;font-family:var(--ui);font-size:10px;color:var(--cream-faint);">
                            Main ${team.played || 0} · Menang ${team.win || 0} · Kalah ${team.lose || 0}
                        </div>
                    </div>
                    <div class="p-titles">
                        ${ICONS.trophy} ${team.total_score || 0} Score
                    </div>
                </div>
            `).join("")}
        </div>
    `;
}

function bracketColumn(title, matches) {
    return `<div style="flex:1; min-width:220px;">
        <div class="eyebrow dim" style="margin-bottom:14px; text-align:center;">${title}</div>
        <div style="display:flex; flex-direction:column; gap:36px; justify-content:center; height:100%;">
            ${matches
                .map(
                    (m) => `
                <div class="m-card" style="max-width:260px; margin:0 auto;">
                    <div class="m-team ${m.winner_id === m.team1_id ? "winner" : ""}">
                        <div class="names" style="font-size:14px;">${m.team1?.player1?.name || "?"}<br>${m.team1?.player2?.name || "?"}</div>
                        <div class="score" style="font-size:18px;">${m.score1 ?? "-"}</div>
                    </div>
                    <div class="m-divider"></div>
                    <div class="m-team ${m.winner_id === m.team2_id ? "winner" : ""}">
                        <div class="names" style="font-size:14px;">${m.team2?.player1?.name || "?"}<br>${m.team2?.player2?.name || "?"}</div>
                        <div class="score" style="font-size:18px;">${m.score2 ?? "-"}</div>
                    </div>
                </div>`,
                )
                .join("")}
        </div>
    </div>`;
}

function renderBracket() {
    return `<div style="overflow-x:auto; padding-bottom:20px;">
        <div style="display:flex; gap:30px; min-width:900px;">
            ${bracketColumn("Round of 16", R16)}
            ${bracketColumn("Quarter Final", QF)}
            ${bracketColumn("Semi Final", SF)}
            ${bracketColumn("Final", FINAL)}
        </div>
    </div>`;
}

async function viewMatchDetail(id) {
    // Fetch match detail from API
    const data = await fetchAPI("/matches/" + id);
    if (!data || !data.match) {
        return `<div class="empty">Match not found</div>`;
    }

    const m = data.match;
    const total = (m.score1 || 0) + (m.score2 || 0);
    const pct1 = total > 0 ? Math.round(((m.score1 || 0) / total) * 100) : 50;
    const pct2 = 100 - pct1;

    const team1Names = m.team1
        ? [m.team1.player1?.name || "?", m.team1.player2?.name || "?"]
        : ["?", "?"];
    const team2Names = m.team2
        ? [m.team2.player1?.name || "?", m.team2.player2?.name || "?"]
        : ["?", "?"];

    const scoreHistory = m.score_history || [
        { score: `${m.score1 || 0}-${m.score2 || 0}`, time: "00.00.00" },
    ];

    return `
    <a class="back-link" href="#/tournament/${m.tournament_id}">${ICONS.back} Kembali ke Detail Turnamen</a>
    <div class="md-hero">
        <div class="md-status">
            <span style="color:var(--gold);">${m.played_at ? "SELESAI" : "SCHEDULED"}</span>
            <span>·</span>
            <span>${m.round || "LAP 1"}</span>
            <span>·</span>
            <span>${m.stage || "GROUP STAGE"}</span>
        </div>
        <div class="md-score-row">
            <div class="md-side">
                <div class="md-avatars">
                    <div class="avatar">${getInitials(team1Names[0])}</div>
                    <div class="avatar">${getInitials(team1Names[1])}</div>
                </div>
                <div class="nm">${team1Names[0]}<br>${team1Names[1]}</div>
            </div>
            <div class="md-score">
                <span class="${m.winner_id === m.team1_id ? "bright" : "dim"}">${m.score1 ?? 0}</span>
                <span style="color:var(--cream-faint); font-size:28px;">–</span>
                <span class="${m.winner_id === m.team2_id ? "bright" : "dim"}">${m.score2 ?? 0}</span>
            </div>
            <div class="md-side">
                <div class="md-avatars">
                    <div class="avatar">${getInitials(team2Names[0])}</div>
                    <div class="avatar">${getInitials(team2Names[1])}</div>
                </div>
                <div class="nm">${team2Names[0]}<br>${team2Names[1]}</div>
                ${m.winner_id ? `<div class="winner-tag">Pemenang</div>` : ""}
            </div>
        </div>
        <div class="md-sub">${m.tournament?.name || "Platinum Tournament"}</div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Statistik</div>
        <div class="stat-names">
            <span><span class="stat-dot" style="background:var(--cream-faint);"></span>${team1Names[0].split(" ")[0]} / ${team1Names[1].split(" ")[0]}</span>
            <span>${team2Names[0].split(" ")[0]} / ${team2Names[1].split(" ")[0]}<span class="stat-dot" style="background:var(--gold); margin-left:7px;"></span></span>
        </div>
        <div class="eyebrow dim" style="text-align:center; margin-bottom:10px;">Total Game</div>
        <div class="bar-wrap">
            <div class="bar-num">${m.score1 ?? 0}</div>
            <div class="bar">
                <div class="bar-fill" style="width:${pct1}%; background:var(--cream-faint);"></div>
                <div class="bar-fill" style="width:${pct2}%;"></div>
            </div>
            <div class="bar-num">${m.score2 ?? 0}</div>
        </div>
    </div>

    <div class="history-card">
        <div class="eyebrow dim" style="padding:18px 22px 0;">Riwayat Skor</div>
        ${scoreHistory
            .map(
                (h) => `
            <div class="history-item">
                <span class="score">${h.score} <span style="font-family:var(--ui); font-size:10px; color:var(--cream-faint); text-transform:uppercase; margin-left:8px;">Set 1</span></span>
                <span class="time">Pembaruan Skor · ${h.time}</span>
            </div>`,
            )
            .join("")}
    </div>
    `;
}

function playerRow(p, i) {
    const rank = i + 1;
    const rankClass = rank <= 3 ? `rank-${rank}` : "";
    const rankTop = rank <= 3 ? "top" : "";

    const name = p.user?.name ?? "Unknown";
    const location = p.city ?? "—";
    const points = p.ranking_point ?? 0;

    return `
    <a class="p-row ${rankClass}" href="#/player/${p.id}">
        <div class="p-rank ${rankTop}">${rank}</div>
        <div class="avatar">${getInitials(name)}</div>
        <div class="p-info">
            <div class="nm">${name}</div>
            <div class="loc">${location !== "—" ? ICONS.pin + " " + location : "—"}</div>
        </div>
        <div class="p-titles">${ICONS.trophy} ${points} Points</div>
    </a>`;
}

function teamGroupCard(team) {
    const searchText =
        `${team.code} ${team.players?.join(" ") || ""}`.toLowerCase();
    const players = team.players || ["?", "?"];

    return `
    <article class="team-group-card" data-team-search="${searchText}">
        <div class="team-code"><small>Team</small><strong>${team.code}</strong></div>
        <div class="team-player-list">
            ${players
                .map(
                    (name, index) => `
                <div class="team-player-item">
                    <div class="avatar">${getInitials(name)}</div>
                    <div class="team-player-copy">
                        <div class="nm">${name}</div>
                        <div class="role">Pemain ${index + 1}</div>
                    </div>
                </div>`,
                )
                .join("")}
        </div>
        <div class="team-player-count">2 Pemain</div>
    </article>`;
}

function groupRosterSection(group) {
    const teams = GROUPS[group] || [];
    return `
    <section class="group-roster-section" data-roster-group="${group}">
        <div class="group-roster-head">
            <h2>Group ${group}</h2>
            <span>${teams.length} Team · ${teams.length * 2} Pemain</span>
        </div>
        <div class="team-group-grid">
            ${teams.map(teamGroupCard).join("")}
        </div>
    </section>`;
}

function viewPlayers() {
    const groups = Object.keys(GROUPS);

    // Jika masih loading, tampilkan skeleton
    if (groups.length === 0) {
        return `
        <div style="padding-top:40px;"></div>
        <div class="lb-head">
            <div class="lb-title">
                <div class="eyebrow">The Roster</div>
                <h1>Player Grouping</h1>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            ${[1, 2, 3, 4]
                .map(
                    () => `
                <div style="background:#173a2e;border-radius:12px;padding:20px;border:1px solid rgba(201,167,102,0.22);">
                    <div style="height:60px;background:linear-gradient(90deg,#1c4335 25%,#2a5a48 50%,#1c4335 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px;"></div>
                    <div style="height:40px;margin-top:12px;background:linear-gradient(90deg,#1c4335 25%,#2a5a48 50%,#1c4335 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px;"></div>
                </div>
            `,
                )
                .join("")}
        </div>
        <style>
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        </style>
        `;
    }

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
    ${groups.map((g) => groupRosterSection(g)).join("")}
    <div class="group-search-empty" id="groupSearchEmpty">Team atau nama pemain tidak ditemukan.</div>
    `;
}

async function viewPlayerProfile(id) {
    const data = await fetchAPI("/players/" + id);
    if (!data || !data.player) {
        return `<div class="empty">Player not found</div>`;
    }

    const p = data.player;
    const profile = data.profile || {};
    const years = profile.years || [];
    const history = profile.history || [];

    return `
    <a class="back-link" href="#/players">${ICONS.back} Kembali ke Leaderboard</a>
    <div class="prof-banner">
        <div class="avatar">${getInitials(p.name)}</div>
        <h1>${p.name}</h1>
    </div>
    <div class="stat-boxes">
        <div class="stat-box"><div class="num">${profile.main || 0}</div><div class="lbl">Main</div></div>
        <div class="stat-box"><div class="num">${profile.menang || 0}</div><div class="lbl">Menang</div></div>
        <div class="stat-box"><div class="num">${profile.winrate || 0}%</div><div class="lbl">Win Rate</div></div>
        <div class="stat-box"><div class="num">${ICONS.trophy} ${p.titles || 0}</div><div class="lbl">Juara</div></div>
    </div>

    ${
        years.length > 0
            ? `
    <div class="section" style="margin-top:44px;">
        <div class="eyebrow" style="margin-bottom:16px;">${ICONS.calendar} Statistik Per Tahun</div>
        ${years
            .map(
                (y) => `
            <div class="year-card">
                <div class="year-top"><span>${y.year}</span><span class="wr">${y.menang}/${y.main} menang</span></div>
                <div class="bar"><div class="bar-fill" style="width:${y.main > 0 ? Math.round((y.menang / y.main) * 100) : 0}%;"></div></div>
                <div class="year-bottom">
                    <span>${y.main} main</span>
                    <span class="legend-dot"><i style="background:var(--win);"></i>${y.menang} menang</span>
                    <span class="legend-dot"><i style="background:var(--lose);"></i>${y.kalah || 0} kalah</span>
                    <span style="color:var(--gold-light);">${ICONS.trophy} ${y.juara || 0} juara</span>
                </div>
            </div>`,
            )
            .join("")}
    </div>`
            : ""
    }

    ${
        history.length > 0
            ? `
    <div class="section" style="margin-top:44px;">
        <div class="eyebrow" style="margin-bottom:16px;">${ICONS.trophy} Riwayat Turnamen</div>
        <div class="history-card">
            ${history
                .map(
                    (h) => `
                <div class="history-item">
                    <span>${h.name}<br><span class="time">${h.date || "TBD"}</span></span>
                    <span class="pill">${h.result || "PLAYED"}</span>
                </div>`,
                )
                .join("")}
        </div>
    </div>`
            : ""
    }
    `;
}

/* ============================= ROUTER ============================= */
const app = document.getElementById("app");

function toggleAcc(el) {
    if (!el) return;
    const wasOpen = el.classList.contains("open");
    document
        .querySelectorAll(".acc")
        .forEach((a) => a.classList.remove("open"));
    if (!wasOpen) el.classList.add("open");
}

function toggleSubAcc(el) {
    if (!el) return;
    const wasOpen = el.classList.contains("open");
    el.parentElement
        .querySelectorAll(".sub-acc")
        .forEach((a) => a.classList.remove("open"));
    if (!wasOpen) el.classList.add("open");
}

let homeCountdownTimer = null;

function attachHomeHandlers() {
    const eventTime = new Date("2026-08-28T06:00:00+07:00").getTime();
    const pad = (value) => String(Math.max(0, value)).padStart(2, "0");
    const updateCountdown = () => {
        const distance = eventTime - Date.now();
        const ids = {
            inviteDays: Math.floor(Math.max(0, distance) / 86400000),
            inviteHours: Math.floor(
                (Math.max(0, distance) % 86400000) / 3600000,
            ),
            inviteMinutes: Math.floor(
                (Math.max(0, distance) % 3600000) / 60000,
            ),
            inviteSeconds: Math.floor((Math.max(0, distance) % 60000) / 1000),
        };
        Object.entries(ids).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = pad(value);
        });
    };
    updateCountdown();
    homeCountdownTimer = window.setInterval(updateCountdown, 1000);

    document.querySelectorAll("[data-invite-scroll]").forEach((button) => {
        button.addEventListener("click", () => {
            const target = button.dataset.inviteScroll;
            if (target === "top")
                window.scrollTo({ top: 0, behavior: "smooth" });
            else
                document
                    .getElementById(target)
                    ?.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });

    const reveals = document.querySelectorAll(".invite-reveal");
    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 },
        );
        reveals.forEach((item) => observer.observe(item));
    } else {
        reveals.forEach((item) => item.classList.add("is-visible"));
    }
}

function attachTournamentHandlers() {
    document.querySelectorAll("#catRow .cat-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            currentCat = btn.dataset.cat;
            applyCategoryData(currentCat);

            document
                .querySelectorAll("#catRow .cat-btn")
                .forEach((button) => button.classList.remove("active"));
            btn.classList.add("active");

            const content = document.getElementById("tabContent");
            if (content) content.innerHTML = renderTabContent();
        });
    });

    document.querySelectorAll("#tabRow .tab-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            currentTab = btn.dataset.tab;
            document
                .querySelectorAll("#tabRow .tab-btn")
                .forEach((button) => button.classList.remove("active"));
            btn.classList.add("active");

            const content = document.getElementById("tabContent");
            if (content) content.innerHTML = renderTabContent();
        });
    });
}

function attachPlayersHandlers() {
    const search = document.getElementById("groupSearch");
    if (!search) return;
    search.addEventListener("input", () => {
        const keyword = search.value.trim().toLowerCase();
        let visibleCount = 0;
        document.querySelectorAll("[data-team-search]").forEach((card) => {
            const visible =
                !keyword || card.dataset.teamSearch.includes(keyword);
            card.style.display = visible ? "" : "none";
            if (visible) visibleCount++;
        });
        document.querySelectorAll("[data-roster-group]").forEach((section) => {
            const hasVisibleCard = Array.from(
                section.querySelectorAll("[data-team-search]"),
            ).some((card) => card.style.display !== "none");
            section.style.display = hasVisibleCard ? "" : "none";
        });
        const empty = document.getElementById("groupSearchEmpty");
        if (empty) empty.style.display = visibleCount ? "none" : "block";
    });
}

function updateNav(route) {
    document
        .querySelectorAll(".nav-left a, .nav-left .nav-link")
        .forEach((a) => {
            const linkRoute =
                a.dataset.route || a.getAttribute("href")?.replace("#/", "");
            if (linkRoute === route) {
                a.classList.add("active");
            } else {
                a.classList.remove("active");
            }
        });
}

let isLoading = false;

// Load players only
async function loadPlayersOnly() {
    console.log("🔄 Loading players...");
    const data = await fetchAPI("/players?limit=50");
    if (data?.players) {
        PLAYERS = data.players;
        console.log("✅ Players loaded:", PLAYERS.length);
    }
}

// Load tournaments only
async function loadTournamentsOnly() {
    console.log("🔄 Loading tournaments...");
    const [tournamentsData, leadersData] = await Promise.all([
        fetchAPI("/tournaments?status=ongoing,upcoming&limit=10"),
        fetchAPI("/players/leaders?limit=6"),
    ]);

    if (tournamentsData?.tournaments) {
        TOURNAMENTS = tournamentsData.tournaments;
        console.log("✅ Tournaments loaded:", TOURNAMENTS.length);
    }

    if (leadersData?.players) {
        FINALISTS = leadersData.players.map((p) => ({
            initials: getInitials(p.name),
            name: p.name,
            tname: p.titles > 0 ? `${p.titles}x Champion` : "Player",
        }));
        console.log("✅ Finalists loaded:", FINALISTS.length);
    }
}


function applyCategoryData(categoryId) {
    const selected = CATEGORY_DATA[String(categoryId)] || {};
    const matches = selected.matches || {};

    GROUP_MATCHES = matches.group || {};
    R16 = matches.r16 || [];
    QF = matches.qf || [];
    SF = matches.sf || [];
    FINAL = matches.final || [];
    LEADERBOARD = selected.leaderboard || [];
}

// Load tournament detail
async function loadTournamentDetail(id) {
    console.log("🔄 Loading tournament detail...");
    const data = await fetchAPI(`/tournaments/${id}?with=teams,matches`);

    if (!data) return;

    CURRENT_TOURNAMENT = data.tournament || null;

    if (CURRENT_TOURNAMENT) {
        const existingIndex = TOURNAMENTS.findIndex((tournament) => tournament.id == CURRENT_TOURNAMENT.id);
        if (existingIndex >= 0) {
            TOURNAMENTS[existingIndex] = CURRENT_TOURNAMENT;
        } else {
            TOURNAMENTS.push(CURRENT_TOURNAMENT);
        }
    }

    CATEGORIES = data.categories || [];
    CATEGORY_DATA = data.category_data || {};
    GROUPS = data.teams || {};

    const categoryExists = CATEGORIES.some(
        (category) => String(category.id) === String(currentCat),
    );

    if (!categoryExists) {
        currentCat = CATEGORIES[0]?.id ?? null;
    }

    applyCategoryData(currentCat);
    console.log("✅ Tournament detail loaded");
}

async function router() {
    if (homeCountdownTimer) {
        window.clearInterval(homeCountdownTimer);
        homeCountdownTimer = null;
    }

    if (tournamentLiveTimer) {
        window.clearInterval(tournamentLiveTimer);
        tournamentLiveTimer = null;
    }

    const hash = location.hash || "#/";
    const isHome = hash === "#/" || hash === "";
    app.classList.toggle("home-page", isHome);
    window.scrollTo(0, 0);

    if (isHome) {
        app.innerHTML = viewHome();
        updateNav("home");
        attachHomeHandlers();
        return;
    }

    // LOAD DATA PER HALAMAN (lazy loading)
    if (hash === "#/tournaments") {
        if (TOURNAMENTS.length === 0) {
            app.innerHTML = `<div class="empty" style="padding:80px 20px;">⏳ Loading tournaments...</div>`;
            await loadTournamentsOnly();
        }
        app.innerHTML = viewTournaments();
        updateNav("tournaments");
    } else if (hash.startsWith("#/tournament/")) {
        const id = hash.split("/")[2];
        app.innerHTML = `<div class="empty" style="padding:80px 20px;">⏳ Loading tournament details...</div>`;
        await loadTournamentDetail(id);
        app.innerHTML = viewTournamentDetail(id);
        updateNav("tournaments");
        setTimeout(attachTournamentHandlers, 100);

        tournamentLiveTimer = window.setInterval(async () => {
            if (location.hash !== `#/tournament/${id}`) return;

            await loadTournamentDetail(id);
            const content = document.getElementById("tabContent");
            if (content) content.innerHTML = renderTabContent();
        }, 3000);
    } else if (hash.startsWith("#/match/")) {
        const id = hash.split("/")[2];
        app.innerHTML = `<div class="empty" style="padding:80px 20px;">⏳ Loading match...</div>`;
        const content =
            (await viewMatchDetail(id)) ||
            `<div class="empty">Match not found</div>`;
        app.innerHTML = content;
        updateNav("tournaments");
    } else if (hash === "#/players") {
        if (PLAYERS.length === 0) await loadPlayersOnly();

        if (Object.keys(GROUPS).length === 0) {
            if (TOURNAMENTS.length === 0) await loadTournamentsOnly();

            const active =
                TOURNAMENTS.find((t) => t.status === "ongoing") ||
                TOURNAMENTS[0];

            if (active) await loadTournamentDetail(active.id);
        }

        app.innerHTML = viewPlayers();
        updateNav("players");
        setTimeout(attachPlayersHandlers, 100);
    } else if (hash.startsWith("#/player/")) {
        const id = hash.split("/")[2];
        app.innerHTML = `<div class="empty" style="padding:80px 20px;">⏳ Loading player profile...</div>`;
        const content =
            (await viewPlayerProfile(id)) ||
            `<div class="empty">Player not found</div>`;
        app.innerHTML = content;
        updateNav("players");
    } else {
        app.innerHTML = `<div class="empty">Halaman tidak ditemukan.</div>`;
        updateNav("");
    }
}

window.addEventListener("hashchange", router);

// Start the app
router();
