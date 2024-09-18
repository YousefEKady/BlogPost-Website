<?php
require_once "../inc/connection.php";
if (isset($_SESSION['user_id'])) {

    if (isset($_POST["submit"]) && isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $query = "select id, image from posts where id = $id";
        $runQuery = mysqli_query($conn, $query);
        if (mysqli_num_rows($runQuery) == 1) {
            // UNLINK for Image
            $post = mysqli_fetch_assoc($runQuery);
            $image = $post["image"];
            echo $image;
            unlink("../uploads/$image");
            // DELETE
            $query = "DELETE FROM posts WHERE id = $id";
            $runQuery = mysqli_query($conn, $query);
            if ($runQuery) {
                $_SESSION['success'] = "Post Deleted Successfuly";
                header("location:../index.php");
            } else {
                $_SESSION["errors"] = ["Error While Deleting"];
            }
        } else {
            $_SESSION['errors'] = ['Post Not Found'];
            header("location:../index.php");
        }
    } else {
        $_SESSION['errors'] = ['Please Choose Correct Operation'];
        header("location:../index.php");
    }
} else {
    header("location:../index.php");
}