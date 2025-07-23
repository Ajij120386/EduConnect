<?php
include '../conn.php';

$term = isset($_GET['term']) ? trim($_GET['term']) : '';
if ($term !== '') {
    $term = "%{$term}%";
    $stmt = $conn->prepare("SELECT id, first_name, last_name FROM user_data WHERE CONCAT(first_name, ' ', last_name) LIKE ?");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'label' => $row['first_name'] . ' ' . $row['last_name'],
            'value' => $row['id']
        ];
    }

    echo json_encode($users);
}
?>
