<template>
  <div class="d-flex flex-column flex-root" v-if="isAuthenticated">
    <Loader v-if="loaderEnabled" v-bind:logo="loaderLogo"></Loader>
    <div v-else>
      <KTHeaderMobile></KTHeaderMobile>
      <div class="d-flex flex-row flex-column-fluid page">
        <KTAside v-if="asideEnabled"></KTAside>
        <div id="kt_wrapper" class="d-flex flex-column flex-row-fluid wrapper">
          <KTHeader></KTHeader>
          <div
            id="kt_content"
            class="content d-flex flex-column flex-column-fluid"
          >
            <div class="d-flex flex-column-fluid">
              <div
                :class="{
                  'container-fluid': contentFluid,
                  container: !contentFluid,
                }"
              >
                <div class="d-lg-flex flex-row-fluid">
                  <div class="content-wrapper flex-row-fluid">
                    <router-view />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <KTScrollTop></KTScrollTop>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import KTAside from "@/view/layout/aside/Aside.vue";
import KTHeader from "@/view/layout/header/Header.vue";
import KTHeaderMobile from "@/view/layout/header/HeaderMobile.vue";
import KTFooter from "@/view/layout/footer/Footer.vue";
import HtmlClass from "@/core/services/htmlclass.service";
import KTScrollTop from "@/view/layout/extras/ScrollTop";
import Loader from "@/view/content/Loader.vue";
import { VERIFY_AUTH } from "@/core/services/store/auth.module.js";
import {
  ADD_BODY_CLASSNAME,
} from "@/core/services/store/htmlclass.module.js";

export default {
  name: "Layout",
  data() {
    return {
      loaderEnabled: true,
    };
  },
  components: {
    KTAside,
    KTHeader,
    KTHeaderMobile,
    KTFooter,
    KTScrollTop,
    Loader,
  },
  beforeMount() {
    // show page loading
    this.$store.dispatch(VERIFY_AUTH);
    this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");

    // initialize html element classes
    HtmlClass.init(this.layoutConfig());
  },
  mounted() {
    // check if current user is authenticated
    if (!this.isAuthenticated) {
      this.$router.push({ name: "welcome" });
    } else {
      this.$store.dispatch("loadLayoutData").then(({ data }) => {
        this.loaderEnabled = false;
      });
    }
  },
  methods: {},
  computed: {
    ...mapGetters([
      "isAuthenticated",
      "layoutConfig",
    ]),

    /**
     * Check if container width is fluid
     * @returns {boolean}
     */
    contentFluid() {
      return this.layoutConfig("content.width") === "fluid";
    },

    /**
     * Page loader logo image using require() function
     * @returns {string}
     */
    loaderLogo() {
      return "https://app.bixgrow.com/media/logos/bixgrow-yellow.png";
    },

    /**
     * Check if the left aside menu is enabled
     * @returns {boolean}
     */
    asideEnabled() {
      return !!this.layoutConfig("aside.self.display");
    },

    /**
     * Set the right toolbar display
     * @returns {boolean}
     */
    toolbarDisplay() {
      // return !!this.layoutConfig("toolbar.display");
      return true;
    },

    /**
     * Set the subheader display
     * @returns {boolean}
     */
    subheaderDisplay() {
      return !!this.layoutConfig("subheader.display");
    },
  },
};
</script>
<style scoped>
/* @media (min-width: 992px) {
  .aside-fixed .wrapper {
    padding-left: 240px;
  }
} */
</style>
