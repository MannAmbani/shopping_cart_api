<?php
//setting header
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control -Allow-Headers, Authprization, X-Request-With');

//include function.php
include('function.php');

//get the request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//checking if request method is GET
if ($requestMethod == "GET") {
    
  // checking if endpoint has id
if(isset($_GET['order_id'])) {
    
     //get single data
    $comments = getComments($_GET);
    echo $comments;
}else{
    
      // get list of data
    $commentsList = getCommentsList();
    echo $commentsList;
}

   
} else {
    
           // if request method is not GET
            // setting response array
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>