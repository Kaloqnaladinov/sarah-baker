const state = {
    products: [],
    category: 'all'
};

async function loadMenu() {

    const response = await fetch('/api/products.php');
    const data = await response.json();

    if (!data.success) {
        return;
    }

    state.products = data.products;

    renderCategories();
    renderProducts();
}

function renderCategories() {

    const container =
        document.getElementById('category-tabs');

    const categories = [
        {
            slug: 'all',
            name: 'All'
        },
        ...[
            ...new Map(
                state.products.map(product => [
                    product.category_slug,
                    {
                        slug: product.category_slug,
                        name: product.category_name
                    }
                ])
            ).values()
        ]
    ];

    container.innerHTML = categories.map(category => `
        <button
            onclick="selectCategory('${category.slug}')"
            class="
                whitespace-nowrap
                px-5 py-2
                rounded-full
                border
                ${state.category === category.slug
                    ? 'bg-stone-900 text-white'
                    : 'bg-white text-stone-800'}
            "
        >
            ${escapeHtml(category.name)}
        </button>
    `).join('');
}

function selectCategory(slug) {

    state.category = slug;

    renderCategories();
    renderProducts();
}

function renderProducts() {

    const grid =
        document.getElementById('product-grid');

    let products = state.products.filter(
        product => product.available
    );

    if (state.category !== 'all') {
        products = products.filter(
            product =>
                product.category_slug === state.category
        );
    }

    if (!products.length) {

        grid.innerHTML = `
            <p class="text-stone-500">
                No products available right now.
            </p>
        `;

        return;
    }

    grid.innerHTML = products.map(product => `

        <article
            class="rounded-2xl border border-stone-200
                   overflow-hidden bg-white"
        >

            ${
                product.image_url
                    ? `
                    <img
                        src="${escapeAttribute(product.image_url)}"
                        alt="${escapeAttribute(product.name)}"
                        class="w-full h-56 object-cover"
                    >
                    `
                    : `
                    <div
                        class="h-56 bg-stone-100
                               flex items-center justify-center"
                    >
                        <span class="text-stone-400">
                            Sarah Baker
                        </span>
                    </div>
                    `
            }

            <div class="p-6">

                <div class="flex justify-between gap-4">

                    <h3 class="font-semibold text-lg">
                        ${escapeHtml(product.name)}
                    </h3>

                    <span class="font-medium">
                        €${Number(product.price).toFixed(2)}
                    </span>

                </div>

                ${
                    product.description
                        ? `
                        <p class="mt-3 text-sm text-stone-500">
                            ${escapeHtml(product.description)}
                        </p>
                        `
                        : ''
                }

            </div>

        </article>

    `).join('');
}

function escapeHtml(value) {

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function escapeAttribute(value) {
    return escapeHtml(value);
}

loadMenu();
