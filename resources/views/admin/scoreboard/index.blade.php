<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Scoreboard - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #0c1e17;
            color: #f2ecdd;
            font-family: Inter, sans-serif
        }

        .container {
            width: 92%;
            max-width: 1150px;
            margin: 34px auto
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        h1 {
            color: #c9a766;
            margin: 0
        }

        a {
            text-decoration: none;
            color: inherit
        }

        .back {
            color: #c9a766;
            font-weight: 700
        }

        .filter {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            background: #173a2e;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px
        }

        select {
            background: #0f2a21;
            color: #f2ecdd;
            border: 1px solid rgba(201, 167, 102, .32);
            border-radius: 8px;
            padding: 11px
        }

        .btn {
            border: 0;
            border-radius: 9px;
            padding: 11px 17px;
            background: #c9a766;
            color: #0c1e17;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none
        }

        .match-card {
            background: #173a2e;
            border: 1px solid rgba(201, 167, 102, .22);
            border-radius: 16px;
            padding: 26px;
            margin-bottom: 22px
        }

        .match-header {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: #c9a766;
            font-weight: 700;
            margin-bottom: 26px
        }

        .status {
            padding: 6px 11px;
            border-radius: 999px;
            font-size: 11px;
            color: white
        }

        .status.scheduled {
            background: #746640
        }

        .status.ongoing {
            background: #1f8b4c
        }

        .status.finished {
            background: #37526d
        }

        .scoreboard {
            display: grid;
            grid-template-columns: 1fr 130px 1fr;
            align-items: center;
            gap: 24px
        }

        .team {
            text-align: center
        }

        .team-code {
            font-size: 30px;
            color: #e6cf9c;
            font-weight: 800;
            margin-bottom: 9px
        }

        .player {
            font-size: 16px;
            font-weight: 600;
            margin: 5px 0
        }

        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px
        }

        .controls button {
            width: 46px;
            height: 46px;
            border: 0;
            border-radius: 10px;
            background: #c9a766;
            font-size: 24px;
            font-weight: 800;
            cursor: pointer
        }

        .controls input {
            width: 82px;
            height: 66px;
            text-align: center;
            font-size: 32px;
            font-weight: 800;
            border: 0;
            border-radius: 10px;
            background: white;
            color: #0c1e17
        }

        .vs {
            text-align: center;
            color: #c9a766;
            font-size: 30px;
            font-weight: 800
        }

        .save {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 28px
        }

        .finish {
            background: #1f8b4c;
            color: white
        }

        .empty,
        .notice {
            background: #173a2e;
            padding: 20px;
            border-radius: 12px;
            text-align: center
        }

        .notice {
            background: #1f8b4c;
            margin-bottom: 18px;
            text-align: left
        }

        .error {
            background: #963f3f
        }

        @media(max-width:760px) {
            .filter {
                grid-template-columns: 1fr
            }

            .scoreboard {
                grid-template-columns: 1fr
            }

            .vs {
                margin: 4px 0
            }

            .match-card {
                padding: 20px
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top">
            <h1>🏆 Live Scoreboard</h1>
            <div>
                <a class="back" href="{{ route('matches.index') }}">Matches</a>
                <span style="opacity:.4;margin:0 8px">|</span>
                <a class="back" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </div>
        </div>

        @if(session('success'))
        <div class="notice">{{ session('success') }}</div> @endif
        @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div> @endif

        <form class="filter" method="GET">
            <select name="category_id">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->tournament?->title }} — {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button class="btn" type="submit">Filter</button>
        </form>

        @forelse($matches as $match)
            <article class="match-card">
                <div class="match-header">
                    <div>
                        {{ $match->category?->name }} · {{ strtoupper($match->round) }} · {{ $match->court ?: 'Court TBD' }}
                        @if($match->match_date)
                            <div style="font-size:12px;color:rgba(242,236,221,.55);margin-top:6px">
                                {{ $match->match_date->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                    <span class="status {{ $match->status }}">{{ strtoupper($match->status) }}</span>
                </div>

                <form action="{{ route('scoreboard.update', $match) }}" method="POST">
                    @csrf
                    <input type="hidden" name="stay_on_match" value="{{ $match->id }}">

                    @php
                        $sets = $match->sets;
                        $currentSet = min($sets->count() + 1, 3);
                    @endphp

                    @if($sets->count())

                        <div style="margin-bottom:20px">

                            @foreach($sets as $set)

                                <div style="
                                                                                display:flex;
                                                                                justify-content:space-between;
                                                                                align-items:center;
                                                                                background:#0f2a21;
                                                                                border-radius:8px;
                                                                                padding:10px 16px;
                                                                                margin-bottom:10px;
                                                                            ">

                                    <strong style="color:#c9a766">
                                        SET {{ $set->set_number }}
                                    </strong>

                                    <span style="font-size:22px;font-weight:bold">
                                        {{ $set->score_team_a }}
                                        -
                                        {{ $set->score_team_b }}
                                    </span>

                                </div>

                            @endforeach

                        </div>
                    @endif

                    <h2 style="
                    margin-bottom:20px;
                    color:#c9a766;
                    text-align:center;
                    ">
                        SET {{ $currentSet }}
                    </h2>

                    <div class="scoreboard">
                        <div class="team">
                            <div class="team-code">{{ $match->teamA?->team_code ?? 'TBD' }}</div>
                            @forelse($match->teamA?->members ?? [] as $member)
                                <div class="player">{{ $member->player?->user?->name ?? '?' }}</div>
                            @empty
                                <div class="player">Belum ada pemain</div>
                            @endforelse

                            <div class="controls">
                                <button type="button" onclick="changeScore('a{{ $match->id }}', -1)">−</button>
                                <input id="a{{ $match->id }}" type="number" min="0" max="255" name="score_team_a"
                                    value="{{ $match->score_team_a }}">
                                <button type="button" onclick="changeScore('a{{ $match->id }}', 1)">+</button>
                            </div>
                        </div>

                        <div class="vs">VS</div>

                        <div class="team">
                            <div class="team-code">{{ $match->teamB?->team_code ?? 'TBD' }}</div>
                            @forelse($match->teamB?->members ?? [] as $member)
                                <div class="player">{{ $member->player?->user?->name ?? '?' }}</div>
                            @empty
                                <div class="player">Belum ada pemain</div>
                            @endforelse

                            <div class="controls">
                                <button type="button" onclick="changeScore('b{{ $match->id }}', -1)">−</button>
                                <input id="b{{ $match->id }}" type="number" min="0" max="255" name="score_team_b"
                                    value="{{ $match->score_team_b }}">
                                <button type="button" onclick="changeScore('b{{ $match->id }}', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="save">
                        <button class="btn" type="submit">💾 Save Set</button>
                        @if(false)
                            <button class="btn finish" type="submit" name="finish" value="1"
                                onclick="return confirm('Selesaikan pertandingan dan update bracket?')">
                                ✅ {{ $match->status === 'finished' ? 'Update Finished Match' : 'Finish Match' }}
                            </button>
                        @endif
                    </div>
                </form>
            </article>
        @empty
            <div class="empty">Belum ada pertandingan. Buat match melalui menu Matches.</div>
        @endforelse
    </div>

    <script>
        function changeScore(id, delta) {
            const input = document.getElementById(id);
            const current = Number.parseInt(input.value || '0', 10);
            input.value = Math.max(0, current + delta);
        }
    </script>
</body>

</html>