<?php
//database comfiguration connection file
require '../config.php';


//functio handeling error 422 and response
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

//storing data
function storeComments($commentsInput){
    //global value for connection
    global $conn;
//    comment_id	product_id	user_id		images	comment_text							

//getting table fields from data 
    $product_id = mysqli_real_escape_string($conn,$commentsInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$commentsInput['user_id']);
    $rating = mysqli_real_escape_string($conn,$commentsInput['rating']);
    $images = mysqli_real_escape_string($conn,$commentsInput['images']);
    $comment_text = mysqli_real_escape_string($conn,$commentsInput['comment_text']);

    //validating input data
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($rating))){
        return error422('Enter rating');
    }else if(empty(trim($images))){
        return error422('Insert Images');
    }else if(empty(trim($comment_text))){
        return error422('Enter Comment');
    }else{
        //insert data query
        $query = "INSERT INTO comments (product_id,user_id,rating,images,comment_text) VALUES ('$product_id','$user_id','$rating','$images','$comment_text')";
        //execute query
        $result = mysqli_query($conn,$query);
        //if result true
        if($result){
            //success response
            $data = [
                'status' => 201,
                'message' => 'Comment added Successfully',
            ];
            header("HTTP/1.0 201 Success");
            return json_encode($data);
        }else{
            //error response
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
    }





}
// getting list of data
function getCommentsList()
{
    global $conn;
    // select query
    $query = "SELECT * FROM comments";
    // executing query
    $query_run = mysqli_query($conn, $query);
    // if success
    if ($query_run) {
        // if there are more data then 0
        if (mysqli_num_rows($query_run) > 0) {
            // fetching all data from database
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Comment LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            //if there are no comments
            $data = [
                'status' => 404,
                'message' => 'No Comment Found',
            ];
            header("HTTP/1.0 404 No Comment Found");
            return json_encode($data);

        }
    } else {
        // server error
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);

    }
}

// getting single data
function getComments($commentsParams){
    global $conn;
    // checking if id is null
   if($commentsParams['comment_id'] == null){
    return error422('Enter comment id');
   } 

   //getting id
   $commentId = mysqli_real_escape_string($conn,$commentsParams['comment_id']);
   //select query
   $query = "SELECT * FROM comments WHERE comment_id='$commentId' LIMIT 1";
   //executing query
   $result = mysqli_query($conn, $query);

//    if success
   if($result){
    // check for one output
    if(mysqli_num_rows($result) == 1){
        //fetching single data
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'Comment Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        // if no comments found
        $data = [
            'status' => 404,
            'message' => 'No Comment Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data); 
    }

   }else{
    // server error
    $data = [
        'status' => 500,
        'message' => 'Internal Server Error',
    ];
    header("HTTP/1.0 500 Internal Server Error");
    return json_encode($data);
   }
}


//update function
function updateComments($commentsInput,$commentsParams){
    global $conn;
    // check if id is set or not
    if(!isset($commentsParams['comment_id'])){
        return error422('Comment id not Found in URL');
    }else if($commentsParams['comment_id'] == null){
        //if id is null
        return error422('Enter Comment Id');
    }

    // getting data 
    $comment_id = mysqli_real_escape_string($conn,$commentsParams['comment_id']);

    $product_id = mysqli_real_escape_string($conn,$commentsInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$commentsInput['user_id']);
    $rating = mysqli_real_escape_string($conn,$commentsInput['rating']);
    $images = mysqli_real_escape_string($conn,$commentsInput['images']);
    $comment_text = mysqli_real_escape_string($conn,$commentsInput['comment_text']);

    //validation
    if(empty(trim($product_id))){
        return error422('Enter Product Id');
    }else if(empty(trim($user_id))){
        return error422('Enter User Id');
    }else if(empty(trim($rating))){
        return error422('Enter rating');
    }else if(empty(trim($images))){
        return error422('Insert Images');
    }else if(empty(trim($comment_text))){
        return error422('Enter Comment');
    }else{
        // update query
        $query = "UPDATE comments SET product_id='$product_id',user_id ='$user_id',rating='$rating',images='$images',comment_text='$comment_text' WHERE comment_id='$comment_id' LIMIT 1";
        // executing query
        $result = mysqli_query($conn,$query);
        // result success
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Comment Updated Successfully',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);
        }else{
            // result fail
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
function deleteComments($commentsParams){
    global $conn;
    // check for id
    if(!isset($commentsParams['comment_id'])){
        return error422('Comment id not Found in URL');
    }else if($commentsParams['comment_id'] == null){
        // if id is null
        return error422('Enter Comment Id');
    }
    //getting id
    $comment_id = mysqli_real_escape_string($conn,$commentsParams['comment_id']);
    // delete query
    $query = "DELETE FROM comments  WHERE comment_id='$comment_id' LIMIT 1";
    // executing data
    $result = mysqli_query($conn,$query);
    // if success
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Comment Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        // if fail
        $data = [
            'status' => 404,
            'message' => 'Comment not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>