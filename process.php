<?php
require 'config.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

if($action == 'add'){
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $daily_rate = $_POST['daily_rate'];
    $status = $_POST['status'];

    $sql = "INSERT INTO cars (brand, model, year, color, daily_rate, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$brand, $model, $year, $color, $daily_rate, $status])){
        echo json_encode(['status' => 'success', 'message' => 'Car added successfully!']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Failed to add car.']);
    }
}

if($action == 'fetch'){
    $sql = "SELECT * FROM cars ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $output = '';
    if(count($cars) > 0){
        foreach($cars as $car){
            $statusBadge = $car['status'] == 'Available' ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-danger">Rented</span>';
            $output .= '<tr>
                <td>'.$car['id'].'</td>
                <td>'.$car['brand'].'</td>
                <td>'.$car['model'].'</td>
                <td>'.$car['year'].'</td>
                <td>'.$car['color'].'</td>
                <td>$'.number_format($car['daily_rate'], 2).'</td>
                <td>'.$statusBadge.'</td>
                <td>
                    <button class="btn btn-sm btn-info editBtn" data-id="'.$car['id'].'" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$car['id'].'">Delete</button>
                </td>
            </tr>';
        }
    }else{
        $output = '<tr><td colspan="8" class="text-center text-muted">No cars available. Add some!</td></tr>';
    }
    echo $output;
}

if($action == 'edit_fetch'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($car);
}

if($action == 'update'){
    $id = $_POST['id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $daily_rate = $_POST['daily_rate'];
    $status = $_POST['status'];

    $sql = "UPDATE cars SET brand=?, model=?, year=?, color=?, daily_rate=?, status=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$brand, $model, $year, $color, $daily_rate, $status, $id])){
        echo json_encode(['status' => 'success', 'message' => 'Car updated successfully!']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Failed to update car.']);
    }
}

if($action == 'delete'){
    $id = $_POST['id'];
    $sql = "DELETE FROM cars WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$id])){
        echo json_encode(['status' => 'success', 'message' => 'Car deleted successfully!']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete car.']);
    }
}
?>
