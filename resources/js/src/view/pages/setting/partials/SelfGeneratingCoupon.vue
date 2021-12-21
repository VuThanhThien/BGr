<template>
  <b-modal
    ref="self_generating_coupon"
    size="lg"
    id="self_generating"
    hide-footer
    title="Allow self-generating coupon"
  >
    <form @submit.stop.prevent="submit">
      <div class="form-group">
        <div class="form-group">
          <label class="font-weight-bolder">Enable: </label>
          <b-form-checkbox
            v-model="selfGeneratingCoupon.allow_self_generating_coupon"
            id="enable_mlm"
            switch
            size="lg"
            value="1"
            unchecked-value="0"
          ></b-form-checkbox>
          <span class="form-text text-muted mt-5" style="font-size: 0.9rem"
            >Allow affiliates to redeem coupon code themself from their store
            credit wallet.
          </span>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Minimum redeemable amount (optional)</label>
              <div class="input-group">
                <input
                  v-model="
                    selfGeneratingCoupon.autopay_discount_code.min_amount
                  "
                  type="number"
                  class="form-control"
                  placeholder="Min"
                  aria-describedby="basic-addon2"
                  min="0"
                  step="any"
                />
                <div class="input-group-append">
                  <span class="input-group-text">{{ symbolCurrency }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Maximum redeemable amount (optional)</label>
              <div class="input-group">
                <input
                  v-model="
                    selfGeneratingCoupon.autopay_discount_code.max_amount
                  "
                  type="number"
                  class="form-control"
                  placeholder="Max"
                  aria-describedby="basic-addon2"
                  min="0"
                  step="any"
                />
                <div class="input-group-append">
                  <span class="input-group-text">{{ symbolCurrency }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="form-group">
          <label>Conversion Factor (optional)</label>
          <input
            type="number"
            class="form-control"
            placeholder="1"
            min="1"
            v-model="selfGeneratingCoupon.autopay_discount_code.conversion_factor"
          />
          <span class="form-text text-muted"
            >Increase the redemption amount by a this factor. eg. Set this to 2
            to give your affiliate discount coupon of double their redemption
            amount.</span
          >
        </div> -->
            <div class="form-group">
              <label>Redemption Multipliers (optional)</label>
              <div class="input-group">
                <input
                  v-model="
                    selfGeneratingCoupon.autopay_discount_code.conversion_factor
                  "
                  type="number"
                  class="form-control"
                  min="1"
                  step="any"
                />
                <div class="input-group-append">
                  <span class="input-group-text">X</span>
                </div>
              </div>
              <span class="form-text text-muted"
            >Multiply the redemption amount by the number entered below. eg. If you enter 2, your affilaites can generate a coupon of double their redemption amount.</span
          >
            </div>
        <div class="form-group">
          <label>Coupon code prefix (optional)</label>
          <input
            type="text"
            class="form-control"
            v-model="selfGeneratingCoupon.autopay_discount_code.prefix"
          />
          <span class="form-text text-muted"
            >Set a prefix that appears in all generated coupon code.</span
          >
        </div>
      </div>

      <div class="w-100 mt-3 mb-10">
        <b-button variant="primary" size="md" class="float-right" type="submit">
          <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
            <inline-svg src="media/svg/icons/General/Save.svg" />
          </span>
          <b-spinner v-if="loading" small></b-spinner>
          Save
        </b-button>
      </div>
    </form>
  </b-modal>
</template>

<script>
import ApiService from "@/core/services/api.service";
import { BSpinner, BButton, BFormCheckbox } from "bootstrap-vue";
export default {
  components: {
    BSpinner,
    BButton,
    BFormCheckbox,
  },
  props: ["shopSettings"],
  data() {
    return {
      model: {},
      submited: false,
      loading: false,
      selfGeneratingCoupon: {
        allow_self_generating_coupon: 0,
        autopay_discount_code: {
          min_amount: 0,
          max_amount: "",
          conversion_factor: 1,
          prefix: "",
        },
      },
    };
  },
  computed: {
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
  },
  methods: {
    submit() {
      this.submited = true;
      this.loading = true;
      let payload = this.convertFields(this.selfGeneratingCoupon);
      if (
        (payload.autopay_discount_code.min_amount ||
          payload.autopay_discount_code.min_amount === 0) &&
        (payload.autopay_discount_code.max_amount ||
          payload.autopay_discount_code.max_amount === 0)
      ) {
        if (
          payload.autopay_discount_code.min_amount >=
          payload.autopay_discount_code.max_amount
        ) {
          this.$toast.error(
            "Maximum amount has to be greater than minimum amount",
            { position: "bottom" }
          );
          this.loading = false;
          return;
        }
      }
      let params = {
        autopay_discount_code: payload.autopay_discount_code,
        allow_self_generating_coupon: payload.allow_self_generating_coupon,
      };
      let resource = `settings/self-generating-coupon`;
      ApiService.put(resource, params)
        .then(({ data }) => {
          this.$refs['self_generating_coupon'].hide();
          this.$store.commit("UPDATE_SELF_GENERATING_COUPON", {
            autopay_discount_code: payload.autopay_discount_code,
            allow_self_generating_coupon: payload.allow_self_generating_coupon,
          });
          this.$toast.success("Updated", { position: "bottom" });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    convertFields(selfGeneratingCoupon) {
      let payload = {
        autopay_discount_code: {},
      };
      payload.allow_self_generating_coupon = parseInt(
        selfGeneratingCoupon.allow_self_generating_coupon
      );
      payload.autopay_discount_code.conversion_factor = selfGeneratingCoupon
        .autopay_discount_code.conversion_factor
        ? parseFloat(
            selfGeneratingCoupon.autopay_discount_code.conversion_factor
          )
        : selfGeneratingCoupon.autopay_discount_code.conversion_factor;
      payload.autopay_discount_code.min_amount = selfGeneratingCoupon
        .autopay_discount_code.min_amount
        ? parseFloat(selfGeneratingCoupon.autopay_discount_code.min_amount)
        : selfGeneratingCoupon.autopay_discount_code.min_amount;
      payload.autopay_discount_code.max_amount = selfGeneratingCoupon
        .autopay_discount_code.max_amount
        ? parseFloat(selfGeneratingCoupon.autopay_discount_code.max_amount)
        : selfGeneratingCoupon.autopay_discount_code.max_amount;
      payload.autopay_discount_code.prefix =
        selfGeneratingCoupon.autopay_discount_code.prefix;
      return payload;
    },
  },
  mounted(){
          if (this.shopSettings) {
        this.selfGeneratingCoupon.allow_self_generating_coupon = parseInt(
          this.shopSettings.allow_self_generating_coupon
        );
        this.selfGeneratingCoupon.autopay_discount_code.conversion_factor =
          this.shopSettings.autopay_discount_code.conversion_factor
            ? parseFloat(this.shopSettings.autopay_discount_code.conversion_factor)
            : this.shopSettings.autopay_discount_code.conversion_factor;
        this.selfGeneratingCoupon.autopay_discount_code.min_amount = this.shopSettings
          .autopay_discount_code.min_amount
          ? parseFloat(this.shopSettings.autopay_discount_code.min_amount)
          : this.shopSettings.autopay_discount_code.min_amount;
        this.selfGeneratingCoupon.autopay_discount_code.max_amount = this.shopSettings
          .autopay_discount_code.max_amount
          ? parseFloat(this.shopSettings.autopay_discount_code.max_amount)
          : this.shopSettings.autopay_discount_code.max_amount;
        this.selfGeneratingCoupon.autopay_discount_code.prefix =
          this.shopSettings.autopay_discount_code.prefix;
      } else {
        this.selfGeneratingCoupon = {
          allow_self_generating_coupon: 0,
          autopay_discount_code: {
            min_amount: 0,
            max_amount: "",
            conversion_factor: 1,
            prefix: "",
          },
        };
      }
  }
};
</script>

<style>
</style>
