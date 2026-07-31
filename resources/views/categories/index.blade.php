<!DOCTYPE html>
<html>

<head>

    <title>Categories</title>

    <style>
        body {
            background: #0c1e17;
            color: white;
            font-family: Arial;
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 12px;
        }

        th {
            background: #c9a766;
            color: black;
        }

        .btn {
            background: #c9a766;
            color: black;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <h2>Categories</h2>

    <table>

        <tr>

            <th>Name</th>
            <th>Teams</th>
            <th>Action</th>

        </tr>

        @foreach($categories as $category)

            <tr>

                <td>{{ $category->name }}</td>

                <td>{{ $category->teams_count }}</td>

                <td>

                    <form action="{{ route('matches.generate', $category->id) }}" method="POST" style="display:inline;">

                        @csrf

                        <button class="btn">
                            🎯 Generate
                        </button>

                    </form>

                    <a class="btn" href="{{ route('matches.index', $category->id) }}" style="margin-left:10px;">
                        📋 Matches
                    </a>

                </td>

            </tr>

        @endforeach

    </table>

</body>

</html>