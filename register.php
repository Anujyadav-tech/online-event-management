<?php

include "config.php";


// ================= SELECTED EVENT =================

$eventId = isset($_GET['event']) ? intval($_GET['event']) : 0;

$eventName = "";


// ================= GET EVENT NAME =================

if ($eventId > 0) {

    $eventResult = $conn->query(
        "SELECT title FROM events WHERE id = $eventId"
    );

    if ($eventResult && $eventResult->num_rows > 0) {

        $eventData = $eventResult->fetch_assoc();

        $eventName = $eventData['title'];
    }
}


// ================= REGISTRATION =================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $event = $_POST["event"];
    $message = $_POST["message"];


    $sql = "INSERT INTO registrations
            (name, email, phone, event, message)
            VALUES
            ('$name', '$email', '$phone', '$event', '$message')";


    if ($conn->query($sql) === TRUE) {

        echo "<script>
                alert('Registration Successful!');
              </script>";

    } else {

        echo "<script>
                alert('Registration Failed!');
              </script>";
    }
}


// ================= GET ACTIVE EVENTS =================

$eventsResult = $conn->query(
    "SELECT * FROM events
     WHERE status = 'Active'
     AND date >= CURDATE()
     ORDER BY date ASC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Event Registration | EventHub
    </title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fb;
            color: #222;
        }


        /* ================= NAVBAR ================= */

        header {
            background: #ffffff;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.07);
            position: sticky;
            top: 0;
            z-index: 1000;
        }


        .navbar {
            max-width: 1150px;
            margin: auto;
            padding: 17px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }


        .logo {
            font-size: 27px;
            font-weight: bold;
            color: #5b4bdb;
        }


        .logo span {
            color: #ff6b6b;
        }


        .nav-links {
            display: flex;
            list-style: none;
            gap: 28px;
        }


        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }


        .nav-links a:hover {
            color: #5b4bdb;
        }


        /* ================= REGISTRATION ================= */

        .register-section {
            min-height: calc(100vh - 145px);
            padding: 55px 20px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(91, 75, 219, 0.10),
                    transparent 35%
                ),
                #f5f6fb;
        }


        .register-container {
            width: 100%;
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 30px 34px;
            border-radius: 18px;

            box-shadow:
                0 15px 45px rgba(0, 0, 0, 0.10);

            border: 1px solid #eeeeF5;
        }


        /* ================= HEADING ================= */

        .section-title {
            text-align: center;
            margin-bottom: 25px;
        }


        .section-title p {
            display: inline-block;

            color: #5b4bdb;

            background: #f0eeff;

            padding: 6px 13px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;

            letter-spacing: 1.5px;

            margin-bottom: 9px;
        }


        .section-title h2 {
            font-size: 28px;
            color: #222;
            margin-bottom: 5px;
        }


        .section-title::after {

            content:
            "Fill in your details to register for an upcoming event";

            display: block;

            color: #777;

            font-size: 13px;

            margin-top: 7px;
        }


        /* ================= FORM ================= */

        .register-form {
            margin-top: 25px;
        }


        .form-group {
            margin-bottom: 16px;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: 600;

            color: #333;
        }


        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            padding: 12px 13px;

            border: 1px solid #dedee8;

            border-radius: 9px;

            background: #fafafe;

            color: #333;

            font-size: 14px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            outline: none;

            transition: all 0.25s ease;
        }


        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }


        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            background: #ffffff;

            border-color: #5b4bdb;

            box-shadow:
                0 0 0 3px
                rgba(91, 75, 219, 0.10);
        }


        .form-group textarea {

            min-height: 90px;

            resize: vertical;
        }


        /* ================= BUTTON ================= */

        .register-btn {

            width: 100%;

            padding: 13px;

            margin-top: 5px;

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

            box-shadow:
                0 7px 18px
                rgba(91, 75, 219, 0.25);

            transition: all 0.3s ease;
        }


        .register-btn:hover {

            transform: translateY(-2px);

            box-shadow:
                0 10px 22px
                rgba(91, 75, 219, 0.32);
        }


        /* ================= FOOTER ================= */

        footer {

            background: #17152b;

            color: #ffffff;

            text-align: center;

            padding: 20px;

            font-size: 13px;
        }


        /* ================= MOBILE ================= */

        @media (max-width: 650px) {

            .navbar {

                flex-direction: column;

                gap: 13px;
            }


            .nav-links {

                gap: 16px;

                flex-wrap: wrap;

                justify-content: center;
            }


            .register-section {

                padding: 35px 15px;
            }


            .register-container {

                max-width: 100%;

                padding: 25px 20px;
            }


            .section-title h2 {

                font-size: 25px;
            }

        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<header>

    <nav class="navbar">


        <div class="logo">

            Event<span>Hub</span>

        </div>


        <ul class="nav-links">


            <li>
                <a href="index.php">
                    Home
                </a>
            </li>


            <li>
                <a href="index.php#events">
                    Events
                </a>
            </li>


            <li>
                <a href="index.php#about">
                    About
                </a>
            </li>


            <li>
                <a href="index.php#contact">
                    Contact
                </a>
            </li>


        </ul>


    </nav>

</header>



<!-- ================= REGISTRATION FORM ================= -->

<section class="register-section">


    <div class="register-container">


        <div class="section-title">


            <p>
                EVENT REGISTRATION
            </p>


            <h2>
                Register for an Event
            </h2>


        </div>



        <form
            class="register-form"
            action=""
            method="post"
        >


            <!-- FULL NAME -->

            <div class="form-group">


                <label for="name">
                    Full Name
                </label>


                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >


            </div>



            <!-- EMAIL -->

            <div class="form-group">


                <label for="email">
                    Email Address
                </label>


                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email address"
                    required
                >


            </div>



            <!-- PHONE -->

            <div class="form-group">


                <label for="phone">
                    Phone Number
                </label>


                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Enter your phone number"
                    required
                >


            </div>



            <!-- EVENT -->

            <div class="form-group">


                <label for="event">
                    Select Event
                </label>


                <select
                    id="event"
                    name="event"
                    required
                >


                    <option value="">
                        -- Select an Event --
                    </option>


                    <?php

                    if (
                        $eventsResult &&
                        $eventsResult->num_rows > 0
                    ) {

                        while (
                            $event = $eventsResult->fetch_assoc()
                        ) {

                            $selected = "";

                            if (
                                $eventName != "" &&
                                $event['title'] == $eventName
                            ) {

                                $selected = "selected";
                            }

                    ?>


                            <option
                                value="<?php
                                    echo htmlspecialchars(
                                        $event['title']
                                    );
                                ?>"
                                <?php echo $selected; ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $event['title']
                                );
                                ?>

                            </option>


                    <?php

                        }

                    }

                    ?>


                </select>


            </div>



            <!-- MESSAGE -->

            <div class="form-group">


                <label for="message">
                    Message
                </label>


                <textarea
                    id="message"
                    name="message"
                    placeholder="Write your message..."
                ></textarea>


            </div>



            <!-- SUBMIT -->

            <button
                type="submit"
                class="register-btn"
            >

                Register Now

            </button>


        </form>


    </div>


</section>



<!-- ================= FOOTER ================= -->

<footer>


    <p>

        © 2026 EventHub |
        Online Event Management Portal

    </p>


</footer>


</body>

</html>