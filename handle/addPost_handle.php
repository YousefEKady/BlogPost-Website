<?php
require_once '../inc/connection.php';

if (isset($_POST['submit']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['body']));
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'] / (1024 * 1024);
    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $error = $image['error'];

    $newName = uniqid() . ".$ext";
    // Validation
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Title Is Required';
    } elseif (is_numeric($title)) {
        $errors[] = 'Title Must Be String';
    }

    if (empty($body)) {
        $errors[] = 'Body Is Required';
    } elseif (is_numeric($body)) {
        $errors[] = 'Body Must Be String';
    }

    $ext_arr = ['png', 'jpg', 'jpeg', 'gif'];
    if ($error != 0) {
        $errors[] = 'Image Is Required';
    } elseif (!in_array($ext, $ext_arr)) {
        $errors[] = 'Image Not Correct';
    } elseif ($imageSize > 1) {
        $errors[] = "Large Size";
    }

    // Insert
    if (empty($errors)) {
        $query = "insert into posts (`title`,`body`,`image`,`user_id`) values ('$title','$body','$newName','$user_id')";
        $runQuery = mysqli_query($conn, $query);
        if ($runQuery) {
            // Upload image to file named uploads
            move_uploaded_file($imageTmpName, "../uploads/$newName");
            $_SESSION['success'] = "Post Added Successfuly";
            header("location:../index.php");
        } else {
            $_SESSION['errors'] = ["Error While Adding Post"];
            header("location:../addPost.php");
        }
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['title'] = $title;
        $_SESSION['body'] = $body;
        header("location:../addPost.php");
    }

} else {
    header("location:../addPost.php");
}