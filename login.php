<?php

session_start();

include "../config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, email, password FROM admins WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin["password"])) {

            $_SESSION["admin_logged_in"] = true;
            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_email"] = $admin["email"];

            header("Location: index.php");
            exit();

        } else {

            $error = "Invalid email or password.";

        }

    } else {

        $error = "Invalid email or password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | EventHub</title>

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

        .login-container {

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

        .login-title {

            text-align: center;

            margin-bottom: 30px;
        }

        .login-title h2 {

            color: #111827;

            font-size: 25px;

            margin-bottom: 7px;
        }

        .login-title p {

            color: #6b7280;

            font-size: 13px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {

            display: block;

            margin-bottom: 7px;

            color: #374151;

            font-size: 14px;

            font-weight: 600;
        }

        .form-group input {

            width: 100%;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 9px;

            background: #f9fafb;

            font-size: 14px;

            outline: none;

            transition: 0.3s;
        }

        .form-group input:focus {

            border-color: #6366f1;

            background: white;

            box-shadow:
                0 0 0 3px
                rgba(99,102,241,0.12);
        }

        .login-btn {

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

            transition: 0.3s;
        }

        .login-btn:hover {

            transform: translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(91,75,219,0.30);
        }

        .error {

            background: #fee2e2;

            color: #b91c1c;

            padding: 10px;

            border-radius: 8px;

            font-size: 13px;

            text-align: center;

            margin-bottom: 18px;
        }

        .forgot {

            text-align: right;

            margin-top: -8px;

            margin-bottom: 20px;
        }

        .forgot a {

            color: #6366f1;

            text-decoration: none;

            font-size: 13px;
        }

        .forgot a:hover {
            text-decoration: underline;
        }

        .back-link {

            text-align: center;

            margin-top: 20px;
        }

        .back-link a {

            color: #6366f1;

            text-decoration: none;

            font-size: 13px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>


<div class="login-container">


    <div class="logo">
        Event<span>Hub</span>
    </div>


    <div class="login-title">

    </div>


    <?php if ($error != "") { ?>

        <div class="error">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php } ?>


    <form method="POST">


        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter admin email"
                required
            >

        </div>


        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter admin password"
                required
            >

        </div>


        <div class="forgot">

            <a href="forgot_password.php">
                Forgot Password?
            </a>

        </div>


        <button
            type="submit"
            class="login-btn"
        >
            Login 
        </button>


    </form>


    <div class="back-link">

        <a href="../index.php">
            ← Back to Website
        </a>

    </div>


</div>


</body>

</html>