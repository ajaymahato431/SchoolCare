<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learners Learn</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #25252b;
        }

        .container {
            position: relative;
            width: 750px;
            height: 450px;
            border: 2px solid #ff2770;
            box-shadow: 0 0 25px #ff2770;
            overflow: hidden;
        }

        .container .form-box {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .form-box.Login {
            left: 0;
            padding: 0 40px;
        }

        .form-box.Login .animation {
            transform: translateX(0%);
            transition: .7s;
            opacity: 1;
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .form-box.Login .animation {
            transform: translateX(-120%);
            opacity: 0;
            transition-delay: calc(.1s * var(--D));
        }

        .form-box.Register {
            /* display: none; */
            right: 0;
            padding: 0 60px;
        }

        .form-box.Register .animation {
            transform: translateX(120%);
            transition: .7s ease;
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .form-box.Register .animation {
            transform: translateX(0%);
            opacity: 1;
            filter: blur(0px);
            transition-delay: calc(.1s * var(--li));
        }

        .form-box h2 {
            font-size: 32px;
            text-align: center;
        }

        .form-box .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin-top: 25px;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            transition: .5s;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-bottom: 2px solid #ff2770;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            transition: .5s;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
            color: #ff2770;
        }

        .input-box box-icon {
            position: absolute;
            top: 50%;
            right: 0;
            font-size: 18px;
            transform: translateY(-50%);
            color: #fff;
        }

        .input-box input:focus~box-icon,
        .input-box input:valid~box-icon {
            color: #ff2770;
        }

        .btn {
            position: relative;
            width: 100%;
            height: 45px;
            background: transparent;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border: 2px solid #ff2770;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: "";
            position: absolute;
            height: 300%;
            width: 100%;
            background: linear-gradient(#25252b, #ff2770, #25252b, #ff2770);
            top: -100%;
            left: 0;
            z-index: -1;
            transition: .5s;
        }

        .btn:hover:before {
            top: 0;
        }

        .regi-link {
            font-size: 14px;
            text-align: center;
            margin: 20px 0 10px;
        }

        .regi-link a {
            text-decoration: none;
            color: #ff2770;
            font-weight: 600;
        }

        .regi-link a:hover {
            text-decoration: underline;
        }

        .info-content {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .info-content.Login {
            right: 0;
            text-align: right;
            padding: 0 40px 60px 150px;
        }

        .info-content.Login .animation {
            transform: translateX(0);
            transition: .7s ease;
            transition-delay: calc(.1s * var(--S));
            opacity: 1;
            filter: blur(0px);
        }

        .container.active .info-content.Login .animation {
            transform: translateX(120%);
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--D));
        }

        .info-content.Register {
            /* display: none; */
            left: 0;
            text-align: left;
            padding: 0 150px 60px 38px;
            pointer-events: none;
        }

        .info-content.Register .animation {
            transform: translateX(-120%);
            transition: .7s ease;
            opacity: 0;
            filter: blur(10PX);
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .info-content.Register .animation {
            transform: translateX(0%);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.1s * var(--li));
        }

        .info-content h2 {
            text-transform: uppercase;
            font-size: 36px;
            line-height: 1.3;
        }

        .info-content p {
            font-size: 16px;
        }

        .container .curved-shape {
            position: absolute;
            right: 0;
            top: -5px;
            height: 600px;
            width: 850px;
            background: linear-gradient(45deg, #25252b, #ff2770);
            /*transform: rotate(10deg) skewY(40deg);*/
            transform: rotate(10deg) skewY(40deg);
            transform-origin: bottom right;
            transition: 1.5s ease;
            transition-delay: 1.6s;
        }

        .container.active .curved-shape {
            transform: rotate(0deg) skewY(0deg);
            transition-delay: .5s;
        }

        .container .curved-shape2 {
            position: absolute;
            left: 250px;
            top: 100%;
            height: 700px;
            width: 850px;
            background: #25252b;
            border-top: 3px solid #ff2770;
            transform: rotate(0deg) skewY(0deg);
            transform-origin: bottom left;
            transition: 1.5s ease;
            transition-delay: .5s;
        }

        .container.active .curved-shape2 {
            transform: rotate(-11deg) skewY(-41deg);
            transition-delay: 1.2s;
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="curved-shape"></div>
        <div class="curved-shape2"></div>
        <div class="form-box Login">
            <h2 class="animation" style="--D:0; --S:21">Register</h2>
            <form action="{{ route('teacher.store') }}" method="POST">
                @csrf

                <div class="input-box animation" style="--li:18; --S:1">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    <label for="name">Name</label>
                    <box-icon type='solid' name='user'></box-icon>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    <label for="email">Email</label>
                    <box-icon name='envelope' type='solid'></box-icon>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:19; --S:3">
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                    <label for="phone">Phone</label>
                    <box-icon name='phone' type='solid'></box-icon>
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:20; --S:4">
                    <button class="btn" type="submit">Register</button>
                </div>

                <div class="regi-link animation" style="--li:21; --S:5">
                    <p>Are you a student? <br> <a href="#" class="SignUpLink">Register as student</a></p>
                </div>
            </form>
        </div>

        <div class="info-content Login">
            <h2 class="animation" style="--D:0; --S:20">Register as a Teacher!</h2>
            {{-- <p class="animation" style="--D:1; --S:21">We are happy to have you with us again. If you need anything, we
                are here to help.</p> --}}
        </div>

        <div class="form-box Register">
            <h2 class="animation" style="--li:17; --S:0">Register</h2>
            <form action="{{ route('student.store') }}" method="POST">
                @csrf
                <div class="input-box animation" style="--li:18; --S:1">
                    <input type="text" id="name" name="name" oninput="this.setAttribute('value', this.value)"
                        required>
                    <label for="name">Name</label>
                    <box-icon type='solid' name='user'></box-icon>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input type="email" id="email" name="email" oninput="this.setAttribute('value', this.value)"
                        required>
                    <label for="email">Email</label>
                    <box-icon name='envelope' type='solid'></box-icon>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:19; --S:3">
                    <input type="text" id="phone" name="phone" oninput="this.setAttribute('value', this.value)"
                        required>
                    <label for="phone">Phone</label>
                    <box-icon name='phone' type='solid'></box-icon>
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box animation" style="--li:20; --S:4">
                    <button class="btn" type="submit">Register</button>
                </div>

                <div class="regi-link animation" style="--li:21; --S:5">
                    <p>Are you a teacher? <br> <a href="#" class="SignInLink">Register as teacher</a></p>
                </div>
            </form>
        </div>

        <div class="info-content Register">
            <h2 class="animation" style="--li:17; --S:0">Register as a Student!</h2>

        </div>

    </div>

    @if (session('success'))
        <div id="custom-alert" class="custom-alert">
            <div class="custom-alert-content">
                <p>{{ session('success') }}</p>
                <button onclick="closeAlert()">OK</button>
            </div>
        </div>

        <script>
            function closeAlert() {
                document.getElementById('custom-alert').style.display = 'none';
            }

            // Auto-close after 3 seconds
            setTimeout(() => {
                closeAlert();
            }, 3000);
        </script>

        <style>
            .custom-alert {
                position: fixed;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                background: #28a745;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                z-index: 9999;
                width: 300px;
                font-size: 16px;
            }

            .custom-alert-content button {
                background: white;
                color: #28a745;
                border: none;
                padding: 5px 10px;
                margin-top: 10px;
                cursor: pointer;
                border-radius: 3px;
            }
        </style>
    @endif

    <!-- Include SweetAlert -->
    @include('sweetalert::alert')

    <script src="index.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <script>
        const container = document.querySelector('.container');
        const LoginLink = document.querySelector('.SignInLink');
        const RegisterLink = document.querySelector('.SignUpLink');

        RegisterLink.addEventListener('click', () => {
            container.classList.add('active');
        })

        LoginLink.addEventListener('click', () => {
            container.classList.remove('active');
        })
    </script>

</body>

</html>
