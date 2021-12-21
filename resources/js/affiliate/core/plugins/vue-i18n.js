import Vue from "vue";
import VueI18n from "vue-i18n";

// Localisation language list
import { locale as en } from "aff/core/config/i18n/en";
import { locale as zh } from "aff/core/config/i18n/zh";
import { locale as es } from "aff/core/config/i18n/es";
import { locale as it } from "aff/core/config/i18n/it";
import { locale as de } from "aff/core/config/i18n/de";
import { locale as fr } from "aff/core/config/i18n/fr";
import { locale as pt } from "aff/core/config/i18n/pt";

Vue.use(VueI18n);

let messages = {};
messages = { ...messages, en, zh, es, it, de, fr, pt};
// get current selected language
const lang = localStorage.getItem("language") || "en";

// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: lang, // set locale
  messages, // set locale messages
  silentTranslationWarn: true
});

export default i18n;
