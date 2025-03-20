<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #f8f9fa; /* Optional fallback color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            transition: all 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 0px;
            text-align: center;
        }
        .card-body {
            padding: 30px 20px;
        }
        .logo {
            width: 300px;
            height:150px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert-danger {
            margin-bottom: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            padding: 10px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            box-shadow: none;
            border: 2px solid #ccc;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: black;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-header">
            <img src="{{ asset('img/logo.jpg') }}" alt="Mobile Shop Logo" class="logo">
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>

        <div class="footer-text">
        <p>&copy; 2025 D-Mart. Powered by <a href="https://ceylongit.online/" target="_blank">CeylonGIT</a></p>

    </div>
    </div>

   
</body>
</html>
