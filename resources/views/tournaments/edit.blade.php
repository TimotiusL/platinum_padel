<!DOCTYPE html>
<html>

<head>
    <title>Edit Tournament</title>

    <style>
        body {
            font-family: Arial;
            background: #0c1e17;
            color: white;
            margin: 40px;
        }

        input,
        textarea,
        select {
            width: 400px;
            padding: 10px;
            margin-bottom: 15px;
            display: block;
            border-radius: 8px;
        }

        button {
            padding: 10px 20px;
            background: #c9a766;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        a {
            color: white;
        }
    </style>
</head>

<body>

    <h2>Edit Tournament</h2>

    <form action="{{ route('tournaments.update', $tournament->id) }}" method="POST">

        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ $tournament->title }}">

        <textarea name="description">{{ $tournament->description }}</textarea>

        <input type="text" name="poster" value="{{ $tournament->poster }}">

        <input type="text" name="venue" value="{{ $tournament->venue }}">

        <input type="text" name="location" value="{{ $tournament->location }}">

        <label>Registration Deadline</label>
        <input type="date" name="registration_deadline" value="{{ $tournament->registration_deadline }}">

        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ $tournament->start_date }}">

        <label>End Date</label>
        <input type="date" name="end_date" value="{{ $tournament->end_date }}">

        <select name="status">
            <option value="upcoming" {{ $tournament->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
            <option value="ongoing" {{ $tournament->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="finished" {{ $tournament->status == 'finished' ? 'selected' : '' }}>Finished</option>
        </select>

        <input type="number" step="0.01" name="prize_pool" value="{{ $tournament->prize_pool }}">

        <button type="submit">
            Update Tournament
        </button>

    </form>

    <br>

    <a href="{{ route('tournaments.index') }}">
        Back
    </a>

</body>

</html>