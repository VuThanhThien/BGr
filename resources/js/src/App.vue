<template>
  <router-view></router-view>
</template>

<style lang="scss">
// 3rd party plugins css
@import "~bootstrap-vue/dist/bootstrap-vue.css";
@import "~perfect-scrollbar/css/perfect-scrollbar.css";
@import "assets/plugins/keenthemes-icons/font/ki.css";
// @import "~socicon/css/socicon.css";
@import "~@fortawesome/fontawesome-free/css/all.css";
// @import "assets/plugins/flaticon/flaticon.css";
@import "assets/sass/style.vue";
// Check documentation for RTL css
// Update HTML with RTL attribute at public/index.html
/*@import "assets/css/style.vue.rtl";*/
.custom-switch.b-custom-control-lg .custom-control-label::before{
    border-radius: 15px;
    width: 54px;
    height: 30px;
    background-color: #EBEDF3;
    border:none;
    cursor: pointer;
}
.custom-switch.b-custom-control-lg .custom-control-label::after{
  height: 24px;
  width: 24px;
  border-radius: 15px;
  background: #fff;
  top: 7px;
    cursor: pointer;
}
.custom-switch.b-custom-control-lg .custom-control-input:checked ~ .custom-control-label::after{
  left: -22px;
  background-color: #3699FF;
  content: '\f108';
  font-family: "Ki";
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  color: #fff;
  font-size: 0.65em;
  text-align: center;
  line-height: 24px;
}
.fa-calendar:before {
  content: "\f073" !important;
  font-size: 1.5rem !important;
  font-family: "Font Awesome 5 Free";
  font-weight: 400 !important;
  color: #7E8299 !important;
}
</style>

<script>
import { OVERRIDE_LAYOUT_CONFIG } from "@/core/services/store/config.module";

export default {
  name: "MetronicVue",
  mounted() {
    this.$store.dispatch(OVERRIDE_LAYOUT_CONFIG);
  }
};
</script>
