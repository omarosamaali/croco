
// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
import i18next from 'i18next';
import i18nextHttpBackend from 'i18next-http-backend';
import '../js/translation';



window.paypal
  .Buttons({
    style: {
      shape: "rect",
      layout: "vertical",
      color: "gold",
      label: "paypal",
    },

    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [
          {
            amount: {
              value: "100.00", // Replace with your desired amount
              currency_code: "USD",
            },
          },
        ],
      });
    },

    onApprove: function (data, actions) {
      return actions.order.capture().then(function (orderData) {
        // Successful capture
        console.log("Capture result", orderData, JSON.stringify(orderData, null, 2));
        resultMessage(
          `Transaction completed by ${orderData.payer.name.given_name}! Transaction ID: ${orderData.id}`
        );
      }).catch(function (error) {
        console.error(error);
        resultMessage(`Sorry, your transaction could not be processed...<br><br>${error}`);
      });
    },

    onError: function (err) {
      console.error(err);
      resultMessage(`An error occurred: ${err}`);
    },
  })
  .render("#paypal-button-container");

function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}

// Get the initial language from the URL
const urlPath = window.location.pathname;
const initialLangFromUrl = urlPath.startsWith('/en') ? 'en' : urlPath.startsWith('/ar') ? 'ar' : 'en';

// Set the initial language in localStorage
localStorage.setItem('language', initialLangFromUrl);
console.log('Initial language set in localStorage:', localStorage.getItem('language'));

document.addEventListener("DOMContentLoaded", function () {
    i18next
        .use(i18nextHttpBackend)
        .init({
            fallbackLng: "en",
            debug: true,
            lng: localStorage.getItem('language') || 'en',
            supportedLngs: ['en', 'ar'],
            backend: {
                loadPath: "/locales/{{lng}}.json"
            }
        }, function (err, t) {
            if (err) {
                console.error("Error loading translations:", err);
                return;
            }
            console.log('i18next initialization complete, language:', i18next.language);
            updateContent();
            // Update the button text and page direction after i18next initializes
            const langText = document.querySelector('.lang-text');
            if (langText) {
                langText.textContent = i18next.language === 'ar' ? 'العربية' : 'EN';
            }
            document.documentElement.setAttribute('dir', i18next.language === 'ar' ? 'rtl' : 'ltr');
            document.documentElement.setAttribute('lang', i18next.language);
        });

    function updateContent() {
        document.querySelectorAll("[data-i18n]").forEach((element) => {
            const key = element.getAttribute("data-i18n");
            element.textContent = i18next.t(key);
        });
    }

    i18next.on("languageChanged", () => {
        console.log("i18next Language Changed:", i18next.language);
        updateContent();
    });

    i18next.on("initialized", () => {
        console.log("i18next Initialized:", i18next.language);
        updateContent();
    });
});