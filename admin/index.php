<?php

session_start();

if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}

include "../config.php";

date_default_timezone_set("Asia/Kolkata");

$message = "";
$messageType = "";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


/* =====================================================
   DELETE EVENT
===================================================== */

if (isset($_GET['delete'])) {

    $deleteId = intval($_GET['delete']);

    if ($deleteId > 0) {

        /* Get image name first */

        $stmt = $conn->prepare(
            "SELECT image FROM events WHERE id = ?"
        );

        if ($stmt) {

            $stmt->bind_param("i", $deleteId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                $event = $result->fetch_assoc();

                $imageName = $event['image'];

                /* Delete event */

                $deleteStmt = $conn->prepare(
                    "DELETE FROM events WHERE id = ?"
                );

                if ($deleteStmt) {

                    $deleteStmt->bind_param("i", $deleteId);

                    if ($deleteStmt->execute()) {

                        /* Delete image from uploads folder */

                        if (!empty($imageName)) {

                            $imagePath =
                                "../uploads/" . $imageName;

                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        $message = "Event deleted successfully!";
                        $messageType = "success";

                    } else {

                        $message =
                            "Unable to delete event.";

                        $messageType = "error";
                    }

                    $deleteStmt->close();
                }

            } else {

                $message = "Event not found.";
                $messageType = "error";
            }

            $stmt->close();
        }
    }

    $page = "manage";
}


/* =====================================================
   ADD EVENT
===================================================== */

if (isset($_POST['add_event'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $location = trim($_POST['location']);
    $status = $_POST['status'];

    if (
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] != 0
    ) {

        $message = "Please select an event image.";
        $messageType = "error";
        $page = "add";

    } else {

        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        $extension = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        $allowed = array(
            "jpg",
            "jpeg",
            "png",
            "webp"
        );

        if (!in_array($extension, $allowed)) {

            $message =
                "Only JPG, JPEG, PNG and WEBP images are allowed.";

            $messageType = "error";
            $page = "add";

        } else {

            $imageName =
                time() . "_" . uniqid() . "." . $extension;

            $destination =
                $uploadDir . $imageName;

            if (move_uploaded_file($tmpName, $destination)) {

                $sql = "INSERT INTO events
                        (title, description, date, location, status, image)
                        VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                if ($stmt) {

                    $stmt->bind_param(
                        "ssssss",
                        $title,
                        $description,
                        $date,
                        $location,
                        $status,
                        $imageName
                    );

                    if ($stmt->execute()) {

                        $message =
                            "Event successfully added!";

                        $messageType = "success";
                        $page = "add";

                    } else {

                        $message =
                            "Database Error: " . $stmt->error;

                        $messageType = "error";
                        $page = "add";
                    }

                    $stmt->close();

                } else {

                    $message =
                        "Database Query Error: " . $conn->error;

                    $messageType = "error";
                    $page = "add";
                }

            } else {

                $message =
                    "Image could not be uploaded.";

                $messageType = "error";
                $page = "add";
            }
        }
    }
}


/* =====================================================
   EDIT EVENT
===================================================== */

if (isset($_POST['update_event'])) {

    $id = intval($_POST['id']);

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $location = trim($_POST['location']);
    $status = $_POST['status'];

    if ($id <= 0) {

        $message = "Invalid event ID.";
        $messageType = "error";
        $page = "manage";

    } else {

        /* Get old image */

        $oldImage = "";

        $getStmt = $conn->prepare(
            "SELECT image FROM events WHERE id = ?"
        );

        if ($getStmt) {

            $getStmt->bind_param("i", $id);
            $getStmt->execute();

            $getResult = $getStmt->get_result();

            if ($getResult->num_rows > 0) {

                $oldEvent = $getResult->fetch_assoc();

                $oldImage = $oldEvent['image'];

            } else {

                $message = "Event not found.";
                $messageType = "error";
            }

            $getStmt->close();
        }


        /* Check whether new image is selected */

        $newImageName = $oldImage;

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] == 0
        ) {

            $originalName =
                $_FILES['image']['name'];

            $tmpName =
                $_FILES['image']['tmp_name'];

            $extension =
                strtolower(
                    pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    )
                );

            $allowed = array(
                "jpg",
                "jpeg",
                "png",
                "webp"
            );

            if (!in_array($extension, $allowed)) {

                $message =
                    "Only JPG, JPEG, PNG and WEBP images are allowed.";

                $messageType = "error";
                $page = "edit";

            } else {

                $uploadDir = "../uploads/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newImageName =
                    time() . "_" .
                    uniqid() .
                    "." .
                    $extension;

                $destination =
                    $uploadDir . $newImageName;

                if (
                    !move_uploaded_file(
                        $tmpName,
                        $destination
                    )
                ) {

                    $message =
                        "New image could not be uploaded.";

                    $messageType = "error";
                    $page = "edit";
                }
            }
        }


        /* Update database */

        if ($message == "") {

            $sql = "UPDATE events
                    SET title = ?,
                        description = ?,
                        date = ?,
                        location = ?,
                        status = ?,
                        image = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            if ($stmt) {

                $stmt->bind_param(
                    "ssssssi",
                    $title,
                    $description,
                    $date,
                    $location,
                    $status,
                    $newImageName,
                    $id
                );

                if ($stmt->execute()) {

                    /* Delete old image if new image uploaded */

                    if (
                        $newImageName != $oldImage &&
                        !empty($oldImage)
                    ) {

                        $oldImagePath =
                            "../uploads/" . $oldImage;

                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $message =
                        "Event updated successfully!";

                    $messageType = "success";
                    $page = "manage";

                } else {

                    $message =
                        "Database Error: " .
                        $stmt->error;

                    $messageType = "error";
                    $page = "edit";
                }

                $stmt->close();

            } else {

                $message =
                    "Database Query Error: " .
                    $conn->error;

                $messageType = "error";
                $page = "edit";
            }
        }
    }
}


/* =====================================================
   GET EDIT EVENT
===================================================== */

$editEvent = null;

if ($page == "edit" && isset($_GET['id'])) {

    $editId = intval($_GET['id']);

    if ($editId > 0) {

        $stmt = $conn->prepare(
            "SELECT * FROM events WHERE id = ?"
        );

        if ($stmt) {

            $stmt->bind_param("i", $editId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                $editEvent =
                    $result->fetch_assoc();

            } else {

                $message = "Event not found.";
                $messageType = "error";
                $page = "manage";
            }

            $stmt->close();
        }
    }
}


/* =====================================================
   DASHBOARD COUNTS
===================================================== */

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM registrations"
);

$row = $result->fetch_assoc();

$totalRegistrations = $row['total'];


$result = $conn->query(
    "SELECT COUNT(*) AS total FROM events"
);

$row = $result->fetch_assoc();

$totalEvents = $row['total'];


$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM events
     WHERE status = 'Active'"
);

$row = $result->fetch_assoc();

$activeEvents = $row['total'];


$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM events
     WHERE status = 'Active'
     AND date >= CURDATE()"
);

$row = $result->fetch_assoc();

$upcomingEvents = $row['total'];


if ($totalEvents > 0) {

    $activePercentage =
        round(($activeEvents / $totalEvents) * 100);

    $upcomingPercentage =
        round(($upcomingEvents / $totalEvents) * 100);

} else {

    $activePercentage = 0;
    $upcomingPercentage = 0;
}


$currentDate = date("l, d F Y");
$currentTime = date("h:i A");


/* =====================================================
   EVENTS
===================================================== */

$eventsResult = $conn->query(
    "SELECT * FROM events ORDER BY date ASC"
);


/* =====================================================
   REGISTRATIONS
===================================================== */

$registrationsResult = $conn->query(
    "SELECT * FROM registrations ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>EventHub | Admin Dashboard</title>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", Arial, sans-serif;
}

body {
    background: white;
    color: #172033;
}


/* =====================================================
   SIDEBAR
===================================================== */

.sidebar {

    position: fixed;

    left: 0;
    top: 0;

    width: 245px;
    height: 100vh;

    background: linear-gradient(
        180deg,
        #10172d,
        #121a32,
        #0e162b
    );

    color: white;

    padding: 25px 15px;

    box-shadow:
        8px 0 30px rgba(18,25,55,0.08);

    z-index: 1000;
}


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


.menu-title {

    color: #78839b;

    font-size: 10px;

    font-weight: bold;

    letter-spacing: 1.5px;

    padding: 0 12px;

    margin-bottom: 10px;
}


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
        rgba(255,255,255,0.07);
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


/* =====================================================
   PROFILE
===================================================== */

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

    border-radius: 50%;

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

    background: transparent !important;

    font-size: 12px !important;
}


/* =====================================================
   MAIN
===================================================== */

.main {

    margin-left: 245px;

    min-height: 100vh;

    padding: 0 28px 35px;
}


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


.notification {

    position: relative;

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: white;

    border: 1px solid #edf0f6;
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
}


/* =====================================================
   PAGE TITLE
===================================================== */

.page-title {

    background:
        linear-gradient(
            105deg,
            #ffffff,
            #f8f9ff,
            #edf2ff
        );

    border: 1px solid #edf0f8;

    border-radius: 16px;

    padding: 24px 28px;

    margin-bottom: 20px;

    box-shadow:
        0 6px 25px rgba(33,42,80,0.055);
}


.page-title h1 {

    font-size: 25px;

    color: #172033;

    margin-bottom: 6px;
}


.page-title p {

    color: #7c8498;

    font-size: 13px;
}


/* =====================================================
   DASHBOARD
===================================================== */

.welcome {

    min-height: 118px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 25px 28px;

    margin-bottom: 20px;

    border-radius: 16px;

    background:
        linear-gradient(
            105deg,
            #ffffff,
            #f8f9ff,
            #edf2ff
        );

    border: 1px solid #edf0f8;

    box-shadow:
        0 6px 25px rgba(33,42,80,0.055);
}


.welcome h1 {

    font-size: 25px;

    margin-bottom: 7px;
}


.welcome p {

    color: #7c8498;

    font-size: 13px;
}


.welcome-art {

    font-size: 64px;
}


/* =====================================================
   CARDS
===================================================== */

.cards {

    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 17px;

    margin-bottom: 20px;
}


.card-link {

    text-decoration: none;

    color: inherit;
}


.card {

    min-height: 148px;

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.055);

    transition: 0.25s;
}


.card:hover {

    transform: translateY(-4px);
}


.card-top {

    display: flex;

    justify-content: space-between;
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
}


.card h3 {

    color: #727b90;

    font-size: 12px;

    margin-top: 15px;
}


.card h2 {

    color: #172033;

    font-size: 28px;

    margin-top: 5px;
}


.card-bottom {

    margin-top: 8px;

    font-size: 10px;
}


.up {

    color: #45ad71;

    font-weight: 600;
}


/* =====================================================
   PANELS
===================================================== */

.dashboard-grid {

    display: grid;

    grid-template-columns:
        1.4fr 0.9fr;

    gap: 18px;

    margin-bottom: 18px;
}


.panel {

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;

    box-shadow:
        0 5px 20px rgba(28,40,80,0.05);
}


.panel-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 18px;
}


.panel-header h2 {

    font-size: 16px;
}


/* =====================================================
   CHART
===================================================== */

.chart {

    height: 205px;
}


.chart-area {

    width: 100%;
    height: 180px;
}


.chart-area svg {

    width: 100%;
    height: 100%;
}


/* =====================================================
   STATUS
===================================================== */

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
            <?php echo $activePercentage * 3.6; ?>deg,

            #f5a623
            <?php echo $activePercentage * 3.6; ?>deg
            360deg
        );

    display: flex;

    align-items: center;
    justify-content: center;

    position: relative;
}


.donut::after {

    content: "";

    position: absolute;

    width: 92px;
    height: 92px;

    border-radius: 50%;

    background: white;
}


.donut-center {

    position: relative;

    z-index: 2;

    text-align: center;
}


.donut-center strong {

    display: block;

    font-size: 22px;
}


.donut-center span {

    font-size: 10px;

    color: #8c94a5;
}


.status-item {

    display: flex;

    justify-content: space-between;

    gap: 15px;

    margin: 16px 0;

    font-size: 11px;
}


.status-name {

    display: flex;

    gap: 8px;

    align-items: center;
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


/* =====================================================
   QUICK ACTIONS
===================================================== */

.quick-panel {

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 15px;

    padding: 20px;
}


.quick-header {

    margin-bottom: 15px;
}


.quick-actions {

    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 14px;
}


.quick-action {

    display: flex;

    align-items: center;

    gap: 12px;

    text-decoration: none;

    padding: 13px;

    border-radius: 10px;

    color: #172033;
}


.quick-icon {

    width: 39px;
    height: 39px;

    border-radius: 9px;

    display: flex;

    align-items: center;
    justify-content: center;
}


.quick-purple {
    background: #f3edff;
}

.quick-blue {
    background: #edf7ff;
}

.quick-green {
    background: #eefaf2;
}

.quick-orange {
    background: #fff7e9;
}


.quick-action strong {

    display: block;

    font-size: 12px;
}


.quick-action span {

    display: block;

    font-size: 9px;

    color: #8a92a4;
}


/* =====================================================
   FORM
===================================================== */

.form-card {

    max-width: 850px;

    margin: auto;

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 16px;

    padding: 30px;

    box-shadow:
        0 6px 25px rgba(28,40,80,0.055);
}


.form-grid {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 18px;
}


.form-group {

    margin-bottom: 18px;
}


.form-group.full {

    grid-column: 1 / -1;
}


.form-group label {

    display: block;

    margin-bottom: 7px;

    color: #30394d;

    font-size: 13px;

    font-weight: 600;
}


.form-group input,
.form-group textarea,
.form-group select {

    width: 100%;

    padding: 13px 14px;

    border:
        1px solid #e1e5ee;

    border-radius: 9px;

    outline: none;

    background: #fafbfe;

    color: #172033;

    font-size: 13px;
}


.form-group textarea {

    min-height: 120px;

    resize: vertical;
}


.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {

    border-color: #6543df;

    background: white;

    box-shadow:
        0 0 0 3px rgba(101,67,223,0.08);
}


.image-note {

    margin-top: 7px;

    color: #8a92a4;

    font-size: 11px;
}


.btn {

    width: 100%;

    border: none;

    padding: 14px;

    background:
        linear-gradient(
            135deg,
            #6043df,
            #7939ed
        );

    color: white;

    border-radius: 9px;

    font-size: 14px;

    font-weight: 700;

    cursor: pointer;
}


.btn:hover {

    opacity: 0.92;
}


.back {

    display: inline-block;

    margin-top: 18px;

    text-decoration: none;

    color: #6043df;

    font-size: 12px;

    font-weight: 600;
}


/* =====================================================
   MESSAGE
===================================================== */

.message {

    padding: 13px 16px;

    border-radius: 9px;

    margin-bottom: 20px;

    font-size: 13px;

    font-weight: 600;
}


.success {

    background: #eaf9f0;

    color: #178447;

    border: 1px solid #c8efd8;
}


.error {

    background: #fff0f1;

    color: #d33b4a;

    border: 1px solid #ffd1d5;
}


/* =====================================================
   TABLE
===================================================== */

.table-card {

    background: white;

    border:
        1px solid #edf0f6;

    border-radius: 16px;

    padding: 20px;

    overflow-x: auto;
}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 850px;
}


th {

    text-align: left;

    background: #f6f7fb;

    color: #687188;

    font-size: 12px;

    padding: 13px;
}


td {

    padding: 13px;

    border-bottom:
        1px solid #edf0f5;

    font-size: 12px;

    color: #40495c;
}


.event-image {

    width: 55px;

    height: 45px;

    object-fit: cover;

    border-radius: 7px;
}


.status-active {

    color: #16894a;

    background: #e9f9ef;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;
}


.status-inactive {

    color: #c0392b;

    background: #fff0ee;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.action-buttons {

    display: flex;

    gap: 7px;

    align-items: center;
}


.edit-btn {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    text-decoration: none;

    background: #eee9ff;

    color: #6241df;

    border: 1px solid #ddd2ff;

    padding: 7px 11px;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 600;

    transition: 0.2s;
}


.edit-btn:hover {

    background: #6241df;

    color: white;
}


.delete-btn {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    text-decoration: none;

    background: #fff0f1;

    color: #d9364b;

    border: 1px solid #ffd5da;

    padding: 7px 11px;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 600;

    transition: 0.2s;
}


.delete-btn:hover {

    background: #d9364b;

    color: white;
}


/* =====================================================
   CURRENT IMAGE
===================================================== */

.current-image {

    margin-top: 10px;

    display: flex;

    align-items: center;

    gap: 12px;
}


.current-image img {

    width: 100px;

    height: 75px;

    object-fit: cover;

    border-radius: 8px;

    border: 1px solid #e1e5ee;
}


.current-image span {

    font-size: 11px;

    color: #7c8498;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:1200px) {

    .cards {
        grid-template-columns: repeat(2,1fr);
    }

    .quick-actions {
        grid-template-columns: repeat(2,1fr);
    }
}


@media(max-width:950px) {

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


@media(max-width:700px) {

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

        padding: 15px;
    }

    .cards {
        grid-template-columns: 1fr;
    }

    .quick-actions {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

    .welcome-art {
        display: none;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

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


    <!-- DASHBOARD -->

    <a href="index.php?page=dashboard"
       class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">

        <span class="nav-icon">⌂</span>

        Dashboard

    </a>


    <!-- MANAGE EVENTS -->

    <a href="index.php?page=manage"
       class="<?php echo ($page == 'manage' || $page == 'edit') ? 'active' : ''; ?>">

        <span class="nav-icon">▣</span>

        Manage Events

    </a>


    <!-- ADD EVENT -->

    <a href="index.php?page=add"
       class="<?php echo ($page == 'add') ? 'active' : ''; ?>">

        <span class="nav-icon">⊕</span>

        Add Event

    </a>


    <!-- REGISTRATIONS -->

    <a href="index.php?page=registrations"
       class="<?php echo ($page == 'registrations') ? 'active' : ''; ?>">

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


    <!-- PROFILE -->

    <div class="sidebar-bottom">

        <a href="profile.php"
           style="display:block; padding:0; margin:0;">

            <div class="admin-profile">

                <div class="admin-avatar">

                    <img
                        src="anuj.jpg"
                        alt="Anuj Yadav">

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
           class="logout">

            <span class="nav-icon">
                ↪
            </span>

            Logout

        </a>

    </div>

</aside>


<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<main class="main">


    <!-- HEADER -->

    <header class="top-header">

        <div class="header-left">

            <div class="menu-toggle">
                ☰
            </div>

            <div class="date-info">

                <?php echo $currentDate; ?>

                &nbsp; | &nbsp;

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


    <!-- =================================================
         DASHBOARD
    ================================================= -->

    <?php if ($page == 'dashboard') { ?>


    <section class="welcome">

        <div>

            <h1>
                Dashboard
            </h1>

            <p>
                Welcome to your EventHub Admin Panel.
            </p>

        </div>

        <div class="welcome-art">
            📅
        </div>

    </section>


    <section class="cards">


        <a href="index.php?page=manage"
           class="card-link">

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

                    <span>
                        vs last month
                    </span>

                </div>

            </div>

        </a>


        <a href="index.php?page=registrations"
           class="card-link">

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

                    <span>
                        vs last month
                    </span>

                </div>

            </div>

        </a>


        <a href="index.php?page=manage"
           class="card-link">

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

                    <span>
                        vs last month
                    </span>

                </div>

            </div>

        </a>


        <a href="index.php?page=manage"
           class="card-link">

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

                    <span>
                        vs last month
                    </span>

                </div>

            </div>

        </a>

    </section>


    <section class="dashboard-grid">


        <!-- REGISTRATION CHART -->

        <div class="panel">

            <div class="panel-header">

                <h2>
                    Registrations Overview
                </h2>

            </div>


            <div class="chart">

                <div class="chart-area">

                    <svg
                        viewBox="0 0 700 180"
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
                            C70 125,100 125,145 105
                            S220 90,260 75
                            S330 10,380 35
                            S450 95,500 80
                            S570 115,620 90
                            S670 65,700 55
                            L700 180
                            L0 180 Z"
                            fill="url(#chartGradient)"
                        />


                        <path
                            d="
                            M0 150
                            C70 125,100 125,145 105
                            S220 90,260 75
                            S330 10,380 35
                            S450 95,500 80
                            S570 115,620 90
                            S670 65,700 55"
                            fill="none"
                            stroke="#713ce6"
                            stroke-width="3"
                        />

                    </svg>

                </div>

            </div>


            <div style="
                color:#7c8498;
                font-size:11px;
                padding-top:8px;
            ">

                Total registrations:

                <strong style="color:#6841df;">

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


                <div>

                    <div class="status-item">

                        <div class="status-name">

                            <span class="status-dot status-green">
                            </span>

                            Active Events

                        </div>

                        <strong>

                            <?php echo $activeEvents; ?>

                            (<?php echo $activePercentage; ?>%)

                        </strong>

                    </div>


                    <div class="status-item">

                        <div class="status-name">

                            <span class="status-dot status-orange">
                            </span>

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


    <!-- QUICK ACTIONS -->

    <section class="quick-panel">

        <div class="quick-header">

            <h2>
                Quick Actions
            </h2>

        </div>


        <div class="quick-actions">


            <a href="index.php?page=add"
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


            <a href="index.php?page=manage"
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


            <a href="index.php?page=registrations"
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


    <?php } ?>


    <!-- =================================================
         ADD EVENT
    ================================================= -->

    <?php if ($page == 'add') { ?>


    <section class="page-title">

        <h1>
            ➕ Add New Event
        </h1>

        <p>
            Create and publish a new event on EventHub.
        </p>

    </section>


    <div class="form-card">


        <?php if ($message != "") { ?>

        <div class="message <?php echo $messageType; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

        <?php } ?>


        <form method="POST"
              enctype="multipart/form-data">


            <div class="form-grid">


                <div class="form-group full">

                    <label>
                        🎯 Event Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter event title"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label>
                        📝 Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Enter event description"
                        required
                    ></textarea>

                </div>


                <div class="form-group">

                    <label>
                        📅 Event Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        min="<?php echo date('Y-m-d'); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        📍 Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Enter event location"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        🟢 Status
                    </label>

                    <select name="status" required>

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        🖼️ Event Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >

                    <p class="image-note">
                        JPG, JPEG, PNG and WEBP images allowed hain.
                    </p>

                </div>


                <div class="form-group full">

                    <button
                        type="submit"
                        name="add_event"
                        class="btn">

                        ➕ ADD EVENT

                    </button>

                </div>


            </div>

        </form>


        <a href="index.php?page=dashboard"
           class="back">

            ← Back to Dashboard

        </a>

    </div>


    <?php } ?>


    <!-- =================================================
         EDIT EVENT
    ================================================= -->

    <?php if ($page == 'edit' && $editEvent) { ?>


    <section class="page-title">

        <h1>
            ✏️ Edit Event
        </h1>

        <p>
            Update your event details.
        </p>

    </section>


    <div class="form-card">


        <?php if ($message != "") { ?>

        <div class="message <?php echo $messageType; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

        <?php } ?>


        <form method="POST"
              enctype="multipart/form-data">


            <input
                type="hidden"
                name="id"
                value="<?php echo $editEvent['id']; ?>"
            >


            <div class="form-grid">


                <div class="form-group full">

                    <label>
                        🎯 Event Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="<?php echo htmlspecialchars($editEvent['title']); ?>"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label>
                        📝 Description
                    </label>

                    <textarea
                        name="description"
                        required
                    ><?php echo htmlspecialchars($editEvent['description']); ?></textarea>

                </div>


                <div class="form-group">

                    <label>
                        📅 Event Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="<?php echo htmlspecialchars($editEvent['date']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        📍 Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        value="<?php echo htmlspecialchars($editEvent['location']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        🟢 Status
                    </label>

                    <select name="status" required>

                        <option
                            value="Active"
                            <?php
                            echo ($editEvent['status'] == 'Active')
                                ? 'selected'
                                : '';
                            ?>
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            <?php
                            echo ($editEvent['status'] == 'Inactive')
                                ? 'selected'
                                : '';
                            ?>
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        🖼️ Change Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <p class="image-note">
                        New image select nahi karoge to old image rahegi.
                    </p>

                </div>


                <?php if (!empty($editEvent['image'])) { ?>


                <div class="form-group full">

                    <label>
                        Current Image
                    </label>


                    <div class="current-image">

                        <img
                            src="../uploads/<?php echo htmlspecialchars($editEvent['image']); ?>"
                            alt="Current Event Image"
                        >

                        <span>
                            Current event image
                        </span>

                    </div>

                </div>


                <?php } ?>


                <div class="form-group full">

                    <button
                        type="submit"
                        name="update_event"
                        class="btn">

                        💾 UPDATE EVENT

                    </button>

                </div>


            </div>

        </form>


        <a href="index.php?page=manage"
           class="back">

            ← Back to Manage Events

        </a>

    </div>


    <?php } ?>


    <!-- =================================================
         MANAGE EVENTS
    ================================================= -->

    <?php if ($page == 'manage') { ?>


    <section class="page-title">

        <h1>
            📅 Manage Events
        </h1>

        <p>
            View, edit and delete all events available on EventHub.
        </p>

    </section>


    <?php if ($message != "") { ?>

    <div class="message <?php echo $messageType; ?>">

        <?php echo htmlspecialchars($message); ?>

    </div>

    <?php } ?>


    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Image</th>

                    <th>Event Title</th>

                    <th>Date</th>

                    <th>Location</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>


            <?php

            if (
                $eventsResult &&
                $eventsResult->num_rows > 0
            ) {

                while (
                    $event =
                    $eventsResult->fetch_assoc()
                ) {

            ?>


                <tr>


                    <td>
                        <?php echo $event['id']; ?>
                    </td>


                    <td>


                        <?php if (!empty($event['image'])) { ?>


                            <img
                                src="../uploads/<?php echo htmlspecialchars($event['image']); ?>"
                                class="event-image"
                                alt="Event Image"
                            >


                        <?php } else { ?>


                            No Image


                        <?php } ?>


                    </td>


                    <td>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $event['title']
                            );
                            ?>
                        </strong>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $event['date']
                        );
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $event['location']
                        );
                        ?>

                    </td>


                    <td>


                        <?php if ($event['status'] == 'Active') { ?>


                            <span class="status-active">
                                Active
                            </span>


                        <?php } else { ?>


                            <span class="status-inactive">
                                Inactive
                            </span>


                        <?php } ?>


                    </td>


                    <!-- ACTIONS -->

                    <td>


                        <div class="action-buttons">


                            <!-- EDIT -->

                            <a
                                href="index.php?page=edit&id=<?php echo $event['id']; ?>"
                                class="edit-btn"
                            >

                                ✏️ Edit

                            </a>


                            <!-- DELETE -->

                            <a
                                href="index.php?page=manage&delete=<?php echo $event['id']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this event?');"
                            >

                                🗑️ Delete

                            </a>


                        </div>


                    </td>


                </tr>


            <?php

                }

            } else {

            ?>


                <tr>

                    <td
                        colspan="7"
                        style="text-align:center;padding:30px;">

                        No events found.

                    </td>

                </tr>


            <?php } ?>


            </tbody>

        </table>

    </div>


    <?php } ?>


    <!-- =================================================
         REGISTRATIONS
    ================================================= -->

    <?php if ($page == 'registrations') { ?>


    <section class="page-title">

        <h1>
            👥 Registrations
        </h1>

        <p>
            View all event registrations.
        </p>

    </section>


    <div class="table-card">

        <table>

            <thead>

                <tr>


                <?php

                if ($registrationsResult) {

                    $fields =
                        $registrationsResult->fetch_fields();

                    foreach ($fields as $field) {

                ?>


                    <th>

                        <?php

                        echo htmlspecialchars(
                            $field->name
                        );

                        ?>

                    </th>


                <?php

                    }

                }

                ?>


                </tr>

            </thead>


            <tbody>


            <?php

            if (
                $registrationsResult &&
                $registrationsResult->num_rows > 0
            ) {

                while (
                    $registration =
                    $registrationsResult->fetch_assoc()
                ) {

            ?>


                <tr>


                <?php

                    foreach ($registration as $value) {

                ?>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $value
                        );

                        ?>

                    </td>


                <?php

                    }

                ?>


                </tr>


            <?php

                }

            } else {

            ?>


                <tr>

                    <td
                        colspan="10"
                        style="text-align:center;padding:30px;">

                        No registrations found.

                    </td>

                </tr>


            <?php } ?>


            </tbody>

        </table>

    </div>


    <?php } ?>


</main>


</body>

</html>