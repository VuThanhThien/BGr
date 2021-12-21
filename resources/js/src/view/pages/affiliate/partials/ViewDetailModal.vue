<template>
  <div>
    <b-modal
      size="lg"
      ref="viewDetailModal"
      :title="affiliate.full_name"
      hide-footer
    >
      <div class="">
        <table class="table-content">
          <tr>
            <td>
              <span class="text-muted font-size-lg">Email:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.email
              }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Full name:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.full_name
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.phone">
            <td>
              <span class="text-muted font-size-lg">Phone:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.phone
              }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Affiliate link:</span>
            </td>
            <td>
              <span
                ref="affiliate_link"
                class="font-weight-bolder font-size-lg"
                >{{ affLink }}</span
              >
              <span
                @click="copy('affiliate_link')"
                v-b-tooltip.hover
                title="Copy"
                class="svg-icon svg-icon-lg svg-icon-primary"
              >
                <inline-svg src="media/svg/icons/General/Duplicate.svg" />
              </span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Payment method:</span>
            </td>
            <td>
              <span
                v-if="affiliate.payment_method"
                class="
                  font-weight-bolder font-size-lg
                  label label-lg label-light-success label-inline
                "
                >{{
                  getNamePaymentMethod(affiliate.payment_method)
                }}</span
              >
              <span v-else class="text-danger">No payment details set</span>
            </td>
          </tr>
          <tr v-if="affiliate.company">
            <td>
              <span class="text-muted font-size-lg">Company:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.company
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.country">
            <td>
              <span class="text-muted font-size-lg">Country:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                getCountryNameByCode(affiliate.country)
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.city">
            <td>
              <span class="text-muted font-size-lg">City:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.city
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.address">
            <td>
              <span class="text-muted font-size-lg">Address:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.address
              }}</span>
            </td>
          </tr>

          <tr v-if="affiliate.state">
            <td>
              <span class="text-muted font-size-lg">State:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.state
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.zipcode">
            <td>
              <span class="text-muted font-size-lg">Zipcode:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.zipcode
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.facebook">
            <td>
              <span class="text-muted font-size-lg">Facebook:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.facebook
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.youtube">
            <td>
              <span class="text-muted font-size-lg">Youtube:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.youtube
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.instagram">
            <td>
              <span class="text-muted font-size-lg">Instagram:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.instagram
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.twitter">
            <td>
              <span class="text-muted font-size-lg">Twitter:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.twitter
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.website">
            <td>
              <span class="text-muted font-size-lg">Website:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.website
              }}</span>
            </td>
          </tr>
          <tr v-if="affiliate.personal_detail">
            <td>
              <span class="text-muted font-size-lg">Personal detail:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                affiliate.personal_detail
              }}</span>
            </td>
          </tr>
          <template v-if="affiliate.additional_infos">
            <tr
              v-for="(additional_info, i) in affiliate.additional_infos"
              :key="i"
            >
              <td>
                <span class="text-muted font-size-lg"
                  >{{ additional_info.label }}:</span
                >
              </td>
              <td>
                <span class="font-weight-bolder font-size-lg">{{
                  additional_info.value
                }}</span>
              </td>
            </tr>
          </template>
        </table>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  props: ["affiliate"],
  computed: {
    ...mapGetters(["defaultAffiliateLink"]),
    shopDomain() {
      return this.$store.getters.shopDomain;
    },
    paymentMethodAvailable() {
      return this.$store.getters.paymentMethodAvailable;
    },
    affLink() {
      let affLink = this.affiliate.primary_aff_link
        ? this.affiliate.primary_aff_link
        : this.defaultAffiliateLink;
      return this.generateLinkAffiliate(affLink) +this.affiliate.group_id+':'+ this.affiliate.hash_code;
    },
  },

  methods: {
    getNamePaymentMethod(key) {
      if (this.paymentMethodAvailable.length > 0) {
        return this.paymentMethodAvailable.find((method) => {
          return method.key == key;
        }).name;
      }
      return "";
    },
  },
};
</script>

<style scoped>
.table-content tr td {
  padding: 10px;
}
</style>
