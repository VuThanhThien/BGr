<template>
  <b-modal
    hide-footer
    ref="automaticCoupon"
    size="lg"
    title="Automatic Coupon"
    body-bg-variant="light"
    @hidden="updatePriceRule"
  >
    <div class="card card-custom card-stretch gutter-b">
      <b-tabs card nav-class="nav-tabs-line">
        <b-tab title="Use an existing coupon">
          <div class="form-group mb-8">
            <div class="alert alert-custom alert-default" role="alert">
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
                Make a sample coupon with the rule that will be applied to all
                future auto-generated coupons (percentage, fixed amount or free
                shipping). <br />
              </div>
            </div>
          </div>
          <div class="form-group mb-8">
            <div class="alert alert-custom alert-default" role="alert">
              <div class="alert-text">
                <strong>Please take the folowing two steps: </strong> <br />
                <br />
                1. Create a discount code through the Shopify admin page:
                Shopify admin page > discounts > create discount > discount code
                ( do not choose the buy X get Y discount type). <br />
                <br />
                2. Fill the box below with the coupon code you have just
                created.
              </div>
            </div>
          </div>
          <form @submit.stop.prevent="submit">
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Coupon code"
              label-for="email_address"
            >
              <b-form-input
                id="email_address"
                v-model="$v.coupon.couponCode.$model"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="fieldset"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Coupons style"
              label-for="discount_type"
            >
              <b-form-select
                v-model="coupon.couponStyle"
                :options="coupons_style"
                id="discount_type"
                @input="changeCouponStyle(coupon.couponStyle)"
              ></b-form-select>
              <div class="mt-2">
                <span class="text-muted"
                  >The coupon code given to affiliates on sign up will try and
                  match</span
                >
                <div class="mt-1" v-if="style == 'name'">
                  <span>Examples: </span
                  ><span class="label label-lg label-bixgrow label-inline"
                    >JAMESMILLER</span
                  >
                  <span class="label label-lg label-bixgrow label-inline"
                    >MARRYAUSTIN</span
                  >
                </div>
                <div class="mt-1" v-else>
                  <span>Examples: </span
                  ><span class="label label-lg label-bixgrow label-inline"
                    >MG3N7NL8R1</span
                  >
                  <span class="label label-lg label-bixgrow label-inline"
                    >0IK41N8IDG</span
                  >
                </div>
              </div>
            </b-form-group>
            <button
              type="submit"
              class="btn btn-primary float-right mb-6"
              :disabled="loading"
            >
              <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
                <inline-svg src="media/svg/icons/General/Save.svg" />
              </span>
              <b-spinner v-if="loading" small></b-spinner>
              Add coupon
            </button>
          </form>
          <!-- <ImageButton
            :loading="loading"
            @click="submit"
           class="float-right mb-6"
          >
            Add coupon
          </ImageButton> -->
        </b-tab>
        <b-tab title="Add a new coupon" lazy>
          <AutomaticNewCouponModal></AutomaticNewCouponModal>
        </b-tab>
      </b-tabs>
    </div>
  </b-modal>
</template>

<script>
import ApiService from "aff/core/services/api.service";
// import LoadingSubmitButton from "aff/view/content/LoadingSubmitButton.vue";
import { validationMixin } from "vuelidate";
import { required, minLength, email } from "vuelidate/lib/validators";
import { mapMutations } from "vuex";
import {
  BFormGroup,
  BFormSelect,
  BFormInput,
  BTabs,
  BTab,
  BSpinner,
} from "bootstrap-vue";
// import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import AutomaticNewCouponModal from "./AutomaticNewCouponModal.vue";
export default {
  props: ["coupon"],
  mixins: [validationMixin],
  components: {
    BFormGroup,
    BFormSelect,
    BFormInput,
    BTabs,
    BTab,
    AutomaticNewCouponModal,
    BSpinner,
  },
  data() {
    return {
      style: "name",
      loading: false,
      couponCodeSuccess: null,
      coupons_style: [
        { value: "name", text: "Affiliate's Name" },
        { value: "random", text: "Random letters" },
      ],
    };
  },
  validations: {
    coupon: {
      couponCode: {
        required,
      },
    },
  },
  methods: {
    changeCouponStyle(style) {
      this.style = style;
    },
    submit() {
      this.loading = true;
      this.$v.coupon.$touch();
      if (this.$v.coupon.$anyError) {
        this.loading = false;
        return;
      }
      ApiService.post("settings/settings-automatic-coupon", {
        couponCode: this.coupon.couponCode,
        coupon_style: this.coupon.couponStyle,
        isExist: 1,
      })
        .then(({ data }) => {
          if (data.data.status) {
            this.$toast.success("Added", { position: "bottom" });
          } else {
            this.$toast.error(data.data.message, { position: "bottom" });
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    updatePriceRule() {
      this.$emit("close");
    },
  },
};
</script>

<style>
.field-box {
  padding: 25px;
  background: #f3f3f3;
}
.form-preview-box {
  padding: 20px;
  background: #f3f6f9;
}
</style>
