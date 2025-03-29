<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Register - Inventory Management</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <div class="register-container">
        <h1>KIG</h1>
        <h3>KKF INTERNATIONAL GROUP</h3>

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            <h2 class="register-title">Register</h2>

            <div class="form-group">
                <label for="name">Name :</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="username">Username :</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password :</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>

        <div class="login">
            <p>Have an account? <a href="{{ route('login') }}">Login now</a></p>
        </div>
    </div>
</body>

</html>