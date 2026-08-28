<?php

session_start();

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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>EventHub | Admin Profile</title>

<style>

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

.profile-page {
    min-height: 100vh;
    padding: 40px;
}

.back-btn {
    display: inline-block;
    text-decoration: none;
    color: white;
    background: linear-gradient(135deg, #536df5, #7939ed);
    padding: 11px 20px;
    border-radius: 9px;
    margin-bottom: 25px;
    font-size: 14px;
}

.profile-card {
    max-width: 650px;
    margin: 20px auto;
    background: white;
    border-radius: 18px;
    padding: 35px;
    text-align: center;
    box-shadow: 0 10px 35px rgba(30,40,80,0.10);
    border: 1px solid #edf0f6;
}

.profile-image {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #eee8ff;
    box-shadow: 0 8px 25px rgba(90,66,235,0.20);
}

.profile-card h1 {
    margin-top: 20px;
    font-size: 28px;
    color: #172033;
}

.profile-card .role {
    margin-top: 7px;
    color: #713df1;
    font-size: 14px;
    font-weight: 600;
}

.profile-info {
    margin-top: 30px;
    text-align: left;
}

.info-box {
    background: #f8f9fd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 12px;
}

.info-box strong {
    display: block;
    color: #6f778b;
    font-size: 12px;
    margin-bottom: 5px;
}

.info-box span {
    color: #172033;
    font-size: 14px;
}

</style>

</head>

<body>

<div class="profile-page">

    <a href="index.php" class="back-btn">
        ← Back to Dashboard
    </a>

    <div class="profile-card">

        <!-- PROFILE IMAGE -->
        <img
            src="anuj.jpg"
            alt="Anuj Yadav"
            class="profile-image"
        >

        <h1>
            Anuj Yadav
        </h1>

        <div class="role">
            Administrator
        </div>

        <div class="profile-info">

            <div class="info-box">
                <strong>Name</strong>
                <span>Anuj Yadav</span>
            </div>

            <div class="info-box">
                <strong>Role</strong>
                <span>Administrator</span>
            </div>

            <div class="info-box">
                <strong>Portal</strong>
                <span>EventHub - Online Event Management Portal</span>
            </div>

        </div>

    </div>

</div>

</body>

</html>