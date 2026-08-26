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


/* ================= TOTAL REGISTRATIONS ================= */

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM registrations"
);

$row = $result->fetch_assoc();

$totalRegistrations = $row['total'];


/* ================= TOTAL EVENTS ================= */

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM events"
);

$row = $result->fetch_assoc();

$totalEvents = $row['total'];


/* ================= ACTIVE EVENTS ================= */

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM events
     WHERE status = 'Active'"
);

$row = $result->fetch_assoc();

$activeEvents = $row['total'];


/* ================= UPCOMING EVENTS ================= */

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM events
     WHERE status = 'Active'
     AND date >= CURDATE()"
);

$row = $result->fetch_assoc();

$upcomingEvents = $row['total'];


/* ================= PERCENTAGE ================= */

if ($totalEvents > 0) {

    $activePercentage =
        round(($activeEvents / $totalEvents) * 100);

    $upcomingPercentage =
        round(($upcomingEvents / $totalEvents) * 100);

} else {

    $activePercentage = 0;
    $upcomingPercentage = 0;
}


/* ================= CURRENT DATE ================= */

date_default_timezone_set("Asia/Kolkata");

$currentDate = date("l, d F Y");
$currentTime = date("h:i A");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>EventHub | Admin Dashboard</title>


<style>

/* =========================================================
   GLOBAL
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, Helvetica, sans-serif;
    font-size: 15px;
}

body {
    background: #f5f7fc;
    color: #172033;
}


/* =========================================================
   SIDEBAR
========================================================= */

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
        8px 0 30px rgba(18, 25, 55, 0.08);

    z-index: 100;
}


/* LOGO */

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

    box-shadow:
        0 8px 20px rgba(124, 80, 245, 0.35);

    font-size: 22px;
}

.logo-text h2 {

    font-size: 22px;
    line-height: 22px;

    font-weight: 700;

    letter-spacing: -0.5px;
}

.logo-text h2 span {
    color: #8c5cf5;
}

.logo-text small {

    display: block;

    margin-top: 4px;

    color: #8f98ae;

    font-size: 11px;

    letter-spacing: 0.3px;
}


/* MENU TITLE */

.menu-title {

    color: #78839b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.5px;

    padding: 0 12px;

    margin-bottom: 10px;
}


/* SIDEBAR LINKS */

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

    font-weight: 500;

    transition: all 0.25s ease;
}

.sidebar a:hover {

    color: white;

    background:
        rgba(255,255,255,0.06);

    transform: translateX(2px);
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
        0 8px 20px rgba(90, 66, 235, 0.30);
}

.nav-icon {

    width: 20px;

    text-align: center;

    font-size: 17px;
}


/* SIDEBAR BOTTOM */

.sidebar-bottom {

    position: absolute;

    left: 15px;
    right: 15px;
    bottom: 20px;

    background:
        rgba(255,255,255,0.055);

    border:
        1px solid rgba(255,255,255,0.06);

    border-radius: 13px;

    padding: 13px;
}

.admin-profile {

    display: flex;

    align-items: center;

    gap: 10px;

    padding-bottom: 12px;

    border-bottom:
        1px solid rgba(255,255,255,0.07);
}

.admin-avatar {
    width: 42px !important;
    height: 42px !important;
    min-width: 42px;
    border-radius: 50% !important;
    overflow: hidden;
    padding: 0;
    background: transparent;
}

.admin-avatar img {
    width: 42px !important;
    height: 42px !important;
    min-width: 42px;
    min-height: 42px;
    border-radius: 50% !important;
    object-fit: cover;
    display: block;
}

.admin-info strong {

    display: block;

    color: #ffffff;

    font-size: 12px;
}

.admin-info span {

    display: block;

    color: #7f8aa2;

    font-size: 10px;

    margin-top: 2px;
}


/* ================= ADMIN PROFILE LINK ================= */

.admin-profile-link {

    display: block;

    text-decoration: none;

    color: inherit;
}

.admin-profile-link:hover {

    opacity: 0.9;
}


/* LOGOUT */

.logout {

    color: #ff6b7d !important;

    padding: 10px 2px 0 !important;

    margin: 0 !important;

    background: transparent !important;

    font-size: 12px !important;
}

.logout:hover {
    transform: none !important;
}


/* =========================================================
   MAIN
========================================================= */

.main {

    margin-left: 245px;

    min-height: 100vh;

    padding: 0 28px 35px;
}


/* =========================================================
   TOP HEADER
========================================================= */

.top-header {

    height: 70px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    border-bottom:
        1px solid #e9ecf4;

    margin-bottom: 20px;
}

.header-left {

    display: flex;

    align-items: center;

    gap: 20px;
}

.menu-toggle {

    font-size: 22px;

    color: #566078;
}

.date-info {

    color: #8a92a5;

    font-size: 12px;
}

.date-divider {

    display: inline-block;

    margin: 0 12px;

    color: #d4d8e2;
}

.notification {

    position: relative;

    width: 40px;
    height: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #ffffff;

    border: 1px solid #edf0f6;

    font-size: 19px;

    box-shadow:
        0 4px 15px rgba(30,40,80,0.05);
}

.notification-badge {

    position: absolute;

    top: -3px;
    right: -2px;

    width: 17px;
    height: 17px;

    border-radius: 50%;

    background: #713df1;

    color: white;

    font-size: 9px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 2px solid #f5f7fc;
}


/* =========================================================
   WELCOME
========================================================= */

.welcome {

    position: relative;

    min-height: 118px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    overflow: hidden;

    padding: 25px 28px;

    margin-bottom: 20px;

    border-radius: 16px;

    background:
        linear-gradient(
            105deg,
            #ffffff 0%,
            #f8f9ff 65%,
            #edf2ff 100%
        );

    border: 1px solid #edf0f8;

    box-shadow:
        0 6px 25px rgba(33,42,80,0.055);
}

.welcome h1 {

    font-size: 25px;

    color: #182033;

    margin-bottom: 7px;

    letter-spacing: -0.5px;
}

.welcome p {

    color: #7c8498;

    font-size: 13px;
}

.welcome-art {

    font-size: 64px;

    opacity: 0.90;

    margin-right: 20px;
}


/* =========================================================
   STAT CARDS
========================================================= */

.cards {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 17px;

    margin-bottom: 20px;
}

.card-link {

    text-decoration: none;

    color: inherit;
}

.card {

    position: relative;

    min-height: 148px;

    background: #ffffff;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    overflow: hidden;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.055);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.card:hover {

    transform: translateY(-4px);

    box-shadow:
        0 12px 30px rgba(28,40,80,0.10);
}

.card-top {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;
}

.card-icon {

    width: 47px;
    height: 47px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 21px;
}

.purple {
    background: #eee8ff;
}

.blue {
    background: #e5f2ff;
}

.green {
    background: #e4f8ec;
}

.orange {
    background: #fff0dc;
}

.card-menu {

    color: #9aa2b4;

    font-size: 18px;

    letter-spacing: 2px;
}

.card h3 {

    color: #727b90;

    font-size: 12px;

    font-weight: 600;

    margin-top: 15px;
}

.card h2 {

    color: #172033;

    font-size: 28px;

    margin-top: 5px;

    font-weight: 700;
}

.card-bottom {

    display: flex;

    align-items: center;

    gap: 5px;

    margin-top: 8px;

    font-size: 10px;
}

.up {

    color: #45ad71;

    font-weight: 600;
}

.card-line {

    position: absolute;

    right: 15px;
    bottom: 20px;

    width: 70px;
    height: 28px;

    opacity: 0.5;
}

.card-line svg {

    width: 100%;
    height: 100%;
}


/* =========================================================
   LOWER GRID
========================================================= */

.dashboard-grid {

    display: grid;

    grid-template-columns:
        1.4fr 0.9fr;

    gap: 18px;

    margin-bottom: 18px;
}


/* PANEL */

.panel {

    background: #ffffff;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.05);
}

.panel-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 18px;
}

.panel-header h2 {

    color: #1a2234;

    font-size: 16px;
}

.panel-select {

    border:
        1px solid #e3e7ef;

    background: white;

    padding: 7px 11px;

    border-radius: 7px;

    color: #687186;

    font-size: 11px;
}


/* =========================================================
   REGISTRATION OVERVIEW
========================================================= */

.chart {

    height: 205px;

    position: relative;

    padding:
        10px 10px 25px 38px;
}

.chart-lines {

    position: absolute;

    left: 38px;
    right: 8px;
    top: 10px;
    bottom: 25px;

    display: flex;

    flex-direction: column;

    justify-content: space-between;
}

.chart-lines span {

    display: block;

    border-top:
        1px dashed #e8ebf2;
}

.chart-labels {

    position: absolute;

    left: 7px;
    top: 4px;
    bottom: 25px;

    display: flex;

    flex-direction: column;

    justify-content: space-between;

    color: #9aa1b2;

    font-size: 9px;
}

.chart-area {

    position: absolute;

    left: 38px;
    right: 8px;
    top: 10px;
    bottom: 25px;
}

.chart-area svg {

    width: 100%;
    height: 100%;

    overflow: visible;
}

.chart-dates {

    position: absolute;

    left: 38px;
    right: 8px;
    bottom: 0;

    display: flex;

    justify-content: space-between;

    color: #8f96a7;

    font-size: 9px;
}

.chart-footer {

    color: #7c8498;

    font-size: 11px;

    padding-top: 8px;
}

.chart-footer strong {
    color: #6841df;
}


/* =========================================================
   EVENTS STATUS
========================================================= */

.status-content {

    min-height: 205px;

    display: flex;

    align-items: center;

    justify-content: space-around;
}

.donut {

    width: 145px;
    height: 145px;

    border-radius: 50%;

    background:
        conic-gradient(
            #21c76a 0deg
            <?php echo ($activePercentage * 3.6); ?>deg,
            #f5a623 <?php echo ($activePercentage * 3.6); ?>deg
            360deg
        );

    position: relative;

    display: flex;

    align-items: center;

    justify-content: center;
}

.donut::after {

    content: "";

    width: 92px;
    height: 92px;

    border-radius: 50%;

    background: #ffffff;

    position: absolute;
}

.donut-center {

    position: relative;

    z-index: 2;

    text-align: center;
}

.donut-center strong {

    display: block;

    font-size: 22px;

    color: #1c2436;
}

.donut-center span {

    font-size: 10px;

    color: #8c94a5;
}

.status-list {

    width: 145px;
}

.status-item {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin: 16px 0;

    font-size: 11px;

    color: #697287;
}

.status-name {

    display: flex;

    align-items: center;

    gap: 8px;
}

.status-dot {

    width: 9px;
    height: 9px;

    border-radius: 50%;
}

.status-green {
    background: #20c96b;
}

.status-orange {
    background: #f5a623;
}

.status-item strong {

    color: #7c8497;

    font-size: 10px;
}


/* =========================================================
   QUICK ACTIONS
========================================================= */

.quick-panel {

    background: #ffffff;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.05);
}

.quick-header {

    margin-bottom: 15px;
}

.quick-header h2 {

    font-size: 16px;

    color: #1a2234;
}

.quick-actions {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 14px;
}

.quick-action {

    display: flex;

    align-items: center;

    gap: 12px;

    text-decoration: none;

    padding: 13px;

    border-radius: 10px;

    transition: 0.25s;

    border: 1px solid transparent;
}

.quick-action:hover {

    transform: translateY(-2px);

    box-shadow:
        0 7px 18px rgba(30,40,80,0.07);
}

.quick-icon {

    width: 39px;
    height: 39px;

    border-radius: 9px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 17px;
}

.quick-action strong {

    display: block;

    font-size: 12px;

    margin-bottom: 3px;
}

.quick-action span {

    display: block;

    font-size: 9px;

    color: #8a92a4;
}


/* QUICK COLORS */

.quick-purple {

    background: #f3edff;

    border-color: #e9ddff;
}

.quick-purple .quick-icon {
    background: #e4d7ff;
}

.quick-purple strong {
    color: #7041d8;
}


.quick-blue {

    background: #edf7ff;

    border-color: #d9ecff;
}

.quick-blue .quick-icon {
    background: #d8edff;
}

.quick-blue strong {
    color: #3186c7;
}


.quick-green {

    background: #eefaf2;

    border-color: #d9f0df;
}

.quick-green .quick-icon {
    background: #d9f2df;
}

.quick-green strong {
    color: #3a9b57;
}


.quick-orange {

    background: #fff7e9;

    border-color: #f7e8c8;
}

.quick-orange .quick-icon {
    background: #ffebc7;
}

.quick-orange strong {
    color: #d58b1b;
}


/* =========================================================
   FOOTER
========================================================= */

.dashboard-footer {

    text-align: center;

    color: #a0a7b7;

    font-size: 10px;

    padding-top: 25px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .cards {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .quick-actions {

        grid-template-columns:
            repeat(2, 1fr);
    }
}


@media (max-width: 950px) {

    .sidebar {

        width: 210px;
    }

    .main {

        margin-left: 210px;
    }

    .dashboard-grid {

        grid-template-columns: 1fr;
    }
}


@media (max-width: 700px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

        min-height: auto;
    }

    .sidebar-bottom {

        position: relative;

        left: auto;
        right: auto;
        bottom: auto;

        margin-top: 25px;
    }

    .main {

        margin-left: 0;

        padding: 0 15px 25px;
    }

    .top-header {

        height: 65px;
    }

    .welcome {

        padding: 20px;

    }

    .welcome-art {

        display: none;
    }

    .cards {

        grid-template-columns: 1fr;
    }

    .quick-actions {

        grid-template-columns: 1fr;
    }

    .date-info {

        display: none;
    }

    .status-content {

        flex-direction: column;

        gap: 15px;
    }
}


</style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
========================================================= -->

<aside class="sidebar">


    <!-- LOGO -->

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


    <!-- MAIN MENU -->

    <div class="menu-title">
        MAIN MENU
    </div>


    <a href="index.php" class="active">

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


    <a href="registrations.php">

        <span class="nav-icon">♟</span>

        Registrations

    </a>


    <br>


    <!-- WEBSITE -->

    <div class="menu-title">
        WEBSITE
    </div>


    <a href="../index.php">

        <span class="nav-icon">↗</span>

        View Website

    </a>


    <!-- ADMIN PROFILE -->

    <div class="sidebar-bottom">

        <a href="profile.php" class="admin-profile-link">

            <div class="admin-profile">

                <div class="admin-avatar">
                    <img src="anuj.jpg" alt="Anuj Yadav">
                </div>

                <div class="admin-info">

                    <strong>
                        Anuj Yadav
                    </strong>

                    <span>
                        Administrator
                    </span>

                </div>

            </div>

        </a>


        <a href="logout.php" class="logout">

            <span class="nav-icon">↪</span>

            Logout

        </a>

    </div>

</aside>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="main">


    <!-- TOP HEADER -->

    <header class="top-header">

        <div class="header-left">

            <div class="menu-toggle">
                ☰
            </div>

            <div class="date-info">

                <?php echo $currentDate; ?>

                <span class="date-divider">|</span>

                <?php echo $currentTime; ?>

            </div>

        </div>


        <div class="notification">

            🔔

            <span class="notification-badge">
                3
            </span>

        </div>

    </header>


    <!-- WELCOME -->

    <section class="welcome">

        <div>

            <h1>
                Dashboard
            </h1>

            <p>
                Welcome back, Anuj Yadav! Here's what's happening with your events.
            </p>

        </div>


        <div class="welcome-art">
            📅
        </div>

    </section>


    <!-- STATISTICS -->

    <section class="cards">


        <!-- TOTAL EVENTS -->

        <a href="manage_event.php" class="card-link">

            <div class="card">

                <div class="card-top">

                    <div class="card-icon purple">
                        📅
                    </div>

                    <div class="card-menu">
                        •••
                    </div>

                </div>

                <h3>
                    Total Events
                </h3>

                <h2>
                    <?php echo $totalEvents; ?>
                </h2>

                <div class="card-bottom">

                    <span class="up">
                        ↗ +100%
                    </span>

                    <span style="color:#9ca3af;">
                        vs last month
                    </span>

                </div>


                <div class="card-line">

                    <svg viewBox="0 0 100 40">

                        <polyline
                            points="0,32 18,25 34,28 48,20 63,22 78,10 100,6"
                            fill="none"
                            stroke="#8b5cf6"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>

            </div>

        </a>


        <!-- TOTAL REGISTRATIONS -->

        <a href="registrations.php" class="card-link">

            <div class="card">

                <div class="card-top">

                    <div class="card-icon blue">
                        👥
                    </div>

                    <div class="card-menu">
                        •••
                    </div>

                </div>

                <h3>
                    Total Registrations
                </h3>

                <h2>
                    <?php echo $totalRegistrations; ?>
                </h2>

                <div class="card-bottom">

                    <span class="up">
                        ↗ +57%
                    </span>

                    <span style="color:#9ca3af;">
                        vs last month
                    </span>

                </div>


                <div class="card-line">

                    <svg viewBox="0 0 100 40">

                        <polyline
                            points="0,30 18,27 35,28 50,21 65,22 82,12 100,5"
                            fill="none"
                            stroke="#60a5fa"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>

            </div>

        </a>


        <!-- ACTIVE EVENTS -->

        <a href="manage_event.php" class="card-link">

            <div class="card">

                <div class="card-top">

                    <div class="card-icon green">
                        ✓
                    </div>

                    <div class="card-menu">
                        •••
                    </div>

                </div>

                <h3>
                    Active Events
                </h3>

                <h2>
                    <?php echo $activeEvents; ?>
                </h2>

                <div class="card-bottom">

                    <span class="up">
                        ↗ +100%
                    </span>

                    <span style="color:#9ca3af;">
                        vs last month
                    </span>

                </div>


                <div class="card-line">

                    <svg viewBox="0 0 100 40">

                        <polyline
                            points="0,31 18,29 34,25 50,26 66,17 82,19 100,7"
                            fill="none"
                            stroke="#39c978"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>

            </div>

        </a>


        <!-- UPCOMING EVENTS -->

        <a href="upcoming_events.php" class="card-link">

            <div class="card">

                <div class="card-top">

                    <div class="card-icon orange">
                        📆
                    </div>

                    <div class="card-menu">
                        •••
                    </div>

                </div>

                <h3>
                    Upcoming Events
                </h3>

                <h2>
                    <?php echo $upcomingEvents; ?>
                </h2>

                <div class="card-bottom">

                    <span class="up">
                        ↗ +100%
                    </span>

                    <span style="color:#9ca3af;">
                        vs last month
                    </span>

                </div>


                <div class="card-line">

                    <svg viewBox="0 0 100 40">

                        <polyline
                            points="0,32 18,29 34,30 49,24 64,25 80,17 100,8"
                            fill="none"
                            stroke="#f3aa3c"
                            stroke-width="3"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>

            </div>

        </a>


    </section>


    <!-- =====================================================
         DASHBOARD LOWER GRID
    ====================================================== -->

    <section class="dashboard-grid">


        <!-- REGISTRATION OVERVIEW -->

        <div class="panel">

            <div class="panel-header">

                <h2>
                    Registrations Overview
                </h2>

                <select class="panel-select">

                    <option>
                        This Month
                    </option>

                    <option>
                        Last Month
                    </option>

                </select>

            </div>


            <div class="chart">

                <div class="chart-lines">

                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>

                </div>


                <div class="chart-labels">

                    <span>20</span>
                    <span>15</span>
                    <span>10</span>
                    <span>5</span>
                    <span>0</span>

                </div>


                <div class="chart-area">

                    <svg viewBox="0 0 700 180"
                         preserveAspectRatio="none">

                        <defs>

                            <linearGradient
                                id="chartGradient"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1">

                                <stop
                                    offset="0%"
                                    stop-color="#8b5cf6"
                                    stop-opacity="0.25"/>

                                <stop
                                    offset="100%"
                                    stop-color="#8b5cf6"
                                    stop-opacity="0.02"/>

                            </linearGradient>

                        </defs>


                        <path
                            d="
                            M0 150
                            C70 125,
                            100 125,
                            145 105
                            S220 90,
                            260 75
                            S330 10,
                            380 35
                            S450 95,
                            500 80
                            S570 115,
                            620 90
                            S670 65,
                            700 55
                            L700 180
                            L0 180 Z"
                            fill="url(#chartGradient)"
                        />


                        <path
                            d="
                            M0 150
                            C70 125,
                            100 125,
                            145 105
                            S220 90,
                            260 75
                            S330 10,
                            380 35
                            S450 95,
                            500 80
                            S570 115,
                            620 90
                            S670 65,
                            700 55"
                            fill="none"
                            stroke="#713ce6"
                            stroke-width="3"
                        />


                        <circle cx="0" cy="150" r="5" fill="#713ce6"/>
                        <circle cx="145" cy="105" r="5" fill="#713ce6"/>
                        <circle cx="260" cy="75" r="5" fill="#713ce6"/>
                        <circle cx="380" cy="35" r="5" fill="#713ce6"/>
                        <circle cx="500" cy="80" r="5" fill="#713ce6"/>
                        <circle cx="620" cy="90" r="5" fill="#713ce6"/>
                        <circle cx="700" cy="55" r="5" fill="#713ce6"/>

                    </svg>

                </div>


                <div class="chart-dates">

                    <span>1 Jun</span>
                    <span>5 Jun</span>
                    <span>10 Jun</span>
                    <span>15 Jun</span>
                    <span>20 Jun</span>
                    <span>25 Jun</span>
                    <span>30 Jun</span>

                </div>

            </div>


            <div class="chart-footer">

                Total registrations:

                <strong>
                    <?php echo $totalRegistrations; ?>
                </strong>

            </div>

        </div>


        <!-- EVENTS STATUS -->

        <div class="panel">

            <div class="panel-header">

                <h2>
                    Events Status
                </h2>

            </div>


            <div class="status-content">


                <div class="donut">

                    <div class="donut-center">

                        <strong>
                            <?php echo $totalEvents; ?>
                        </strong>

                        <span>
                            Total
                        </span>

                    </div>

                </div>


                <div class="status-list">


                    <div class="status-item">

                        <div class="status-name">

                            <span class="status-dot status-green"></span>

                            Active Events

                        </div>

                        <strong>
                            <?php echo $activeEvents; ?>
                            (<?php echo $activePercentage; ?>%)
                        </strong>

                    </div>


                    <div class="status-item">

                        <div class="status-name">

                            <span class="status-dot status-orange"></span>

                            Upcoming Events

                        </div>

                        <strong>
                            <?php echo $upcomingEvents; ?>
                            (<?php echo $upcomingPercentage; ?>%)
                        </strong>

                    </div>


                </div>

            </div>

        </div>


    </section>


    <!-- =====================================================
         QUICK ACTIONS
    ====================================================== -->

    <section class="quick-panel">


        <div class="quick-header">

            <h2>
                Quick Actions
            </h2>

        </div>


        <div class="quick-actions">


            <!-- ADD EVENT -->

            <a href="add_event.php"
               class="quick-action quick-purple">

                <div class="quick-icon">
                    ➕
                </div>

                <div>

                    <strong>
                        Add New Event
                    </strong>

                    <span>
                        Create a new event
                    </span>

                </div>

            </a>


            <!-- MANAGE EVENTS -->

            <a href="manage_event.php"
               class="quick-action quick-blue">

                <div class="quick-icon">
                    📅
                </div>

                <div>

                    <strong>
                        Manage Events
                    </strong>

                    <span>
                        View and manage events
                    </span>

                </div>

            </a>


            <!-- REGISTRATIONS -->

            <a href="registrations.php"
               class="quick-action quick-green">

                <div class="quick-icon">
                    👥
                </div>

                <div>

                    <strong>
                        View Registrations
                    </strong>

                    <span>
                        See all registrations
                    </span>

                </div>

            </a>


            <!-- WEBSITE -->

            <a href="../index.php"
               class="quick-action quick-orange">

                <div class="quick-icon">
                    ↗
                </div>

                <div>

                    <strong>
                        View Website
                    </strong>

                    <span>
                        Visit your website
                    </span>

                </div>

            </a>


        </div>

    </section>


    <div class="dashboard-footer">

    </div>


</main>


</body>

</html>