// resources/js/translation.js
import i18next from 'i18next';

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Function to set up the language toggle
    const setupLanguageToggle = () => {
        const languageToggle = document.getElementById('language-toggle');
        const langText = document.querySelector('.lang-text');

        if (!languageToggle) {
            console.error('Language toggle button not found!');
            return;
        }

        const currentLang = i18next.language || localStorage.getItem('language') || 'ar';
        langText.textContent = currentLang === 'ar' ? 'العربية' : 'EN';

        languageToggle.addEventListener('click', () => {
            console.log('Language toggle button clicked!');
            console.log('Current i18next.language:', i18next.language);
            const newLang = i18next.language === 'en' ? 'ar' : 'en';
            console.log('New language:', newLang);
            i18next.changeLanguage(newLang, (err) => {
                if (err) {
                    console.error('Error changing language:', err);
                    return;
                }
                localStorage.setItem('language', newLang);
                console.log('localStorage updated:', localStorage.getItem('language'));

                langText.textContent = newLang === 'ar' ? 'العربية' : 'EN';
                document.documentElement.setAttribute('dir', newLang === 'ar' ? 'rtl' : 'ltr');
                document.documentElement.setAttribute('lang', newLang);

                // Update translated text in the DOM
                document.querySelectorAll("[data-i18n]").forEach((element) => {
                    const key = element.getAttribute("data-i18n");
                    element.textContent = i18next.t(key);
                });

                // Update the URL to toggle between /en and /ar
                console.log('Navigating to URL:', `/${newLang}`);
                setTimeout(() => {
                    window.location.href = `/${newLang}`;
                }, 100);
            });
        });
    };

    // Wait for i18next to be initialized
    if (i18next.isInitialized) {
        console.log('i18next already initialized, setting up language toggle');
        setupLanguageToggle();
    } else {
        i18next.on('initialized', () => {
            console.log('i18next initialized, setting up language toggle');
            setupLanguageToggle();
        });
    }
});