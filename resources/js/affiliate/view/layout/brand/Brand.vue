<template>
  <!-- begin:: Aside -->
  <div class="brand flex-column-auto" id="kt_brand" ref="kt_brand">
    <div class="brand-logo">
      <router-link to="/">
        <img v-if="this.logo" :src="siteLogo()" alt="Logo" style="height: auto; max-width:130px; max-height: 65px"/>
      </router-link>
    </div>
    <div class="brand-tools" v-if="allowMinimize">
      <button
        class="brand-toggle btn btn-sm px-0"
        id="kt_aside_toggle"
        ref="kt_aside_toggle"
      >
        <span class="svg-icon svg-icon svg-icon-xl svg-icon-warning">
          <inline-svg
            class="svg-icon"
            src="media/svg/icons/Navigation/Angle-double-left.svg"
          />
        </span>
      </button>
    </div>
  </div>
  <!-- end:: Aside -->
</template>

<style lang="scss" scoped>
.aside-toggle {
  outline: none;
}
</style>

<script>
import { mapGetters, mapState } from "vuex";
import KTLayoutBrand from "@/assets/js/layout/base/brand.js";
import KTLayoutAsideToggle from "@/assets/js/layout/base/aside-toggle.js";

export default {
  name: "KTBrand",
  mounted() {
    // Init Brand Panel For Logo
    KTLayoutBrand.init(this.$refs["kt_brand"]);

    // Init Aside Menu Toggle
    KTLayoutAsideToggle.init(this.$refs["kt_aside_toggle"]);
  },
  methods: {
    siteLogo() {
        if(!!this.logo){
            let myLogo = '';
            if (typeof this.logo === "string") {
              myLogo = this.logo;
            }
            return myLogo;
        }
    }
  },
  computed: {
    ...mapGetters(["layoutConfig"]),
    ...mapState({
        logo: state => state.layout.settings.logo,
        }),
    allowMinimize() {
      return !!this.layoutConfig("aside.self.minimize.toggle");
    }
  }
};
</script>
