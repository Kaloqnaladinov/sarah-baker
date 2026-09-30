<?php
session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
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

<body class="bg-stone-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-2xl shadow-sm w-full max-w-md">

    <h1 class="text-2xl font-semibold">
        Sarah Baker Admin
    </h1>

    <p class="text-stone-500 mt-2">
        Sign in to manage the menu.
    </p>

    <form id="login-form" class="mt-8 space-y-5">

        <input
            id="email"
            type="email"
            placeholder="Email"
            value="admin@sarahbaker.local"
            required
            class="w-full border rounded-xl px-4 py-3"
        >

        <input
            id="password"
            type="password"
            placeholder="Password"
            required
            class="w-full border rounded-xl px-4 py-3"
        >

        <button
            class="w-full bg-stone-900 text-white rounded-xl py-3"
        >
            Sign in
        </button>

        <p
            id="error"
            class="text-red-600 text-sm hidden"
        ></p>

    </form>

</div>

<script>

document
    .getElementById('login-form')
    .addEventListener('submit', async function(event) {

        event.preventDefault();

        const response = await fetch(
            '/api/admin.php?action=login',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email:
                        document.getElementById('email').value,

                    password:
                        document.getElementById('password').value
                })
            }
        );

        const data = await response.json();

        if (data.success) {
            window.location.href = '/admin/index.php';
            return;
        }

        const error =
            document.getElementById('error');

        error.textContent =
            data.message || 'Login failed.';

        error.classList.remove('hidden');
    });

</script>

</body>
</html>
