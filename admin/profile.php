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

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Admin Profile | EventHub</title>

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

    z-index: 100;
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

    box-shadow:
        0 8px 20px rgba(124,80,245,0.35);
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

    font-weight: 500;

    transition: 0.25s;
}

.sidebar a:hover {

    color: white;

    background:
        rgba(255,255,255,0.06);

    transform: translateX(2px);
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

    margin-bottom: 25px;
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


/* ================= PROFILE CARD ================= */

.profile-wrapper {

    display: flex;

    justify-content: center;
}

.profile-card {

    width: 100%;

    max-width: 700px;

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 18px;

    padding: 35px;

    box-shadow:
        0 8px 30px rgba(28,40,80,0.06);

    text-align: center;
}


/* ================= PROFILE IMAGE ================= */

.profile-image {

    width: 125px;

    height: 125px;

    border-radius: 50%;

    object-fit: cover;

    border: 5px solid #eee8ff;

    box-shadow:
        0 8px 25px rgba(90,66,235,0.20);
}


/* ================= NAME ================= */

.profile-card h2 {

    margin-top: 18px;

    font-size: 27px;

    color: #172033;
}

.role {

    margin-top: 6px;

    color: #713df1;

    font-size: 13px;

    font-weight: 600;
}


/* ================= INFO ================= */

.profile-info {

    margin-top: 30px;

    text-align: left;

    display: grid;

    gap: 12px;
}

.info-box {

    background: #f8f9fd;

    border:
        1px solid #edf0f6;

    border-radius: 10px;

    padding: 16px 18px;
}

.info-box strong {

    display: block;

    color: #7a8295;

    font-size: 11px;

    margin-bottom: 5px;
}

.info-box span {

    color: #172033;

    font-size: 14px;

    font-weight: 500;
}


/* ================= BACK BUTTON ================= */

.back-btn {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    margin-top: 25px;

    padding: 11px 18px;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #536df5,
            #7939ed
        );

    color: white;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

    transition: 0.25s;
}

.back-btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 8px 18px rgba(90,66,235,0.25);
}


/* ================= ADMIN BOTTOM ================= */

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

    width: 42px;

    height: 42px;

    min-width: 42px;

    border-radius: 50%;

    overflow: hidden;
}

.admin-avatar img {

    width: 42px;

    height: 42px;

    object-fit: cover;

    display: block;
}

.admin-info strong {

    display: block;

    color: white;

    font-size: 12px;
}

.admin-info span {

    display: block;

    color: #7f8aa2;

    font-size: 10px;

    margin-top: 2px;
}

.logout {

    color: #ff6b7d !important;

    padding: 10px 2px 0 !important;

    margin: 0 !important;

    font-size: 12px !important;
}

.logout:hover {

    transform: none !important;

    background: transparent !important;
}


/* ================= RESPONSIVE ================= */

@media (max-width: 700px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;
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


    <a href="registrations.php">

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


    <!-- ================= ADMIN ================= -->

    <div class="sidebar-bottom">


        <a href="profile.php"
           style="
           padding:0;
           margin:0;
           background:transparent;
           "
        >

            <div class="admin-profile">

                <div class="admin-avatar">

                    <img
                        src="anuj.jpg"
                        alt="Anuj Yadav"
                    >

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


        <a href="logout.php"
           class="logout"
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
                Admin Profile
            </h1>

            <p>
                Manage your EventHub administrator profile
            </p>

        </div>

    </header>


    <!-- ================= PROFILE ================= -->

    <div class="profile-wrapper">


        <div class="profile-card">


            <img
                src="anuj.jpg"
                alt="Anuj Yadav"
                class="profile-image"
            >


            <h2>
                Anuj Yadav
            </h2>


            <div class="role">
                Administrator
            </div>


            <div class="profile-info">


                <div class="info-box">

                    <strong>
                        NAME
                    </strong>

                    <span>
                        Anuj Yadav
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        ROLE
                    </strong>

                    <span>
                        Administrator
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        PORTAL
                    </strong>

                    <span>
                        EventHub - Online Event Management Portal
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        ACCOUNT TYPE
                    </strong>

                    <span>
                        Admin Account
                    </span>

                </div>


            </div>


            <a
                href="index.php"
                class="back-btn"
            >

                ← Back to Dashboard

            </a>


        </div>


    </div>


</main>


</body>

</html>