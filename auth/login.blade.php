<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-card .card-header {
            background-color: #00468b;
            color: #fff;
        }

        .login-card .card-body {
            padding: 2rem;
        }

        .login-card .form-group input {
            height: 45px;
            padding: 10px;
        }

        .login-card .btn-primary {
            background-color: #00468b;
            border: none;
            height: 45px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-card .btn-primary:hover {
            background-color: #00468b;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            /* Adjust this value as needed */
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-header text-center">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="logo-container">
                    <img src="{{ asset('assets/img/logo.jpeg') }}" alt="Logo">
                    <h3 class="my-2">Aplikasi Sparepart</h3>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
            <div class="card-footer text-center">
                {{-- <a href="{{ route('password.request') }}">Forgot Your Password?</a> --}}
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
