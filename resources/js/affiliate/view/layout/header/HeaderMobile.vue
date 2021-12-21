<template>
  <div
    id="kt_header_mobile"
    class="header-mobile align-items-center"
    v-bind:class="headerClasses"
  >
    <!--begin::Logo-->
    <router-link to="/">
      <img alt="Logo" v-if="this.logo" :src="siteLogo()" style="height: 50px; width: auto"/>
    </router-link>
    <!--end::Logo-->
    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">
      <!--begin::Aside Mobile Toggle-->
      <button
        v-if="asideEnabled"
        class="btn p-0 burger-icon burger-icon-left"
        id="kt_aside_mobile_toggle"
      >
        <span></span>
      </button>
      <!--end::Aside Mobile Toggle-->
      <!--begin::Header Menu Mobile Toggle-->
      <!-- <button
        class="btn p-0 burger-icon ml-4"
        id="kt_header_mobile_toggle"
        ref="kt_header_mobile_toggle"
      >
        <span></span>
      </button> -->
      <!--end::Header Menu Mobile Toggle-->
      <!--begin::Topbar Mobile Toggle-->
      <button
        class="btn btn-hover-text-primary p-0 ml-2"
        id="kt_header_mobile_topbar_toggle"
        ref="kt_header_mobile_topbar_toggle"
      >
        <span class="svg-icon svg-icon-xl">
          <!--begin::Svg Icon | path:svg/icons/General/User.svg-->
          <inline-svg class="svg-icon" src="media/svg/icons/General/User.svg" />
          <!--end::Svg Icon-->
        </span>
      </button>
      <!--end::Topbar Mobile Toggle-->
    </div>
    <!--end::Toolbar-->
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import KTLayoutHeaderTopbar from "@/assets/js/layout/base/header-topbar.js";

export default {
  name: "KTHeaderMobile",
  components: {},
  mounted() {
    // Init Header Topbar For Mobile Mode
    KTLayoutHeaderTopbar.init(this.$refs["kt_header_mobile_topbar_toggle"]);
  },
  computed: {
    ...mapGetters(["layoutConfig", "getClasses","layoutConfig"]),
    ...mapState({
        logo: state => state.layout.settings.logo,
        }),

    headerClasses() {
      const classes = this.getClasses("header_mobile");
      if (typeof classes !== "undefined") {
        return classes.join(" ");
      }
      return null;
    },

    asideEnabled() {
      return !!this.layoutConfig("aside.self.display");
    },
        allowMinimize() {
      return !!this.layoutConfig("aside.self.minimize.toggle");
    }
  },
  methods:{
    siteLogo() {
        if(!!this.logo){
            let myLogo = '';
            if (typeof this.logo === "string") {
              myLogo = this.logo;
            }
            return myLogo;
        }
    }
  }
};
</script>
