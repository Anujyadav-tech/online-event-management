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

    } elseif ($_FILES['image']['error'] != 0) {

        $message = "❌ Image upload error. Error Code: " .
                   $_FILES['image']['error'];

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
                "❌ Sirf JPG, JPEG, PNG aur WEBP image allowed hai.";

        } else {

            $imageName =
                time() . "_" . uniqid() . "." . $extension;

            $destination =
                $uploadDir . $imageName;

            if (move_uploaded_file($tmpName, $destination)) {

                $message =
                    "✅ Image successfully upload ho gayi.";

            } else {

                $message =
                    "❌ Image uploads folder mein save nahi ho paayi.";

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

                $message =
                    "✅ Event aur image successfully add ho gaye!";

            } else {

                $message =
                    "❌ Database Error: " . $stmt->error;
            }

            $stmt->close();

        } else {

            $message =
                "❌ Database Query Error: " . $conn->error;
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
    font-family: "Segoe UI", Arial, sans-serif;
}

body {

    background: #f5f7fc;

    padding: 35px;

}


/* ================= CONTAINER ================= */

.container {

    max-width: 750px;

    margin: auto;

}


/* ================= BOX ================= */

.box {

    background: white;

    padding: 35px;

    border-radius: 16px;

    box-shadow:
        0 8px 30px rgba(30,40,80,0.08);

    border:
        1px solid #edf0f6;

}


/* ================= HEADER ================= */

h1 {

    text-align: center;

    color: #24134f;

    font-size: 27px;

    margin-bottom: 8px;

}

.subtitle {

    text-align: center;

    color: #8a92a5;

    font-size: 13px;

    margin-bottom: 28px;

}


/* ================= MESSAGE ================= */

.message {

    padding: 13px;

    background: #f1edff;

    color: #5137c7;

    border-radius: 8px;

    margin-bottom: 20px;

    text-align: center;

    font-size: 13px;

    font-weight: 600;

}


/* ================= FORM ================= */

.form-group {

    margin-bottom: 18px;

}

label {

    display: block;

    margin-bottom: 7px;

    font-weight: 600;

    color: #303746;

    font-size: 13px;

}

input,
textarea,
select {

    width: 100%;

    padding: 13px 14px;

    border:
        1px solid #dfe3eb;

    border-radius: 8px;

    outline: none;

    background: #fafbfc;

    font-size: 13px;

    transition: 0.2s;

}

input:focus,
textarea:focus,
select:focus {

    border-color: #6346df;

    background: white;

    box-shadow:
        0 0 0 3px rgba(99,70,223,0.08);

}

textarea {

    min-height: 110px;

    resize: vertical;

}


/* ================= IMAGE ================= */

input[type="file"] {

    padding: 10px;

    background: #fafbfc;

}

.image-note {

    margin-top: 6px;

    color: #8a92a5;

    font-size: 11px;

}


/* ================= BUTTON ================= */

.btn {

    width: 100%;

    border: none;

    padding: 14px;

    background:
        linear-gradient(
            135deg,
            #5d45dc,
            #7339e8
        );

    color: white;

    border-radius: 8px;

    font-size: 14px;

    font-weight: 600;

    cursor: pointer;

    margin-top: 8px;

    transition: 0.25s;

}

.btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 8px 20px rgba(91,64,220,0.25);

}


/* ================= BACK ================= */

.back {

    display: block;

    text-align: center;

    margin-top: 20px;

    text-decoration: none;

    color: #5d45dc;

    font-size: 13px;

    font-weight: 600;

}


/* ================= RESPONSIVE ================= */

@media(max-width:700px) {

    body {

        padding: 15px;

    }

    .box {

        padding: 25px 20px;

    }

}

</style>

</head>


<body>


<div class="container">


    <div class="box">


        <h1>
            ➕ ADD NEW EVENT
        </h1>


        <p class="subtitle">
            Create and publish a new event
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
                    🎯 Event Title
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
                    📝 Description
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
                    📅 Event Date
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
                    📍 Location
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
                    🟢 Status
                </label>

                <select
                    name="status"
                    required
                >

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
                    🖼️ Event Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

                <p class="image-note">
                    JPG, JPEG, PNG aur WEBP images allowed hain.
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