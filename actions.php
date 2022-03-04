<?php
include("functions.php");

if ($_GET['action'] == "loginSignup"){
    $error = "";

    if (!$_POST['email']){

        $error = "Email address is required";

    } else if(!$_POST['password']){

        $error = "Password is required.";

    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){

        $error = "Please enter a valid email address.";
        
    }


    if ($error != "") {
        echo $error;
        exit();
    }


    if ($_POST['loginActive'] == "0"){
        
        $email = mysqli_real_escape_string(con(), $_POST['email']);

        $user_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        
        $result = mysqli_query(con(), $user_check_query);
        // var_dump((mysqli_num_rows($result)));
        // die;


        if(mysqli_num_rows($result) > 0) {

            $error = "Email address is already taken.";

        }else{
            $email = mysqli_real_escape_string(con(), $_POST['email']);

            $password = md5(mysqli_real_escape_string(con(), $_POST['password']));
            
            $query = "INSERT INTO users(email, password) VALUES('$email', '$password')";


            if (mysqli_query(con(), $query)){

                echo 1;
                $_SESSION['id']= mysqli_insert_id(con());

            } else{
                $error = "User could not be created!";
            }
        }
    }else{
        $email = mysqli_real_escape_string(con(), $_POST['email']);
        
        $password = md5(mysqli_real_escape_string(con(), $_POST['password']));

        $user_check_query = "SELECT * FROM users WHERE email = '$email' AND password ='$password'";

        $result = mysqli_query(con(), $user_check_query);

        $row = mysqli_fetch_assoc($result);


        if(mysqli_num_rows($result) == 1) {

            echo 1;
            $_SESSION['id'] = $row['id'];

        }else{

            $error = "Incorrect email or password combination. Try again later or sign up";

        }     
    }

    if ($error != "") {
        echo $error;
        exit();
    }
    
}


if ($_GET['action'] == 'toggleFollow'){

    $sessionId = mysqli_real_escape_string(con(), $_SESSION['id']);

    $isFollowing = mysqli_real_escape_string(con(), $_POST['userId']);

    $followQuery = "SELECT * FROM isFollowing WHERE follower = '$sessionId' AND isFollowing ='$isFollowing' LIMIT 1";

    $result = mysqli_query(con(), $followQuery);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        $id = mysqli_real_escape_string(con(), $row['id']);
        
        mysqli_query(con(), "DELETE FROM isFollowing WHERE id = '$id' LIMIT 1");
        echo "1";
    }else{

        $query = "INSERT INTO isFollowing (follower, isFollowing) VALUES ('$sessionId', '$isFollowing')";
    
        mysqli_query(con(), $query);

        echo '2';
    }
    
}

if($_GET['action'] == 'postTweet'){
    if (!$_POST['tweetContent']) {

                echo "Your tweet is empty!";

            } else if(strlen($_POST['tweetContent']) > 140) {

               echo "Your tweet is too long!" ;

            } else{
                
                $tweet = mysqli_real_escape_string(con(), $_POST['tweetContent']);

                $userId = mysqli_real_escape_string(con(), $_SESSION['id']);

                $postQuery = "INSERT INTO tweets (tweet, userid, datetime) VALUES ('$tweet', '$userId', NOW())";


                mysqli_query(con(), $postQuery);

                echo "1";
            }
        }
?>