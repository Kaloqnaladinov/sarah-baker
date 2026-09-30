<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sarah Baker Coffee</title>

    <meta
        name="description"
        content="Sarah Baker Coffee — bakery, pastries and coffee in Châtenay-Malabry."
    >

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-stone-50 text-stone-900">

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b">

    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">

        <a href="/" class="font-bold text-xl">
            SARAH BAKER
        </a>

        <nav class="hidden md:flex gap-8 text-sm">
            <a href="#menu">Menu</a>
            <a href="#about">About</a>
            <a href="#location">Location</a>
        </nav>

        <a
            href="menu.php"
            class="rounded-full bg-stone-900 text-white px-5 py-2 text-sm"
        >
            View Menu
        </a>

    </div>

</header>

<main>

<section class="min-h-[80vh] flex items-center">

    <div class="max-w-6xl mx-auto px-5 py-20 grid md:grid-cols-2 gap-12 items-center">

        <div>

            <p class="uppercase tracking-[0.25em] text-sm mb-5">
                Boulangerie · Coffee Shop · Pâtisserie
            </p>

            <h1 class="text-5xl md:text-7xl font-semibold tracking-tight leading-tight">
                Good coffee.
                <br>
                Good food.
                <br>
                Good moments.
            </h1>

            <p class="mt-7 text-lg text-stone-600 max-w-xl">
                Fresh bakery favourites, pastries and coffee
                in the heart of Châtenay-Malabry.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">

                <a
                    href="#menu"
                    class="bg-stone-900 text-white px-7 py-3 rounded-full"
                >
                    Explore the menu
                </a>

                <a
                    href="https://www.google.com/maps/search/?api=1&query=Sarah+Baker+Coffee+Chatenay-Malabry"
                    target="_blank"
                    class="border border-stone-300 px-7 py-3 rounded-full"
                >
                    Get directions
                </a>

            </div>

        </div>

        <div class="rounded-3xl overflow-hidden bg-stone-200 min-h-[450px]">

            <img
                src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=1200&q=80"
                alt="Fresh bakery products"
                class="w-full h-full object-cover"
            >

        </div>

    </div>

</section>


<section id="menu" class="py-24 bg-white">

    <div class="max-w-6xl mx-auto px-5">

        <div class="max-w-2xl mb-12">

            <p class="uppercase tracking-widest text-sm">
                The menu
            </p>

            <h2 class="text-4xl md:text-5xl font-semibold mt-3">
                Freshly made.
            </h2>

            <p class="mt-5 text-stone-600">
                Explore our bakery, pastries, breakfast and drinks.
            </p>

        </div>

        <a
    href="/menu.php"
    class="bg-stone-900 text-white px-7 py-3 rounded-full"
>
    Explore the menu
</a>
<a
    href="/order.php"
    class="border border-stone-300 px-7 py-3 rounded-full"
>
    Order for pickup
</a>
    </div>

</section>


<section id="about" class="py-24">

    <div class="max-w-4xl mx-auto px-5 text-center">

        <p class="uppercase tracking-widest text-sm">
            Sarah Baker
        </p>

        <h2 class="text-4xl md:text-5xl font-semibold mt-4">
            Bakery meets coffee shop.
        </h2>

        <p class="mt-7 text-lg text-stone-600 leading-relaxed">
            A place for freshly baked treats, quality coffee,
            breakfast, lunch and everyday moments.
        </p>

    </div>

</section>


<section id="location" class="py-24 bg-stone-900 text-white">

    <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-2 gap-12">

        <div>

            <p class="uppercase tracking-widest text-sm text-stone-400">
                Visit us
            </p>

            <h2 class="text-4xl font-semibold mt-4">
                Châtenay-Malabry
            </h2>

            <p class="mt-6 text-stone-300">
                1 Avenue de Robinson<br>
                92290 Châtenay-Malabry<br>
                France
            </p>

            <a
                href="https://www.google.com/maps/search/?api=1&query=1+Avenue+de+Robinson+92290+Chatenay-Malabry"
                target="_blank"
                class="inline-block mt-7 border border-stone-600 px-6 py-3 rounded-full"
            >
                Open Google Maps
            </a>

        </div>

        <div>

            <p class="uppercase tracking-widest text-sm text-stone-400">
                Opening hours
            </p>

            <p class="mt-6 text-stone-300">
                Check Google for the latest opening hours.
            </p>

        </div>

    </div>

</section>

</main>

<footer class="bg-black text-white py-8">

    <div class="max-w-6xl mx-auto px-5 flex justify-between">

        <span>Sarah Baker Coffee</span>

        <span class="text-stone-500">
            Châtenay-Malabry
        </span>

    </div>

</footer>

<script src="/assets/app.js"></script>

</body>
</html>
