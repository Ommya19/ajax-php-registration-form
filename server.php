<?php
$conn = new mysqli("localhost", "root", "", "user_db");

$action = $_GET['action'] ?? '';

// Fetch Data for the Grid
if ($action == 'list') {
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

// Save or Update
if ($action == 'save') {
    $id = $_POST['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    $imagePath = "";
    // Handle Image if uploaded
    if (!empty($_FILES['image']['name'])) {
        $folder = "uploads/";
        if (!is_dir($folder)) mkdir($folder);
        $imagePath = $folder . time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    if (!empty($id)) {
        // Update existing record
        $updateImg = $imagePath ? ", image_path='$imagePath'" : "";
        $query = "UPDATE users SET name='$name', email='$email', address='$address', phone='$phone' $updateImg WHERE id=$id";
    } else {
        // Insert new record
        $query = "INSERT INTO users (name, email, address, phone, image_path) VALUES ('$name', '$email', '$address', '$phone', '$imagePath')";
    }

    if ($conn->query($query)) echo "Data saved successfully!";
    else echo "Error: " . $conn->error;
    exit;
}
?>