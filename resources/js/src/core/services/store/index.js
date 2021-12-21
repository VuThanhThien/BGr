import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth.module";
import htmlClass from "./htmlclass.module";
import config from "./config.module";
import group from "./admin/group.module";
import commission from "./admin/commission.module";
import layout from "./admin/layout.module";
import affiliate from "./admin/affiliate.module";
import emailtemplates from "./admin/email_template.module";
import file from "./admin/file.module";

import registration from "./admin/affiliate_registration.module";
import afiliatelogin from "./admin/affiliate_login.module";
Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    htmlClass,
    config,
    layout,
    group,
    commission,
    affiliate,
    emailtemplates,
    file,
    registration,
    afiliatelogin
  }
});
