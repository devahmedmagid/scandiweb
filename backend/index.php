<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();


$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $sql = "SELECT * FROM addproduct";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt-> fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
        break;
    case 'POST':
        $product = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO addProduct(sku,name,price,switcher,size,weight,height,width,length,checked,id) VALUES(:sku,:name,:price,:switcher,:size,:weight,:height,:width,:length,:checked,:id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sku', $product->sku);
        $stmt->bindParam(':id', $product->id);
        $stmt->bindParam(':name', $product->name);
        $stmt->bindParam(':price', $product->price);
        $stmt->bindParam(':switcher', $product->switcher);
        $stmt->bindParam(':size', $product->size);
        $stmt->bindParam(':weight', $product->weight);
        $stmt->bindParam(':height', $product->height);
        $stmt->bindParam(':width', $product->width);
        $stmt->bindParam(':length', $product->length);
        $stmt->bindParam(':checked', $product->checked);

        
        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Created!'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed!'];
        }
        break;


    case "PUT":
        $product = json_decode( file_get_contents('php://input') );
        $sql = "UPDATE addproduct SET checked = :checked WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $product->id);
        $stmt->bindParam(':checked', $product->checked);
           
    
            if($stmt->execute()) {
                $response = ['status' => 1, 'message' => 'Record updated successfully.'];
            } else {
                $response = ['status' => 0, 'message' => 'Failed to update record.'];
            }
            echo json_encode($response);
            break;
    
   
    case 'DELETE':
        $sql = "DELETE FROM addproduct WHERE checked=true";
        $stmt = $conn->prepare($sql);
       

        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }
        echo json_encode($response);
        break;


} 
?>