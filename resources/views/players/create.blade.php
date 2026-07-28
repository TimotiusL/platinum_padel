<!DOCTYPE html>
<html>
<head>
    <title>Add Player</title>

    <style>

        body{
            font-family:Arial;
            background:#0d2318;
            color:white;
            margin:40px;
        }

        input,select{
            width:350px;
            padding:10px;
            margin-bottom:15px;
            display:block;
            border-radius:6px;
        }

        button{
            background:#d6b46a;
            padding:10px 20px;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }

        a{
            color:white;
        }

    </style>

</head>

<body>

<h2>Add Player</h2>

<form action="{{ route('players.store') }}" method="POST">

    @csrf

    <input type="text" name="name" placeholder="Name">

    <input type="text" name="phone" placeholder="Phone">

    <input type="date" name="birth_date">

    <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

    <input type="text" name="city" placeholder="City">

    <button type="submit">
        Save
    </button>

</form>

<br>

<a href="{{ route('players.index') }}">Back</a>

</body>
</html>