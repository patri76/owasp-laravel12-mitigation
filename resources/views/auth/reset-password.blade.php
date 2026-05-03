<!DOCTYPE html>
<html>
<head>
    <title>Nuova password</title>
</head>
<body>

<h1>Nuova password</h1>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>

    <br><br>

    <label>Nuova password</label><br>
    <input type="password" name="password" required>

    <br><br>

    <label>Conferma password</label><br>
    <input type="password" name="password_confirmation" required>

    @if ($errors->any())
        <div style="color:red; margin-top:15px;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <br>

    <button type="submit">Reimposta password</button>
</form>

</body>
</html>
