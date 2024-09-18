<?php
require_once '../inc/connection.php';

if (isset($_POST['submit'])) {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    $phone = trim(htmlspecialchars($_POST['phone']));

    // Validation
    $errors = [];
    // Name (Required, strind, max 50)
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) > 50) {
        $errors[] = 'Name must be less than 50 characters';
    } elseif (is_numeric($name)) {
        $errors[] = 'Name must be strind';
    }
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
    // Phone (int , 15)
    if (!is_string($phone)) {
        $errors[] = 'Phone must be numbers';
    } //elseif (strlen($phone) < 12) {
    //$errors[] = 'Phone must be 11 numbers';
    //}

    // Hash Password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    if (empty($errors)) {
        // Insert
        $query = "INSERT INTO users(`name`,`email`,`password`,`phone`) VALUES('$name','$email','$password_hashed','$phone')";
        $runQuery = mysqli_query($conn, $query);
        if ($runQuery) {
            $_SESSION["success"] = "The Account Created Successfuly";
            header("location:../login.php");
        } else {
            $_SESSION['errors'] = ['Error While Insert'];
            header("location:../register.php");
        }
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['phone'] = $phone;
        header("location:../register.php");
    }

} else {
    header("location:../register.php");
}