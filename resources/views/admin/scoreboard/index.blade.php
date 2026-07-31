<!DOCTYPE html>
<html>

<head>
    <title>Live Scoreboard - Platinum Padel</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #0c1e17;
            color: #f2ecdd;
            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        h1 {
            color: #c9a766;
            margin: 0;
        }

        .back {
            color: #c9a766;
            text-decoration: none;
            font-weight: 600;
        }

        .match-card {

            background: #173a2e;

            border: 1px solid rgba(201, 167, 102, .18);

            border-radius: 14px;

            padding: 28px;

            margin-bottom: 30px;
        }

        .match-header {

            display: flex;

            justify-content: space-between;

            margin-bottom: 30px;

            color: #c9a766;

            font-weight: bold;

        }

        .status {

            background: #1f8b4c;

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 13px;
        }

        .scoreboard {

            display: grid;

            grid-template-columns: 1fr 250px 1fr;

            align-items: center;

            gap: 20px;
        }

        .team {

            text-align: center;
        }

        .team h2 {

            margin-bottom: 25px;

            font-size: 28px;
        }

        .controls {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 15px;
        }

        .controls button {

            width: 45px;

            height: 45px;

            border: none;

            border-radius: 10px;

            background: #c9a766;

            cursor: pointer;

            font-size: 24px;

            font-weight: bold;
        }

        .controls input {

            width: 70px;

            height: 60px;

            text-align: center;

            font-size: 28px;

            border: none;

            border-radius: 10px;

            background: white;
        }

        .middle {

            text-align: center;
        }

        .vs {

            font-size: 32px;

            font-weight: bold;

            color: #c9a766;
        }

        .save {

            margin-top: 30px;

            text-align: center;
        }

        .save button {

            background: #c9a766;

            color: #0c1e17;

            border: none;

            padding: 14px 30px;

            border-radius: 10px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;
        }

        .empty {

            background: #173a2e;

            padding: 25px;

            border-radius: 12px;

            text-align: center;
        }
    </style>

</head>

<body>

    <div class="container">
        @if(session('success'))

            <div style="
                        background:#1f8b4c;
                        padding:15px;
                        margin-bottom:20px;
                        border-radius:10px;
                        ">

                {{ session('success') }}

            </div>

        @endif
        <div class="top">

            <h1>🏆 Live Scoreboard</h1>

            <a href="{{ route('admin.dashboard') }}" class="back">
                ← Dashboard
            </a>

        </div>

        @forelse($matches as $match)

                <div class="match-card">

                    <div class="match-header">

                        <div>

                            Court {{ $match->court }}

                            |

                            {{ ucfirst($match->round) }}

                        </div>

                        <div class="status">

                            {{ strtoupper($match->status) }}

                        </div>

                    </div>

                    <form action="{{ route('scoreboard.update', $match->id) }}" method="POST">

                        @csrf

                        <div class="scoreboard">

                            <div class="team">

                                <div class="team">

                                    <h2>{{ $match->teamA->team_code }}</h2>

                                    @foreach($match->teamA->members as $member)

                                        <div style="margin:8px 0;font-size:18px;font-weight:bold;">

                                            {{ $member->player->user->name }}

                                        </div>

                                    @endforeach

                                </div>

                                <div class="controls">

                                    <button type="button" onclick="minus('a{{ $match->id }}')">-</button>

                                    <input id="a{{ $match->id }}" type="number" min="0" name="score_team_a"
                                        value="{{ $match->score_team_a }}">

                                    <button type="button" onclick="plus('a{{ $match->id }}')">+</button>

                                </div>

                            </div>

                            <div class="middle">

                                <div class="vs">
                                    VS
                                </div>

                            </div>

                            <div class="team">

                                <div class="team">

                                    <h2>{{ $match->teamB->team_code }}</h2>

                                    @foreach($match->teamB->members as $member)

                                        <div style="margin:8px 0;font-size:18px;font-weight:bold;">

                                            {{ $member->player->user->name }}

                                        </div>

                                    @endforeach

                                </div>

                                <div class="controls">

                                    <button type="button" onclick="minus('b{{ $match->id }}')">-</button>

                                    <input id="b{{ $match->id }}" type="number" min="0" name="score_team_b"
                                        value="{{ $match->score_team_b }}">

                                    <button type="button" onclick="plus('b{{ $match->id }}')">+</button>

                                </div>

                            </div>

                        </div>

                        <div class="save">

                            <button>

                                💾 Save Score

                            </button>

                            <button type="submit" name="finish" value="1" style="
                margin-top:10px;
                background:#1f8b4c;
                color:white;
                border:none;
                padding:14px 30px;
                border-radius:10px;
                cursor:pointer;
            ">
                                ✅ Finish Match
                            </button>
                        </div>

                    </form>

                </div>

        @empty

            <div class="empty">

                Belum ada pertandingan.

            </div>

        @endforelse

    </div>

    <script>

        function plus(id) {

            let x = document.getElementById(id);

            x.value = parseInt(x.value) + 1;

        }

        function minus(id) {

            let x = document.getElementById(id);

            if (parseInt(x.value) > 0) {

                x.value = parseInt(x.value) - 1;

            }

        }

    </script>

</body>

</html>