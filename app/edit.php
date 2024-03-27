<?php
if(isset($_POST['id']) && isset($_POST['title'])){
    require '../db_conn.php';

    $id = $_POST['id'];
    $title = $_POST['title'];

    if(empty($id) || empty($title)){
       echo 'error';
    }else {
        $stmt = $conn->prepare("UPDATE todos SET title = ? WHERE id = ?");
        $result = $stmt->execute([$title, $id]);

        if($result){
            echo '<script>alert("Success! Todo item updated."); window.location.href="../index.php";</script>';
        }else {
            echo "error";
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}
?>
