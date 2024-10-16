<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'connectphp.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', $_SERVER['REQUEST_URI']);

switch ($method) {
    case "GET":
        $sql = "SELECT * FROM building";
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $building = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $building = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($building);
        break;

    case "POST":
        $building = json_decode(file_get_contents('php://input'));
        
        // Check if all required fields are present
        if (isset($building->building_name) && isset($building->floor_count) && isset($building->create_by)) {
            $sql = "INSERT INTO building (building_name, floor_count, status_active, create_by, create_date, update_by, update_date) 
                    VALUES (:building_name, :floor_count, :status_active, :create_by, :create_date, :update_by, :update_date)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':building_name', $building->building_name);
            $stmt->bindParam(':floor_count', $building->floor_count);
            $stmt->bindParam(':status_active', $building->status_active);
            $stmt->bindParam(':create_by', $building->create_by);
            $stmt->bindParam(':create_date', $building->create_date);
            $stmt->bindParam(':update_by', $building->update_by);
            $stmt->bindParam(':update_date', $building->update_date);

            if ($stmt->execute()) {
                $response = ['status' => 1, 'message' => 'Record created successfully.'];
            } else {
                $response = ['status' => 0, 'message' => 'Failed to create record.'];
            }
        } else {
            $response = ['status' => 0, 'message' => 'Invalid input data.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql = "DELETE FROM building WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);

            if ($stmt->execute()) {
                $response = ['status' => 1, 'message' => 'Delete successfully.'];
            } else {
                $response = ['status' => 0, 'message' => 'Failed to delete record.'];
            }
        } else {
            $response = ['status' => 0, 'message' => 'Invalid ID.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        if (isset($path[3]) && is_numeric($path[3])) {
            $building = json_decode(file_get_contents('php://input'));

            $sql = "UPDATE building SET building_name = :building_name, floor_count = :floor_count, 
                    status_active = :status_active, create_by = :create_by, create_date = :create_date, 
                    update_by = :update_by, update_date = :update_date 
                    WHERE id = :id"; 

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->bindParam(':building_name', $building->building_name);
            $stmt->bindParam(':floor_count', $building->floor_count);
            $stmt->bindParam(':status_active', $building->status_active);
            $stmt->bindParam(':create_by', $building->create_by);
            $stmt->bindParam(':create_date', $building->create_date);
            $stmt->bindParam(':update_by', $building->update_by);
            $stmt->bindParam(':update_date', $building->update_date);

            if ($stmt->execute()) {
                $response = ['status' => 1, 'message' => 'Record updated successfully.'];
            } else {
                $response = ['status' => 0, 'message' => 'Failed to update record.'];
            }
        } else {
            $response = ['status' => 0, 'message' => 'Invalid ID.'];
        }
        echo json_encode($response);
        break;
}
?>
