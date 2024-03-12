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
function storeUser($userInput){
    global $conn;

    // getting data
    $email = mysqli_real_escape_string($conn,$userInput['email']);
    $password = mysqli_real_escape_string($conn,$userInput['password']);
    $username = mysqli_real_escape_string($conn,$userInput['username']);
    $purchase_history = mysqli_real_escape_string($conn,$userInput['purchase_history']);
    $shipping_address = mysqli_real_escape_string($conn,$userInput['shipping_address']);
   
    // validation
    if(empty(trim($email))){
        return error422('Enter email');
    }else if(empty(trim($password))){
        return error422('Enter password');
    }else if(empty(trim($username))){
        return error422('Enter username');
    }else if(empty(trim($purchase_history))){
        return error422('Enter purchase history');
    }else if(empty(trim($shipping_address))){
        return error422('Enter shipping address');
    }else{
        // insert query
        $query = "INSERT INTO user (email,password,username,purchase_history,shipping_address) VALUES ('$email','$password','$username','$purchase_history','$shipping_address')";
    //    exexuting query
        $result = mysqli_query($conn,$query);
        // handeling response
        if($result){
            $data = [
                'status' => 201,
                'message' => 'User Created Successfully',
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

// get lis of data
function getUserList()
{
    global $conn;

    // select query for all data
    $query = "SELECT * FROM user";
    // executing query
    $query_run = mysqli_query($conn, $query);
    // handeling response
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'User LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            $data = [
                'status' => 404,
                'message' => 'No User Found',
            ];
            header("HTTP/1.0 404 No User Found");
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

// get single data
function getUser($userParams){
    global $conn;
    // check for user id 
   if($userParams['user_id'] == null){
    return error422('Enter User id');
   } 
//    getting user id
   $userId = mysqli_real_escape_string($conn,$userParams['user_id']);
//    select quoery for one output
   $query = "SELECT * FROM user WHERE user_id='$userId' LIMIT 1";
//    executing query
   $result = mysqli_query($conn, $query);
// handeling response
   if($result){

    if(mysqli_num_rows($result) == 1){
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'User Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        $data = [
            'status' => 404,
            'message' => 'No User Found',
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


// update data

function updateUser($userInput,$userParams){
    global $conn;
    // checking for id
    if(!isset($userParams['user_id'])){
        return error422('user id not Found in URL');
    }else if($userParams['user_id'] == null){
        return error422('Enter user Id');
    }
    // getting data
    $user_id = mysqli_real_escape_string($conn,$userParams['user_id']);

    $email = mysqli_real_escape_string($conn,$userInput['email']);
    $password = mysqli_real_escape_string($conn,$userInput['password']);
    $username = mysqli_real_escape_string($conn,$userInput['username']);
    $purchase_history = mysqli_real_escape_string($conn,$userInput['purchase_history']);
    $shipping_address = mysqli_real_escape_string($conn,$userInput['shipping_address']);
   
    // validation
    if(empty(trim($email))){
        return error422('Enter email');
    }else if(empty(trim($password))){
        return error422('Enter password');
    }else if(empty(trim($username))){
        return error422('Enter username');
    }else if(empty(trim($purchase_history))){
        return error422('Enter purchase history');
    }else if(empty(trim($shipping_address))){
        return error422('Enter shipping address');
    }else{
        // update query
        $query = "UPDATE user SET email='$email',password ='$password',username='$username',purchase_history='$purchase_history',shipping_address='$shipping_address' WHERE user_id='$user_id' LIMIT 1";
        // execuing query
        $result = mysqli_query($conn,$query);
        // handeling response
        if($result){
            $data = [
                'status' => 200,
                'message' => 'User Updated Successfully',
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
function deleteUser($userParams){
    global $conn;
    // check for id
    if(!isset($userParams['user_id'])){
        return error422('user id not Found in URL');
    }else if($userParams['user_id'] == null){
        return error422('Enter user Id');
    }
    // getting id
    $user_id = mysqli_real_escape_string($conn,$userParams['user_id']);
    // delete query
    $query = "DELETE FROM user  WHERE user_id='$user_id' LIMIT 1";
    // executing query
    $result = mysqli_query($conn,$query);
    // handeling response
    if($result){
        $data = [
            'status' => 200,
            'message' => 'user Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'user not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>