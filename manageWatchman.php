<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();


$method=$_SERVER['REQUEST_METHOD'];

switch($method){
    case "GET":
        $sql = "SELECT * FROM signup";
        $stmt = $conn->prepare($sql);
        $stmt -> execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;
        
    case "POST":
        $user=json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO signup(f_name,l_name,u_name,dob,gender,email,pass1,mobile,role1) VALUES(:firstname,:lastname,:username,:occupantdob,:gender,:email,:pass2,:occupant_mobile,:Role1)";  
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':firstname',$user->firstname);
        $stmt->bindParam(':lastname',$user->lastname);
        $stmt->bindParam(':username',$user->username);
        $stmt->bindParam(':occupantdob',$user->occupantdob);
        $stmt->bindParam(':gender',$user->gender);
        $stmt->bindParam(':email',$user->email);
        $stmt->bindParam(':pass2',$user->pass2);
        $stmt->bindParam(':occupant_mobile',$user->occupant_mobile);
        $stmt->bindParam(':Role1',$user->Role1);
        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;
    }