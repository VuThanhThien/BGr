const i18nService = {
    defaultLanguage: "en",

    languages: [
      {
        lang: "en",
        name: "English",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/012-uk.svg"
      },
      {
        lang: "zh",
        name: "China",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/034-china.svg"
      },
      {
        lang: "es",
        name: "Spanish",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/128-spain.svg"
      },
      {
        lang: "it",
        name: "Italy",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/013-italy.svg"
      },
      {
        lang: "de",
        name: "German",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/162-germany.svg"
      },
      {
        lang: "fr",
        name: "French",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/195-france.svg"
      },
      {
        lang: "pt",
        name: "Portuguese",
        flag: process.env.MIX_BASE_URL + "/media/svg/flags/224-portugal.svg"
      }
    ],

    /**
     * Keep the active language in the localStorage
     * @param lang
     */
    setActiveLanguage(lang) {
      localStorage.setItem("language", lang);
    },

    /**
     * Get the current active language
     * @returns {string | string}
     */
    getActiveLanguage() {
      return localStorage.getItem("language") || this.defaultLanguage;
    }
  };

  export default i18nService;
