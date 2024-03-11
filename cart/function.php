<?php
require '../config.php';


function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}
function storeCart($cartInput){
    global $conn;
// product_id	user_id	quantities	

    $product_id = mysqli_real_escape_string($conn,$cartInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$cartInput['user_id']);
    $quantities = mysqli_real_escape_string($conn,$cartInput['quantities']);
    
    
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($quantities))){
        return error422('Enter Product quantities');
    }else{
        $query = "INSERT INTO cart (product_id,user_id,quantities) VALUES ('$product_id','$user_id','$quantities')";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Cart Created Successfully',
            ];
            header("HTTP/1.0 201 Success");
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
function getCartList()
{
    global $conn;
    $query = "SELECT * FROM cart";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'CART LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            $data = [
                'status' => 404,
                'message' => 'No Data Found',
            ];
            header("HTTP/1.0 404 No Data Found");
            return json_encode($data);

        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);

    }
}

function getCart($cartParams){
    global $conn;
   if($cartParams['cart_id'] == null){
    return error422('Enter cart id');
   } 

   $cartId = mysqli_real_escape_string($conn,$cartParams['cart_id']);
   $query = "SELECT * FROM cart WHERE cart_id='$cartId' LIMIT 1";
   $result = mysqli_query($conn, $query);

   if($result){

    if(mysqli_num_rows($result) == 1){
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'Cart Data Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        $data = [
            'status' => 404,
            'message' => 'No Data Found',
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


function updateCart($cartInput,$cartParams){
    global $conn;
    if(!isset($cartParams['cart_id'])){
        return error422('Cart id not Found in URL');
    }else if($cartParams['cart_id'] == null){
        return error422('Enter Cart Id');
    }
    $cart_id = mysqli_real_escape_string($conn,$cartParams['cart_id']);

    $product_id = mysqli_real_escape_string($conn,$cartInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$cartInput['user_id']);
    $quantities = mysqli_real_escape_string($conn,$cartInput['quantities']);
    
    
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($quantities))){
        return error422('Enter Product quantities');
    }else{
        $query = "UPDATE cart SET product_id='$product_id',user_id ='$user_id',quantities='$quantities' WHERE cart_id='$cart_id' LIMIT 1";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Cart Data Updated Successfully',
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


function deleteCart($cartParams){
    global $conn;
    if(!isset($cartParams['cart_id'])){
        return error422('Cart id not Found in URL');
    }else if($cartParams['cart_id'] == null){
        return error422('Enter Cart Id');
    }
    $cart_id = mysqli_real_escape_string($conn,$cartParams['cart_id']);
    $query = "DELETE FROM cart  WHERE cart_id='$cart_id' LIMIT 1";
    $result = mysqli_query($conn,$query);
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Cart Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'Cart not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>