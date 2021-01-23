<?php
// Initialize variables
// NOTE: Database connection should be done in a separate script that is located in a non-public location on the server
$servername = "localhost";
$dbname = '<your database name here>'; // Set this variable to your database name
$username = '<your database user name here>'; // Set this variable to your database user name
$password = '<database user password here>'; // Set this variable to your database user password
$fixedcount = 0;
error_reporting(E_ALL);
// Booleans
$success = false;
// Create connection
$conn = new mysqli( $servername, $username, $password, $dbname );
mysqli_set_charset($conn,"utf8");
// Check connection
if ( $conn->connect_error ) {
    die( "Connection failed: " . $conn->connect_error );
    alert( "No Connection" );
    //echo "Database connection error";
    console_log ("No Connection");
}
// Note...this should be changed to a prepared statement!
$topicsql = "SELECT `topic_id` FROM `phpbb_topics` WHERE `topic_id` > 0 AND `topic_id` < 10001"; // Set a range please kthxbye
$topicresult = $conn->query( $topicsql );
$aryTopicIDs = array();
if ( $topicresult->num_rows > 0 ) {
    while ( $row = $topicresult->fetch_assoc() ) {
        array_push( $aryTopicIDs, $row['topic_id']);
   }
} else {
    console_log( "We got nothing" );
}

foreach ( $aryTopicIDs as $topicid ) {
$stmt = $conn->prepare( "SELECT COUNT(*) FROM phpbb_posts WHERE topic_id LIKE ?" );
$stmt->bind_param( "i", $topicid ); 
$stmt->execute();
$result = $stmt->get_result();
while ( $row = $result->fetch_assoc() ) {
   foreach ($row as $value){
       console_log("Topic " . $topicid . " post count = " . $value);
       // Query the database for this topic data
       $checkit = $conn->prepare("SELECT * FROM phpbb_topics WHERE topic_id LIKE ?");
       $checkit->bind_param( 'i', $topicid);
    $checkit->execute();
    $checkresult = $checkit->get_result();
    while ($newrow = $checkresult->fetch_assoc()){
        $approved = $newrow["topic_posts_approved"];
        $softdelete = $newrow["topic_posts_softdeleted"];
        $unapproved = $newrow["topic_posts_unapproved"];
        $total = $approved + $softdelete + $unapproved;
        console_log("Topic ". $topicid . " total = " . $total);
        if ($total <> $value){
            $fixit = $value - ($softdelete + $unapproved);
            console_log("Fixit " . $topicid . " = ". $fixit);
            $fixquery = $conn->prepare("UPDATE phpbb_topics SET topic_posts_approved = ? WHERE topic_id LIKE ?");
            $fixquery->bind_param('ii', $fixit,$topicid);
            $fixquery->execute();
            $fixresult = $fixquery->get_result();
            $fixedcount++;
        }
    }
   }
}
}

$stmt->close();
$checkit->close();
$fixquery->close();
$conn->close();
console_log("Fixed " . $fixedcount . " topics.");
function number_log( $data ){
    echo '<script>';
    echo 'console.log(' .  $data . ')';
    echo '</script>';
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log(' . json_encode( $data ). ')';
    echo '</script>';
}

function check_content( $result ) {
    $result = trim( $result );
    $result = stripslashes( $result );
    $result = htmlspecialchars( $result );
    return $result;
}
?>
