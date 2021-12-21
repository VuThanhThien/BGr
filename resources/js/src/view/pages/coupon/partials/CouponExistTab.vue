<template>
  <div>
    <alert-note :type="'alert-default alert-shadow'">
      <p>
        Please follow these two steps to allow your affiliate to advertise your store using coupons:
      </p>
      <p>
        1. Create a coupon code through via Shopify admin page > Discounts > Create
        discount > Discount code (please do not create automatic discount).
      </p>
      <p>
        2. Fill in the box with coupon code you have created.
        Please keep in mind that each coupon can only be assigned to one affiliate and
        should not be used in any other advertising campaigns.
      </p>
    </alert-note>
    <AlertError :errorMsg="errorMsg"></AlertError>
    <label>Affiliate</label>
    <SearchAff @input="setAffiliateSelected" ref="searchAff" />
    <div
      class="invalid-feedback"
      v-if="submit"
      v-bind:style="{ display: haveAffiliate ? 'none' : 'block' }"
    >
      Please enter an affiliate.
    </div>
    <div class="form-group mt-4">
      <label>Coupon code</label>
      <input
        class="form-control"
        :class="{ 'border-error': !$v.form.couponCode.required && submit }"
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
import SearchAff from "@/view/content/SearchAffiliate.vue";
import { BFormInput } from "bootstrap-vue";
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import AlertError from "@/view/content/AlertError.vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
export default {
  mixins: [validationMixin],
  components: {
    BFormInput,
    ImageButton,
    SearchAff,
    AlertError,
  },
  data() {
    return {
      submit: false,
      errorMsg: "",
      haveAffiliate: false,
      submitLoading: false,
      affiliateFilterSelected: null,
      form: {
        couponCode: "",
        affiliate: null,
      },
    };
  },
  methods: {
    onSearchAffiliate(search, loading) {
      if (search.length) {
        loading(true);
        this.searchAffiliate(loading, search, this);
      }
    },

    searchAffiliate: _.debounce((loading, search, vm) => {
      let resource = "affiliates/search?query=" + escape(search);
      ApiService.query(resource).then(({ data }) => {
        vm.affiliates = data;
        loading(false);
      });
    }),
    setAffiliateSelected(value) {
      if (value) {
        this.affiliateFilterSelected = value;
        this.form.affiliate = value.id;
        this.haveAffiliate = true;
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.remove(
          "border-error"
        );
      } else {
        this.haveAffiliate = false;
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
          "border-error"
        );
      }
    },
    saveCoupon() {
      this.submit = true;
      this.errorMsg = "";
      this.submitLoading = true;
      // this.form.priceId=this.price_id;

      // nếu không có afiliate thì border đỏ ô affiliate
      if (this.haveAffiliate == false) {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
          "border-error"
        );
        this.submitLoading = false;
        return;
      }
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.submitLoading = false;
        return;
      }

      //  if (!this.$v.form.couponCode.required) {
      //       this.errorMsg='Please enter a coupon';
      //        this.submitLoading = false;
      //       return;
      //   }
      // nếu check create in store mà k có price id thì toast
      // if (this.form.isEnable && !this.price_id) {
      //   this.$toast.error("Please setting automatic coupon", {
      //     position: "bottom",
      //   });
      //    this.submitLoading = false;
      //   return;
      // }

      /**
       * Gọi API
       * thành công: đóng form - toast - emit
       * thất bại toast lỗi
       *
       */
      if (this.haveAffiliate == true) {
        this.form.isExist = 1;
        ApiService.post("coupons", this.form)
          .then(({ data }) => {
            if (data.data.status) {
              this.form.couponCode = "";
              this.$v.$reset();
              this.$toast.success("Added", { position: "bottom" });
            } else {
              this.errorMsg = data.data.message;
            }
          })
          .catch(({ response }) => {
            if (response.status == 422) {
              this.errorMsg = response.data.errors.couponCode[0];
            }
          })
          .finally(() => {
            this.submit = false;
            this.submitLoading = false;
          });
      }
    },
  },
  validations: {
    form: {
      couponCode: {
        required,
      },
      affiliate: {
        required,
      },
    },
  },
};
</script>
<style scoped>
.error {
  font-size: 0.75rem;
  line-height: 1;
  display: none;
  margin-left: 14px;
  margin-top: -1.6875rem;
  margin-bottom: 0.9375rem;
}
</style>