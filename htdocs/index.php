<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background-color: #ffffff;
    }

    .left-half {
      width: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-form {
      transform: scale(1.3); 
      transform-origin: center;
      width: 100%;
      max-width: 400px;
    }

    h1 {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    p {
      font-size: 14px;
      margin-bottom: 30px;
      color: #555;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #1E1E3F;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #1E1E3F;
    }

    .signup {
      margin-top: 15px;
      font-size: 14px;
    }

    .signup a {
      color: #1E1E3F;
      text-decoration: none;
      font-weight: bold;
    }

    .right-half {
      width: 50%;
      
    }
  </style>
</head>
<body>
  <div class="left-half">
    <div class="login-form">
      <h1>WELCOME BACK!</h1>
      <p>Please enter your details.</p>
      <form action="login.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="******" required>

        <button type="submit">Sign in</button>
      </form>
      <div class="signup">
        Don’t have an account? <a href="signUp.html">Sign up for free!</a>
      </div>
    </div>
  </div>

  <div class="right-half">
        <img src="logo.png" alt="Login Illustration" style="width: 100%; height: 100%; object-fit: cover;">
  </div>
</body>
</html>