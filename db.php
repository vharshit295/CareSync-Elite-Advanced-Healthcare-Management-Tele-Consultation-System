<?php
$conn = mysqli_connect("localhost", "root", "", "caresync_new");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>