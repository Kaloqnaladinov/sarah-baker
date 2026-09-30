<?php

require_once __DIR__ . '/bootstrap.php';

try {

    $stmt = db()->query("
        SELECT
            p.id,
            p.category_id,
            p.name,
            p.description,
            p.price,
            p.image_url,
            p.available,
            p.featured,
            c.name AS category_name,
            c.slug AS category_slug

        FROM products p

        INNER JOIN categories c
            ON c.id = p.category_id

        WHERE c.active = 1

        ORDER BY
            c.sort_order ASC,
            p.sort_order ASC,
            p.name ASC
    ");

    $products = $stmt->fetchAll();

    foreach ($products as &$product) {
        $product['price'] = (float) $product['price'];
        $product['available'] = (bool) $product['available'];
        $product['featured'] = (bool) $product['featured'];
    }

    jsonResponse([
        'success' => true,
        'products' => $products
    ]);

} catch (Throwable $e) {

    jsonResponse([
        'success' => false,
        'message' => 'Unable to load products.'
    ], 500);
}
