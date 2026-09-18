<?php

include "config.php";

/* ================= GET UPCOMING EVENTS ================= */

$sql = "SELECT * FROM events
        WHERE status = 'Active'
        AND date >= CURDATE()
        ORDER BY date ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EventHub | Online Event Management</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f7f5ff;
            color: #202033;
        }

        /* ================= NAVBAR ================= */

        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
            box-shadow: 0 3px 20px rgba(0,0,0,0.08);
        }

        .navbar {
            max-width: 1200px;
            margin: auto;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #24134f;
        }

        .logo span {
            color: #6d4df5;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #444;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #6d4df5;
        }

        .nav-btn {
            text-decoration: none;
            color: white;
            background: #5d43d8;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        /* ================= HERO ================= */

        .hero {
            min-height: 590px;
            position: relative;
            overflow: hidden;

            background:
                radial-gradient(circle at 15% 40%, #d72fbc 0%, transparent 28%),
                radial-gradient(circle at 85% 25%, #2464dd 0%, transparent 30%),
                linear-gradient(135deg, #3c087d, #5520c6 55%, #3025a9);

            color: white;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 700px;
            height: 700px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            left: -220px;
            top: -280px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border: 80px solid rgba(255,255,255,0.05);
            border-radius: 50%;
            right: -180px;
            bottom: -250px;
        }

        .hero-content {
            max-width: 1200px;
            margin: auto;
            padding: 110px 25px 80px;
            position: relative;
            z-index: 2;
        }

        .hero-small {
            color: #ddd1ff;
            font-size: 16px;
            font-style: italic;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .hero h1 {
            font-size: 58px;
            line-height: 1.08;
            max-width: 650px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #ffffff;
        }

        .hero-text {
            max-width: 580px;
            color: #e2ddf5;
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
        }

        .primary-btn {
            display: inline-block;
            text-decoration: none;
            background: white;
            color: #4c32c8;
            padding: 14px 25px;
            border-radius: 9px;
            font-weight: bold;
            transition: 0.3s;
        }

        .primary-btn:hover {
            transform: translateY(-3px);
        }

        .outline-btn {
            display: inline-block;
            text-decoration: none;
            color: white;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 13px 25px;
            border-radius: 9px;
            font-weight: bold;
            transition: 0.3s;
        }

        .outline-btn:hover {
            background: rgba(255,255,255,0.12);
        }

        /* ================= SEARCH BOX ================= */

        .search-box {
            max-width: 1000px;
            margin: -42px auto 0;
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.16);

            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr auto;
            gap: 8px;
        }

        .search-input {
            border: none;
            padding: 15px;
            outline: none;
            background: #fafafa;
            border-radius: 8px;
        }

        .search-button {
            border: none;
            background: #4d36c5;
            color: white;
            padding: 0 22px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }

        /* ================= EVENTS ================= */

        .events {
            padding: 100px 25px 80px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title p {
            color: #d04ca7;
            font-size: 14px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 8px;
        }

        .section-title h2 {
            font-size: 38px;
            color: #25243a;
        }

        .event-container {
            max-width: 1100px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .event-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(40,30,80,0.10);
            transition: 0.3s;
        }

        .event-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(40,30,80,0.16);
        }

        /* ================= EVENT IMAGE ================= */

        .event-image {
            height: 175px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 55px;
            color: white;

            background:
                linear-gradient(135deg, #6136d8, #d13bb4);

            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .event-card:nth-child(2) .event-image {
            background:
                linear-gradient(135deg, #ef7b35, #d43d91);
        }

        .event-card:nth-child(3) .event-image {
            background:
                linear-gradient(135deg, #1577d4, #692ed2);
        }

        .event-card:nth-child(4) .event-image {
            background:
                linear-gradient(135deg, #27b94c, #138d98);
        }

        .event-card:nth-child(5) .event-image {
            background:
                linear-gradient(135deg, #df416e, #7235cf);
        }

        .event-card:nth-child(6) .event-image {
            background:
                linear-gradient(135deg, #ed8a20, #c9328c);
        }

        .event-content {
            padding: 20px;
        }

        .event-content h3 {
            font-size: 18px;
            color: #25243a;
            margin-bottom: 10px;
            min-height: 42px;
        }

        .event-description {
            color: #777;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .event-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: #666;
            font-size: 12px;
            margin-bottom: 18px;
        }

        .event-info span {
            display: block;
        }

        .register-btn {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #4e36c8;
            border: 1px solid #4e36c8;
            padding: 10px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
            transition: 0.3s;
        }

        .register-btn:hover {
            background: #4e36c8;
            color: white;
        }

        .no-event {
            grid-column: 1 / -1;
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        /* ================= ABOUT ================= */

        .about {
            padding: 90px 25px;
            background: white;
        }

        .about-content {
            max-width: 900px;
            margin: auto;
            text-align: center;
        }

        .about-content p {
            color: #666;
            line-height: 1.9;
            font-size: 16px;
        }

        /* ================= CONTACT ================= */

        .contact {
            padding: 90px 25px;
            background: #f3f1fc;
        }

        .contact-wrapper {
            max-width: 1050px;
            margin: auto;

            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .contact-info {
            background: #201943;
            color: white;
            padding: 35px;
            border-radius: 16px;
        }

        .contact-info h3 {
            font-size: 26px;
            margin-bottom: 15px;
        }

        .contact-info > p {
            color: #ccc7df;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;

            background: #684de0;
            border-radius: 8px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-item h4 {
            margin-bottom: 4px;
        }

        .contact-item p {
            color: #ccc7df;
            font-size: 13px;
        }

        /* ================= CONTACT FORM ================= */

        .contact-form {
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }

        .contact-form h3 {
            font-size: 25px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            border: 1px solid #ddd;
            background: #fafafa;
            padding: 12px;
            border-radius: 8px;
            outline: none;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #674ddd;
        }

        .send-btn {
            width: 100%;
            border: none;
            padding: 13px;
            border-radius: 8px;
            background: #5941ce;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .send-btn:hover {
            background: #4630ad;
        }

        /* ================= FOOTER ================= */

        footer {
            background: #151226;
            color: #aaa;
            text-align: center;
            padding: 25px;
            font-size: 13px;
        }

        footer span {
            color: #8069ed;
            font-weight: bold;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 45px;
            }

            .event-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-box {
                grid-template-columns: 1fr 1fr;
                margin: -30px 25px 0;
            }

        }

        @media (max-width: 600px) {

            .navbar {
                padding: 0 15px;
            }

            .nav-btn {
                display: none;
            }

            .hero-content {
                padding: 80px 20px;
            }

            .hero h1 {
                font-size: 38px;
            }

            .hero-text {
                font-size: 14px;
            }

            .hero-buttons {
                flex-direction: column;
                width: 180px;
            }

            .search-box {
                grid-template-columns: 1fr;
                margin: -20px 15px 0;
            }

            .search-button {
                padding: 14px;
            }

            .events {
                padding-top: 70px;
            }

            .section-title h2 {
                font-size: 30px;
            }

            .event-container {
                grid-template-columns: 1fr;
            }

            .contact-wrapper {
                grid-template-columns: 1fr;
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
                <a href="#home">HOME</a>
            </li>

            <li>
                <a href="#events">EVENTS</a>
            </li>

            <li>
                <a href="#about">ABOUT</a>
            </li>

            <li>
                <a href="#contact">CONTACT</a>
            </li>

        </ul>

        <a href="#events" class="nav-btn">
            BUY TICKETS
        </a>

    </nav>

</header>


<!-- ================= HERO ================= -->

<section id="home" class="hero">

    <div class="hero-content">

        <p class="hero-small">
            Find Your Next Experience
        </p>

        <h1>
            Discover & Promote
            <br>
            <span>Upcoming Events</span>
        </h1>

        <p class="hero-text">
            Discover amazing events, connect with people
            and register for your favourite events easily
            with EventHub.
        </p>

        <div class="hero-buttons">

            <a href="#events" class="primary-btn">
                Explore Events
            </a>

            <a href="#about" class="outline-btn">
                Learn More
            </a>

        </div>

    </div>

</section>


<!-- ================= SEARCH ================= -->

<div class="search-box">

    <input
        type="text"
        id="eventSearch"
        class="search-input"
        placeholder="🔍 Search Event"
    >

    <input
        type="text"
        id="locationSearch"
        class="search-input"
        placeholder="📍 Search Location"
    >

    <select
        id="categorySearch"
        class="search-input"
    >

        <option value="">
            📋 Category
        </option>

        <option value="conference">
            Conference
        </option>

        <option value="workshop">
            Workshop
        </option>

        <option value="seminar">
            Seminar
        </option>

        <option value="cultural">
            Cultural Event
        </option>

    </select>

    <button
        class="search-button"
        onclick="searchEvents()"
    >
        🔍
    </button>

</div>


<!-- ================= EVENTS ================= -->

<section id="events" class="events">

    <div class="section-title">

        <p>UPCOMING EVENT</p>

        <h2>Featured Events</h2>

    </div>


    <div
        class="event-container"
        id="eventContainer"
    >


        <?php

        if ($result && $result->num_rows > 0) {

            while ($event = $result->fetch_assoc()) {

        ?>

            <div
                class="event-card"
                data-title="<?php echo htmlspecialchars($event['title']); ?>"
                data-location="<?php echo htmlspecialchars($event['location']); ?>"
            >

                <div class="event-image">

                    <img
    src="uploads/<?php echo htmlspecialchars($event['image']); ?>"
    alt="<?php echo htmlspecialchars($event['title']); ?>"
>

                </div>


                <div class="event-content">

                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $event['title']
                        );
                        ?>

                    </h3>


                    <p class="event-description">

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
                                strtotime($event['date'])
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


                    <a
                        href="register.php?event=<?php echo $event['id']; ?>"
                        class="register-btn"
                    >
                        REGISTER NOW
                    </a>

                </div>

            </div>


        <?php

            }

        } else {

        ?>

            <div class="no-event">

                <h3>
                    📅 No Upcoming Events
                </h3>

                <p style="margin-top:10px;color:#777;">
                    New events will appear here when
                    they are added by the administrator.
                </p>

            </div>

        <?php

        }

        ?>


    </div>

</section>


<!-- ================= ABOUT ================= -->

<section id="about" class="about">

    <div class="section-title">

        <p>ABOUT EVENTHUB</p>

        <h2>Everything You Need</h2>

    </div>


    <div class="about-content">

        <p>

            EventHub is an Online Event Management Portal
            designed to make event discovery, registration
            and management simple and convenient.

            Users can explore upcoming events and register
            online, while administrators can easily add,
            edit, delete and manage events through the
            admin dashboard.

        </p>

    </div>

</section>


<!-- ================= CONTACT ================= -->

<section id="contact" class="contact">

    <div class="section-title">

        <p>CONTACT US</p>

        <h2>Get In Touch</h2>

    </div>


    <div class="contact-wrapper">


        <!-- CONTACT INFORMATION -->

        <div class="contact-info">

            <h3>
                Let's Connect
            </h3>

            <p>
                Have a question about an event?
                Contact us and we will be happy to help.
            </p>


            <!-- EMAIL -->

            <div class="contact-item">

                <div class="contact-icon">
                    📧
                </div>

                <div>

                    <h4>
                        Email
                    </h4>

                    <p>
                        ay4460394@gmail.com
                    </p>

                </div>

            </div>


            <!-- PHONE -->

            <div class="contact-item">

                <div class="contact-icon">
                    📞
                </div>

                <div>

                    <h4>
                        Phone
                    </h4>

                    <p>
                        +91 6394159793
                    </p>

                </div>

            </div>


            <!-- LOCATION -->

            <div class="contact-item">

                <div class="contact-icon">
                    📍
                </div>

                <div>

                    <h4>
                        Location
                    </h4>

                    <p>
                        Maharishi University, Lucknow
                    </p>

                </div>

            </div>


        </div>


        <!-- CONTACT FORM -->

        <div class="contact-form">

            <h3>
                Send Us a Message
            </h3>


            <form
                onsubmit="sendMessage(event)"
            >

                <div class="form-group">

                    <label>
                        Your Name
                    </label>

                    <input
                        type="text"
                        id="contactName"
                        placeholder="Enter your name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="contactEmail"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Subject
                    </label>

                    <input
                        type="text"
                        id="contactSubject"
                        placeholder="Enter subject"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Message
                    </label>

                    <textarea
                        id="contactMessage"
                        placeholder="Write your message..."
                        required
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="send-btn"
                >
                    SEND MESSAGE
                </button>

            </form>

        </div>

    </div>

</section>


<!-- ================= FOOTER ================= -->

<footer>

    <p>
        © 2026
        <span>EventHub</span>.
        All Rights Reserved.
    </p>

    <p style="margin-top:8px;">
        Online Event Management Portal
    </p>

</footer>


<!-- ================= JAVASCRIPT ================= -->

<script>

/* ================= SEARCH EVENTS ================= */

function searchEvents() {

    let eventSearch =
        document.getElementById("eventSearch")
        .value
        .toLowerCase()
        .trim();

    let locationSearch =
        document.getElementById("locationSearch")
        .value
        .toLowerCase()
        .trim();

    let categorySearch =
        document.getElementById("categorySearch")
        .value
        .toLowerCase()
        .trim();

    let cards =
        document.querySelectorAll(".event-card");

    let found = false;

    cards.forEach(function(card) {

        let title =
            card
            .getAttribute("data-title")
            .toLowerCase();

        let location =
            card
            .getAttribute("data-location")
            .toLowerCase();

        let matchesEvent =
            title.includes(eventSearch);

        let matchesLocation =
            location.includes(locationSearch);

        let matchesCategory = true;

        if (categorySearch !== "") {

            matchesCategory =
                title.includes(categorySearch) ||
                card.innerText
                    .toLowerCase()
                    .includes(categorySearch);

        }

        if (
            matchesEvent &&
            matchesLocation &&
            matchesCategory
        ) {

            card.style.display = "block";

            found = true;

        } else {

            card.style.display = "none";

        }

    });


    let noEventMessage =
        document.getElementById("searchNoResult");


    if (!found && cards.length > 0) {

        if (!noEventMessage) {

            noEventMessage =
                document.createElement("div");

            noEventMessage.id =
                "searchNoResult";

            noEventMessage.className =
                "no-event";

            noEventMessage.innerHTML =
                "<h3>🔍 No Matching Events Found</h3>" +
                "<p style='margin-top:10px;color:#777;'>" +
                "Try another event name, location or category." +
                "</p>";

            document
                .getElementById("eventContainer")
                .appendChild(noEventMessage);

        }

        noEventMessage.style.display = "block";

    } else {

        if (noEventMessage) {

            noEventMessage.style.display =
                "none";

        }

    }

}


/* ================= CONTACT FORM ================= */

function sendMessage(event) {

    event.preventDefault();

    alert(
        "Thank you! Your message has been received."
    );

    event.target.reset();

}

</script>


</body>

</html>