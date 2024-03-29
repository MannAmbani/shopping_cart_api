<?php
//this function removes unwanted warnings
error_reporting(0);

//setting header
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: PUT');
header('Access-Control-Allow-Headers: Content-Type, Access-Control -Allow-Headers, Authprization, X-Request-With');

//include function.php
include('function.php');

//get the request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//checking if request method is PUT
if ($requestMethod == "PUT") {
    
  //getting input data from request body
    $inputData = json_decode(file_get_contents("php://input"), true);

  //updating comments
    $updateComments = updateComments($inputData, $_GET);


    echo $updateComments;

} else {
    
   // if request method is not post
            // setting response array
    $data = [
        'status' => 405,
        'message' => $requestMethod . 'Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>