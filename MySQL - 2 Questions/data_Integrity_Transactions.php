<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->beginTransaction();

    // Assuming user ID is stored in session
    session_start();
    $user_id = $_SESSION['user_id'] ?? 1; // Defaulting to user ID 1 for now

    // Fetch cart items from database (or session)
    $stmt = $pdo->query("SELECT product_id, quantity, price FROM cart WHERE user_id = $user_id");
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        throw new Exception("Cart is empty. Cannot place order.");
    }

    // Calculate total price
    $total_price = 0;
    foreach ($cartItems as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }

    // Step 1: Insert the Order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $total_price, 'Pending']);
    $orderId = $pdo->lastInsertId();

    // Step 2: Insert Order Items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Step 3: Insert Payment Record
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_status) VALUES (?, ?, ?)");
    $stmt->execute([$orderId, $total_price, 'Completed']);

    // Step 4: Commit the transaction
    $pdo->commit();
    echo "Order placed successfully!";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed to place order: " . $e->getMessage();
}

?>
