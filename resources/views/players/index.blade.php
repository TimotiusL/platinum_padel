<!DOCTYPE html>
<html>
<head>
    <title>Players</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#0d2318;
            color:white;
            margin:40px;
        }

        h2{
            margin-bottom:20px;
        }

        .btn{
            display:inline-block;
            background:#d6b46a;
            color:black;
            padding:10px 18px;
            text-decoration:none;
            border-radius:8px;
            margin-bottom:20px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#1d3a2d;
            border-radius:10px;
            overflow:hidden;
        }

        th{
            background:#d6b46a;
            color:black;
            padding:12px;
        }

        td{
            padding:12px;
            border-bottom:1px solid #355646;
        }

        tr:hover{
            background:#264737;
        }

        .action a{
            margin-right:10px;
            text-decoration:none;
        }

        .edit{
            color:#4da3ff;
        }

        .delete{
            color:#ff6b6b;
        }
    </style>
</head>

<body>

<h2>Players</h2>

<a href="{{ route('players.create') }}" class="btn">
    + Add Player
</a>

<table>

    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Ranking</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    @foreach($players as $player)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>{{ $player->user->name }}</td>

        <td>{{ $player->phone }}</td>

        <td>{{ $player->gender }}</td>

        <td>{{ $player->ranking_point }}</td>

        <td class="action">

            <a href="{{ route('players.edit',$player->id) }}" class="edit">
                Edit
            </a>

            <form action="{{ route('players.destroy', $player->id) }}"
      method="POST"
      style="display:inline;">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        onclick="return confirm('Yakin ingin menghapus player ini?')"
        style="background:none;border:none;color:red;cursor:pointer;">

        Delete

    </button>

</form>

        </td>

    </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>