<?php
require_once '../inc/connection.php';
if (isset($_SESSION["user_id"])) {

    if (isset($_POST['submit']) && isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $title = htmlspecialchars(trim($_POST['title']));
        $body = htmlspecialchars(trim($_POST['body']));

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

        //Check for image
        $query = "SELECT id, image from posts where id = $id";
        $runQuery = mysqli_query($conn, $query);
        if (mysqli_num_rows($runQuery) == 1) {
            $post = mysqli_fetch_assoc($runQuery);
            $oldImage = $post['image'];

            // Check Image
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image'];
                $imageName = $image['name'];
                $imageTmpName = $image['tmp_name'];
                $imageSize = $image['size'] / (1024 * 1024);
                $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $error = $image['error'];
                $ext_arr = ['png', 'jpg', 'jpeg', 'gif'];

                if ($error != 0) {
                    $errors[] = 'Image Is Required';
                } elseif (!in_array($ext, $ext_arr)) {
                    $errors[] = 'Image Not Correct';
                } elseif ($imageSize > 1) {
                    $errors[] = "Large Size";
                }
                $newName = uniqid() . ".$ext";
            } else {
                $newName = $oldImage;
            }
            // Update
            if (empty($errors)) {
                $query = "UPDATE posts SET `title` = '$title', `body` = '$body', `image` = '$newName' where id = $id";
                $runQuery = mysqli_query($conn, $query);
                if ($runQuery) {
                    if (!empty($_FILES['image']['name'])) {
                        unlink("../uploads/$oldImage");
                        move_uploaded_file($imageTmpName, "../uploads/$newName");
                    }
                    $_SESSION['success'] = "Post Updated Successfuly";
                    header("location:../viewPost.php?id=$id");
                } else {
                    $_SESSION['errors'] = ["Error While Update Post"];
                    header("location:../editPost.php?id=$id");
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['title'] = $title;
                $_SESSION['body'] = $body;
                header("location:../editPost.php?id=$id");
            }
        } else {
            $_SESSION['errors'] = ['Post not found'];
            header("location:../index.php");
        }

    } else {
        $_SESSION['errors'] = ['Please Choose Correct Operation'];
        header("location:../index.php");
    }
} else {
    header("location:../index.php");
}