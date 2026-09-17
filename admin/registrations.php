<?php

session_start();

/* ================= ADMIN SECURITY ================= */

if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}

include "../config.php";


/* ================= GET REGISTRATIONS ================= */

$sql = "SELECT * FROM registrations ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Registrations | EventHub</title>

<style>

/* ================= GLOBAL ================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    background: #f5f7fc;
    color: #172033;
}


/* ================= SIDEBAR ================= */

.sidebar {

    position: fixed;

    left: 0;
    top: 0;

    width: 245px;
    height: 100vh;

    background:
        linear-gradient(
            180deg,
            #10172d 0%,
            #121a32 55%,
            #0e162b 100%
        );

    color: white;

    padding: 25px 15px;

    box-shadow:
        8px 0 30px rgba(18,25,55,0.08);
}


/* ================= LOGO ================= */

.logo-area {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 4px 12px 28px;

    border-bottom:
        1px solid rgba(255,255,255,0.07);

    margin-bottom: 28px;
}

.logo-icon {

    width: 43px;
    height: 43px;

    border-radius: 12px;

    display: flex;

    align-items: center;
    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #7167f5,
            #9d45ef
        );

    font-size: 22px;
}

.logo-text h2 {

    font-size: 22px;

    line-height: 22px;
}

.logo-text h2 span {

    color: #8c5cf5;
}

.logo-text small {

    display: block;

    margin-top: 4px;

    color: #8f98ae;

    font-size: 11px;
}


/* ================= MENU ================= */

.menu-title {

    color: #78839b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.5px;

    padding: 0 12px;

    margin-bottom: 10px;
}


/* ================= SIDEBAR LINKS ================= */

.sidebar a {

    display: flex;

    align-items: center;

    gap: 13px;

    color: #b8c0d0;

    text-decoration: none;

    padding: 13px 14px;

    margin: 5px 0;

    border-radius: 10px;

    font-size: 13.5px;

    transition: 0.25s;
}

.sidebar a:hover {

    color: white;

    background:
        rgba(255,255,255,0.06);
}

.sidebar a.active {

    color: white;

    background:
        linear-gradient(
            135deg,
            #536df5,
            #7939ed
        );

    box-shadow:
        0 8px 20px rgba(90,66,235,0.30);
}

.nav-icon {

    width: 20px;

    text-align: center;

    font-size: 17px;
}


/* ================= MAIN ================= */

.main {

    margin-left: 245px;

    min-height: 100vh;

    padding: 0 28px 35px;
}


/* ================= HEADER ================= */

.top-header {

    height: 70px;

    display: flex;

    align-items: center;

    border-bottom:
        1px solid #e9ecf4;

    margin-bottom: 20px;
}

.header-title h1 {

    font-size: 25px;

    color: #182033;
}

.header-title p {

    margin-top: 4px;

    color: #8a92a5;

    font-size: 12px;
}


/* ================= TABLE ================= */

.table-box {

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.05);

    overflow-x: auto;
}

table {

    width: 100%;

    border-collapse: collapse;

    min-width: 850px;
}

thead th {

    background: #111827;

    color: white;

    padding: 14px;

    text-align: left;

    font-size: 12px;
}

thead th:first-child {

    border-radius: 8px 0 0 8px;
}

thead th:last-child {

    border-radius: 0 8px 8px 0;
}

tbody td {

    padding: 14px;

    border-bottom:
        1px solid #e8ebf2;

    color: #4b5563;

    font-size: 13px;

    vertical-align: middle;
}

tbody tr:hover {

    background: #f8f9ff;
}


/* ================= ID ================= */

.id {

    color: #6841df;

    font-weight: 700;
}


/* ================= EVENT BADGE ================= */

.event-badge {

    display: inline-block;

    background: #eee8ff;

    color: #6841df;

    padding: 6px 10px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 700;
}


/* ================= MESSAGE ================= */

.message {

    max-width: 250px;

    color: #7b8498;

    line-height: 1.5;
}


/* ================= EMPTY ================= */

.empty {

    text-align: center;

    padding: 60px 20px;

    color: #8a92a5;
}

.empty-icon {

    font-size: 45px;

    margin-bottom: 12px;
}

.empty h3 {

    color: #374151;

    font-size: 17px;

    margin-bottom: 7px;
}

.empty p {

    font-size: 12px;
}


/* ================= FOOTER ================= */

.footer {

    text-align: center;

    color: #a0a7b7;

    font-size: 10px;

    padding-top: 25px;
}


/* ================= RESPONSIVE ================= */

@media (max-width: 700px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;
    }

    .main {

        margin-left: 0;

        padding: 20px 15px;
    }

}

</style>

</head>


<body>


<!-- ================= SIDEBAR ================= -->

<aside class="sidebar">


    <div class="logo-area">

        <div class="logo-icon">
            📅
        </div>

        <div class="logo-text">

            <h2>
                Event<span>Hub</span>
            </h2>

            <small>
                Admin Panel
            </small>

        </div>

    </div>


    <div class="menu-title">
        MAIN MENU
    </div>


    <a href="index.php">

        <span class="nav-icon">⌂</span>

        Dashboard

    </a>


    <a href="manage_event.php">

        <span class="nav-icon">▣</span>

        Manage Events

    </a>


    <a href="add_event.php">

        <span class="nav-icon">⊕</span>

        Add Event

    </a>


    <a href="registrations.php" class="active">

        <span class="nav-icon">♟</span>

        Registrations

    </a>


    <br>


    <div class="menu-title">
        WEBSITE
    </div>


    <a href="../index.php">

        <span class="nav-icon">↗</span>

        View Website

    </a>


    <!-- ================= PROFILE ================= -->

    <div style="
        position:absolute;
        left:15px;
        right:15px;
        bottom:20px;
        background:rgba(255,255,255,0.055);
        border:1px solid rgba(255,255,255,0.06);
        border-radius:13px;
        padding:13px;
    ">


        <a href="profile.php"
           style="
           padding:0;
           margin:0;
           "
        >

            <div style="
                display:flex;
                align-items:center;
                gap:10px;
                padding-bottom:12px;
                border-bottom:1px solid rgba(255,255,255,0.07);
            ">

                <div style="
                    width:42px;
                    height:42px;
                    min-width:42px;
                    border-radius:50%;
                    overflow:hidden;
                ">

                    <img
                        src="anuj.jpg"
                        alt="Anuj Yadav"
                        style="
                        width:42px;
                        height:42px;
                        object-fit:cover;
                        display:block;
                        "
                    >

                </div>


                <div>

                    <strong style="
                        display:block;
                        color:white;
                        font-size:12px;
                    ">
                        Anuj Yadav
                    </strong>

                    <span style="
                        display:block;
                        color:#7f8aa2;
                        font-size:10px;
                        margin-top:2px;
                    ">
                        Administrator
                    </span>

                </div>

            </div>

        </a>


        <a href="logout.php"
           style="
           color:#ff6b7d;
           padding:10px 2px 0;
           margin:0;
           font-size:12px;
           "
        >

            <span class="nav-icon">
                ↪
            </span>

            Logout

        </a>

    </div>


</aside>



<!-- ================= MAIN ================= -->

<main class="main">


    <header class="top-header">

        <div class="header-title">

            <h1>
                Registered Users
            </h1>

            <p>
                View all event registrations
            </p>

        </div>

    </header>


    <!-- ================= TABLE ================= -->

    <div class="table-box">


        <?php if ($result && $result->num_rows > 0) { ?>


            <table>

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Event
                        </th>

                        <th>
                            Message
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php while ($row = $result->fetch_assoc()) { ?>


                        <tr>


                            <td class="id">

                                <?php
                                echo htmlspecialchars($row['id']);
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars($row['name']);
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars($row['email']);
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars($row['phone']);
                                ?>

                            </td>


                            <td>

                                <span class="event-badge">

                                    <?php
                                    echo htmlspecialchars($row['event']);
                                    ?>

                                </span>

                            </td>


                            <td class="message">

                                <?php

                                if (
                                    isset($row['message']) &&
                                    $row['message'] != ""
                                ) {

                                    echo htmlspecialchars(
                                        $row['message']
                                    );

                                } else {

                                    echo "—";

                                }

                                ?>

                            </td>


                        </tr>


                    <?php } ?>


                </tbody>

            </table>


        <?php } else { ?>


            <div class="empty">

                <div class="empty-icon">
                    👥
                </div>

                <h3>
                    No Registrations Found
                </h3>

                <p>
                    Registered users will appear here.
                </p>

            </div>


        <?php } ?>


    </div>


    <div class="footer">

        EventHub Admin Panel

    </div>


</main>


</body>

</html>