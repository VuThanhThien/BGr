<template>
  <div>
    <b-modal
      size="md"
      ref="detail_discount_code_modal"
      :title='$t("redeem")'
      hide-footer
    >
      <div class="text-center mb-4">
        <p v-html="$t('here_is_your_coupon_code_popup',{amount:formatMoney(couponCodeCurrent.discount_amount, format)})">
          <!-- Here is your
          <strong>{{
            formatMoney(couponCodeCurrent.discount_amount, format)
          }}</strong>
          coupon code -->
        </p>
        <p
          class="text-center p-4 font-weight-bolder font-size-lg"
          ref="couponCodeCurrent"
          style="
            border: 1px dashed;
            width: 100%;
            letter-spacing: 2px;
            font-size: 20px;
            cursor: default;
            user-select: all;
          "
        >
          {{ couponCodeCurrent.coupon_code }}
        </p>
        <div class="row">
          <div class="col text-center">
            <button
              type="button"
              class="word-break btn btn btn-outline-primary btn-sm"
              @click="copy('couponCodeCurrent', $event)"
            >
              {{ $t("copy") }}</button
            ><a
              target="_blank"
              :href="linkCoupon + couponCodeCurrent.coupon_code"
              :title="$t('use_discount_code')"
              class="ml-2 btn btn-outline-primary btn-sm"
              >{{ $t("use_coupon_code") }}</a
            >
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  props: ["couponCodeCurrent"],
  computed: {
    ...mapGetters(["shopDomain"]),
    linkCoupon() {
      return "https://" + this.shopDomain + "/discount/";
    },
  },
  methods: {},
};
</script>

<style>
</style>
