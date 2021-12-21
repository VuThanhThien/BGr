<template>
  <div>
    <div class="form-group mb-8">
      <alert-note :type="'alert-default alert-shadow'">
        Make a sample coupon with the rule that will be applied to all future
        auto-generated coupons (percentage, fixed amount or free shipping).
        <br />
        Based on the rule you set in here, our system will generate this coupon on the Discount section of your
            Shopify admin 
      </alert-note>
    </div>
    
    <AlertError :errorMsg="errorMsg"></AlertError>
    <div class="form-group mt-4">
      <label>Coupon code</label>
      <input
        class="form-control"
        :class="{
          'border-error': !$v.form.couponCode.required && submit,
        }"
        v-model.trim="$v.form.couponCode.$model"
      />
      <div
        class="invalid-feedback"
        v-if="submit"
        v-bind:style="{
          display: $v.form.couponCode.required ? 'none' : 'block',
        }"
      >
        Please enter a coupon.
      </div>
    </div>
    <div class="row mb-8">
      <div class="col-md-6">
        <label for="discount_type">Discount type</label>
        <b-form-select
          v-model="form.discountType"
          :options="discount_type_options"
          id="discount_type"
        ></b-form-select>
      </div>
      <div class="col-md-6" v-if="form.discountType != 3">
        <label for="discount_amount">Discount amount</label>
        <b-form-input
          v-model="form.discountAmount"
          min="0"
          type="number"
          id="discount_amount"
          value="0"
        ></b-form-input>
      </div>
    </div>
      <label for="discount_coupon_type">Coupons style</label>
      <b-form-select
        v-model="form.coupon_style"
        :options="coupons_style"
        id="discount_coupon_type"
      ></b-form-select>
      <div class="mt-2">
        <span class="text-muted"
          >The coupon code given to affiliates on sign up will try and
          match</span
        >
        <div class="mt-1" v-if="form.coupon_style == 'name'">
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
    <ImageButton
      :loading="submitLoading"
      @click="saveCoupon"
      class="float-right mb-6"
    >
      Add coupon
    </ImageButton>
  </div>
</template>
<script>
import ApiService from "@/core/services/api.service";
import { BFormInput, BFormSelect } from "bootstrap-vue";
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import AlertError from "@/view/content/AlertError.vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import moment, { unix } from "moment";
export default {
  mixins: [validationMixin],
  components: {
    BFormInput,
    ImageButton,
    AlertError,
    BFormSelect
  },
  data() {
    return {
      submit: false,
      errorMsg: "",
      submitLoading: false,
      form: {
        couponCode: "",
        discountType: 1,
        discountAmount: 0,
        coupon_style: "name"
      },
      discount_type_options: [
        { value: 1, text: "Percentage" },
        { value: 2, text: "Fixed amount" },
        { value: 3, text: "Free shipping" },
      ],
      coupons_style: [
        { value: "name", text: "Affiliate's Name" },
        { value: "random", text: "Random letters" },
      ],
    };
  },
  methods: {
    saveCoupon() {
      this.submit = true;
      this.errorMsg = "";
      this.submitLoading = true;
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.submitLoading = false;
        return;
      }
      this.form.isExist = 0;
      this.form.startAt = moment().unix();
      ApiService.post("settings/settings-automatic-coupon", this.form)
        .then(({ data }) => {
          if (data.data.status) {
            this.$toast.success("Added", { position: "bottom" });
          } else {
            this.errorMsg = data.data.message;
          }
        })
        .catch(({ response }) => {
              if (response.status == 422) {
              let arrError = Object.keys(response.data.errors);
              if (arrError.length) {
                this.errorMsg = response.data.errors[arrError[0]][0];
              }
            }
        })
        .finally(() => {
          this.submit = false;
          this.submitLoading = false;
        });
    },
  },
  validations: {
    form: {
      couponCode: {
        required,
      }
    },
  },
};
</script>
<style scoped>
.border-error {
  border-color: #f64e60 !important;
}
.error {
  font-size: 0.75rem;
  line-height: 1;
  display: none;
  margin-left: 14px;
  margin-top: -1.6875rem;
  margin-bottom: 0.9375rem;
}
</style>