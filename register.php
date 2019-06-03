<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="register.js"></script>
</head>
<body>

<h2>Sign Up</h2>

<form id="register" action='' method='post'>
    <p>
    <label for="user">E-mail</label>
    <input type="email" name="user" id="name" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p>
    <label for="pass">Password:&nbsp;&nbsp;</label>
    <input type="password" name="pass" id="name" placeholder="********" style="text-align: center">
    </p><br>

    <button type="submit" name="submit" onclick="check()">Sign Up</button><br><br>
</form>

</body>
</html>
