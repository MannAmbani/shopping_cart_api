<?php
//connecting to database
require '../config.php';

//making common function for error422
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}
//inserting cart data
function storeCart($cartInput){
    //calling the connection function from db
    global $conn;
// product_id	user_id	quantities	

//getting input from parameters passed by user
    $product_id = mysqli_real_escape_string($conn,$cartInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$cartInput['user_id']);
    $quantities = mysqli_real_escape_string($conn,$cartInput['quantities']);
    
    //validation of fields
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($quantities))){
        return error422('Enter Product quantities');
    }else{
        //insert query
        $query = "INSERT INTO cart (product_id,user_id,quantities) VALUES ('$product_id','$user_id','$quantities')";
        // execute query
        $result = mysqli_query($conn,$query);
        // if result success
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Cart Created Successfully',
            ];
            header("HTTP/1.0 201 Success");
            return json_encode($data);
        }else{
            //if result fail then print error 
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
    }
}

//getting list of data
function getCartList()
{
    global $conn;
    //select query
    $query = "SELECT * FROM cart";
    // execute query
    $query_run = mysqli_query($conn, $query);
    // if query executes successfully
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
            // if query fails
            $data = [
                'status' => 404,
                'message' => 'No Data Found',
            ];
            header("HTTP/1.0 404 No Data Found");
            return json_encode($data);

        }
    } else {
        // if error occurs in execution
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);

    }
}

//get single data from cart
function getCart($cartParams){
    //getting connection variable 
    global $conn;
    //if there is no cart id given
   if($cartParams['cart_id'] == null){
    return error422('Enter cart id');
   } 


   //getting cart id
   $cartId = mysqli_real_escape_string($conn,$cartParams['cart_id']);
//    select query for one variable 
   $query = "SELECT * FROM cart WHERE cart_id='$cartId' LIMIT 1";
//    execute query
   $result = mysqli_query($conn, $query);
   //if result is fetched successfully 
   if($result){
    //if number if result is one
    if(mysqli_num_rows($result) == 1){
        //getting response
        $res = mysqli_fetch_assoc($result);
        // sending response back 
        $data = [
            'status' => 200,
            'message' => 'Cart Data Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        // if no response found
        $data = [
            'status' => 404,
            'message' => 'No Data Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data); 
    }

   }else{
    // handeling error
    $data = [
        'status' => 500,
        'message' => 'Internal Server Error',
    ];
    header("HTTP/1.0 500 Internal Server Error");
    return json_encode($data);
   }
}


// updating cart
function updateCart($cartInput,$cartParams){
    // getting connection
    global $conn;
    // if id not exists
    if(!isset($cartParams['cart_id'])){
        return error422('Cart id not Found in URL');
    }else if($cartParams['cart_id'] == null){
        // if id is null
        return error422('Enter Cart Id');
    }

    // getting cart id
    $cart_id = mysqli_real_escape_string($conn,$cartParams['cart_id']);
    //getting other fields
    $product_id = mysqli_real_escape_string($conn,$cartInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$cartInput['user_id']);
    $quantities = mysqli_real_escape_string($conn,$cartInput['quantities']);
    
    //validation
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($quantities))){
        return error422('Enter Product quantities');
    }else{
        // update query 
        $query = "UPDATE cart SET product_id='$product_id',user_id ='$user_id',quantities='$quantities' WHERE cart_id='$cart_id' LIMIT 1";
        // executing data
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Cart Data Updated Successfully',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);
        }else{
            //error handeling
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
    }
}

//delete cart
function deleteCart($cartParams){
    global $conn;
    // if id is not set
    if(!isset($cartParams['cart_id'])){
        return error422('Cart id not Found in URL');
    }else if($cartParams['cart_id'] == null){
        // if id is null
        return error422('Enter Cart Id');
    }
    // getting cart id
    $cart_id = mysqli_real_escape_string($conn,$cartParams['cart_id']);
    // delete query
    $query = "DELETE FROM cart  WHERE cart_id='$cart_id' LIMIT 1";
    // executing query
    $result = mysqli_query($conn,$query);
    // sending response
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Cart Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        // error handeling
        $data = [
            'status' => 404,
            'message' => 'Cart not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>