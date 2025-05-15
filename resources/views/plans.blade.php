<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/translation.js'])

<head>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/plans.css') }}">
</head>

<body>
    <div>
        <img style="position: fixed; width: 100%; height: 100%;"
            src="https://tod-preprod.enhance.diagnal.com/resources/images/eam/production/ed90d982b63f4238a93d4dd6f4c5988d/800x450/packageselection_bg_5447.jpg"
            alt="">

        {{-- Start Header --}}
        <header
            style="border-bottom: 5px solid var(--main-color); z-index: 9; position: relative; padding: 20px 35px; display: grid; align-items: center; grid-template-columns: repeat(3, minmax(0, 1fr));">
            {{-- <a href="/"
                style="text-align: left; font-size: 20px; color: var(--main-color); text-decoration: none; font-weight: bold; font-family: 'cairo';">
                {{ $lang == 'ar' ? 'تسجيل دخول' : 'Login' }}
            </a> --}}
            <div class="logo-container" style="text-align: center;">
                <a href="/" class="logo-link">
                    <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important;"
                        class="logo" alt="Joystick Logo">
                </a>
            </div>
            <a href="/" style="text-align: right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                    fill="none">
                    <path d="M14.4297 5.92969L20.4997 11.9997L14.4297 18.0697" stroke="white" stroke-width="1.5"
                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.5 12H20.33" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

        </header>
        {{-- End Header --}}

        {{-- Start Body --}}
        <div style="z-index: 99; position: relative;">
            <h1 style="font-size: 32px; color: white; text-align: center;">
                {{ $lang == 'ar' ? 'اختار باقتك' : 'Choose your plan' }}
            </h1>

            <!-- Keep the header and other parts as they are -->
            <div class="container-cards">
                @forelse ($mainCategories as $mainCategory)
                    <div class="card">
                        <div>
                            @if ($mainCategory->image)
                                <img src="{{ asset('storage/' . $mainCategory->image) }}"
                                    alt="{{ $mainCategory->name_ar }}"
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.25rem;">
                            @else
                                <div
                                    style="width: 100px; height: 100px; background-color: #4b5563; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #9ca3af; font-size: 0.875rem;">
                                        {{ $lang == 'ar' ? 'لا توجد صورة' : 'No image' }}
                                    </span>
                                </div>
                            @endif
                            <span class="title">
                                {{ $lang == 'ar' ? $mainCategory->name_ar : $mainCategory->name_en }}
                            </span>
                        </div>

                        <div class="price" style="flex-direction: row !important;">
                            <span class="price-value" id="price-{{ $mainCategory->id }}">
                                {{ $mainCategory->subCategories->first()->price ?? '0.00' }}
                            </span>
                            <span class="price-title">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</span>
                        </div>

                        <div class="price" style="border-top: 1px solid #9ca3af;">
                            <div style="margin-left: auto;">
                                <span class="price-title">{{ $lang == 'ar' ? 'اختر خطتك' : 'Duration' }}</span>
                            </div>

                            <div class="selector-container">
                                @php
                                    $durations = $mainCategory->subCategories->pluck('duration')->unique()->sort();
                                    $defaultSubCategory = $mainCategory->subCategories->first();
                                    $defaultDuration = $defaultSubCategory ? $defaultSubCategory->duration : 30;
                                    $subCategoryPrices = $mainCategory->subCategories
                                        ->pluck('price', 'duration')
                                        ->toArray();
                                    $subCategoryIds = $mainCategory->subCategories->pluck('id', 'duration')->toArray();
                                @endphp
                                @foreach ($durations as $duration)
                                    @php
                                        $price = $subCategoryPrices[$duration] ?? 0;
                                        $subCategoryId = $subCategoryIds[$duration] ?? null;
                                        $months = floor($duration / 30);
                                        $monthText =
                                            $lang == 'ar'
                                                ? ($months == 1 || $months >= 11
                                                    ? 'شهر'
                                                    : 'أشهر')
                                                : ($months == 1
                                                    ? 'month'
                                                    : 'months');
                                        $durationText = $lang == 'ar' ? "$months $monthText" : "$months $monthText";
                                    @endphp
                                    <label class="duration-option" data-main-category-id="{{ $mainCategory->id }}"
                                        data-sub-category-id="{{ $subCategoryId }}"
                                        data-duration="{{ $duration }}" data-price="{{ $price }}">
                                        <input type="radio" name="duration-{{ $mainCategory->id }}"
                                            value="{{ $duration }}"
                                            {{ $duration == $defaultDuration ? 'checked' : '' }}>
                                        {{ $durationText }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="description-container hidden">
                            <!-- Keep the description logic as is -->
                            @if ($mainCategory->name_ar == 'الاطفال')
                                <div class="description">...</div>
                                <!-- Other descriptions -->
                            @elseif ($mainCategory->name_ar == 'الرياضة')
                                <div class="description">...</div>
                                <!-- Other descriptions -->
                            @elseif ($mainCategory->name_ar == 'الأفلام')
                                <div class="description">...</div>
                                <!-- Other descriptions -->
                            @endif
                        </div>

                        @if ($mainCategory->subCategories->first())
                            <button class="choose-plan" data-main-category-id="{{ $mainCategory->id }}"
                                data-sub-category-id="{{ $mainCategory->subCategories->first()->id }}"
                                data-duration="{{ $defaultDuration }}"
                                onclick="redirectToSubscriberForm('{{ $lang }}', this.getAttribute('data-main-category-id'), this.getAttribute('data-sub-category-id'))">
                                {{ $lang == 'ar' ? 'اختر باقتك' : 'Choose Plan' }}
                            </button>
                        @else
                            <p style="color: red;">
                                {{ $lang == 'ar' ? 'خطأ: لا توجد فئات فرعية متاحة' : 'Error: No sub-categories available' }}
                            </p>
                        @endif

                        <button class="toggle-details-btn">
                            {{ $lang == 'ar' ? 'عرض تفاصيل الخطة +' : 'Show Plan Details +' }}
                        </button>
                    </div>
                @empty
                    <p style="color: white; text-align: center;">
                        {{ $lang == 'ar' ? 'لا توجد باقات متاحة' : 'No packages available' }}
                    </p>
                @endforelse
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Handle toggle details buttons
                    const toggleButtons = document.querySelectorAll('.toggle-details-btn');
                    toggleButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const card = button.closest('.card');
                            const descriptionContainer = card.querySelector('.description-container');
                            const isHidden = descriptionContainer.classList.contains('hidden');

                            descriptionContainer.classList.toggle('hidden');
                            button.innerHTML = isHidden ?
                                button.innerHTML.replace('عرض تفاصيل الخطة +', 'إخفاء تفاصيل الخطة -')
                                .replace('Show Plan Details +', 'Hide Plan Details -') :
                                button.innerHTML.replace('إخفاء تفاصيل الخطة -', 'عرض تفاصيل الخطة +')
                                .replace('Hide Plan Details -', 'Show Plan Details +');
                        });
                    });

                    // Handle duration selection and price update
                    const durationOptions = document.querySelectorAll('.duration-option');
                    durationOptions.forEach(option => {
                        const radio = option.querySelector('input[type="radio"]');
                        const mainCategoryId = option.getAttribute('data-main-category-id');
                        const subCategoryId = option.getAttribute('data-sub-category-id');
                        const duration = parseInt(option.getAttribute('data-duration'));
                        const price = parseFloat(option.getAttribute('data-price'));

                        if (radio.checked) {
                            option.classList.add('selected');
                            updatePrice(mainCategoryId, price);
                            updateButtonAttributes(mainCategoryId, subCategoryId, duration);
                        }

                        option.addEventListener('click', () => {
                            const siblings = document.querySelectorAll(
                                `.duration-option[data-main-category-id="${mainCategoryId}"]`);
                            siblings.forEach(sib => sib.classList.remove('selected'));

                            option.classList.add('selected');
                            radio.checked = true;

                            updatePrice(mainCategoryId, price);
                            updateButtonAttributes(mainCategoryId, subCategoryId, duration);
                        });
                    });

                    function updatePrice(gameId, price) {
                        const priceElement = document.getElementById(`price-${gameId}`);
                        if (priceElement) {
                            priceElement.textContent = price.toFixed(2);
                        }
                    }

                    function updateButtonAttributes(gameId, duration) {
                        const choosePlanButton = document.querySelector(
                            `.choose-plan[data-game-id="${gameId}"]`);
                        if (choosePlanButton) {
                            choosePlanButton.setAttribute('data-duration', duration || '');
                            console.log('Updated button attributes:', {
                                gameId,
                                duration
                            });
                        }
                    }

                    // Function to redirect to subscriber form
                    // window.redirectToSubscriberForm = function(lang, gameId, duration) {
                    //     console.log('Attempting redirect:', {
                    //         lang,
                    //         gameId,
                    //         duration
                    //     });
                    //     if (!gameId || !duration) {
                    //         alert(lang === 'ar' ? 'يرجى اختيار خطة صالحة.' : 'Please select a valid plan.');
                    //         console.error('Invalid gameId or duration');
                    //         return;
                    //     }
                    //     const url = `/${lang}/subscriber-form?game_id=${gameId}&duration=${duration}`;
                    //     console.log('Redirect URL:', url);
                    //     window.location.href = url;
                    // };
                });
            </script>
        </div>
        {{-- End Body --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Handle toggle details buttons
            const toggleButtons = document.querySelectorAll('.toggle-details-btn');
            toggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const card = button.closest('.card');
                    const descriptionContainer = card.querySelector('.description-container');
                    const isHidden = descriptionContainer.classList.contains('hidden');

                    descriptionContainer.classList.toggle('hidden');
                    button.innerHTML = isHidden ?
                        button.innerHTML.replace('عرض تفاصيل الخطة +', 'إخفاء تفاصيل الخطة -')
                        .replace('Show Plan Details +', 'Hide Plan Details -') :
                        button.innerHTML.replace('إخفاء تفاصيل الخطة -', 'عرض تفاصيل الخطة +')
                        .replace('Hide Plan Details -', 'Show Plan Details +');
                });
            });

            // Handle duration selection and price update
            const durationOptions = document.querySelectorAll('.duration-option');
            durationOptions.forEach(option => {
                const radio = option.querySelector('input[type="radio"]');
                const mainCategoryId = option.getAttribute('data-main-category-id');
                const subCategoryId = option.getAttribute('data-sub-category-id');
                const duration = parseInt(option.getAttribute('data-duration'));
                const price = parseFloat(option.getAttribute('data-price'));

                // Set initial state
                if (radio.checked) {
                    option.classList.add('selected');
                    updatePrice(mainCategoryId, price);
                    updateButtonAttributes(mainCategoryId, subCategoryId, duration);
                }

                option.addEventListener('click', () => {
                    // Unselect all options in the same group
                    const siblings = document.querySelectorAll(
                        `.duration-option[data-main-category-id="${mainCategoryId}"]`);
                    siblings.forEach(sib => sib.classList.remove('selected'));

                    // Select the clicked option
                    option.classList.add('selected');
                    radio.checked = true;

                    // Update price
                    updatePrice(mainCategoryId, price);

                    // Update the choose-plan button's data attributes
                    updateButtonAttributes(mainCategoryId, subCategoryId, duration);
                });
            });

            function updatePrice(mainCategoryId, price) {
                const priceElement = document.getElementById(`price-${mainCategoryId}`);
                if (priceElement) {
                    priceElement.textContent = price.toFixed(2);
                }
            }

            function updateButtonAttributes(mainCategoryId, subCategoryId, duration) {
                const choosePlanButton = document.querySelector(
                    `.choose-plan[data-main-category-id="${mainCategoryId}"]`);
                if (choosePlanButton) {
                    choosePlanButton.setAttribute('data-sub-category-id', subCategoryId || '');
                    choosePlanButton.setAttribute('data-duration', duration || '');
                    console.log('Updated button attributes:', {
                        mainCategoryId,
                        subCategoryId,
                        duration
                    });
                }
            }

            // Function to redirect to subscriber form
            window.redirectToSubscriberForm = function(lang, mainCategoryId, subCategoryId) {
                const duration = document.querySelector(
                    `.choose-plan[data-main-category-id="${mainCategoryId}"]`).getAttribute('data-duration');
                console.log('Attempting redirect:', {
                    lang,
                    mainCategoryId,
                    subCategoryId,
                    duration
                });
                if (!mainCategoryId || !subCategoryId) {
                    alert(lang === 'ar' ? 'يرجى اختيار خطة صالحة.' : 'Please select a valid plan.');
                    console.error('Invalid mainCategoryId or subCategoryId');
                    return;
                }
                const url =
                    `/${lang}/subscriber-form?main_category_id=${mainCategoryId}&sub_category_id=${subCategoryId}&duration=${duration}`;
                console.log('Redirect URL:', url);
                window.location.href = url;
            };
        });
    </script>
</body>

</html>
