<template>
  <b-modal
    ref="assignCouponModal"
    hide-footer
    size="lg"
    title="Assign Coupon"
    body-bg-variant="light"
    @hidden="resetAssignModal"
  >
    <div class="card card-custom card-stretch gutter-b">
      <b-tabs card nav-class="nav-tabs-line">
        <b-tab title="Add a new coupon" >
          <!-- <div v-if="this.errorMsg" class="d-flex align-items-center bg-light-danger rounded p-5 mb-9">
            <span class="svg-icon svg-icon-danger mr-5">
                <span class="svg-icon svg-icon-4x svg-icon-primary">
                  <inline-svg src="media/svg/icons/Code/Warning-2.svg" />
                </span>
            </span>
            <div class="d-flex flex-column flex-grow-1 mr-2">
                <ul href="#" class="font-weight-bold text-danger font-size-lg mb-1">{{this.errorMsg}}</ul>
            </div>
        </div> -->
          <alert-note :type="'alert-default alert-shadow'">
             Based on the rule you set in here, our system will generate this coupon on the Discount section of your
            Shopify admin 
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
          <!-- <div class="form-group row">
        <div class="col-9 col-form-label">
          <div class="checkbox-inline">
            <label class="checkbox checkbox-primary">
              <input
                type="checkbox"
                @change="checkCouponCode"
                v-model="form.isEnable"
                name="Checkboxes5"
              />
              <span></span>
              Also generate in store
            </label>
          </div>
        </div>
        <div class="d-flex align-items-center rounded p-5 ml-2" style="width:100%;margin-right: 14px;" :class="[success==true ? 'bg-light-success' : 'bg-light-warning']">
            <span class="svg-icon mr-5"  :class="[success==true ? 'svg-icon-success' : 'svg-icon-warning']">
            <span
              class="svg-icon svg-icon-3x"
              :class="[success==true ? 'svg-icon-success' : 'svg-icon-warning']"
            >
              <inline-svg src="/media/svg/icons/Code/Info-circle.svg" />
            </span>
            </span>
            <div class="d-flex flex-column flex-grow-1 mr-2">
                <a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Attention</a>
                <span class="text-muted font-weight-bold">
            <span v-if="form.isEnable" v-html="textMessage"></span>
            <span v-else>{{ textChange }}</span>
            </span>
            </div>
        </div>

      </div> -->
          <div class="row">
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
          <ImageButton
            :loading="submitLoading"
            @click="saveCoupon"
            class="float-right mb-6 mt-6"
          >
            Add coupon
          </ImageButton>
        </b-tab>
        <b-tab title="Use an existing coupon" lazy>
          <CouponExistTab></CouponExistTab>
        </b-tab>
      </b-tabs>
    </div>
  </b-modal>
</template>

<script>
import AlertError from "@/view/content/AlertError.vue";
import CouponExistTab from "./CouponExistTab.vue";
import vSelect from "vue-select";
import ApiService from "@/core/services/api.service";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BTabs,
  BTab,
} from "bootstrap-vue";
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import moment, { unix } from "moment";
export default {
  props: ["price_id"],
  mixins: [validationMixin],
  components: {
    vSelect,
    SearchAff,
    BFormGroup,
    BFormSelect,
    BFormInput,
    BTabs,
    BTab,
    ImageButton,
    CouponExistTab,
    AlertError,
  },
  data() {
    return {
      submit: false,
      errorMsg: "",
      haveAffiliate: false,
      textMessage:
        "The app will generate a coupon with the discount value as specified in Automatic Coupons",
      textChange: "For coupon to work, you must generate it in your store ",
      discount_type_options: [
        { value: 1, text: "Percentage" },
        { value: 2, text: "Fixed amount" },
        { value: 3, text: "Free shipping" },
      ],
      success: true,
      submitLoading: false,
      affiliates: [],
      affiliateFilterSelected: null,
      form: {
        couponCode: "",
        affiliate: null,
        discountType: 1,
        discountAmount: 0,
      },
    };
  },
  // watch:{
  //   price_id(){
  //     this.checkCouponCode();
  //   }

  // },
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

      // nếu check create in store mà k có price id thì toast
      // if (this.form.isEnable && !this.price_id) {
      //   this.$toast.error("Please setting automatic coupon", {
      //     position: "bottom",
      //   });
      //    this.submitLoading = false;
      //   return;
      // }

      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.submitLoading = false;
        return;
      }
      /**
       * Gọi API
       * thành công: đóng form - toast - emit
       * thất bại toast lỗi
       *
       */
      if (this.haveAffiliate == true) {
        this.form.isExist = 0;
        this.form.startAt = moment().unix();
        ApiService.post("coupons", this.form)
          .then(({ data }) => {
            if (data.data.status) {
              this.form.couponCode = "";
              this.form.discountType = 1;
              this.form.discountAmount = 0;
              this.$v.$reset();
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
      }
    },
    // checkCouponCode() {
    //   if(!this.form.isEnable)
    //   {
    //     this.success=true;
    //     return;
    //   }
    //   if (this.form.isEnable && this.price_id) {
    //     this.success = true;
    //     this.textMessage =
    //       "The coupon will be created by the app with the discount value as set in Automatic Coupons";
    //   } else {
    //     if (this.form.isEnable && !this.price_id) {
    //       this.success = false;
    //       this.textMessage =
    //         '<span class="text-error">Please setting automatic coupon</span>';
    //     }
    //   }
    // },
    resetAssignModal() {
      this.$emit("close");
    },
  },
};
</script>
<style>
.border-error {
  border-color: #f64e60 !important;
}
</style>
<style scoped>
.span-text-error {
  color: #f64e60 !important;
}
.alert-success {
  background-color: #eef8f8;
  color: #181c32;
  border-color: transparent;
}
.alert-warning {
  color: #181c32;
  border-color: transparent;
  background-color: #e3d2b1;
}
</style>
