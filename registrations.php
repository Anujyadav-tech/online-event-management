<?php
include "../config.php";

$result = $conn->query("SELECT * FROM registrations ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrations | EventHub</title>

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

        /* Sidebar */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 245px;
            height: 100vh;
            background: linear-gradient(180deg, #111827, #1f2937);
            color: white;
            padding: 25px 15px;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.08);
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

        .sidebar a:hover,
        .sidebar a.active {
            background: #6366f1;
            color: white;
            transform: translateX(3px);
        }

        /* Main */

        .main {
            margin-left: 245px;
            padding: 30px;
        }

        /* Topbar */

        .topbar {
            background: white;
            padding: 25px 28px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
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

        /* Table */

        .table-container {
            background: white;
            padding: 22px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th {
            background: #111827;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 13px;
        }

        th:first-child {
            border-radius: 8px 0 0 8px;
        }

        th:last-child {
            border-radius: 0 8px 8px 0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-size: 14px;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #f8f8ff;
        }

        .id {
            font-weight: bold;
            color: #6366f1;
        }

        .event-badge {
            display: inline-block;
            background: #eef2ff;
            color: #4f46e5;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .message {
            max-width: 220px;
            color: #6b7280;
        }

        .empty {
            text-align: center;
            padding: 45px 20px;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* Footer */

        .dashboard-footer {
            margin-top: 35px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        /* Responsive */

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

        }

    </style>

</head>

<body>

    <!-- Sidebar -->

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

        <a href="registrations.php" class="active">
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


    <!-- Main Content -->

    <main class="main">

        <div class="topbar">

            <h1>
                Registered Users
            </h1>


        </div>


        <div class="table-container">

            <?php if ($result->num_rows > 0) { ?>

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Event</th>

                            <th>Message</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>

                                <td class="id">
                                    <?php echo $row['id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['phone']); ?>
                                </td>

                                <td>

                                    <span class="event-badge">
                                        <?php echo htmlspecialchars($row['event']); ?>
                                    </span>

                                </td>

                                <td class="message">
                                    <?php echo htmlspecialchars($row['message']); ?>
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


        <div class="dashboard-footer">


        </div>

    </main>

</body>

</html>