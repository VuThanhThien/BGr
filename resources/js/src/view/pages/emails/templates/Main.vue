<template>
  <div id="email-editor">
    <alert-note :type="'alert-white alert-shadow'" v-if="!isVerifiedFromEmail">
      Your affiliates will receive emails from us at
      <code>{{ fromContactEmail }}</code
      >. To use your email as a sender email, go
      <a class="font-weight-bold" href="#/settings/notification">HERE</a>.
    </alert-note>
    <alert-note :type="'alert-white alert-shadow'" v-else>
      We will send email alerts to your affiliates on behalf of your business
      email
      <code>{{ fromContactEmail }}</code
      >. To update your business's email address, go
      <a class="font-weight-bold" href="#/settings/notification">HERE</a>.
    </alert-note>

    <div class="row d-flex" id="kt_inbox_aside">
      <!-- cac button ben trai  -->
      <div class="col-lg-3 p-3">
        <div class="card-body px-5 card card-custom card-stretch">
          <!--begin::Navigations-->
          <div
            class="
              navi
              navi-hover
              navi-active
              navi-link-rounded
              navi-bold
              navi-icon-center
              navi-light-icon
            "
            role="tablist"
          >
            <!--begin::Item-->
            <h4>Affiliates</h4>
            <div class="space-25"></div>

            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'affiliate_review' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="far fa-clock icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Affiliates Review</span
                >
              </router-link>
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'approved_affiliate' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-user-check icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Approved Affiliates</span
                >
              </router-link>
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'denied_affiliate' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-user-times icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Denied Affiliates</span
                >
              </router-link>
            </div>
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'invitation_affiliate' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fas fa-user-plus icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Added Affiliates</span
                >
              </router-link>
            </div>
            <!-- <div class="navi-item my-2">
            <router-link :to="{name:'emails.edit' , params: {slug:'a'}}" class="navi-link" active-class="active">
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class=" fas fa-user-plus icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder font-size-lg"
                  >Added/imported affiliates</span
                >
                <span class="navi-label">
                  <span  class="label label-rounded label-light-success  font-weight-bolder">ON</span>
                </span>
            </router-link>
            </div> -->

            <!-- <div class="navi-item my-2">
            <router-link :to="{name:'emails.edit' , params: {slug:'b'}}" class="navi-link" active-class="active">
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="far fa-credit-card icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder font-size-lg"
                  >Payment method reminder</span
                >
                <span class="navi-label">
                  <span  class="label label-rounded label-light-success  font-weight-bolder">ON</span>
                </span>
            </router-link>
            </div> -->

            <!-- <div class="navi-item my-2">
            <router-link :to="{name:'emails.edit' , params: {slug:'affiliate_verification'}}" class="navi-link" active-class="active">
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-user-shield icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder font-size-lg"
                  >Affiliate Verification</span
                >
                <span class="navi-label">
                  <span  class="label label-rounded label-light-success  font-weight-bolder">ON</span>
                </span>
            </router-link>
            </div> -->
            <!--end::Item-->
            <!--begin::Item-->
            <h4>Conversions</h4>
            <div class="space-25"></div>
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'new_conversion' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-cart-plus icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder">New Conversion</span>
              </router-link>
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'approved_conversion' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-check-circle icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Approved Conversion</span
                >
              </router-link>
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'denied_conversion' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-times-circle icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Denied Conversion</span
                >
              </router-link>
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <h4>Payout</h4>
            <div class="space-25"></div>
            <div class="navi-item my-2">
              <router-link
                :to="{
                  name: 'templates.edit',
                  params: { slug: 'commission_payout' },
                }"
                class="navi-link"
                active-class="active"
              >
                <span class="navi-icon mr-4">
                  <span class="svg-icon svg-icon-lg">
                    <i class="fas fa-credit-card icon-md"></i>
                  </span>
                </span>
                <span class="navi-text font-weight-bolder"
                  >Commission Payout</span
                >
              </router-link>
            </div>
            <!--end::Item-->
          </div>
          <!--end::Navigations-->
          <!--begin::Compose-->

          <!--end::Compose-->
        </div>
      </div>
      <!-- ckeditor ben phai  -->
      <div class="col-lg-9 p-3">
        <transition name="fade-in-up">
          <router-view />
        </transition>

        <div class="tag-editor ibox d-flex flex-wrap rounded mt-10">
          <span class="label label-light label-inline mr-2 py-5"
            ><i class="fas fa-tag"></i> { first_name } : {{ first_name }}</span
          >
          <span class="label label-light label-inline mr-2 py-5">
            <i class="fas fa-tag"></i>{ brand_name } : {{ branch_name }}</span
          >
          <span class="label label-light label-inline mr-2 py-5">
            <i class="fas fa-tag"></i>{ affiliate_login_url } :
            {{ affiliate_login_url }}</span
          >
          <span class="label label-light label-inline mr-2 py-5">
            <i class="fas fa-tag"></i>{ order_number } :
            {{ order_number }}</span
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
  // created(){
  //   this.$store.dispatch('getListStatus');
  // },
  name: "email-editor",
  data() {
    return {
      first_name:"Affiliate's first name",
      logo: "Your Logo",
      branch_name:"Your brand name",
      affiliate_login_url:"Url to login to affiliate account",
      order_number: "Order number"
    };
  },
  computed:{
    ...mapGetters(['fromContactEmail','isVerifiedFromEmail']),
  },
  methods:{
    // calcBreadcum(string){
    //   string = string.substr(8).replace("_"," ");
    //   return string.charAt(0).toUpperCase() + string.slice(1);
    // },

  },
//   mounted(){
//     this.$store.dispatch(SET_BREADCRUMB, [
//         { title: "Emails", route: "/emails/affiliate_review" },
//         { title: this.calcBreadcum(this.$route.path)}
//       ]);
//   },
//   watch:{
//     $route (to, from){
//         this.$store.dispatch(SET_BREADCRUMB, [
//         { title: "Emails", route: "/emails/affiliate_review" },
//         { title: this.calcBreadcum(this.$route.path)}
//       ]);
//     }
// }
};
</script>
<style lang="scss" scoped>
.enable-feature {
  background-color: #ffffff;
  padding: 20px 40px;
  border-color: #e8e9f2;
  min-height: 90px;
  border-style: none;
  text-align: left;
  align-items: center;
  gap: 2rem;
  margin-bottom: 2rem;
  font-size: 14px;
  font-weight: 400;
}
.tag-editor {
  background-color: #fff;
  padding: 20px 40px;
  border-color: #e8e9f2;
  min-height: 90px;
  border-style: none;
  text-align: left;
  align-items: flex-start;
  gap: 1rem;
}
.ibox {
  box-shadow: -2px 2px 15px rgb(9 44 76 / 5%);
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
.file-manager {
  padding: 20px 40px;
  margin-left: auto;
  margin-right: auto;
}
.action-btn {
  display: flex;
  justify-content: space-between;
  padding: 20px 20px;
}

.tip {
  margin: 4rem auto 10rem auto;
  background-color: #ffffff;
  padding: 20px 40px;
  border-color: #e8e9f2;
  min-height: 90px;
  border-style: none;
  text-align: center;
  align-items: center;
  gap: 2rem;
  font-size: 14px;
  font-weight: 400;
}
h4 {
  margin-top: 20px;
  margin-bottom: 20px;
  margin-left: 10px;
}
@media only screen and (max-width: 768px) {
  .tag-editor {
    display: none !important;
  }
}
</style>
