<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <h1>KIG</h1>
        <h3>KKF INTERNATIONAL GROUP</h3>

        <h2>Login</h2>

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Username :</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required>
            </div>

            <div class="form-group">
                <label>Password :</label>
                <input
                    type="password"
                    name="password"
                    required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
        <div class="register">
            <p>Don't have an account ? <a href="{{ route('register') }}">Register now</a></p>
        </div>
    </div>
</body>

</html>