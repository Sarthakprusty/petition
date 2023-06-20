{{--<!DOCTYPE html>--}}
{{--<html lang="en" >--}}
{{--<head>--}}
{{--  <meta charset="UTF-8">--}}
{{--  <title>log.in</title>--}}
{{--  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Rubik:400,700'>--}}
{{--<style>--}}
{{--  * {--}}
{{--  margin: 0;--}}
{{--  padding: 0;--}}
{{--  box-sizing: border-box;--}}
{{--  -webkit-font-smoothing: antialiased;--}}
{{--}--}}

{{--body {--}}
{{--  background: #Dbcebb;--}}
{{--  font-family: 'Rubik', sans-serif;--}}
{{--}--}}

{{--.login-form {--}}
{{--  background: #fff;--}}
{{--  width: 500px;--}}
{{--  margin: 65px auto;--}}
{{--  display: -webkit-box;--}}
{{--  display: flex;--}}
{{--  -webkit-box-orient: vertical;--}}
{{--  -webkit-box-direction: normal;--}}
{{--          flex-direction: column;--}}
{{--  border-radius: 4px;--}}
{{--  box-shadow: 0 2px 25px rgba(0, 0, 0, 0.2);--}}
{{--}--}}
{{--.login-form h1 {--}}
{{--  padding: 35px 35px 0 35px;--}}
{{--  font-weight: 300;--}}
{{--}--}}
{{--.login-form .content {--}}
{{--  padding: 35px;--}}
{{--  text-align: center;--}}
{{--}--}}
{{--.login-form .input-field {--}}
{{--  padding: 12px 5px;--}}
{{--}--}}
{{--.login-form .input-field input {--}}
{{--  font-size: 16px;--}}
{{--  display: block;--}}
{{--  font-family: 'Rubik', sans-serif;--}}
{{--  width: 100%;--}}
{{--  padding: 10px 1px;--}}
{{--  border: 0;--}}
{{--  border-bottom: 1px solid #747474;--}}
{{--  outline: none;--}}
{{--  -webkit-transition: all .2s;--}}
{{--  transition: all .2s;--}}
{{--}--}}
{{--.login-form .input-field input::-webkit-input-placeholder {--}}
{{--  text-transform: uppercase;--}}
{{--}--}}
{{--.login-form .input-field input::-moz-placeholder {--}}
{{--  text-transform: uppercase;--}}
{{--}--}}
{{--.login-form .input-field input:-ms-input-placeholder {--}}
{{--  text-transform: uppercase;--}}
{{--}--}}
{{--.login-form .input-field input::-ms-input-placeholder {--}}
{{--  text-transform: uppercase;--}}
{{--}--}}
{{--.login-form .input-field input::placeholder {--}}
{{--  text-transform: uppercase;--}}
{{--}--}}
{{--.login-form .input-field input:focus {--}}
{{--  border-color: #222;--}}
{{--}--}}
{{--.login-form a.link {--}}
{{--  text-decoration: none;--}}
{{--  color: #747474;--}}
{{--  letter-spacing: 0.2px;--}}
{{--  text-transform: uppercase;--}}
{{--  display: inline-block;--}}
{{--  margin-top: 20px;--}}
{{--}--}}
{{--.login-form .action {--}}
{{--  display: -webkit-box;--}}
{{--  display: flex;--}}
{{--  -webkit-box-orient: horizontal;--}}
{{--  -webkit-box-direction: normal;--}}
{{--          flex-direction: row;--}}
{{--}--}}
{{--.login-form .action button {--}}
{{--  width: 100%;--}}
{{--  border: none;--}}
{{--  padding: 18px;--}}
{{--  font-family: 'Rubik', sans-serif;--}}
{{--  cursor: pointer;--}}
{{--  text-transform: uppercase;--}}
{{--  background: #e8e9ec;--}}
{{--  color: #777;--}}
{{--  border-bottom-left-radius: 4px;--}}
{{--  border-bottom-right-radius: 0;--}}
{{--  letter-spacing: 0.2px;--}}
{{--  outline: 0;--}}
{{--  -webkit-transition: all .3s;--}}
{{--  transition: all .3s;--}}
{{--}--}}
{{--.login-form .action button:hover {--}}
{{--  background: #d8d8d8;--}}
{{--}--}}
{{--.login-form .action button:nth-child(2) {--}}
{{--  background: #2d3b55;--}}
{{--  color: #fff;--}}
{{--  border-bottom-left-radius: 0;--}}
{{--  border-bottom-right-radius: 4px;--}}
{{--}--}}
{{--.login-form .action button:nth-child(2):hover {--}}
{{--  background: #3c4d6d;--}}
{{--}--}}
{{--</style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<!-- partial:index.partial.html -->--}}
{{--<div class="login-form">--}}
{{--  <form>--}}
{{--    <h1>Petition</h1>--}}
{{--    <div class="content">--}}
{{--      <div class="input-field">--}}
{{--        <input type="email" placeholder="Email" autocomplete="nope">--}}
{{--      </div>--}}
{{--      <div class="input-field">--}}
{{--        <input type="password" placeholder="Password" autocomplete="new-password">--}}
{{--      </div>--}}
{{--      <div class="row">--}}
{{--      <a href="#" class="link">Forgot Your Password?</a>--}}
{{--      </div>--}}
{{--      <div class="row">--}}
{{--      devloped by nic--}}
{{--    </div>--}}
{{--    </div>--}}
{{--    <div class="action">--}}
{{--      <button>Register</button>--}}
{{--      <button>Sign in</button>--}}
{{--    </div>--}}
{{--  </form>--}}
{{--</div>--}}
{{--<!-- partial -->--}}
{{--<script>--}}
{{--  let form = document.querySelecter('form');--}}

{{--form.addEventListener('submit', (e) => {--}}
{{--  e.preventDefault();--}}
{{--  return false;--}}
{{--});--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HTML5 Login Form with validation Example</title>

    <style>
        body {
            background-color: #dbcebb;
            font-size: 1.6rem;
            font-family: "Open Sans", sans-serif;
            color: #2b3e51;
        }

        h2 {
            font-weight: 300;
            text-align: center;
        }

        p {
            position: relative;
        }

        a,
        a:link,
        a:visited,
        a:active {
            color: #e9;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }
        a:focus, a:hover,
        a:link:focus,
        a:link:hover,
        a:visited:focus,
        a:visited:hover,
        a:active:focus,
        a:active:hover {
            color: #329dd5;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        #login-form-wrap {
            background-color: #fff;
            width: 35%;
            margin: 30px auto;
            text-align: center;
            padding: 20px 0 0 0;
            border-radius: 4px;
            box-shadow: 0px 30px 50px 0px rgba(0, 0, 0, 0.2);
        }

        #login-form {
            padding: 0 60px;
        }

        input {
            display: block;
            box-sizing: border-box;
            width: 100%;
            outline: none;
            height: 60px;
            line-height: 60px;
            border-radius: 4px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0 0 0 10px;
            margin: 0;
            color: #8a8b8e;
            border: 1px solid #c2c0ca;
            font-style: normal;
            font-size: 16px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            position: relative;
            display: inline-block;
            background: none;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3ca9e2;
        }
        input[type="text"]:focus:invalid,
        input[type="password"]:focus:invalid {
            color: #cc1e2b;
            border-color: #cc1e2b;
        }
        input[type="text"]:valid ~ .validation,
        input[type="password"]:valid ~ .validation {
            display: block;
            border-color: #0C0;
        }
        input[type="text"]:valid ~ .validation span,
        input[type="password"]:valid ~ .validation span {
            background: #0C0;
            position: absolute;
            border-radius: 6px;
        }
        input[type="text"]:valid ~ .validation span:first-child,
        input[type="password"]:valid ~ .validation span:first-child {
            top: 30px;
            left: 14px;
            width: 20px;
            height: 3px;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }
        input[type="text"]:valid ~ .validation span:last-child,
        input[type="password"]:valid ~ .validation span:last-child {
            top: 35px;
            left: 8px;
            width: 11px;
            height: 3px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .validation {
            display: none;
            position: absolute;
            content: " ";
            height: 60px;
            width: 30px;
            right: 15px;
            top: 0px;
        }

        input[type="submit"] {
            border: none;
            display: block;
            background-color: #329dd5;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
            font-size: 18px;
            position: relative;
            display: inline-block;
            cursor: pointer;
            text-align: center;
        }
        input[type="submit"]:hover {
            background-color: deepskyblue;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        #create-account-wrap {
            background-color: #9E643E ;
            color: #eeedf1;
            font-size: 14px;
            width: 100%;
            padding: 10px 0;
            border-radius: 0 0 4px 4px;
        }

        @keyframes vibrate {
            0% { transform: translateX(-20px); }
            50% { transform: translateX(20px); }
            100% { transform: translateX(-20px); }
        }

    </style>
</head>
<body>
<div id="login-form-wrap">
    <style>
        h2 {
            font-family: "Times New Roman", Times, serif;
        }
    </style>
    <h2>Petition</h2>
    <form id="login-form" action="{{ route('login') }}" method="POST">
        @csrf
        <p>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <i class="validation"><span></span><span></span></i>
        </p>
        <p>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        </p>
        <div class="row">&nbsp;</div>
        <p style="padding-bottom: 5px;padding-top: 5px;">
            <input type="submit" id="login" value="Login">
        </p>
    </form>
    <div id="create-account-wrap">
{{--        <p>Not a member? <a href="#">Create Account</a></p>--}}
        <p>Developed by NIC</p>
    </div>
</div>

{{--<script>--}}
{{--    var passwordInput = document.getElementById("password");--}}
{{--    var togglePassword = document.getElementById("togglePassword");--}}
{{--    var loginForm = document.getElementById("login-form");--}}

{{--    togglePassword.addEventListener("click", function() {--}}
{{--        if (passwordInput.type === "password") {--}}
{{--            passwordInput.type = "text";--}}
{{--            togglePassword.classList.remove("fa-eye");--}}
{{--            togglePassword.classList.add("fa-eye-slash");--}}
{{--        } else {--}}
{{--            passwordInput.type = "password";--}}
{{--            togglePassword.classList.remove("fa-eye-slash");--}}
{{--            togglePassword.classList.add("fa-eye");--}}
{{--        }--}}
{{--    });--}}

{{--    loginForm.addEventListener("submit", function(e) {--}}
{{--        var enteredPassword = passwordInput.value;--}}
{{--        var correctPassword = "your-correct-password"; // Replace with the actual correct password--}}

{{--        if (enteredPassword.trim() === "" || enteredPassword !== correctPassword) {--}}
{{--            e.preventDefault(); // Prevent form submission--}}

{{--            passwordInput.style.animation = "vibrate 150ms"; // Apply the vibration animation for 150 milliseconds--}}

{{--            // Clear the animation after 150 milliseconds--}}
{{--            setTimeout(function() {--}}
{{--                passwordInput.style.animation = "";--}}
{{--            }, 150);--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
</body>
</html>
