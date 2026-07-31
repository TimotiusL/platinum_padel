<!DOCTYPE html>
<html>

<head>

    <title>Matches</title>

    <style>
        body {
            background: #0c1e17;
            color: white;
            font-family: Arial;
            margin: 40px;
        }

        h2 {
            color: #c9a766;
        }

        .card {

            background: #173a2e;

            border: 1px solid rgba(201, 167, 102, .25);

            border-radius: 12px;

            padding: 20px;

            margin-bottom: 20px;
        }

        .row {

            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .team {

            font-size: 22px;

            font-weight: bold;
        }

        .vs {

            font-size: 28px;

            color: #c9a766;

            margin: 20px 0;

            text-align: center;
        }

        .btn {

            background: #c9a766;

            color: black;

            padding: 10px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;
        }

        .status {

            color: #c9a766;
        }
    </style>

</head>

<body>

    <h2>

        {{ $category->name }}

    </h2>

    @foreach($matches as $match)

        <div class="card">

            <div class="row">

                <div>

                    Court {{ $match->court }}

                </div>

                <div class="status">

                    {{ strtoupper($match->status) }}

                </div>

            </div>

            <div class="vs">

                <div class="team">

                    {{ $match->teamA->team_name }}

                </div>

                <br>

                VS

                <br><br>

                <div class="team">

                    {{ $match->teamB->team_name }}

                </div>

            </div>

            <a class="btn" href="{{ route('scoreboard.index') }}">
                🎯 Live Score
            </a>

        </div>

    @endforeach

</body>

</html>