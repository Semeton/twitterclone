<?php
    $con = mysqli_connect('localhost', 'root', '', 'twitter' );

    if(mysqli_connect_errno()){
        print_r(mysqli_connect_errno());
        exit();
    }
?>