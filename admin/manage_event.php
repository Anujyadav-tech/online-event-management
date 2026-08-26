<?php

include "../config.php";


/* Get Events */

$sql = "SELECT * FROM events ORDER BY date ASC";

$result = $conn->query($sql);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">


    <title>
        Manage Events - EventHub
    </title>


    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            font-family: Arial, sans-serif;

        }


        body {

            background: #f4f6f9;

        }


        /* ================= SIDEBAR ================= */

        .sidebar {

            position: fixed;

            left: 0;

            top: 0;

            width: 240px;

            height: 100vh;

            background: #111827;

            padding: 25px 15px;

        }


        .logo {

            color: white;

            text-align: center;

            font-size: 25px;

            font-weight: bold;

            margin-bottom: 40px;

        }


        .logo span {

            color: #6366f1;

        }


        .sidebar a {

            display: block;

            color: #d1d5db;

            text-decoration: none;

            padding: 14px 15px;

            margin: 8px 0;

            border-radius: 8px;

            transition: 0.3s;

        }


        .sidebar a:hover {

            background: #6366f1;

            color: white;

        }


        /* ================= MAIN ================= */

        .main {

            margin-left: 240px;

            padding: 30px;

        }


        /* ================= TOPBAR ================= */

        .topbar {

            background: white;

            padding: 25px;

            border-radius: 12px;

            margin-bottom: 25px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.08);

        }


        .topbar h1 {

            color: #111827;

            margin-bottom: 8px;

        }


        .topbar p {

            color: #6b7280;

        }


        /* ================= ADD BUTTON ================= */

        .add-btn {

            display: inline-block;

            margin-bottom: 20px;

            background: #16a34a;

            color: white;

            padding: 12px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;

        }


        .add-btn:hover {

            background: #15803d;

        }


        /* ================= TABLE ================= */

        .table-box {

            background: white;

            padding: 20px;

            border-radius: 14px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.08);

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 900px;

        }


        th {

            background: #111827;

            color: white;

            padding: 14px;

            text-align: left;

        }


        td {

            padding: 14px;

            border-bottom:
                1px solid #e5e7eb;

            color: #374151;

        }


        tr:hover {

            background: #f9fafb;

        }


        /* ================= STATUS ================= */

        .status {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;

        }


        .active {

            background: #dcfce7;

            color: #166534;

        }


        .inactive {

            background: #fee2e2;

            color: #991b1b;

        }


        /* ================= ACTION BUTTONS ================= */

        .action {

            white-space: nowrap;

        }


        .edit {

            display: inline-block;

            background: #2563eb;

            color: white;

            padding: 7px 11px;

            border-radius: 6px;

            text-decoration: none;

            margin-right: 5px;

        }


        .edit:hover {

            background: #1d4ed8;

        }


        .delete {

            display: inline-block;

            background: #dc2626;

            color: white;

            padding: 7px 11px;

            border-radius: 6px;

            text-decoration: none;

        }


        .delete:hover {

            background: #b91c1c;

        }


        /* ================= NO DATA ================= */

        .no-data {

            text-align: center;

            padding: 35px;

            color: #6b7280;

        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 800px) {

            .sidebar {

                width: 190px;

            }


            .main {

                margin-left: 190px;

            }

        }

    </style>

</head>


<body>


<!-- ================= SIDEBAR ================= -->

<div class="sidebar">


    <div class="logo">

        Event<span>Hub</span>

    </div>


    <a href="index.php">

        📊 Dashboard

    </a>


    <a href="upcoming_events.php">

        🎫 Manage Events

    </a>


    <a href="registrations.php">

        👥 Registrations

    </a>


    <a href="#">

        ⚙️ Settings

    </a>


    <a href="../index.php">

        🏠 View Website

    </a>


</div>



<!-- ================= MAIN ================= -->

<div class="main">


    <div class="topbar">

        <h1>

            Manage Events

        </h1>


    </div>



    <!-- ADD EVENT -->

    <a href="add_event.php"
       class="add-btn">

        + Add New Event

    </a>



    <!-- TABLE -->

    <div class="table-box">


        <table>


            <thead>

                <tr>

                    <th>
                        ID
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


                <?php

                if ($result && $result->num_rows > 0) {


                    while ($event = $result->fetch_assoc()) {


                ?>


                    <tr>


                        <td>

                            <?php

                            echo $event['id'];

                            ?>

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

                            <?php

                            echo htmlspecialchars(
                                $event['description']
                            );

                            ?>

                        </td>


                        <td>


                            <?php

                            if (
                                $event['status']
                                == 'Active'
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


                        <td class="action">


                            <a

                                href="edit_event.php?id=<?php echo $event['id']; ?>"

                                class="edit"

                            >

                                ✏️ Edit

                            </a>


                            <a

                                href="delete_event.php?id=<?php echo $event['id']; ?>"

                                class="delete"

                                onclick="return confirm('Are you sure you want to delete this event?');"

                            >

                                🗑️ Delete

                            </a>


                        </td>


                    </tr>


                <?php

                    }


                } else {

                ?>


                    <tr>

                        <td
                            colspan="7"
                            class="no-data"
                        >

                            No events found.

                        </td>

                    </tr>


                <?php

                }

                ?>


            </tbody>


        </table>


    </div>


</div>


</body>

</html>