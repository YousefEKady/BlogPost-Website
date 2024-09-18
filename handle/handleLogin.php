<?php
require_once '../inc/connection.php';

if (isset($_POST['submit'])) {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    // Validation
    $errors = [];
    // Email (Required, email)
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    // Password (Required, >= 6)
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }

    if (empty($errors)) {
        // Check the email is valid or no
        $query = "SELECT id, email, password from users where email = '$email'";
        $runQuery = mysqli_query($conn, $query);
        if (mysqli_num_rows($runQuery) == 1) {
            // Check the password is valid or no
            $user = mysqli_fetch_assoc($runQuery);
            $oldPassword = $user["password"];
            $result = password_verify($password, $oldPassword);
            if ($result) {
                $_SESSION["user_id"] = $user["id"];
                header("location:../index.php");
            } else {
                $_SESSION['errors'] = ["This Account Is Not Exist"];
                header("location:../Login.php");
            }
        } else {
            $_SESSION['errors'] = ["This Account Is Not Exist"];
            header("location:../Login.php");
        }
    } else {
        $_SESSION['errors'] = $errors;
        header("location:../Login.php");
    }
} else {
    header("location:../Login.php");
}