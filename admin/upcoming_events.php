<?php

include "../config.php";


/* =========================
   UPCOMING EVENTS
========================= */

$result = $conn->query(
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

    <title>Upcoming Events | EventHub</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }


        body {
            background: #f5f6fb;
            color: #222;
        }


        /* ================= SIDEBAR ================= */

        .sidebar {

            position: fixed;

            left: 0;
            top: 0;

            width: 245px;
            height: 100vh;

            background: linear-gradient(
                180deg,
                #111827,
                #1f2937
            );

            color: white;

            padding: 25px 15px;

            box-shadow:
                5px 0 20px rgba(0,0,0,0.08);

        }


        .logo {

            text-align: center;

            font-size: 27px;

            font-weight: bold;

            margin-bottom: 40px;

        }


        .logo span {
            color: #8b7cf6;
        }


        .menu-title {

            color: #9ca3af;

            font-size: 11px;

            font-weight: bold;

            letter-spacing: 1.5px;

            padding: 0 12px;

            margin-bottom: 10px;

        }


        .sidebar a {

            display: flex;

            align-items: center;

            gap: 10px;

            color: #d1d5db;

            text-decoration: none;

            padding: 13px 14px;

            margin: 6px 0;

            border-radius: 9px;

            font-size: 14px;

            transition: 0.3s;

        }


        .sidebar a:hover {

            background: #6366f1;

            color: white;

            transform: translateX(3px);

        }


        .sidebar a.active {

            background: #6366f1;

            color: white;

        }


        /* ================= MAIN ================= */

        .main {

            margin-left: 245px;

            padding: 30px;

        }


        /* ================= TOPBAR ================= */

        .topbar {

            background: white;

            padding: 25px 28px;

            border-radius: 16px;

            margin-bottom: 25px;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.06);

        }


        .topbar h1 {

            color: #111827;

            font-size: 28px;

            margin-bottom: 6px;

        }


        .topbar p {

            color: #6b7280;

            font-size: 14px;

        }


        /* ================= EVENTS ================= */

        .event-container {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

        }


        .event-card {

            background: white;

            padding: 24px;

            border-radius: 16px;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.06);

            border: 1px solid #eeeeF5;

            transition: 0.3s;

        }


        .event-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 12px 28px rgba(0,0,0,0.10);

        }


        .event-icon {

            width: 50px;

            height: 50px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f0efff;

            border-radius: 12px;

            font-size: 24px;

            margin-bottom: 15px;

        }


        .event-card h2 {

            color: #111827;

            font-size: 20px;

            margin-bottom: 10px;

        }


        .event-card p {

            color: #6b7280;

            font-size: 14px;

            line-height: 1.6;

            margin-bottom: 15px;

        }


        .event-info {

            display: flex;

            flex-direction: column;

            gap: 8px;

            color: #4b5563;

            font-size: 13px;

            margin-bottom: 18px;

        }


        .status {

            display: inline-block;

            width: fit-content;

            background: #dcfce7;

            color: #15803d;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

            margin-bottom: 15px;

        }


        .manage-btn {

            display: inline-block;

            text-decoration: none;

            background: #6366f1;

            color: white;

            padding: 10px 16px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;

            transition: 0.3s;

        }


        .manage-btn:hover {

            background: #4f46e5;

        }


        /* ================= EMPTY ================= */

        .empty {

            background: white;

            padding: 60px 20px;

            border-radius: 16px;

            text-align: center;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.06);

        }


        .empty-icon {

            font-size: 45px;

            margin-bottom: 15px;

        }


        .empty h2 {

            margin-bottom: 8px;

            color: #111827;

        }


        .empty p {

            color: #6b7280;

        }


        /* ================= FOOTER ================= */

        .footer {

            text-align: center;

            margin-top: 35px;

            color: #9ca3af;

            font-size: 12px;

        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 1100px) {

            .event-container {

                grid-template-columns:
                    repeat(2, 1fr);

            }

        }


        @media (max-width: 750px) {

            .sidebar {

                position: relative;

                width: 100%;

                height: auto;

            }


            .main {

                margin-left: 0;

                padding: 20px;

            }


            .event-container {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>


<body>


<!-- ================= SIDEBAR ================= -->

<aside class="sidebar">


    <div class="logo">

        Event<span>Hub</span>

    </div>


    <div class="menu-title">

        MAIN MENU

    </div>


    <a href="index.php">

        📊 Dashboard

    </a>


    <a href="manage_event.php">

        🎫 Manage Events

    </a>


    <a href="add_event.php">

        ➕ Add Event

    </a>


    <a href="registrations.php">

        👥 Registrations

    </a>


    <br>


    <div class="menu-title">

        WEBSITE

    </div>


    <a href="../index.php">

        🏠 View Website

    </a>


</aside>



<!-- ================= MAIN ================= -->

<main class="main">


    <div class="topbar">

        <h1>

            Upcoming Events

        </h1>


        <p>


        </p>

    </div>



    <!-- ================= EVENTS ================= -->

    <?php if ($result && $result->num_rows > 0) { ?>


        <div class="event-container">


            <?php while ($event = $result->fetch_assoc()) { ?>


                <div class="event-card">


                    <div class="event-icon">

                        🎫

                    </div>


                    <span class="status">

                        Active

                    </span>


                    <h2>

                        <?php

                        echo htmlspecialchars(
                            $event['title']
                        );

                        ?>

                    </h2>


                    <p>

                        <?php

                        echo htmlspecialchars(
                            $event['description']
                        );

                        ?>

                    </p>


                    <div class="event-info">


                        <span>

                            📅

                            <?php

                            echo date(
                                "d M Y",
                                strtotime(
                                    $event['date']
                                )
                            );

                            ?>

                        </span>


                        <span>

                            📍

                            <?php

                            echo htmlspecialchars(
                                $event['location']
                            );

                            ?>

                        </span>


                    </div>


                    <a href="manage_event.php"
                       class="manage-btn">

                        Manage Event

                    </a>


                </div>


            <?php } ?>


        </div>


    <?php } else { ?>


        <div class="empty">


            <div class="empty-icon">

                📅

            </div>


            <h2>

                No Upcoming Events

            </h2>


            <p>

                There are currently no upcoming
                active events.

            </p>


        </div>


    <?php } ?>


    <div class="footer">


    </div>


</main>


</body>

</html>