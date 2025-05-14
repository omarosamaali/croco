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
            <a href="/"
                style="text-align: left; font-size: 20px; color: var(--main-color); text-decoration: none; font-weight: bold; font-family: 'cairo';">
                {{ $lang == 'ar' ? 'تسجيل دخول' : 'Login' }}
            </a>
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

            {{-- Start Container Cards --}}
            <div class="container-cards">
                @forelse ($games as $game)
                    <div class="card">
                        <div>
                            @if ($game->mainCategory->image)
                                <img src="{{ asset('storage/' . $game->mainCategory->image) }}" alt="unknown">
                            @else
                                <div
                                    style="width: 50px; height: 50px; background-color: #4b5563; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #9ca3af; font-size: 0.75rem;">لا توجد صورة</span>
                                </div>

                                <span style="color: #9ca3af;">{{ $lang == 'ar' ? 'لا توجد صورة' : 'No image' }}</span>
                            @endif
                            <span class="title">{{ $game->name }}</span>
                        </div>
                        <div class="price" style="flex-direction: row !important;">
                            <span class="price-value" id="price-{{ $game->id }}">
                                {{ $game->subCategories->first()->price ?? '0.00' }}
                            </span>
                            <span class="price-title">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</span>
                        </div>
                        <div class="price" style="border-top: 1px solid #9ca3af;">
                            <div style="margin-left: auto;">
                                <span class="price-title">{{ $lang == 'ar' ? 'اختر خطتك' : 'Duration' }}</span>
                            </div>

                            <style>
                                .selector-container {
                                    background-color: black;
                                    border-radius: 15px;
                                    padding: 5px;
                                    display: inline-block;
                                }

                                .duration-option {
                                    display: inline-block;
                                    padding: 5px 10px;
                                    margin-right: 5px;
                                    color: white;
                                    cursor: pointer;
                                    user-select: none;
                                    transition: background-color 0.3s;
                                }

                                .duration-option:last-child {
                                    margin-right: 0;
                                }

                                .duration-option input[type="radio"] {
                                    display: none;
                                }

                                .duration-option.selected {
                                    background-color: white;
                                    color: black;
                                    border-radius: 15px;
                                }
                            </style>

                            <div class="selector-container">
                                @php
                                    $durations = $game->subCategories->pluck('duration')->unique()->sort();
                                    $defaultDuration = $durations->first() ?? 6;
                                    $subCategoryPrices = $game->subCategories->pluck('price', 'duration')->toArray();
                                @endphp
                                @foreach ($durations as $duration)
                                    @php
                                        $price = $subCategoryPrices[$duration] ?? 0;
                                        $months = floor($duration / 30);
                                        $remainingDays = $duration % 30;
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
                                    <label class="duration-option" data-game-id="{{ $game->id }}"
                                        data-duration="{{ $duration }}" data-price="{{ $price }}">
                                        <input type="radio" name="duration-{{ $game->id }}"
                                            value="{{ $duration }}"
                                            {{ $duration == $defaultDuration ? 'checked' : '' }}>
                                        {{ $durationText }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="description-container hidden">
                            @foreach ($lang == 'ar' ? $game->description_ar ?? [] : $game->description_en ?? [] as $desc)
                                <div class="description">{{ $desc }}</div>
                            @endforeach
                        </div>
                        <button class="choose-plan" data-game-id="{{ $game->id }}"
                            data-duration="{{ $defaultDuration }}"
                            onclick="redirectToSubscriberForm({{ $game->id }})">
                            {{ $lang == 'ar' ? 'اختر باقتك' : 'Choose Plan' }}
                        </button>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                // Existing code for toggle details and duration selection...

                                // Update duration when selecting a new option
                                const durationOptions = document.querySelectorAll('.duration-option');
                                durationOptions.forEach(option => {
                                    option.addEventListener('click', () => {
                                        const gameId = option.getAttribute('data-game-id');
                                        const duration = option.getAttribute('data-duration');
                                        const price = parseFloat(option.getAttribute('data-price'));

                                        // Update the button's data-duration attribute
                                        const choosePlanButton = document.querySelector(
                                            `.choose-plan[data-game-id="${gameId}"]`);
                                        choosePlanButton.setAttribute('data-duration', duration);

                                        // Existing price update logic
                                        updatePrice(gameId, price);
                                    });
                                });

                                // Function to redirect to subscriber form
                                window.redirectToSubscriberForm = function(gameId) {
                                    const button = document.querySelector(`.choose-plan[data-game-id="${gameId}"]`);
                                    const duration = button.getAttribute('data-duration');
                                    window.location.href = `/subscriber-form?game_id=${gameId}&duration=${duration}`;
                                };
                            });
                        </script> <button class="toggle-details-btn">
                            {{ $lang == 'ar' ? 'عرض تفاصيل الخطة +' : 'Show Plan Details +' }}
                        </button>
                    </div>
                @empty
                    <p style="color: white; text-align: center;">
                        {{ $lang == 'ar' ? 'لا توجد باقات متاحة' : 'No games available' }}
                    </p>
                @endforelse
            </div>
            {{-- End Container Cards --}}
        </div>
        {{-- End Body --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // التعامل مع أزرار إظهار/إخفاء التفاصيل
            const toggleButtons = document.querySelectorAll('.toggle-details-btn');
            toggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const card = button.closest('.card');
                    const descriptionContainer = card.querySelector('.description-container');
                    const isHidden = descriptionContainer.classList.contains('hidden');

                    descriptionContainer.classList.toggle('hidden');

                    if (isHidden) {
                        button.innerHTML = button.innerHTML.replace('عرض تفاصيل الخطة +',
                                'إخفاء تفاصيل الخطة -')
                            .replace('Show Plan Details +', 'Hide Plan Details -');
                    } else {
                        button.innerHTML = button.innerHTML.replace('إخفاء تفاصيل الخطة -',
                                'عرض تفاصيل الخطة +')
                            .replace('Hide Plan Details -', 'Show Plan Details +');
                    }
                });
            });

            // التعامل مع اختيار المدة وتحديث السعر
            const durationOptions = document.querySelectorAll('.duration-option');
            durationOptions.forEach(option => {
                const radio = option.querySelector('input[type="radio"]');
                const gameId = option.getAttribute('data-game-id');
                const duration = parseInt(option.getAttribute('data-duration'));
                const price = parseFloat(option.getAttribute('data-price'));

                // إعداد الحالة الأولية
                if (radio.checked) {
                    option.classList.add('selected');
                    updatePrice(gameId, price);
                }

                option.addEventListener('click', () => {
                    // إلغاء تحديد جميع الخيارات في نفس المجموعة
                    const siblings = document.querySelectorAll(
                        `.duration-option[data-game-id="${gameId}"]`);
                    siblings.forEach(sib => sib.classList.remove('selected'));

                    // تحديد الخيار المختار
                    option.classList.add('selected');
                    radio.checked = true;

                    // تحديث السعر بناءً على المدة المختارة
                    updatePrice(gameId, price);
                });
            });

            function updatePrice(gameId, price) {
                const priceElement = document.getElementById(`price-${gameId}`);
                priceElement.textContent = price.toFixed(2);
            }
        });
    </script>
</body>

</html>
