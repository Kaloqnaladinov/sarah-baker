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

    <title>Menu — Sarah Baker Coffee</title>

    <meta
        name="description"
        content="Explore the Sarah Baker Coffee menu."
    >

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-stone-50 text-stone-900">

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b">

    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">

        <a
            href="/"
            class="font-bold text-xl"
        >
            SARAH BAKER
        </a>

        <div class="flex items-center gap-4">

            <a
                href="/"
                class="text-sm text-stone-600"
            >
                Home
            </a>

            <a
                href="/order.php"
                class="rounded-full bg-stone-900 text-white px-5 py-2 text-sm"
            >
                Order
            </a>

        </div>

    </div>

</header>


<main>

<section class="py-16 md:py-24">

    <div class="max-w-6xl mx-auto px-5">

        <div class="max-w-2xl">

            <p class="uppercase tracking-[0.25em] text-sm">
                Sarah Baker Coffee
            </p>

            <h1 class="text-5xl md:text-6xl font-semibold mt-4">
                Our Menu
            </h1>

            <p class="mt-5 text-lg text-stone-600">
                Bakery, pastries, breakfast, lunch and coffee.
            </p>

        </div>


        <!-- Category filters -->

        <div
            id="category-tabs"
            class="flex gap-3 overflow-x-auto py-10"
        >
            <button
                class="category-button active whitespace-nowrap
                       px-5 py-2 rounded-full bg-stone-900 text-white"
                data-category="all"
            >
                All
            </button>
        </div>


        <!-- Products -->

        <div
            id="product-grid"
            class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div class="text-stone-500">
                Loading menu...
            </div>
        </div>

    </div>

</section>

</main>


<footer class="bg-stone-900 text-white py-10">

    <div class="max-w-6xl mx-auto px-5 flex justify-between">

        <span>
            Sarah Baker Coffee
        </span>

        <a
            href="/"
            class="text-stone-400"
        >
            Home
        </a>

    </div>

</footer>


<script>

let products = [];
let selectedCategory = 'all';


async function loadMenu() {

    try {

        const response =
            await fetch('/api/products.php');

        const data =
            await response.json();

        if (!data.success) {
            throw new Error(
                data.message || 'Unable to load menu.'
            );
        }

        products = data.products;

        renderCategories();
        renderProducts();

    } catch (error) {

        document.getElementById(
            'product-grid'
        ).innerHTML = `
            <div class="col-span-full p-6
                        bg-red-50 text-red-700 rounded-xl">
                Unable to load the menu.
            </div>
        `;

        console.error(error);
    }
}


function renderCategories() {

    const container =
        document.getElementById('category-tabs');

    const categoryMap = new Map();

    products.forEach(product => {

        if (!categoryMap.has(product.category_slug)) {

            categoryMap.set(
                product.category_slug,
                {
                    slug: product.category_slug,
                    name: product.category_name
                }
            );
        }

    });

    const categories = [
        {
            slug: 'all',
            name: 'All'
        },
        ...categoryMap.values()
    ];

    container.innerHTML =
        categories.map(category => `

            <button
                class="
                    category-button
                    whitespace-nowrap
                    px-5 py-2
                    rounded-full
                    border
                    ${
                        selectedCategory === category.slug
                            ? 'bg-stone-900 text-white border-stone-900'
                            : 'bg-white text-stone-700 border-stone-300'
                    }
                "
                data-category="${escapeHtml(category.slug)}"
            >
                ${escapeHtml(category.name)}
            </button>

        `).join('');

    document
        .querySelectorAll('.category-button')
        .forEach(button => {

            button.addEventListener(
                'click',
                () => {

                    selectedCategory =
                        button.dataset.category;

                    renderCategories();
                    renderProducts();
                }
            );

        });
}


function renderProducts() {

    const container =
        document.getElementById('product-grid');

    let visibleProducts =
        products.filter(product => product.available);

    if (selectedCategory !== 'all') {

        visibleProducts =
            visibleProducts.filter(
                product =>
                    product.category_slug ===
                    selectedCategory
            );

    }

    if (!visibleProducts.length) {

        container.innerHTML = `
            <div class="col-span-full text-center
                        py-20 text-stone-500">
                No products are currently available
                in this category.
            </div>
        `;

        return;
    }

    container.innerHTML =
        visibleProducts.map(product => {

            const image = product.image_url
                ? `
                    <img
                        src="${escapeHtml(product.image_url)}"
                        alt="${escapeHtml(product.name)}"
                        class="w-full h-60 object-cover"
                    >
                  `
                : `
                    <div
                        class="w-full h-60 bg-stone-100
                               flex items-center justify-center"
                    >
                        <span class="text-stone-400">
                            Sarah Baker
                        </span>
                    </div>
                  `;

            return `

                <article
                    class="bg-white rounded-2xl overflow-hidden
                           border border-stone-200"
                >

                    ${image}

                    <div class="p-6">

                        <div
                            class="flex justify-between
                                   gap-4"
                        >

                            <h2
                                class="font-semibold text-lg"
                            >
                                ${escapeHtml(product.name)}
                            </h2>

                            <span
                                class="font-semibold whitespace-nowrap"
                            >
                                €${Number(product.price).toFixed(2)}
                            </span>

                        </div>

                        ${
                            product.description
                                ? `
                                    <p
                                        class="mt-3 text-sm
                                               text-stone-500"
                                    >
                                        ${escapeHtml(
                                            product.description
                                        )}
                                    </p>
                                  `
                                : ''
                        }

                        <button
                            onclick="addToOrder(
                                ${product.id}
                            )"
                            class="
                                mt-5
                                w-full
                                rounded-xl
                                bg-stone-900
                                text-white
                                py-3
                            "
                        >
                            Add to order
                        </button>

                    </div>

                </article>

            `;

        }).join('');
}


function addToOrder(productId) {

    let cart =
        JSON.parse(
            localStorage.getItem('sarah_baker_cart')
        ) || [];

    const existing =
        cart.find(
            item => item.product_id === productId
        );

    if (existing) {

        existing.quantity++;

    } else {

        const product =
            products.find(
                item => item.id == productId
            );

        if (!product) {
            return;
        }

        cart.push({
            product_id: product.id,
            name: product.name,
            price: Number(product.price),
            quantity: 1
        });

    }

    localStorage.setItem(
        'sarah_baker_cart',
        JSON.stringify(cart)
    );

    window.location.href = '/order.php';
}


function escapeHtml(value) {

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}


loadMenu();

</script>

</body>
</html>