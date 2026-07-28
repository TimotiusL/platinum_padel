<!DOCTYPE html>
<html>

<head>
    <title>Create Tournament</title>

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

    <h2>Create Tournament</h2>

    <form action="{{ route('tournaments.store') }}" method="POST">

        @csrf

        <input type="text" name="title" placeholder="Tournament Title" required>

        <textarea name="description" placeholder="Description" rows="4"></textarea>

        <input type="text" name="poster" placeholder="Poster URL">

        <input type="text" name="venue" placeholder="Venue">

        <input type="text" name="location" placeholder="Location">

        <label>Registration Deadline</label>
        <input type="date" name="registration_deadline" required>

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <label>Status</label>
        <select name="status">
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="finished">Finished</option>
        </select>

        <input type="number" name="prize_pool" placeholder="Prize Pool" step="0.01" min="0">

        <button type="submit">
            Create Tournament
        </button>

    </form>

    <br>

    <a href="{{ route('tournaments.index') }}">
        Back
    </a>

</body>

</html>