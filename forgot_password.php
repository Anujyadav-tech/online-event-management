<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | EventHub</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #111827,
                    #4f46e5
                );

            padding: 20px;

        }

        .box {

            width: 100%;

            max-width: 420px;

            background: white;

            padding: 40px;

            border-radius: 20px;

            box-shadow:
                0 20px 50px rgba(0,0,0,0.25);

        }

        .logo {

            text-align: center;

            font-size: 30px;

            font-weight: bold;

            color: #5b4bdb;

            margin-bottom: 8px;

        }

        .logo span {
            color: #ff6b6b;
        }

        h2 {

            text-align: center;

            color: #111827;

            margin-bottom: 10px;

        }

        .info {

            text-align: center;

            color: #6b7280;

            font-size: 13px;

            line-height: 1.5;

            margin-bottom: 25px;

        }

        label {

            display: block;

            color: #374151;

            font-size: 14px;

            font-weight: 600;

            margin-bottom: 7px;

        }

        input {

            width: 100%;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 9px;

            background: #f9fafb;

            font-size: 14px;

            outline: none;

            margin-bottom: 18px;

        }

        input:focus {

            border-color: #6366f1;

            background: white;

        }

        button {

            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 9px;

            background:
                linear-gradient(
                    135deg,
                    #5b4bdb,
                    #7665ed
                );

            color: white;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

        }

        .back {

            text-align: center;

            margin-top: 20px;

        }

        .back a {

            color: #6366f1;

            text-decoration: none;

            font-size: 13px;

        }

    </style>

</head>

<body>


<div class="box">

    <div class="logo">
        Event<span>Hub</span>
    </div>

    <h2>
        Forgot Password?
    </h2>

    <p class="info">
        Enter your registered admin email address
        to reset your password.
    </p>


    <form action="reset_password.php" method="POST">

        <label for="email">
            Admin Email Address
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <button type="submit">
            Continue
        </button>

    </form>


    <div class="back">

        <a href="login.php">
            ← Back to Login
        </a>

    </div>

</div>


</body>

</html>