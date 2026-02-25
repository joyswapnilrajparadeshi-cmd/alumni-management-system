<?php
include 'db_connection.php'; // Include the database connection file

try {
    // Step 1: Establish the database connection
    $conn = getDatabaseConnection();

    // Step 2: Write the SQL query to fetch donation records
    $query = "SELECT id, user_id, amount, created_at, payment_method, payment_status, transaction_id FROM donations";

    // Step 3: Execute the query
    $stmt = $conn->query($query);

    // Step 4: Fetch the data as an associative array
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 5: Check if there are any records
    if (empty($donations)) {
        echo "No donation records found.";
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching donation records: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Donation Records</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount (₹)</th>
            <th>Date</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Transaction ID</th>
        </tr>
        <?php foreach ($donations as $donation): ?>
            <tr>
                <td><?= htmlspecialchars($donation['id']) ?></td>
                <td><?= htmlspecialchars($donation['user_id']) ?></td>
                <td><?= htmlspecialchars($donation['amount']) ?></td>
                <td><?= htmlspecialchars($donation['created_at']) ?></td>
                <td><?= htmlspecialchars($donation['payment_method']) ?></td>
                <td><?= htmlspecialchars($donation['payment_status']) ?></td>
                <td><?= htmlspecialchars($donation['transaction_id']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
