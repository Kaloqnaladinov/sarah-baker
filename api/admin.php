<?php

require_once __DIR__ . '/bootstrap.php';

$action = $_GET['action'] ?? '';

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if ($action === 'login') {

    if (requestMethod() !== 'POST') {
        jsonResponse([
            'success' => false,
            'message' => 'Method not allowed.'
        ], 405);
    }

    $data = requestBody();

    $email = trim((string)($data['email'] ?? ''));
    $password = (string)($data['password'] ?? '');

    $stmt = db()->prepare("
        SELECT id, email, password_hash
        FROM admins
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $admin = $stmt->fetch();

    if (
        !$admin ||
        !password_verify($password, $admin['password_hash'])
    ) {
        jsonResponse([
            'success' => false,
            'message' => 'Invalid credentials.'
        ], 401);
    }

    session_regenerate_id(true);

    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_email'] = $admin['email'];

    jsonResponse([
        'success' => true
    ]);
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

if ($action === 'logout') {

    session_unset();
    session_destroy();

    jsonResponse([
        'success' => true
    ]);
}

/*
|--------------------------------------------------------------------------
| ALL ACTIONS BELOW REQUIRE LOGIN
|--------------------------------------------------------------------------
*/

requireAdmin();

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

if ($action === 'products') {

    $stmt = db()->query("
        SELECT
            p.*,
            c.name AS category_name
        FROM products p
        INNER JOIN categories c
            ON c.id = p.category_id
        ORDER BY
            c.sort_order,
            p.sort_order,
            p.name
    ");

    jsonResponse([
        'success' => true,
        'products' => $stmt->fetchAll()
    ]);
}

/*
|--------------------------------------------------------------------------
| TOGGLE PRODUCT AVAILABILITY
|--------------------------------------------------------------------------
*/

if ($action === 'toggle_product') {

    if (requestMethod() !== 'POST') {
        jsonResponse([
            'success' => false
        ], 405);
    }

    $data = requestBody();

    $id = (int)($data['id'] ?? 0);
    $available = !empty($data['available']) ? 1 : 0;

    $stmt = db()->prepare("
        UPDATE products
        SET available = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $available,
        $id
    ]);

    jsonResponse([
        'success' => true
    ]);
}

/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

if ($action === 'orders') {

    $stmt = db()->query("
        SELECT
            id,
            customer_name,
            customer_phone,
            customer_email,
            order_type,
            status,
            notes,
            total,
            created_at
        FROM orders
        ORDER BY created_at DESC
        LIMIT 100
    ");

    jsonResponse([
        'success' => true,
        'orders' => $stmt->fetchAll()
    ]);
}

/*
|--------------------------------------------------------------------------
| UPDATE ORDER STATUS
|--------------------------------------------------------------------------
*/

if ($action === 'update_order') {

    if (requestMethod() !== 'POST') {
        jsonResponse([
            'success' => false
        ], 405);
    }

    $data = requestBody();

    $id = (int)($data['id'] ?? 0);
    $status = (string)($data['status'] ?? '');

    $allowed = [
        'pending',
        'confirmed',
        'preparing',
        'completed',
        'cancelled'
    ];

    if (!in_array($status, $allowed, true)) {
        jsonResponse([
            'success' => false,
            'message' => 'Invalid status.'
        ], 422);
    }

    $stmt = db()->prepare("
        UPDATE orders
        SET status = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $status,
        $id
    ]);

    jsonResponse([
        'success' => true
    ]);
}

jsonResponse([
    'success' => false,
    'message' => 'Unknown action.'
], 404);
