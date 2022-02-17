<?php
include("functions.php");

if ($_GET['action'] == "loginSignup"){
    $error = "";
    if (!$_POST['email']){
        $error = "Email address is required";
    } else if(!$_POST['password']){
        $error = "Password is required";
    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
        $error = "Please enter a valid email address.";
    }

    if ($error != "") {
        echo $error;
        exit();
    }

    if ($_POST['loginActive'] == "0"){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $query = "SELECT * FROM users WHERE email = $email LIMIT 1";
        $result = mysqli_query($con, $query);
        var_dump((mysqli_num_rows($result)));
        die;

        if(mysqli_num_rows($result)) $error = "Email address is already taken.";
    }
    if ($error != "") {
        echo $error;
        exit();
    }
    
}
?>