<?php

include "../config.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);

    $check = $conn->prepare(
        "SELECT id FROM admins WHERE email = ?"
    );

    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $showResetForm = true;

    } else {

        $message = "Email address not found.";
        $messageType = "error";

        $showResetForm = false;
    }

} else {

    $showResetForm = false;
}


if (isset($_POST["reset_password"])) {

    $email = trim($_POST["email"]);
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

        $showResetForm = true;

    } elseif (strlen($newPassword) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

        $showResetForm = true;

    } else {

        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $update = $conn->prepare(
            "UPDATE admins SET password = ? WHERE email = ?"
        );

        $update->bind_param(
            "ss",
            $hashedPassword,
            $email
        );

        if ($update->execute()) {

            $message = "Password reset successful! You can now login.";
            $messageType = "success";

            $showResetForm = false;

        } else {

            $message = "Something went wrong. Please try again.";
            $messageType = "error";

            $showResetForm = true;
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reset Password | EventHub</title>

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

        .message {

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 18px;

            text-align: center;

            font-size: 13px;
        }

        .success {

            background: #dcfce7;

            color: #166534;
        }

        .error {

            background: #fee2e2;

            color: #991b1b;
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
        Reset Password
    </h2>


    <p class="info">
        Create a new password for your admin account.
    </p>


    <?php if ($message != "") { ?>

        <div class="message <?php echo $messageType; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <?php if ($showResetForm) { ?>

        <form method="POST">

            <input
                type="hidden"
                name="email"
                value="<?php echo htmlspecialchars($email); ?>"
            >


            <label for="new_password">
                New Password
            </label>

            <input
                type="password"
                id="new_password"
                name="new_password"
                placeholder="Enter new password"
                required
            >


            <label for="confirm_password">
                Confirm Password
            </label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm new password"
                required
            >


            <button
                type="submit"
                name="reset_password"
            >
                Reset Password
            </button>

        </form>

    <?php } ?>


    <div class="back">

        <a href="login.php">
            ← Back to Login
        </a>

    </div>

</div>


</body>

</html>