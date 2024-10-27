<?php
session_start();

include("../include/connection.php");

if (isset($_POST['login'])) {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    switch ($role) {
        case 'admin':

            $query = $con->prepare ("SELECT * FROM admin WHERE email=? AND password=?");
            $query-> bind_param("ss", $email, $password);
            $query-> execute();
            $result = $query->get_result();
            if ($result->num_rows == 1) {
                $_SESSION['admin'] = $email;
                header("location:../admin/index.php");
                exit();
            } else {
                $errors= "Invalid Admin Details";
            }

            break;

            default:
            break;
    }
}

?>
