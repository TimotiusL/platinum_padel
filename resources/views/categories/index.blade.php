<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{background:#0c1e17;color:#f2ecdd;font-family:Inter,sans-serif;margin:0;padding:24px}
        .container{max-width:1200px;margin:auto}
        .top{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}
        h1{color:#c9a766;margin:0}
        a{color:inherit;text-decoration:none}
        .back,.btn{display:inline-block;border:0;border-radius:8px;padding:10px 14px;font-weight:700;cursor:pointer}
        .back{color:#c9a766}
        .btn{background:#c9a766;color:#0c1e17}
        .btn.secondary{background:#244c3e;color:#f2ecdd}
        .btn.green{background:#1f8b4c;color:white}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:18px}
        .card{background:#173a2e;border:1px solid rgba(201,167,102,.24);border-radius:14px;padding:20px}
        .meta{font-size:13px;color:rgba(242,236,221,.62);margin:7px 0 18px}
        .count{font-size:30px;font-weight:700;margin:8px 0}
        .actions{display:flex;gap:8px;flex-wrap:wrap}
        form{display:inline}
        .notice{padding:14px 16px;border-radius:10px;margin-bottom:18px}
        .success{background:#1f8b4c}
        .error{background:#963f3f}
        @media(max-width:640px){body{padding:16px}.top{align-items:flex-start;flex-direction:column}}
    </style>
</head>
<body>
<div class="container">
    <div class="top">
        <h1>Categories & Match Generator</h1>
        <a class="back" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>

    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
    @endif

    <div class="grid">
        @foreach($categories as $category)
            <article class="card">
                <h2 style="margin:0;color:#e6cf9c">{{ $category->name }}</h2>
                <div class="meta">{{ $category->tournament?->title ?? 'Tournament' }} · {{ ucfirst($category->gender) }}</div>
                <div class="count">{{ $category->teams_count }}</div>
                <div style="color:rgba(242,236,221,.72);margin-bottom:18px">Teams</div>

                <div class="actions">
                    <a class="btn secondary" href="{{ route('matches.index', ['category_id' => $category->id]) }}">View Matches</a>
                    <a class="btn" href="{{ route('matches.create', ['category_id' => $category->id]) }}">+ Add Match</a>

                    <form action="{{ route('matches.generate', $category) }}" method="POST"
                          onsubmit="return confirm('Generate ulang semua group match kategori ini?')">
                        @csrf
                        <button class="btn green" type="submit">Generate Group</button>
                    </form>

                    <form action="{{ route('matches.generate-bracket', $category) }}" method="POST"
                          onsubmit="return confirm('Generate ulang bracket dari ranking kategori ini?')">
                        @csrf
                        <button class="btn green" type="submit">Generate Bracket</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
</div>
</body>
</html>
