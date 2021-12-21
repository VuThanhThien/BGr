<template>
  <div>
    <!--begin::Card-->
    <div class="card card-custom mb-2 mb-xl-5">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">Coupons</h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            ></span
          >
        </div>
      </div>
      <!--end::Header-->
      <div class="form-group m-8">
        <div role="alert" class="alert alert-custom alert-default">
          <div class="alert-icon">
            <span class="svg-icon svg-icon-3x svg-icon-primary px-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                width="24px"
                height="24px"
                viewBox="0 0 24 24"
                version="1.1"
              >
                <g
                  stroke="none"
                  stroke-width="1"
                  fill="none"
                  fill-rule="evenodd"
                >
                  <rect x="0" y="0" width="24" height="24"></rect>
                  <path
                    d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                    fill="#000000"
                    opacity="0.3"
                  ></path>
                  <path
                    d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                    fill="#000000"
                    fill-rule="nonzero"
                  ></path>
                </g>
              </svg>
            </span>
          </div>
          <div class="alert-text">
            <span
              class="alert-heading"
              style="font-size: 15px; font-weight: 600"
              >This feature requires automatic coupon to be set!</span
            >
            <br />In order to activate this feature, you must setup the
            automatic coupons below.
          </div>
        </div>
      </div>
      <!--begin::Body-->
      <div class="card-body">
        <b-form-group
          id="fieldset-horizontal"
          label-cols-sm="4"
          label-cols-lg="3"
          content-cols-sm="8"
          content-cols-lg="9"
          label="Create coupon code automatically: "
          label-for="auto_generate_coupon"
          ><span v-if="!loading">
            <b-form-checkbox
              id="auto_generate_coupon"
              v-model="autoGenerateCoupon"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="
                changeCodeTracking('auto_generate_coupon', autoGenerateCoupon)
              "
            ></b-form-checkbox>
          </span>
          <span v-else>
            <Loading></Loading>
          </span>
          <span class="form-text text-muted mt-5">
            When applied, a coupon code with the rule set in automatic coupon
            will be automatically generated for all future affiliates.
          </span>
        </b-form-group>
        <!-- <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Enable specifying coupon code: "
            label-for="allow_specifying_discount_code"
          >
            <b-form-checkbox
              id="allow_specifying_discount_code"
              switch
              size="lg"
              value=1
              unchecked-value=0
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When applied, a new field will appear during affiliate registration to fill in the preferred coupon code.
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Enable changing coupon code: "
            label-for="allow_changing_discount_code"
          >
            <b-form-checkbox
              id="allow_changing_discount_code"
              switch
              size="lg"
              value=1
              unchecked-value=0
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When applied, affiliates can be able to change the preferred coupon code in Settings.
            </span>
          </b-form-group> -->
      </div>
      <!--end::Body-->
      <!--end::Form-->
    </div>
    <div class="card card-custom">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Automatic Coupon
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Setting automatic coupon</span
          >
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
      <!--begin::Body-->
      <div class="card-body">
        <div v-if="!loading">
          <div class="mt-1" v-if="couponcodeAutomatic">
            <div class="fs-4 fw-bolder mb-5">
              <span class="text font-weight-bolder">Coupon code: </span>
              <span
                class="
                  label label-lg label-bixgrow label-inline
                  text
                  font-weight-bolder
                "
                >{{ couponcodeAutomatic }}</span
              >
            </div>
            <button
              @click="openAutomaticCouponModal"
              type="button"
              class="btn btn-sm btn-primary mr-2"
            >
              Change
            </button>
            <button
              @click="deleteAutomaticCoupon"
              type="button"
              class="btn btn-sm btn-danger mr-2"
            >
              Delete
            </button>
          </div>
          <div v-else>
            <button
              @click="openAutomaticCouponModal"
              type="button"
              class="btn btn-sm btn-secondary mr-2"
            >
              Setup
            </button>
          </div>
        </div>
        <div class="mt-1" v-else>
          <Loading></Loading>
        </div>

        <AutomaticCouponModal
          :key="keyAutomaticCouponModal"
          ref="AutomaticCouponModal"
          @close="updatePriceRule"
          :coupon="{
            couponCode: couponcodeAutomatic,
            couponStyle: couponCodeStyle,
          }"
        ></AutomaticCouponModal>
      </div>
      <!--end::Body-->
      <!--end::Form-->
    </div>
  </div>
</template>

<script>
import AutomaticCouponModal from "@/view/pages/setting/partials/AutomaticCouponModal.vue";
import ApiService from "@/core/services/api.service";
import { BFormGroup, BFormCheckbox } from "bootstrap-vue";
import swal from "sweetalert2";
import Loading from "@/view/content/LoaderVue.vue";
window.Swal = swal;
export default {
  components: {
    AutomaticCouponModal,
    BFormGroup,
    BFormCheckbox,
    Loading,
  },
  data() {
    return {
      priceRuleId: 0,
      couponcodeAutomatic: null,
      autoGenerateCoupon: 0,
      couponCodeStyle: "name",
      loading: false,
      keyAutomaticCouponModal: 0
    };
  },
  methods: {
    changeCodeTracking(key, value) {
      if (!this.couponcodeAutomatic) {
        this.autoGenerateCoupon = 0;
        this.$toast.error("You need to setup automatic coupon first", {
          position: "bottom",
        });
      }
      let resource = `/settings/update-coupon-tracking`;
      ApiService.put(resource, {
        type: key,
        status: value,
      }).then(({ res }) => {});
    },
    openAutomaticCouponModal() {
      this.$refs["AutomaticCouponModal"].$refs["automaticCoupon"].show();
    },
    updatePriceRule() {
      this.getPriceRule();
    },
    getPriceRule() {
      this.loading = true;
      let resource = `/settings/get-coupon-automatic`;
      ApiService.query(resource)
        .then(({ data }) => {
          if (data.data) {
            this.priceRuleId =
              data.data.coupon_automatic != null
                ? data.data.coupon_automatic.price_rule
                : null;
            this.couponcodeAutomatic =
              data.data.coupon_automatic != null
                ? data.data.coupon_automatic.code
                : null;
            this.autoGenerateCoupon =
              data.data.coupon_code_tracking != null &&
              data.data.coupon_automatic != null
                ? data.data.coupon_code_tracking.auto_generate_coupon
                : null;
            this.couponCodeStyle =
              data.data.coupon_automatic != null
                ? data.data.coupon_automatic.coupon_style
                : "name";
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteAutomaticCoupon() {
      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this automatic coupon!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          let resource = `/settings/automatic-coupon`;
          ApiService.delete(resource)
            .then(({ data }) => {
                this.priceRuleId = 0;
                this.couponcodeAutomatic = null;
                this.autoGenerateCoupon = 0;
                this.couponCodeStyle = "name";
            })
            .finally(() => {
              this.loading = false;
            });
        }
      });
    },
  },
  created() {
    this.getPriceRule();
  },
};
</script>

<style>
</style>
