<?php
include "../config.php";

if (!isset($_GET['id'])) {
    header("Location: manage_event.php");
    exit();
}

$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM events WHERE id = $id");

if ($result->num_rows == 0) {
    echo "Event not found.";
    exit();
}

$event = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "UPDATE events SET
            title='$title',
            date='$date',
            location='$location',
            description='$description',
            status='$status'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_event.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Event | EventHub</title>

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
            min-height: 100vh;
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

        /* Form */

        .form-card {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .icon {
            width: 65px;
            height: 65px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            border-radius: 50%;
            font-size: 30px;
        }

        .form-header h2 {
            margin-top: 15px;
            color: #111827;
            font-size: 26px;
        }

        .form-header p {
            margin-top: 6px;
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 13px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 9px;
            font-size: 14px;
            outline: none;
            background: #f9fafb;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.10);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Button */

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb, #6366f1);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        }

        /* Back */

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6366f1;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .back:hover {
            color: #4f46e5;
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

            .form-card {
                padding: 25px 20px;
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

        <a href="manage_event.php" class="active">
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


    <!-- Main -->

    <main class="main">

        <div class="topbar">

            <h1>
                Edit Event
            </h1>

            <p>
                Update the details of your event.
            </p>

        </div>


        <div class="form-card">

            <div class="form-header">

                <div class="icon">
                    ✏️
                </div>

                <h2>
                    Update Event
                </h2>

                <p>
                    Modify the event information below.
                </p>
+6
            </div>


            <form method="POST">

                <div class="form-group">

                    <label>
                        🎯 Event Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="<?php echo htmlspecialchars($event['title']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        📅 Event Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="<?php echo htmlspecialchars($event['date']); ?>"
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
                        value="<?php echo htmlspecialchars($event['location']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        📝 Description
                    </label>

                    <textarea
                        name="description"
                        required><?php echo htmlspecialchars($event['description']); ?></textarea>

                </div>


                <div class="form-group">

                    <label>
                        🟢 Status
                    </label>

                    <select name="status">

                        <option value="Active"
                            <?php if ($event['status'] == 'Active') echo 'selected'; ?>>
                            Active
                        </option>

                        <option value="Inactive"
                            <?php if ($event['status'] == 'Inactive') echo 'selected'; ?>>
                            Inactive
                        </option>

                    </select>

                </div>

                <button type="submit">
                    💾 Update Event
                </button>

            </form>


            <a href="manage_event.php" class="back">
                ← Back to Manage Events
            </a>

        </div>


        <div class="dashboard-footer">

            © 2026 EventHub Admin Panel

        </div>

    </main>

</body>

</html> 