<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input {
            margin: 5px;
            padding: 8px;
            width: 200px;
        }
        button {
            margin: 5px;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="index.php">
            <input type="text" name="id" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($loginError)) { echo "<p style='color:red;'>$loginError</p>"; } ?>
    </div>
</body>
</html>
