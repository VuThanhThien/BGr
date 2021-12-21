<template>
  <div>
    <div class="card card-custom gutter-b">
      <div class="card-body">
        <!--begin::Details-->
        <div class="d-flex">
          <!--begin::Info-->
          <div class="flex-grow-1">
            <!--begin::Title-->
            <div class="d-flex justify-content-between flex-wrap mt-1">
              <div class="d-flex mr-3 align-items-center">
                <span
                  class="
                    text-dark-75
                    font-size-h2 font-weight-bold
                    mr-3
                  "
                  >{{ affiliate.full_name }}</span
                >

                <span>
                  <span class="svg-icon svg-icon-lg svg-icon-success">
                    <inline-svg src="media/svg/icons/General/Shield-check.svg" />
                  </span>
                </span>
              </div>
            </div>
            <!--end::Title-->
            <!--begin::Content-->
            <div class="d-flex flex-wrap justify-content-between mt-1">
              <div class="d-flex flex-column flex-grow-1 pr-8">
                <div class="d-flex flex-wrap mb-4">
                  <span
                    class="
                      text-dark-50
                      font-weight-bold
                      mr-lg-8 mr-5
                      mb-lg-0 mb-2
                    "
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-success">
                      <inline-svg src="media/svg/icons/Communication/Mail.svg" />
                    </span>
                    {{ affiliate.email }}
                  </span>
                  <span
                    class="
                      text-dark-50
                      font-weight-bold
                      mr-lg-8 mr-5
                      mb-lg-0 mb-2
                    "
                  >
                    <i class="far fa-registered mr-2 font-size-lg"></i>Joined:
                    <span
                      v-html="formatDate(affiliate.timestamp, false)"
                    ></span>
                  </span>
                  <span
                    v-if="affiliate.last_login"
                    class="
                      text-dark-50
                      font-weight-bold
                      mr-lg-8 mr-5
                      mb-lg-0 mb-2
                    "
                  >
                    <i class="fas fa-sign-in-alt mr-2 font-size-lg"></i>Last
                    login:
                    <span
                      v-html="formatDate(affiliate.last_login, false)"
                    ></span>
                  </span>
                </div>
                <span
                  v-if="affiliate.personal_detail"
                  class="font-weight-bold text-dark-50"
                  >{{ affiliate.personal_detail }}</span
                >
              </div>
            </div>
            <!--end::Content-->
          </div>
          <!--end::Info-->
        </div>

      <div class="separator separator-solid"></div>
      <div id="myTabContent" class="tab-content card-body pt-10 pb-0 mt-n3">
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom" style="box-shadow: 17px 9px 27px 0 rgb(0 0 0 / 17%) !important;">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <span class="text-bixgrow text-md font-weight-bolder">Clicks</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.clicks }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/General/Cursor.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom" style="box-shadow: 17px 9px 27px 0 rgb(0 0 0 / 17%) !important;">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <span class="text-bixgrow text-md font-weight-bolder">Orders</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.orders }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Cart2.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom" style="box-shadow: 17px 9px 27px 0 rgb(0 0 0 / 17%) !important;">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <span class="text-bixgrow text-md font-weight-bolder">Revenue</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.revenue, format) }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Chart-line1.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 ">
            <div class="card card-custom" style="box-shadow: 17px 9px 27px 0 rgb(0 0 0 / 17%) !important;">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <span class="text-bixgrow text-md font-weight-bolder">Commission</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.commission, format) }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <i
                            class="fas fa-hand-holding-usd icon-2x"
                            style="color: #fff"
                          ></i>
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- end statistics -->
        <div class="mt-10">
          <b-nav class="nav-tabs nav-tabs-line group-nav">
            <b-nav-item
              :to="{
                name: 'affiliates.overview',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Overview</b-nav-item
            >
            <b-nav-item
              :to="{
                name: 'affiliates.sales',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Conversions</b-nav-item
            >
             <b-nav-item
              :to="{
                name: 'affiliates.coupons',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Coupons</b-nav-item
            >
            <b-nav-item
              :to="{
                name: 'affiliates.network',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Network</b-nav-item
            >
            <b-nav-item
              :to="{
                name: 'affiliates.payments',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Payments</b-nav-item
            >
            <b-nav-item
              :to="{
                name: 'affiliates.settings',
                params: { id: $route.params.id },
              }"
              exact
              exact-active-class="active"
              >Settings</b-nav-item
            >
          </b-nav>
        </div>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import { mapActions } from "vuex";
import { BNav,BNavItem } from 'bootstrap-vue'
export default {
  components:{
    BNav,BNavItem
  },
  data() {
    return {
      statistics: {},
    };
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
    affiliate() {
      return this.$store.getters.currentDetailAffiliate;
    },
  },
  methods: {
    ...mapActions(["loadAffiliate"]),
    loadStatistics() {
      let resource = `affiliates/${this.id}/get-statistics`;
      ApiService.query(resource).then(({ data }) => {
          this.statistics = data.data;
      })
    },
  },
  mounted() {
    this.loadAffiliate(this.id).catch(
      err=>{
        this.$toast.error(err.data.message, { position: "bottom" });
      }
    );
    this.loadStatistics(this.id);
    this.$router.push({ name: "affiliates.overview", params: { id: this.id } }).catch(()=>{});
  },
};
</script>

<style scoped>
.nav-tabs-line {
  font-size: 1.15rem;
}
.nav-item .active {
  border-bottom-width: 2px !important;
}
.symbol.symbol-bixgrow .symbol-label {
    background-color: #3f4254;
    color: #fbfbfb;
}
.symbol-label-custom {
  border: solid 1px #3f4254;
}
</style>
