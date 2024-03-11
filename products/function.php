<?php
require '../config.php';

// error
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

// insert
function storeProduct($productInput){
    global $conn;
    $product_name = mysqli_real_escape_string($conn,$productInput['product_name']);
    $description = mysqli_real_escape_string($conn,$productInput['description']);
    $image = mysqli_real_escape_string($conn,$productInput['image']);
    $pricing = mysqli_real_escape_string($conn,$productInput['pricing']);
    $shipping_cost = mysqli_real_escape_string($conn,$productInput['shipping_cost']);
    
    if(empty(trim($product_name))){
        return error422('Enter Product name');
    }else if(empty(trim($description))){
        return error422('Enter Product description');
    }else if(empty(trim($image))){
        return error422('Enter Product image');
    }else if(empty(trim($pricing))){
        return error422('Enter Product pricing');
    }else if(empty(trim($shipping_cost))){
        return error422('Enter Product shipping cost');
    }else{
        $query = "INSERT INTO product (product_name,description,image,pricing,shipping_cost) VALUES ('$product_name','$description','$image','$pricing','$shipping_cost')";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Product Created Successfully',
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
// get list of data
function getProductList()
{
    global $conn;
    $query = "SELECT * FROM product";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'PRODUCT LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            $data = [
                'status' => 404,
                'message' => 'No Product Found',
            ];
            header("HTTP/1.0 404 No Product Found");
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

// get data
function getProduct($productParams){
    global $conn;
   if($productParams['product_id'] == null){
    return error422('Enter product id');
   } 

   $productId = mysqli_real_escape_string($conn,$productParams['product_id']);
   $query = "SELECT * FROM product WHERE product_id='$productId' LIMIT 1";
   $result = mysqli_query($conn, $query);

   if($result){

    if(mysqli_num_rows($result) == 1){
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'Product Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        $data = [
            'status' => 404,
            'message' => 'No Product Found',
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

// update
function updateProduct($productInput,$productParams){
    global $conn;
    if(!isset($productParams['product_id'])){
        return error422('Product id not Found in URL');
    }else if($productParams['product_id'] == null){
        return error422('Enter Product Id');
    }
    $product_id = mysqli_real_escape_string($conn,$productParams['product_id']);

    $product_name = mysqli_real_escape_string($conn,$productInput['product_name']);
    $description = mysqli_real_escape_string($conn,$productInput['description']);
    $image = mysqli_real_escape_string($conn,$productInput['image']);
    $pricing = mysqli_real_escape_string($conn,$productInput['pricing']);
    $shipping_cost = mysqli_real_escape_string($conn,$productInput['shipping_cost']);
    
    if(empty(trim($product_name))){
        return error422('Enter Product name');
    }else if(empty(trim($description))){
        return error422('Enter Product description');
    }else if(empty(trim($image))){
        return error422('Enter Product image');
    }else if(empty(trim($pricing))){
        return error422('Enter Product pricing');
    }else if(empty(trim($shipping_cost))){
        return error422('Enter Product shipping cost');
    }else{
        $query = "UPDATE product SET product_name='$product_name',description ='$description',image='$image',pricing='$pricing',shipping_cost='$shipping_cost' WHERE product_id='$product_id' LIMIT 1";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Product Updated Successfully',
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


// delete
function deleteProduct($productParams){
    global $conn;
    if(!isset($productParams['product_id'])){
        return error422('Product id not Found in URL');
    }else if($productParams['product_id'] == null){
        return error422('Enter Product Id');
    }
    $product_id = mysqli_real_escape_string($conn,$productParams['product_id']);
    $query = "DELETE FROM product  WHERE product_id='$product_id' LIMIT 1";
    $result = mysqli_query($conn,$query);
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Product Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'Product not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>