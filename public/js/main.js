document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth <= 768) {
        new Swiper('#container-images-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true, // Enable looping
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 3000, // Auto-slide every 3 seconds
                disableOnInteraction: false,
            },
        });
    }
});

function closePopup() {
    document.getElementById('appPopup').classList.add('hidden');
}

function downloadApp() {
    window.location.href = '#';
}
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const closeSidebar = document.getElementById('close-sidebar');

    sidebarToggle.addEventListener('click', function () {
        sidebar.style.display = 'flex';
        setTimeout(() => {
            sidebar.classList.remove('sidebar-hidden');
        }, 10);
    });

    closeSidebar.addEventListener('click', function () {
        sidebar.classList.add('sidebar-hidden');
        setTimeout(() => {
            sidebar.style.display = 'none';
        }, 300);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('sidebar-hidden');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.mySwiper', {
        direction: 'vertical',
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        speed: 700,
        slidesPerView: 1,
        spaceBetween: 0,
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.tournament-card');
    const showMoreBtn = document.getElementById('showMoreBtn');
    const itemsPerPage = 12;
    let visibleItems = itemsPerPage;
    cards.forEach((card, index) => {
        if (index >= itemsPerPage) {
            card.classList.add('hidden');
        }
    });
    if (cards.length <= itemsPerPage) {
        showMoreBtn.style.display = 'none';
    }
    showMoreBtn.addEventListener('click', function () {
        const hiddenCards = document.querySelectorAll('.tournament-card.hidden');
        const nextBatch = Array.from(hiddenCards).slice(0, itemsPerPage);
        nextBatch.forEach(card => {
            card.classList.remove('hidden');
        });
        visibleItems += nextBatch.length;
        if (visibleItems >= cards.length) {
            showMoreBtn.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
    });
    sidebarLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentPath || (currentPath === '' && linkPath === '/')) {
            link.classList.add('active');
        }
    });
});
