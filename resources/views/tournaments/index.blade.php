<!DOCTYPE html>
<html>
<head>

<title>Tournaments</title>

<style>

body{
    background:#0c1e17;
    color:white;
    font-family:Arial;
    margin:40px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #555;
    padding:12px;
}

th{
    background:#c9a766;
    color:black;
}

.btn{
    background:#c9a766;
    color:black;
    padding:10px 18px;
    text-decoration:none;
    border-radius:8px;
}

</style>

</head>

<body>

<h2>Tournaments</h2>

<a class="btn"
href="{{ route('tournaments.create') }}">
+ Create Tournament
</a>

<br><br>

<table>

<tr>

<th>No</th>
<th>Name</th>
<th>Venue</th>
<th>Status</th>
<th>Start</th>
<th>Action</th>

</tr>

@foreach($tournaments as $tournament)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $tournament->name }}</td>

<td>{{ $tournament->venue }}</td>

<td>{{ $tournament->status }}</td>

<td>{{ $tournament->start_date }}</td>

<td>

<a href="{{ route('tournaments.edit',$tournament->id) }}">
Edit
</a>

</td>

</tr>

@endforeach

</table>

</body>
</html>