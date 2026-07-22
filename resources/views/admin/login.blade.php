<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Platinum Padel</title>
    <style>
        body {
            background: #0c1e17;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .login-box {
            background: #173a2e;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid rgba(201,167,102,0.22);
            width: 340px;
        }
        h1 { color: #c9a766; font-size: 24px; margin-bottom: 30px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            background: #0c1e17;
            border: 1px solid rgba(201,167,102,0.22);
            color: #f2ecdd;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #c9a766;
            color: #0c1e17;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        .error { color: #c97b6b; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🔐 Admin Login</h1>
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="/admin/login">
            @csrf
            <input type="password" name="password" placeholder="Masukkan password admin" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>