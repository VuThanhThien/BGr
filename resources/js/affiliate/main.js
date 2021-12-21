import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "aff/core/services/store";
import ApiService from "aff/core/services/api.service";
import { RESET_LAYOUT_CONFIG } from "aff/core/services/store/config.module";
import './mixins.js';
import Sticky from 'vue-sticky-directive';
import VueToast from 'vue-toast-notification';


Vue.config.productionTip = false;

// Global 3rd party plugins
import "popper.js";
import "tooltip.js";
import PerfectScrollbar from "perfect-scrollbar";
window.PerfectScrollbar = PerfectScrollbar;
import ClipboardJS from "clipboard";
window.ClipboardJS = ClipboardJS;

// Vue 3rd party plugins
import i18n from "aff/core/plugins/vue-i18n";
import "@/core/plugins/portal-vue";
// import "@/core/plugins/bootstrap-vue";
import "@/core/plugins/perfect-scrollbar";
import "@/core/plugins/inline-svg";
import "@/core/plugins/apexcharts";
import "@/core/plugins/metronic";
import "@mdi/font/css/materialdesignicons.css";
import 'vue-select/dist/vue-select.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import {VBTooltipPlugin, ModalPlugin } from 'bootstrap-vue'
import AlertNote from "aff/view/content/AlertNote.vue";

Vue.component('alert-note',AlertNote )
Vue.use(ModalPlugin)
Vue.use(VBTooltipPlugin)
// API service init
ApiService.init(store, router);

// Remove this to disable mock API
// MockService.init(store, router);

router.beforeEach((to, from, next) => {
  // Ensure we checked auth before each page load.
  // Promise.all([store.dispatch(VERIFY_AUTH)]).then(next);
  // reset config to initial state
  if(to.name === 'register' && store.getters.isAuthenticated) {
    next({ name: 'dashboard' });
  }
  store.dispatch(RESET_LAYOUT_CONFIG);
  next();
  // Scroll page to top on every route change
  // setTimeout(() => {
  //   window.scrollTo(0, 0);
  // }, 100);
});

Vue.directive('Sticky', Sticky);
Vue.use(VueToast);

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount("#app");
