<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match - Platinum Padel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{background:#0c1e17;color:#f2ecdd;font-family:Inter,sans-serif;margin:0;padding:24px}
        .container{max-width:900px;margin:auto}
        h1{color:#c9a766}
        form{background:#173a2e;border:1px solid rgba(201,167,102,.24);border-radius:14px;padding:24px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        label{display:block;font-size:13px;color:#e6cf9c;font-weight:700}
        input,select{width:100%;margin-top:7px;padding:12px;border-radius:8px;border:1px solid rgba(201,167,102,.32);background:#0f2a21;color:#f2ecdd}
        .actions{display:flex;gap:10px;margin-top:22px}
        .btn{display:inline-block;border:0;border-radius:8px;padding:11px 16px;background:#c9a766;color:#0c1e17;text-decoration:none;font-weight:700;cursor:pointer}
        .btn.secondary{background:#244c3e;color:#f2ecdd}
        .notice{padding:14px;border-radius:10px;margin-bottom:16px}.error{background:#963f3f}
        @media(max-width:700px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Match #{{ $match->id }}</h1>
    <form action="{{ route('matches.update', $match) }}" method="POST">
        @csrf
        @method('PUT')
        @include('matches._form')
    </form>
</div>
</body>
</html>
