<!DOCTYPE html>
<html>
<head>
    <title>Create Tournament</title>

    <style>

        body{
            font-family:Arial;
            background:#0c1e17;
            color:white;
            margin:40px;
        }

        input,textarea,select{
            width:400px;
            padding:10px;
            margin-bottom:15px;
            display:block;
            border-radius:8px;
        }

        button{
            padding:10px 20px;
            background:#c9a766;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }

        a{
            color:white;
        }

    </style>

</head>

<body>

<h2>Create Tournament</h2>

<form action="{{ route('tournaments.store') }}" method="POST">

    @csrf

    <input type="text" name="name" placeholder="Tournament Name">

    <input type="text" name="badge" placeholder="Badge">

    <label>Start Date</label>
    <input type="datetime-local" name="start_date">

    <label>End Date</label>
    <input type="datetime-local" name="end_date">

    <input type="text" name="venue" placeholder="Venue">

    <input type="text" name="venue_sub" placeholder="Venue Detail">

    <input type="text" name="location" placeholder="Location">

    <input type="text" name="prize" placeholder="Prize">

    <select name="status">
        <option value="Open">Open</option>
        <option value="Running">Running</option>
        <option value="Finished">Finished</option>
    </select>

    <input type="text" name="poster" placeholder="Poster URL">

    <textarea
        name="tags"
        placeholder="Beginner,Padel,2026"></textarea>

    <button>Create Tournament</button>

</form>

<br>

<a href="{{ route('tournaments.index') }}">
Back
</a>

</body>
</html>