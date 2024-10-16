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
        $sql = "SELECT * FROM meeting_room";
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $meeting_room = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $meeting_room = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($meeting_room);
        break;

    case "POST":
        $meeting_room = json_decode(file_get_contents('php://input'));

        if (!isset($meeting_room->create_date) || empty($meeting_room->create_date)) {
            $meeting_room->create_date = date('Y-m-d');
        } else {
            $meeting_room->create_date = date('Y-m-d', strtotime($meeting_room->create_date));
        }

        $building_id = $meeting_room->building_id;
        $sql_check = "SELECT id FROM building WHERE id = :id"; // Corrected the table name
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':id', $building_id);
        $stmt_check->execute();
        if ($stmt_check->rowCount() == 0) {
            $response = ['status' => 0, 'message' => 'Invalid Building ID.'];
            echo json_encode($response);
            exit;
        }

        $sql = "INSERT INTO meeting_room(id, room_name, room_type, room_size, floor, building_id, status_active, create_by, create_date, 
                update_by, update_date) 
                VALUES(null, :room_name, :room_type, :room_size, :floor, :building_id, :status_active, :create_by, :create_date, 
                :update_by, :update_date)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':room_name', $meeting_room->room_name);
        $stmt->bindParam(':room_type', $meeting_room->room_type);
        $stmt->bindParam(':room_size', $meeting_room->room_size);
        $stmt->bindParam(':floor', $meeting_room->floor);
        $stmt->bindParam(':building_id', $meeting_room->building_id);
        $stmt->bindParam(':status_active', $meeting_room->status_active);
        $stmt->bindParam(':create_by', $meeting_room->create_by);
        $stmt->bindParam(':create_date', $meeting_room->create_date);
        $stmt->bindParam(':update_by', $meeting_room->update_by);
        $stmt->bindParam(':update_date', $meeting_room->update_date);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql = "DELETE FROM meeting_room WHERE id = :id";
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
        if (!isset($path[3]) || !is_numeric($path[3])) {
            $response = ['status' => 0, 'message' => 'Invalid ID.'];
            echo json_encode($response);
            exit;
        }

        $meeting_room = json_decode(file_get_contents('php://input'));

        if (!isset($meeting_room->create_date) || empty($meeting_room->create_date)) {
            $meeting_room->create_date = date('Y-m-d');
        } else {
            $meeting_room->create_date = convertDateToYYYYMMDD($meeting_room->create_date);
        }

        try {
            $sql = "UPDATE meeting_room SET room_name=:room_name, room_type=:room_type, room_size=:room_size, floor=:floor, building_id=:building_id, 
            status_active=:status_active, create_by=:create_by, create_date=:create_date, update_by=:update_by, update_date=:update_date 
            WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':room_name', $meeting_room->room_name);
            $stmt->bindParam(':room_type', $meeting_room->room_type);
            $stmt->bindParam(':room_size', $meeting_room->room_size);
            $stmt->bindParam(':floor', $meeting_room->floor);
            $stmt->bindParam(':building_id', $meeting_room->building_id);
            $stmt->bindParam(':status_active', $meeting_room->status_active);
            $stmt->bindParam(':create_by', $meeting_room->create_by);
            $stmt->bindParam(':create_date', $meeting_room->create_date);
            $stmt->bindParam(':update_by', $meeting_room->update_by);
            $stmt->bindParam(':update_date', $meeting_room->update_date);
            $stmt->bindParam(':id', $path[3]);

            if ($stmt->execute()) {
                http_response_code(200);
                $response = ['status' => 1, 'message' => 'Record updated successfully.'];
            } else {
                http_response_code(500);
                $response = ['status' => 0, 'message' => 'Failed to update record.'];
            }
        } catch (PDOException $e) {
            http_response_code(500);
            $response = ['status' => 0, 'message' => 'Error: ' . $e->getMessage()];
        }

        echo json_encode($response);
        break;
}
?>
