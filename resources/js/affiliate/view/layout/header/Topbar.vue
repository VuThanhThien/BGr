<template>
  <!-- begin:: Header Topbar -->
  <div class="topbar">
    <div class="topbar-item" v-if="enable_affiliate_language ==1 && auto_detect_language==0">
      <b-dropdown
        size="sm"
        variant="link"
        toggle-class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 text-decoration-none"
        no-caret
        right
        no-flip
      >
        <template v-slot:button-content>
          <img
            class="h-20px w-20px rounded-sm"
            :src="languageFlag || getLanguageFlag"
            alt=""
          />
        </template>
        <b-dropdown-text tag="div" class="min-w-md-175px">
          <KTDropdownLanguage
            v-on:language-changed="onLanguageChanged"
          ></KTDropdownLanguage>
        </b-dropdown-text>
      </b-dropdown>
    </div>
    <!--begin: User Bar -->
    <KTQuickUser></KTQuickUser>
    <!--end: User Bar -->
  </div>
  <!-- end:: Header Topbar -->
</template>

<style lang="scss">
.topbar {
  .dropdown-toggle {
    padding: 0;
    &:hover {
      text-decoration: none;
    }

    &.dropdown-toggle-no-caret {
      &:after {
        content: none;
      }
    }
  }

  .dropdown-menu {
    margin: 0;
    padding: 0;
    outline: none;
    .b-dropdown-text {
      padding: 0;
    }
  }
}
</style>

<script>
import KTQuickUser from "aff/view/layout/extras/offcanvas/QuickUser.vue";
import KTDropdownLanguage from "aff/view/layout/extras/dropdown/DropdownLanguage.vue";
import i18nService from "aff/core/services/i18n.service.js";
import { BDropdown, BDropdownText } from "bootstrap-vue";
import {  mapState } from "vuex";
export default {
  name: "KTTopbar",
  data() {
    return {
        languageFlag: "",
        languages: i18nService.languages
    };
  },
  components: {
    KTQuickUser,
    KTDropdownLanguage,
    BDropdown, BDropdownText
  },
    methods: {
    onLanguageChanged() {
      this.languageFlag = this.languages.find(val => {
        return val.lang === i18nService.getActiveLanguage();
      }).flag;
    }
  },
  computed: {
    ...mapState({
        enable_affiliate_language: state => state.layout.settings.enable_affiliate_language,
        auto_detect_language: state => state.layout.settings.auto_detect_language,
    }),
    getLanguageFlag() {
      return this.onLanguageChanged();
    },

  }
};
</script>
