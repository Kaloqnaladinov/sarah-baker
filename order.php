<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order — Sarah Baker Coffee</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-stone-50 text-stone-900">


<header class="bg-white border-b">

    <div
        class="max-w-6xl mx-auto px-5 py-5
               flex items-center justify-between"
    >

        <a
            href="/"
            class="font-bold text-xl"
        >
            SARAH BAKER
        </a>

        <a
            href="/menu.php"
            class="text-sm text-stone-600"
        >
            ← Back to menu
        </a>

    </div>

</header>


<main class="max-w-5xl mx-auto px-5 py-16">


    <div class="max-w-2xl mb-10">

        <p class="uppercase tracking-widest text-sm">
            Sarah Baker Coffee
        </p>

        <h1 class="text-5xl font-semibold mt-3">
            Your order
        </h1>

        <p class="mt-4 text-stone-600">
            Select your items and send your pickup request.
        </p>

    </div>


    <div class="grid lg:grid-cols-2 gap-10">


        <!-- CART -->

        <section>

            <h2 class="text-xl font-semibold mb-5">
                Your items
            </h2>

            <div
                id="cart"
                class="space-y-4"
            ></div>

            <div
                id="cart-total"
                class="mt-6 pt-6 border-t
                       flex justify-between
                       text-xl font-semibold"
            >
                <span>Total</span>
                <span>€0.00</span>
            </div>

        </section>


        <!-- CUSTOMER DETAILS -->

        <section>

            <div class="bg-white rounded-2xl p-6 border">

                <h2 class="text-xl font-semibold">
                    Pickup details
                </h2>

                <form
                    id="order-form"
                    class="mt-6 space-y-5"
                >

                    <div>

                        <label
                            class="block text-sm mb-2"
                        >
                            Name
                        </label>

                        <input
                            id="customer_name"
                            required
                            class="w-full border rounded-xl
                                   px-4 py-3"
                            placeholder="Your name"
                        >

                    </div>


                    <div>

                        <label
                            class="block text-sm mb-2"
                        >
                            Phone
                        </label>

                        <input
                            id="customer_phone"
                            required
                            type="tel"
                            class="w-full border rounded-xl
                                   px-4 py-3"
                            placeholder="+33..."
                        >

                    </div>


                    <div>

                        <label
                            class="block text-sm mb-2"
                        >
                            Email
                        </label>

                        <input
                            id="customer_email"
                            type="email"
                            class="w-full border rounded-xl
                                   px-4 py-3"
                            placeholder="you@example.com"
                        >

                    </div>


                    <div>

                        <label
                            class="block text-sm mb-2"
                        >
                            Notes
                        </label>

                        <textarea
                            id="notes"
                            rows="4"
                            class="w-full border rounded-xl
                                   px-4 py-3"
                            placeholder="Anything we should know?"
                        ></textarea>

                    </div>


                    <button
                        id="submit-button"
                        class="
                            w-full
                            bg-stone-900
                            text-white
                            rounded-xl
                            py-4
                        "
                    >
                        Send pickup request
                    </button>


                    <p
                        id="form-message"
                        class="text-sm hidden"
                    ></p>

                </form>

            </div>

        </section>

    </div>

</main>


<script>

let cart =
    JSON.parse(
        localStorage.getItem('sarah_baker_cart')
    ) || [];


function renderCart() {

    const container =
        document.getElementById('cart');

    const totalElement =
        document.getElementById('cart-total');

    if (!cart.length) {

        container.innerHTML = `
            <div
                class="bg-white rounded-2xl p-8
                       border text-center"
            >

                <p class="text-stone-500">
                    Your order is empty.
                </p>

                <a
                    href="/menu.php"
                    class="
                        inline-block
                        mt-5
                        bg-stone-900
                        text-white
                        px-6 py-3
                        rounded-full
                    "
                >
                    Browse menu
                </a>

            </div>
        `;

        totalElement.innerHTML = `
            <span>Total</span>
            <span>€0.00</span>
        `;

        return;
    }


    container.innerHTML =
        cart.map((item, index) => `

            <div
                class="bg-white border rounded-2xl p-5"
            >

                <div
                    class="flex justify-between
                           gap-4"
                >

                    <div>

                        <h3 class="font-semibold">
                            ${escapeHtml(item.name)}
                        </h3>

                        <p
                            class="text-sm
                                   text-stone-500 mt-1"
                        >
                            €${item.price.toFixed(2)}
                            each
                        </p>

                    </div>

                    <button
                        onclick="removeItem(${index})"
                        class="text-red-600 text-sm"
                    >
                        Remove
                    </button>

                </div>


                <div
                    class="mt-4 flex items-center
                           justify-between"
                >

                    <div
                        class="flex items-center
                               border rounded-xl"
                    >

                        <button
                            onclick="changeQuantity(
                                ${index},
                                -1
                            )"
                            class="px-4 py-2"
                        >
                            −
                        </button>

                        <span class="px-3">
                            ${item.quantity}
                        </span>

                        <button
                            onclick="changeQuantity(
                                ${index},
                                1
                            )"
                            class="px-4 py-2"
                        >
                            +
                        </button>

                    </div>


                    <strong>
                        €${(
                            item.price *
                            item.quantity
                        ).toFixed(2)}
                    </strong>

                </div>

            </div>

        `).join('');


    const total =
        calculateTotal();

    totalElement.innerHTML = `
        <span>Total</span>
        <span>€${total.toFixed(2)}</span>
    `;
}


function calculateTotal() {

    return cart.reduce(
        (total, item) =>
            total +
            item.price * item.quantity,
        0
    );
}


function changeQuantity(index, amount) {

    cart[index].quantity += amount;

    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }

    saveCart();
    renderCart();
}


function removeItem(index) {

    cart.splice(index, 1);

    saveCart();
    renderCart();
}


function saveCart() {

    localStorage.setItem(
        'sarah_baker_cart',
        JSON.stringify(cart)
    );
}


document
    .getElementById('order-form')
    .addEventListener(
        'submit',
        async function(event) {

            event.preventDefault();

            if (!cart.length) {

                showMessage(
                    'Please add at least one item.',
                    true
                );

                return;
            }


            const button =
                document.getElementById(
                    'submit-button'
                );

            button.disabled = true;

            button.textContent =
                'Sending...';


            const payload = {

                customer_name:
                    document.getElementById(
                        'customer_name'
                    ).value.trim(),

                customer_phone:
                    document.getElementById(
                        'customer_phone'
                    ).value.trim(),

                customer_email:
                    document.getElementById(
                        'customer_email'
                    ).value.trim(),

                notes:
                    document.getElementById(
                        'notes'
                    ).value.trim(),

                items:
                    cart.map(item => ({
                        product_id:
                            item.product_id,

                        quantity:
                            item.quantity
                    }))
            };


            try {

                const response =
                    await fetch(
                        '/api/orders.php',
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json'
                            },

                            body:
                                JSON.stringify(payload)
                        }
                    );


                const data =
                    await response.json();


                if (!data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to submit order.'
                    );
                }


                localStorage.removeItem(
                    'sarah_baker_cart'
                );

                cart = [];


                document.getElementById(
                    'order-form'
                ).reset();


                document.getElementById(
                    'cart'
                ).innerHTML = `

                    <div
                        class="
                            bg-green-50
                            border border-green-200
                            rounded-2xl
                            p-8
                        "
                    >

                        <h3
                            class="
                                text-xl
                                font-semibold
                                text-green-900
                            "
                        >
                            Order received.
                        </h3>

                        <p
                            class="
                                mt-3
                                text-green-800
                            "
                        >
                            Your order number is
                            #${data.order_id}.
                        </p>

                        <a
                            href="/"
                            class="
                                inline-block
                                mt-6
                                bg-stone-900
                                text-white
                                px-6 py-3
                                rounded-full
                            "
                        >
                            Back to Sarah Baker
                        </a>

                    </div>

                `;


                document.getElementById(
                    'cart-total'
                ).innerHTML = '';

                showMessage(
                    'Your order has been received.',
                    false
                );


            } catch (error) {

                showMessage(
                    error.message,
                    true
                );

                button.disabled = false;

                button.textContent =
                    'Send pickup request';
            }

        }
    );


function showMessage(message, error) {

    const element =
        document.getElementById(
            'form-message'
        );

    element.textContent = message;

    element.classList.remove('hidden');

    element.className =
        error
            ? 'text-sm text-red-600'
            : 'text-sm text-green-600';
}


function escapeHtml(value) {

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}


renderCart();

</script>

</body>
</html>