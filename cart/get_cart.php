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
    // checking if endpoint has cart_id
if(isset($_GET['cart_id'])) {
    //get single data
    $cart = getCart($_GET);
    echo $cart;
}else{
    // get cart list
    $cartList = getCartList();
    echo $cartList;
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