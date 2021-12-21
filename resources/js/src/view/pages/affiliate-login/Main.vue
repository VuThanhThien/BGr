<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
    <span class="alert-heading" style="font-size: 15px; font-weight: 600;">Customize your affiliate login page</span>
    </alert-note>
  <div class="card card-custom gutter-b">
      <div class="card-header border-0 py-5">
        <h3 class="card-title w-100 justify-content-between flex">
          <span class="card-label font-weight-bolder text-bixgrow">
            Affiliate Login
          </span>
          <LoadingSubmitButton :loading="submitLoading" @click="onApplyChange">
            Save
          </LoadingSubmitButton>
        </h3>
      </div>
    <div class="card-body pb-10 pt-0">
        <b-tabs card nav-class="nav-tabs-line group-nav">
          <b-tab lazy title="Size">
            <LoginSize />
          </b-tab>
          <b-tab lazy title="Style">
            <LoginColor />
          </b-tab>
          <b-tab title="Advanced" lazy>
            <LoginCustomCss />
          </b-tab>
        </b-tabs>
        <div>
            <LoginPreview />
        </div>
    </div>
  </div>
</div>
</template>

<script>
import { BTabs, BTab } from "bootstrap-vue";
import LoginSize from "./LoginSize.vue";
import LoginColor from "./Color.vue";
import LoginCustomCss from "./CustomCss.vue";
import LoginPreview from "./Preview.vue";
import { mapGetters, mapActions } from "vuex";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
export default {
  components:{BTabs, BTab,LoginSize, LoginColor, LoginCustomCss, LoginPreview, LoadingSubmitButton},
  data() {
    return {
        submitLoading: false,
        loginLayoutData:{}
    };
  },
  methods: {
      ...mapActions(["changeLoginLayout", "updateLayoutLogin"]),
      onApplyChange() {
      this.submitLoading = true;
      let obj = this.loginLayoutData;
      this.changeLoginLayout(obj);
      this.updateLayoutLogin(obj)
        .then(() => {
          this.submitLoading = false;
          this.$toast.success("Updated", {
            position: "bottom",
          });
        })
        .catch((respon) => {
          this.$toast.error(respon.data.message, { position: "bottom" });
          this.submitLoading = false;
        });
    },
  },
  created(){
      ApiService.query("/setting/login-form-config")
      .then(({ data }) => {
        this.loginLayoutData = data.data.login_page_config;
        this.changeLoginLayout(this.loginLayoutData);
      })
      .catch(({ response }) => {
        this.$toast.error(response.data.message, { position: "bottom" });
      });
  },
};
</script>
<style >
.group-nav > .nav-item > .nav-link.active, .nav-link:hover{
    color: #7E8299;
}
.group-nav .nav-link:hover:not(.disabled), .nav.nav-tabs.nav-tabs-line .nav-link.active, .nav.nav-tabs.nav-tabs-line .show > .nav-link{
    border-bottom: 1px solid #7E8299 !important;
    color : #7E8299 !important;
}
</style>
