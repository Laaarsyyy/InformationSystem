<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">
    <title>Neverlonely</title>
</head>
<body>

    <img class="neverlogo" src="/Neverlonely/Assets/NEVER LONELY DISTRESSED LOGO black.png" alt="">
    
    <div class="wrapper">

        <div class="form-box login">
            <h2>Login</h2>
            <form action="login.php" method="POST">

                <div class="input-box">
                    <span class="icon"><IoMailSharp /></span>
                    <input type="text" name="username" required />
                    <label>Username</label>
                </div>

                <div class="input-box">
                    <span class="icon"><FaLock /></span>
                    <input type="password" name="password" required/>
                    <label>Password</label>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox" />Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit" class='loginBtn'>Login</button>
            </form>
        </div>
    </div>

</body>
</html>