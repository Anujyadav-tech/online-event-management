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


/* ================= GET EVENTS ================= */

$sql = "SELECT * FROM events ORDER BY date ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Events | EventHub</title>

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

    font-size: 22px;

    box-shadow:
        0 8px 20px rgba(124,80,245,0.35);
}

.logo-text h2 {

    font-size: 22px;

    line-height: 22px;

    font-weight: 700;
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

    transition: 0.25s;
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


/* ================= TOP HEADER ================= */

.top-header {

    height: 70px;

    display: flex;

    align-items: center;

    justify-content: space-between;

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


/* ================= ADD BUTTON ================= */

.add-btn {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    text-decoration: none;

    color: white;

    background:
        linear-gradient(
            135deg,
            #536df5,
            #7939ed
        );

    padding: 12px 18px;

    border-radius: 9px;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 18px;

    box-shadow:
        0 7px 18px rgba(90,66,235,0.22);

    transition: 0.25s;
}

.add-btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 10px 22px rgba(90,66,235,0.30);
}


/* ================= TABLE BOX ================= */

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


/* ================= TABLE ================= */

table {

    width: 100%;

    border-collapse: collapse;

    min-width: 950px;
}

thead th {

    background: #111827;

    color: white;

    padding: 14px;

    text-align: left;

    font-size: 12px;

    font-weight: 600;
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

tbody tr {

    transition: 0.2s;
}

tbody tr:hover {

    background: #f8f9ff;
}


/* ================= ID ================= */

.event-id {

    color: #6841df;

    font-weight: 700;
}


/* ================= TITLE ================= */

.event-title {

    color: #172033;

    font-weight: 600;
}


/* ================= DESCRIPTION ================= */

.description {

    max-width: 250px;

    color: #7b8498;

    line-height: 1.5;
}


/* ================= DATE ================= */

.event-date {

    white-space: nowrap;

    color: #4b5563;

    font-weight: 500;
}


/* ================= LOCATION ================= */

.location {

    max-width: 180px;

    color: #596274;
}


/* ================= IMAGE ================= */

.event-image {

    width: 60px;

    height: 45px;

    object-fit: cover;

    border-radius: 7px;

    border: 1px solid #e5e7eb;
}


/* ================= STATUS ================= */

.status {

    display: inline-block;

    padding: 6px 11px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 700;
}

.active {

    background: #dcfce7;

    color: #166534;
}

.inactive {

    background: #fee2e2;

    color: #991b1b;
}


/* ================= ACTION ================= */

.action {

    white-space: nowrap;
}

.edit-btn,
.delete-btn {

    display: inline-block;

    padding: 7px 10px;

    border-radius: 7px;

    text-decoration: none;

    font-size: 11px;

    font-weight: 600;

    margin-right: 4px;
}

.edit-btn {

    background: #e5edff;

    color: #2563eb;
}

.edit-btn:hover {

    background: #2563eb;

    color: white;
}

.delete-btn {

    background: #fee2e2;

    color: #dc2626;
}

.delete-btn:hover {

    background: #dc2626;

    color: white;
}


/* ================= NO DATA ================= */

.no-data {

    text-align: center;

    padding: 50px 20px !important;

    color: #8a92a5 !important;

    font-size: 14px !important;
}


/* ================= FOOTER ================= */

.footer {

    text-align: center;

    color: #a0a7b7;

    font-size: 10px;

    padding-top: 25px;
}


/* ================= RESPONSIVE ================= */

@media (max-width: 850px) {

    .sidebar {

        width: 210px;
    }

    .main {

        margin-left: 210px;

        padding-left: 20px;

        padding-right: 20px;
    }

}


@media (max-width: 650px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;
    }

    .main {

        margin-left: 0;

        padding: 20px 15px;
    }

    .top-header {

        height: auto;

        padding: 15px 0;

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


    <a href="manage_event.php" class="active">

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


    <!-- ADMIN PROFILE -->

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
                Manage Events
            </h1>

            <p>
                View, edit and manage all events
            </p>

        </div>

    </header>


    <!-- ADD EVENT -->

    <a href="add_event.php"
       class="add-btn">

        ➕ Add New Event

    </a>


    <!-- TABLE -->

    <div class="table-box">


        <?php if ($result && $result->num_rows > 0) { ?>


        <table>

            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Image
                    </th>

                    <th>
                        Event Title
                    </th>

                    <th>
                        Date
                    </th>

                    <th>
                        Location
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php while ($event = $result->fetch_assoc()) { ?>


                <tr>


                    <!-- ID -->

                    <td class="event-id">

                        <?php
                        echo htmlspecialchars($event['id']);
                        ?>

                    </td>


                    <!-- IMAGE -->

                    <td>

                        <?php

                        if (
                            isset($event['image']) &&
                            !empty($event['image'])
                        ) {

                        ?>

                            <img
                                src="../uploads/<?php
                                    echo htmlspecialchars($event['image']);
                                ?>"
                                alt="Event Image"
                                class="event-image"
                            >

                        <?php

                        } else {

                        ?>

                            <span style="
                                color:#9ca3af;
                                font-size:11px;
                            ">
                                No Image
                            </span>

                        <?php

                        }

                        ?>

                    </td>


                    <!-- TITLE -->

                    <td class="event-title">

                        <?php
                        echo htmlspecialchars($event['title']);
                        ?>

                    </td>


                    <!-- DATE -->

                    <td class="event-date">

                        <?php
                        echo htmlspecialchars($event['date']);
                        ?>

                    </td>


                    <!-- LOCATION -->

                    <td class="location">

                        <?php
                        echo htmlspecialchars($event['location']);
                        ?>

                    </td>


                    <!-- DESCRIPTION -->

                    <td class="description">

                        <?php
                        echo htmlspecialchars($event['description']);
                        ?>

                    </td>


                    <!-- STATUS -->

                    <td>

                        <?php

                        if (
                            isset($event['status']) &&
                            $event['status'] == 'Active'
                        ) {

                        ?>

                            <span class="status active">
                                Active
                            </span>

                        <?php

                        } else {

                        ?>

                            <span class="status inactive">
                                Inactive
                            </span>

                        <?php

                        }

                        ?>

                    </td>


                    <!-- ACTION -->

                    <td class="action">


                        <a
                            href="edit_event.php?id=<?php
                                echo urlencode($event['id']);
                            ?>"
                            class="edit-btn"
                        >
                            ✏️ Edit
                        </a>


                        <a
                            href="delete_event.php?id=<?php
                                echo urlencode($event['id']);
                            ?>"
                            class="delete-btn"
                            onclick="return confirm(
                                'Are you sure you want to delete this event?'
                            );"
                        >
                            🗑️ Delete
                        </a>


                    </td>


                </tr>


            <?php } ?>


            </tbody>

        </table>


        <?php } else { ?>


            <div class="no-data">

                📅

                <br><br>

                <strong>
                    No Events Found
                </strong>

                <br><br>

                Add a new event to see it here.


            </div>


        <?php } ?>


    </div>


    <div class="footer">

        EventHub Admin Panel

    </div>


</main>


</body>

</html>