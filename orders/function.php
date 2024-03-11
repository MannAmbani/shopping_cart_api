<?php
require '../config.php';

// setting error
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

// insert data
function storeOrders($ordersInput){
    global $conn;
    // order_id							

    // getting variable input
    $product_id = mysqli_real_escape_string($conn,$ordersInput['product_id']);
    $quantity = mysqli_real_escape_string($conn,$ordersInput['quantity']);
    $total_cost = mysqli_real_escape_string($conn,$ordersInput['total_cost']);
    $shipping_address = mysqli_real_escape_string($conn,$ordersInput['shipping_address']);
    $payment_method = mysqli_real_escape_string($conn,$ordersInput['payment_method']);
    $status = mysqli_real_escape_string($conn,$ordersInput['status']);
    
    //validation
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($quantity))){
        return error422('Enter Product quantity');
    }else if(empty(trim($total_cost))){
        return error422('Enter total_cost');
    }else if(empty(trim($shipping_address))){
        return error422('Enter shipping address');
    }else if(empty(trim($payment_method))){
        return error422('Enter payment method');
    }else if(empty(trim($status))){
        return error422('Enter status');
    }else{
        // insert query
        $query = "INSERT INTO orders (product_id,quantity,total_cost,shipping_address,payment_method,status) VALUES ('$product_id','$quantity','$total_cost','$shipping_address','$payment_method','$status')";
        // execute query
        $result = mysqli_query($conn,$query);
        // success
        if($result){
            
            $data = [
                'status' => 201,
                'message' => 'Order Created Successfully',
            ];
            header("HTTP/1.0 201 Success");
            return json_encode($data);
        }else{
            // fail
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
    }





}

// getting list 
function getOrdersList()
{
    global $conn;
    // select query
    $query = "SELECT * FROM orders";
    // executing query
    $query_run = mysqli_query($conn, $query);
    // success
    if ($query_run) {
        // if more then 0 data
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'ORDER LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            // if no data
            $data = [
                'status' => 404,
                'message' => 'No Order Found',
            ];
            header("HTTP/1.0 404 No Order Found");
            return json_encode($data);

        }
    } else {
        //  fail
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);

    }
}

// get single data
function getOrders($ordersParams){
    global $conn;
   if($ordersParams['order_id'] == null){
    return error422('Enter orders id');
   } 

   $ordersId = mysqli_real_escape_string($conn,$ordersParams['order_id']);
   $query = "SELECT * FROM orders WHERE order_id='$ordersId' LIMIT 1";
   $result = mysqli_query($conn, $query);

   if($result){

    if(mysqli_num_rows($result) == 1){
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'Order Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        $data = [
            'status' => 404,
            'message' => 'No Order Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data); 
    }

   }else{
    $data = [
        'status' => 500,
        'message' => 'Internal Server Error',
    ];
    header("HTTP/1.0 500 Internal Server Error");
    return json_encode($data);
   }
}


//update data
function updateOrders($ordersInput,$ordersParams){
    global $conn;
    if(!isset($ordersParams['order_id'])){
        return error422('Orders id not Found in URL');
    }else if($ordersParams['order_id'] == null){
        return error422('Enter Order Id');
    }
    $order_id = mysqli_real_escape_string($conn,$ordersParams['order_id']);

    $product_id = mysqli_real_escape_string($conn,$ordersInput['product_id']);
    $quantity = mysqli_real_escape_string($conn,$ordersInput['quantity']);
    $total_cost = mysqli_real_escape_string($conn,$ordersInput['total_cost']);
    $shipping_address = mysqli_real_escape_string($conn,$ordersInput['shipping_address']);
    $payment_method = mysqli_real_escape_string($conn,$ordersInput['payment_method']);
    $status = mysqli_real_escape_string($conn,$ordersInput['status']);
    
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($quantity))){
        return error422('Enter Product quantity');
    }else if(empty(trim($total_cost))){
        return error422('Enter total_cost');
    }else if(empty(trim($shipping_address))){
        return error422('Enter shipping address');
    }else if(empty(trim($payment_method))){
        return error422('Enter payment method');
    }else if(empty(trim($status))){
        return error422('Enter status');
    }else{
        $query = "UPDATE orders SET product_id='$product_id',quantity ='$quantity',total_cost='$total_cost',shipping_address='$shipping_address',payment_method='$payment_method',status='$status' WHERE order_id='$order_id' LIMIT 1";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Order Updated Successfully',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
    }
}


// delete data
function deleteOrders($ordersParams){
    global $conn;
    if(!isset($ordersParams['order_id'])){
        return error422('orders id not Found in URL');
    }else if($ordersParams['order_id'] == null){
        return error422('Enter orders Id');
    }
    $order_id = mysqli_real_escape_string($conn,$ordersParams['order_id']);
    $query = "DELETE FROM orders  WHERE order_id='$order_id' LIMIT 1";
    $result = mysqli_query($conn,$query);
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Order Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'Order not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>