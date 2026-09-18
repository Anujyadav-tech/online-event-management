<?php

include "../config.php";

$message = "";

/* ================= ADD EVENT ================= */

if (isset($_POST['add_event'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $location = trim($_POST['location']);
    $status = $_POST['status'];

    $imageName = "";

    /* ================= IMAGE UPLOAD ================= */

    if (!isset($_FILES['image'])) {

        $message = "❌ Image select nahi ki gayi.";

    } 
    elseif ($_FILES['image']['error'] != 0) {

        $error = $_FILES['image']['error'];

        $message = "❌ Image upload error. Error Code: " . $error;

    } 
    else {

        $uploadDir = "../uploads/";

        /* Create uploads folder */

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

        /* Check file type */

        if (!in_array($extension, $allowed)) {

            $message = "❌ Sirf JPG, JPEG, PNG aur WEBP image allowed hai.";

        } 
        else {

            /* Create unique image name */

            $imageName = time() . "_" . uniqid() . "." . $extension;

            $destination = $uploadDir . $imageName;

            /* Move image */

            if (move_uploaded_file($tmpName, $destination)) {

                $message = "✅ Image successfully upload ho gayi.";

            } 
            else {

                $message = "❌ Image uploads folder mein save nahi ho paayi.";

                $imageName = "";

            }

        }

    }


    /* ================= INSERT EVENT ================= */

    if ($message == "✅ Image successfully upload ho gayi.") {

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

                $message = "✅ Event aur image successfully add ho gaye!";

            } 
            else {

                $message = "❌ Database Error: " . $stmt->error;

            }

            $stmt->close();

        } 
        else {

            $message = "❌ Database Query Error: " . $conn->error;

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Event | EventHub</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f1ff;
            padding: 40px 20px;
    
        }

        .container {
            max-width: 650px;
            margin: auto;
        }

        .box {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.10);
    
        }

        h1 {
            text-align: center;
            color: #281653;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
        }

        .message {
            padding: 13px;
            background: #f1edff;
            color: #4b32c5;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            background: #fafafa;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #6042d8;
        }

        textarea {
            height: 110px;
            resize: vertical;
        }

        .image-note {
            font-size: 12px;
            color: #777;
            margin-top: 6px;
        }

        .btn {
            width: 100%;
            border: none;
            padding: 14px;
            background: #5940d1;
            color: white;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #432bb5;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #5940d1;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="box">

        <h1>➕ ADD NEW EVENT</h1>

        <p class="subtitle">
            
        </p>


        <?php if ($message != "") { ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php } ?>


        <form method="POST"
              enctype="multipart/form-data">


            <!-- EVENT TITLE -->

            <div class="form-group">

                <label>
                    Event Title
                </label>

                <input
                    type="text"
                    name="title"
                    placeholder="Enter event title"
                    required
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    placeholder="Enter event description"
                    required
                ></textarea>

            </div>


            <!-- DATE -->

            <div class="form-group">

                <label>
                    Event Date
                </label>

                <input
                    type="date"
                    name="date"
                    required
                >

            </div>


            <!-- LOCATION -->

            <div class="form-group">

                <label>
                    Location
                </label>

                <input
                    type="text"
                    name="location"
                    placeholder="Enter event location"
                    required
                >

            </div>

            <!-- STATUS -->

            <div class="form-group">

                <label>
                    Status
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


            <!-- EVENT IMAGE -->

            <div class="form-group">

                <label>
                    Event Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

                <p class="image-note">
                
                </p>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="add_event"
                class="btn"
            >
                ➕ ADD EVENT
            </button>

        </form>


        <a
            href="index.php"
            class="back"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>