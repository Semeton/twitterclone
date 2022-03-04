<?php
session_start();

function con(){
    $con = mysqli_connect('localhost', 'root', '', 'twitter' );
    return $con;
}
    

    if(mysqli_connect_errno()){
        print_r(mysqli_connect_errno());
        exit();
    }

    if ($_GET['function'] == "logout"){
        session_unset();
    }

    // x minutes ago function
    function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'min'),
        array(1 , 'sec')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
    }

    // Displaying tweets function

    function displayTweets($type){
        // echo "Tweets";
        if($type == 'public'){
            $whereClause = "";
        }else if($type == 'isFollowing'){

            $user_id = mysqli_real_escape_string(con(), $_SESSION['id']);

            $query = "SELECT * FROM isFollowing WHERE follower = $user_id";

            $result = mysqli_query(con(), $query);

            $whereClause = "";
            while($row = mysqli_fetch_assoc($result)){
                // var_dump($row); die;
                if($whereClause == ""){
                    $whereClause = "WHERE";
                }else{
                    $whereClause.= " OR";
                }
                $whereClause.= " userid = ".$row['isFollowing'];
            }

        }else if($type == 'yourtweets'){
            $sessionId = mysqli_real_escape_string(con(), $_SESSION['id']);
            $whereClause = "WHERE userid = '$sessionId'";
        } else if ($type =='search' ){

            $search = mysqli_real_escape_string(con(), $_GET['q']);

            echo "<p>Showing results for '$search'</p>";

            $whereClause = "WHERE tweet LIKE '%".$search."%'";
            
        } else if(is_numeric($type)){

            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string(con(), $type)." LIMIT 1";

            $userQueryResult = mysqli_query(con(), $userQuery);

            $user = mysqli_fetch_assoc($userQueryResult);

            echo "<h2>".mysqli_real_escape_string(con(), $user['email'])."'s Tweets</h2>";

            $whereClause = "WHERE userid = ".mysqli_real_escape_string(con(), $type);
        }
        // $con = mysqli_connect('localhost', 'root', '', 'twitter' );
        $query = "SELECT * FROM tweets ".$whereClause." ORDER BY datetime DESC LIMIT 10";
        $result = mysqli_query(con(), $query);
        // var_dump($result);
        // die;

        if(mysqli_num_rows($result) == 0) {
             echo "There are no tweets to display.";
            }else{
                while($row = mysqli_fetch_assoc($result)){
                    $user_id = mysqli_real_escape_string(con(), $row['userid']);
                    $userQuery = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
                    $userQueryResult = mysqli_query(con(), $userQuery);
                    $user = mysqli_fetch_assoc($userQueryResult);
                    echo "<div class='tweet'><p>".$user['email']. " <span class='time'>" . time_since(time() - strtotime($row['datetime']))." ago</span>:</p>";
                    echo "<p>".$row['tweet']."</p>";
                    echo "<p><a class='toggleFollow' data-userId='".$row['userid']."'>";

                    $sessionId = mysqli_real_escape_string(con(), $_SESSION['id']);

                    $isFollowing = mysqli_real_escape_string(con(), $row['userid']);

                    $followQuery = "SELECT * FROM isFollowing WHERE follower = '$sessionId' AND isFollowing ='$isFollowing' LIMIT 1";

                    $isFollowingQueryResult = mysqli_query(con(), $followQuery);

                    if(mysqli_num_rows($isFollowingQueryResult) > 0){

                       echo "Unfollow";
                       
                    } else{

                        echo "Follow";

                    }
                    
                    echo "</a></p></div>";
                }
            }
        }

        function displaySearch(){
            echo '<form class="form-inline">
            <div class="form-group">
            <input type="hidden" name="page" value="search">
            <input type="text" name="q" class="form-control" id="search" placeholder="Search">
            </div>
            <button class="btn btn-primary">Search Tweets</button>
            </form>';
        } 

        function displayTweetBox(){
            if($_SESSION['id']>0){
                echo'<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
                <div id="tweetFail" class="alert alert-danger"></div>
                <div class="form-inline">
            <div class="form-group">
            <textarea type="text" class="form-control" id="tweetContent" placeholder="Search"></textarea>
            </div>
            <button class="btn btn-primary" id="postTweetButton">Post Tweet</button>
            </div>';
            }
        }

        function displayUsers(){
            $query = "SELECT * FROM users LIMIT 10";

            $result = mysqli_query(con(), $query);

            while ($row = mysqli_fetch_assoc($result)){
                echo "<p><a href='?page=publicprofiles&userid=".$row['id']."'>".$row['email']."</a></p>";
            }
        }
?>