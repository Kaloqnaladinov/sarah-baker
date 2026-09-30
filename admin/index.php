<?php

session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: /admin/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>Sarah Baker Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-stone-100">

<header class="bg-white border-b">

<div class="max-w-6xl mx-auto px-5 py-5 flex justify-between">

    <div>
        <h1 class="font-semibold text-xl">
            Sarah Baker Admin
        </h1>

        <p class="text-sm text-stone-500">
            Menu & orders
        </p>
    </div>

    <button
        onclick="logout()"
        class="text-sm text-red-600"
    >
        Logout
    </button>

</div>

</header>


<main class="max-w-6xl mx-auto px-5 py-10">

    <section>

        <h2 class="text-2xl font-semibold">
            Menu availability
        </h2>

        <p class="text-stone-500 mt-2">
            Quickly mark products available or sold out.
        </p>

        <div
            id="products"
            class="mt-8 grid md:grid-cols-2 gap-4"
        >
            Loading...
        </div>

    </section>


    <section class="mt-16">

        <h2 class="text-2xl font-semibold">
            Recent orders
        </h2>

        <div
            id="orders"
            class="mt-8 space-y-4"
        >
            Loading...
        </div>

    </section>

</main>


<script>

async function api(url, options = {}) {

    const response =
        await fetch(url, options);

    const data =
        await response.json();

    if (response.status === 401) {
        window.location.href =
            '/admin/login.php';
    }

    return data;
}


async function loadProducts() {

    const data = await api(
        '/api/admin.php?action=products'
    );

    const container =
        document.getElementById('products');

    container.innerHTML =
        data.products.map(product => `

            <div
                class="bg-white rounded-2xl p-5
                       flex items-center justify-between"
            >

                <div>

                    <h3 class="font-medium">
                        ${escapeHtml(product.name)}
                    </h3>

                    <p class="text-sm text-stone-500">
                        ${escapeHtml(product.category_name)}
                    </p>

                    <p class="mt-1">
                        €${Number(product.price).toFixed(2)}
                    </p>

                </div>

                <button
                    onclick="toggleProduct(
                        ${product.id},
                        ${product.available ? 'false' : 'true'}
                    )"
                    class="
                        px-4 py-2 rounded-full text-sm
                        ${
                            product.available
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800'
                        }
                    "
                >
                    ${
                        product.available
                            ? 'Available'
                            : 'Sold out'
                    }
                </button>

            </div>

        `).join('');
}


async function toggleProduct(id, available) {

    await api(
        '/api/admin.php?action=toggle_product',
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body: JSON.stringify({
                id,
                available
            })
        }
    );

    loadProducts();
}


async function loadOrders() {

    const data = await api(
        '/api/admin.php?action=orders'
    );

    const container =
        document.getElementById('orders');

    if (!data.orders.length) {

        container.innerHTML = `
            <p class="text-stone-500">
                No orders yet.
            </p>
        `;

        return;
    }

    container.innerHTML =
        data.orders.map(order => `

            <div
                class="bg-white rounded-2xl p-5"
            >

                <div class="flex justify-between">

                    <div>

                        <h3 class="font-medium">
                            ${escapeHtml(order.customer_name)}
                        </h3>

                        <p class="text-sm text-stone-500">
                            ${escapeHtml(order.customer_phone)}
                        </p>

                    </div>

                    <strong>
                        €${Number(order.total).toFixed(2)}
                    </strong>

                </div>

                <div class="mt-4">

                    <select
                        onchange="
                            updateOrder(
                                ${order.id},
                                this.value
                            )
                        "
                        class="border rounded-lg px-3 py-2"
                    >

                        ${[
                            'pending',
                            'confirmed',
                            'preparing',
                            'completed',
                            'cancelled'
                        ].map(status => `

                            <option
                                value="${status}"
                                ${order.status === status
                                    ? 'selected'
                                    : ''}
                            >
                                ${status}
                            </option>

                        `).join('')}

                    </select>

                </div>

            </div>

        `).join('');
}


async function updateOrder(id, status) {

    await api(
        '/api/admin.php?action=update_order',
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body: JSON.stringify({
                id,
                status
            })
        }
    );

    loadOrders();
}


async function logout() {

    await api(
        '/api/admin.php?action=logout'
    );

    window.location.href =
        '/admin/login.php';
}


function escapeHtml(value) {

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}


loadProducts();
loadOrders();

</script>

</body>
</html>
