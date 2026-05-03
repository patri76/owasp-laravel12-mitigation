<!DOCTYPE html>
<html>
<head>
    <title>Reset password</title>
</head>
<body>

<h1>Reset password</h1>

@if (session('status'))
    <div style="color:green;">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email') }}" required autofocus>

    @error('email')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <br><br>

    <button type="submit">Invia link reset password</button>
</form>

</body>
</html>
