<?php
session_start();

include("../include/connection.php");

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $register = $_POST['register'];

    $errors = array();
    if (empty($full_name)) {
        $errors ['register'] = "Enter Your Full Name";
    }
    if (empty($phone)) {
        $errors ['register'] = "Enter Your Phone Number";
    }
    if (empty($age)) {
        $errors ['register'] = "Enter Your Age";
    }
    if (empty($gender)) {
        $errors ['register'] = "Enter Your Gender";
    }
    if (empty($email)) {
        $errors ['register'] = "Enter Your Email";
    }
    if (empty($password)) {
        $errors ['register'] = "Enter Your Password";
    }

    if (count($errors) == 0) {
        $hased_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO admin (full_name,phone,age,gender,email,password,profile,status)
        VALUES (?,?,?,?,?,?,'','active')";
        $stm =mysqli_prepare($con,$query);
        $stm->bind_param("siisss",$full_name,$phone,$age,$gender,$email,$password);
        $result = $stm->execute();
        if ($result) {
            echo "<script type='text/javascript'> alert('Customer Account Registered Successfully');
            window.location.href = 'index.html';
            </script>";
            exit;
        } else {
            echo "<script type='text/javascript'> alert('Customer Registration Failed') </script>";
        }
    }
}

if (isset( $errors['register'])) {
    $error_message =$errors ['register'];
    $show_error= "<h5 class= 'text-center alert alert-danger'>$error_message</h5>";
} else {
    $show_error="";
}

?>