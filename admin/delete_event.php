<?php

include "../config.php";

if (!isset($_GET['id'])) {
    header("Location: 
    manage_event.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM events WHERE id = $id";

if ($conn->query($sql) === TRUE) {

    header("Location: manage_event.php");
    exit();

} else {

    echo "Error deleting event: " . $conn->error;

}

?>