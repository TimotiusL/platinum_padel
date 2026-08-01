<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Matches - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{background:#0c1e17;color:#f2ecdd;font-family:Inter,sans-serif;margin:0;padding:24px}
        .container{max-width:1400px;margin:auto}
        .top{display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:22px}
        h1{color:#c9a766;margin:0}
        a{text-decoration:none;color:inherit}
        .btn{display:inline-block;background:#c9a766;color:#0c1e17;border:0;border-radius:8px;padding:10px 14px;font-weight:700;cursor:pointer}
        .btn.secondary{background:#244c3e;color:#f2ecdd}
        .btn.red{background:#8c4242;color:white}
        .btn.green{background:#1f8b4c;color:white}
        .filters{display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:10px;background:#173a2e;padding:16px;border-radius:12px;margin-bottom:18px}
        select,input{width:100%;background:#0f2a21;color:#f2ecdd;border:1px solid rgba(201,167,102,.32);border-radius:8px;padding:10px}
        table{width:100%;border-collapse:collapse;background:#173a2e;border-radius:12px;overflow:hidden}
        th,td{padding:12px;border-bottom:1px solid rgba(201,167,102,.16);text-align:left;vertical-align:top}
        th{background:#c9a766;color:#0c1e17}
        .team{font-weight:700}
        .players{font-size:12px;color:rgba(242,236,221,.65);line-height:1.5;margin-top:4px}
        .score{font-size:22px;font-weight:700;color:#e6cf9c;white-space:nowrap}
        .badge{display:inline-block;padding:5px 9px;border-radius:999px;font-size:11px;font-weight:700;text-transform:uppercase}
        .scheduled{background:#6c603e}.ongoing{background:#1f8b4c}.finished{background:#37526d}
        .actions{display:flex;gap:6px;flex-wrap:wrap}
        .notice{padding:14px;border-radius:10px;margin-bottom:16px;background:#1f8b4c}
        .error{background:#963f3f}
        .pagination{margin-top:18px}
        @media(max-width:900px){.filters{grid-template-columns:1fr}.table-wrap{overflow-x:auto}table{min-width:1000px}}
    </style>
</head>
<body>
<div class="container">
    <div class="top">
        <h1>Match Management</h1>
        <div>
            <a class="btn secondary" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="btn secondary" href="{{ route('categories.index') }}">Categories</a>
            <a class="btn" href="{{ route('matches.create', request()->only('category_id')) }}">+ Create Match</a>
        </div>
    </div>

    @if(session('success')) <div class="notice">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notice error">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif

    <form class="filters" method="GET">
        <select name="category_id">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                    {{ $category->tournament?->title }} — {{ $category->name }}
                </option>
            @endforeach
        </select>
        <select name="round">
            <option value="">All Rounds</option>
            @foreach(['group' => 'Group', 'r16' => 'R16', 'qf' => 'Quarter Final', 'sf' => 'Semi Final', 'final' => 'Final'] as $value => $label)
                <option value="{{ $value }}" @selected(request('round') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All Status</option>
            @foreach(['scheduled','ongoing','finished'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="btn" type="submit">Filter</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Round</th>
                <th>Team A</th>
                <th>Score</th>
                <th>Team B</th>
                <th>Court / Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($matches as $match)
                <tr>
                    <td>#{{ $match->id }}</td>
                    <td>{{ $match->category?->name }}</td>
                    <td>{{ strtoupper($match->round) }}<br><small>Order {{ $match->bracket_order ?: '-' }}</small></td>
                    <td>
                        <div class="team">{{ $match->teamA?->team_code ?? '-' }}</div>
                        <div class="players">
                            @foreach($match->teamA?->members ?? [] as $member)
                                {{ $member->player?->user?->name ?? '?' }}<br>
                            @endforeach
                        </div>
                    </td>
                    <td class="score">{{ $match->score_team_a }} - {{ $match->score_team_b }}</td>
                    <td>
                        <div class="team">{{ $match->teamB?->team_code ?? '-' }}</div>
                        <div class="players">
                            @foreach($match->teamB?->members ?? [] as $member)
                                {{ $member->player?->user?->name ?? '?' }}<br>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        {{ $match->court ?: '-' }}<br>
                        <small>{{ $match->match_date?->format('d M Y H:i') ?: 'Belum dijadwalkan' }}</small>
                    </td>
                    <td><span class="badge {{ $match->status }}">{{ $match->status }}</span></td>
                    <td>
                        <div class="actions">
                            <a class="btn green" href="{{ route('scoreboard.index', ['match' => $match->id]) }}">Score</a>
                            <a class="btn secondary" href="{{ route('matches.edit', $match) }}">Edit</a>
                            <form method="POST" action="{{ route('matches.destroy', $match) }}" onsubmit="return confirm('Hapus match ini?')">
                                @csrf @method('DELETE')
                                <button class="btn red" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;padding:30px">Belum ada match.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $matches->links() }}</div>
</div>
</body>
</html>
