<template>
  <div>
    <b-modal
      body-bg-variant="light"
      size="lg"
      ref="detail_payment_modal"
      :title="getTextLocale('detail_payment')"
      hide-footer
    >
      <div class="card card-custom card-stretch gutter-b">
        <b-tabs card nav-class="nav-tabs-line">
          <b-tab :title="getTextLocale('payment_detail')">
            <div v-if="Object.keys(currentPayment).length > 0">
              <h2 class="mb-0">
                {{ formatMoney(currentPayment.amount, format) }}
              </h2>
              <span v-html="formatDate(currentPayment.timestamp, false)"></span>
              <div class="mt-5">
                <table>
                  <tr>
                    <td style="min-width: 80px">
                      <span class="text-muted">{{ $t("affiliate") }}:</span>
                    </td>
                    <td>
                      <span
                        >{{ currentPayment.affiliate.full_name }} -
                        {{ currentPayment.affiliate.email }}</span
                      >
                    </td>
                  </tr>
                  <tr>
                    <td style="min-width: 80px">
                      <span class="text-muted">{{ $t("partner_note") }}:</span>
                    </td>
                    <td>
                      <span>{{ currentPayment.affiliate_note }}</span>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="separator separator-dashed my-8"></div>
              <h3>{{ $t("payment_info") }}</h3>
              <div
                class="payment-info-container"
                v-if="currentPayment.payment_method"
              >
                <span class="label label-lg label-light-success label-inline">{{
                  getNamePaymentMethod(currentPayment.payment_method)
                }}</span
                ><br />
                <span
                  v-html="
                    genPaymentInfo(
                      currentPayment.payment_method,
                      currentPayment.payment_info
                    )
                  "
                >
                </span>
              </div>
              <span v-else class="text-danger">{{
                $t("no_payment_details_set")
              }}</span>
            </div>
          </b-tab>
          <b-tab :title="getTextLocale('orders')" lazy>
            <TableOrderPayment
              :paymentId="currentPayment.id"
            ></TableOrderPayment>
          </b-tab>
        </b-tabs>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapState } from "vuex";
import TableOrderPayment from "./TableOrderPayment.vue";
import { BTabs, BTab } from "bootstrap-vue";
export default {
  components: { TableOrderPayment, BTabs, BTab },
  props: ["currentPayment"],
  computed: {
    ...mapState({
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
    }),
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

    genPaymentInfo(methodKey, infos) {
      let html = "";
      if (this.paymentMethodAvailable.length > 0) {
        let currentMethod = this.paymentMethodAvailable.find((method) => {
          return method.key == methodKey;
        });
        let fields = JSON.parse(currentMethod.fields);
        if (currentMethod) {
          html += '<table class="table-payment-info mt-2">';
          if (fields) {
            for (var i = 0; i < fields.length; i++) {
              html +=
                "<tr><td>" +
                '<span class="text-muted">' +
                fields[i].label +
                ":" +
                "</span></td><td><span>" +
                infos[fields[i].model] +
                "</span></td>";
            }
          } else {
            html +=
              '<tr><td class="p-0" style="border-top:none">' +
              '<span class="text-muted">' +
              this.$t('automatically_generated') +
              "</span></td></tr>";
          }

          html += "</table>";
        }
      }

      return html;
    },
  },
};
</script>

<style>
</style>
