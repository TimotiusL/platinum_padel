<!DOCTYPE html>
<html>
<head>
    <title>Edit Player</title>

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

<h2>Edit Player</h2>

<form action="{{ route('players.update',$player->id) }}" method="POST">

    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $player->user->name }}">

    <input type="text" name="phone" value="{{ $player->phone }}">

    <input type="date" name="birth_date" value="{{ $player->birth_date }}">

    <select name="gender">
        <option value="Male" {{ $player->gender=='Male'?'selected':'' }}>Male</option>
        <option value="Female" {{ $player->gender=='Female'?'selected':'' }}>Female</option>
    </select>

    <input type="text" name="city" value="{{ $player->city }}">

    <button type="submit">Update</button>

</form>

<br>

<a href="{{ route('players.index') }}">Back</a>

</body>
</html>