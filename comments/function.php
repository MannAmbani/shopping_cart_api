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
function storeComments($commentsInput){
    global $conn;
//    comment_id	product_id	user_id		images	comment_text							

    $product_id = mysqli_real_escape_string($conn,$commentsInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$commentsInput['user_id']);
    $rating = mysqli_real_escape_string($conn,$commentsInput['rating']);
    $images = mysqli_real_escape_string($conn,$commentsInput['images']);
    $comment_text = mysqli_real_escape_string($conn,$commentsInput['comment_text']);

    
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
        $query = "INSERT INTO comments (product_id,user_id,rating,images,comment_text) VALUES ('$product_id','$user_id','$rating','$images','$comment_text')";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Comment added Successfully',
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
function getCommentsList()
{
    global $conn;
    $query = "SELECT * FROM comments";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Comment LIST FETCHED SUCCESSFULLY',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        } else {
            $data = [
                'status' => 404,
                'message' => 'No Comment Found',
            ];
            header("HTTP/1.0 404 No Comment Found");
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

function getComments($commentsParams){
    global $conn;
   if($commentsParams['comment_id'] == null){
    return error422('Enter comment id');
   } 

   $commentId = mysqli_real_escape_string($conn,$commentsParams['comment_id']);
   $query = "SELECT * FROM comments WHERE comment_id='$commentId' LIMIT 1";
   $result = mysqli_query($conn, $query);

   if($result){

    if(mysqli_num_rows($result) == 1){
        $res = mysqli_fetch_assoc($result);
        $data = [
            'status' => 200,
            'message' => 'Comment Found',
            'data' => $res
        ];
        header("HTTP/1.0 200 Successful");
        return json_encode($data); 
    }else{
        $data = [
            'status' => 404,
            'message' => 'No Comment Found',
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


function updateComments($commentsInput,$commentsParams){
    global $conn;
    if(!isset($commentsParams['comment_id'])){
        return error422('Comment id not Found in URL');
    }else if($commentsParams['comment_id'] == null){
        return error422('Enter Comment Id');
    }
    $comment_id = mysqli_real_escape_string($conn,$commentsParams['comment_id']);

    $product_id = mysqli_real_escape_string($conn,$commentsInput['product_id']);
    $user_id = mysqli_real_escape_string($conn,$commentsInput['user_id']);
    $rating = mysqli_real_escape_string($conn,$commentsInput['rating']);
    $images = mysqli_real_escape_string($conn,$commentsInput['images']);
    $comment_text = mysqli_real_escape_string($conn,$commentsInput['comment_text']);

    
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
        $query = "UPDATE comments SET product_id='$product_id',user_id ='$user_id',rating='$rating',images='$images',comment_text='$comment_text' WHERE comment_id='$comment_id' LIMIT 1";
        $result = mysqli_query($conn,$query);
        if($result){
            $data = [
                'status' => 200,
                'message' => 'Comment Updated Successfully',
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


function deleteComments($commentsParams){
    global $conn;
    if(!isset($commentsParams['comment_id'])){
        return error422('Comment id not Found in URL');
    }else if($commentsParams['comment_id'] == null){
        return error422('Enter Comment Id');
    }
    $comment_id = mysqli_real_escape_string($conn,$commentsParams['comment_id']);
    $query = "DELETE FROM comments  WHERE comment_id='$comment_id' LIMIT 1";
    $result = mysqli_query($conn,$query);
    if($result){
        $data = [
            'status' => 200,
            'message' => 'Comment Deleted Successfully',
        ];
        header("HTTP/1.0 200 Success");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'Comment not Found',
        ];
        header("HTTP/1.0 404 Unsuccessful");
        return json_encode($data);  
    }

}
?>