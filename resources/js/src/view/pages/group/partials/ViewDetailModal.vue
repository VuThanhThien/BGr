<template>
  <div>
    <b-modal size="lg" ref="viewDetailModal" :title="group.name" hide-footer>
      <div class="">
        <table class="table-content">
          <tr>
            <td>
              <span class="text-muted font-size-lg">Name:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                group.name
              }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Commission type:</span>
            </td>
            <td>
              <span
                class="font-weight-bolder font-size-lg"
                >{{genTextCommissionType(group.commission_type)}}</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Commission amount</span>
            </td>
            <td>
              <span
                class="text-dark-75 font-weight-bolder d-block font-size-lg"
                >
            {{
                  formatCommissionAmount(
                    group.commission_type,
                    group.commission_amount,
                    format
                  )
                }}
                </span
              >
            </td>
          </tr>
                    <tr>
            <td>
              <span class="text-muted font-size-lg">Program Signup Page</span>
            </td>
            <td>
              <a :href="group.is_default?GroupSignupPageDefault:GroupSignupPage"
                class=" font-weight-bolder d-block font-size-lg" target="_blank"
                >{{group.is_default?GroupSignupPageDefault:GroupSignupPage}}</a
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Active:</span>
            </td>
            <td>
              <span
                v-if="group.is_active"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span v-if="!group.is_active" class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Private:</span>
            </td>
            <td>
              <span
                v-if="group.is_private"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span v-if="!group.is_private" class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">Include discounts</span>
            </td>
            <td>
              <span
                v-if="group.include_discounts"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.include_discounts"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>

          <tr>
            <td>
              <span class="text-muted font-size-lg">Exclude shipping:</span>
            </td>
            <td>
              <span
                v-if="group.exclude_shipping"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.exclude_shipping"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>

          <tr>
            <td>
              <span class="text-muted font-size-lg">Exclude taxes:</span>
            </td>
            <td>
              <span
                v-if="group.exclude_tax"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.exclude_tax"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <!-- <tr>
            <td>
              <span class="text-muted font-size-lg"
                >Generate discount codes automatically:</span
              >
            </td>
            <td>
              <span
                v-if="group.auto_generate_coupon"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.auto_generate_coupon"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg"
                >Allow specifying discount code:</span
              >
            </td>
            <td>
              <span
                v-if="group.allow_specifying_discount_code"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.allow_specifying_discount_code"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg"
                >Allow changing discount code:</span
              >
            </td>
            <td>
              <span
                v-if="group.allow_changing_discount_code"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.allow_changing_discount_code"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr> -->
          <tr>
            <td>
              <span class="text-muted font-size-lg">Cookie days:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                group.cookie_days
              }}</span>
            </td>
          </tr>
          <!-- <tr>
            <td>
              <span class="text-muted font-size-lg">Customer reward:</span>
            </td>
            <td>
              <span
                v-if="group.customer_commission_type == 0"
                class="font-weight-bolder font-size-lg"
                >No reward</span
              >
              <span
                v-if="group.customer_commission_type == 1"
                class="font-weight-bolder font-size-lg"
                >Percent</span
              >
              <span
                v-if="group.customer_commission_type == 2"
                class="font-weight-bolder font-size-lg"
                >Flat</span
              >
            </td>
          </tr> -->
          <tr>
            <td>
              <span class="text-muted font-size-lg">Auto approve order:</span>
            </td>
            <td>
              <span
                v-if="group.auto_approve_order"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.auto_approve_order"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg"
                >Auto approve affiliate:</span
              >
            </td>
            <td>
              <span
                v-if="group.auto_approve_affiliate"
                class="label label-lg label-light-success label-inline"
                >Enable</span
              >
              <span
                v-if="!group.auto_approve_affiliate"
                class="label label-lg label-inline"
                >Disable</span
              >
            </td>
          </tr>
        </table>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  props: ["group"],
  methods: {},
  computed:{
    ...mapGetters(['subDomain']),
     GroupSignupPageDefault() {
      return (
        "https://" +
        this.subDomain +
        "." +
        process.env.MIX_BASE_DOMAIN +
        "/#/register"
      );
    },
    GroupSignupPage(){
        return this.GroupSignupPageDefault + '/'+ this.group.slug;
    }
  }
};
</script>

<style scoped>
.table-content tr td {
  padding: 10px;
}
</style>
