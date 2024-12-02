<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #ffffff;
        }
        .wrapper {
            max-width: 350px;
            min-height: 500px;
            padding: 40px 30px 30px 30px;
            background-color: #ecf0f3;
            border-radius: 15px;
            box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
        }
        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            border-radius: 50%;
            box-shadow: 0px 0px 3px #5f5f5f, 0px 0px 0px 5px #ecf0f3, 8px 8px 15px #a7aaa7, -8px -8px 15px #fff;
            overflow: hidden;
        }
        .logo img {
            width: 80%;
            height: 80%;
            object-fit: cover;
            border-radius: 50%;
        }
        .wrapper .name {
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 1.3px;
            padding-left: 10px;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-field {
            position: relative;
            padding-left: 10px;
            margin-bottom: 20px;
            border-radius: 20px;
            box-shadow: inset 8px 8px 8px #cbced1, inset -8px -8px 8px #fff;
        }
        .form-field input {
            width: 100%;
            display: block;
            border: none;
            outline: none;
            background: none;
            font-size: 1.2rem;
            color: #666;
            padding: 10px 15px 10px 40px;
        }
        .form-field .fa {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
        }
        .wrapper .btn {
            box-shadow: none;
            width: 100%;
            height: 45px;
            background-color: #050eb9;
            color: #fff;
            border-radius: 25px;
            box-shadow: 3px 3px 3px #b1b1b1, -3px -3px 3px #fff;
            letter-spacing: 1.3px;
            font-size: 1rem;
            margin-top: 20px;
        }
        .wrapper .btn:hover {
            background-color: #039BE5;
        }
        .wrapper a {
            text-decoration: none;
            font-size: 0.8rem;
            color: #0655ff;
        }
        .wrapper a:hover {
            color: #0303e5;
        }
        .alert {
            color: red;
            font-size: 0.8rem;
            text-align: center;
            margin-top: 10px;
        }
        @media(max-width: 380px) {
            .wrapper {
                margin: 30px 20px;
                padding: 40px 15px 15px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="logo">
            <img src="{{ asset('assets/img2.png') }}" alt="Twitter Logo">
        </div>
        <div class="text-center mt-4 name">PT Tolan Tiga Indonesia</div>
       
        <form class="p-3 mt-3" action="{{route('actionlogin')}}" method="POST">
            @csrf
            <div class="form-field d-flex align-items-center">
                <i class="fa fa-user"></i>
                <input type="email" name="email" id="inputEmail" placeholder="Email">
            </div>
            <div class="form-field d-flex align-items-center">
                <i class="fa fa-key"></i>
                <input type="password" name="password" id="inputPassword" placeholder="Password">
            </div>

            <button class="btn mt-3" type="submit">Login</button>
            @if(session('error'))
            <div class="alert alert-danger"> {{session('error')}}
            </div>
            @endif
        </form>
        <div class="alert" id="alertBox"></div>
        <div class="text-center fs-6">
    <a href="{{ route('register') }}">Don't have an account? Register</a>
</div>
    </div>
</body>
</html>
