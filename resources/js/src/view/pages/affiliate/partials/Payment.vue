<template>
  <div>
    <div class="card card-custom gutter-b">
      <!--begin::header-->
      <div class="card-header align-items-center border-0">
        <h3 class="card-title align-items-start flex-column">
          <span class="font-weight-bolder text-bixgrow">Pay Affiliate</span>
        </h3>
      </div>
      <!--end::header-->

      <!--begin::body-->
      <div class="card-body">
        <Datatable :loading="loadingPendingPaymentTable">
            <thead>
              <tr class="text-uppercase">
                <th style="min-width: 250px" class="pl-7">
                  <span class="text-dark-75">amount</span>
                </th>
                <th style="min-width: 100px">total order</th>
                <th style="min-width: 100px">Payment detail</th>
                <th style="min-width: 100px" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="pendingPayment">
                <tr>

                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ formatMoney(pendingPayment.amount, format) }}</span
                  >
                </td>
                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ pendingPayment.total_orders }}</span
                  >
                </td>
                <td>
                  <div v-if="affiliate.payment_method">
                    <span
                      class="
                        text-dark-75
                        font-weight-bolder
                        d-block
                        font-size-lg
                      "
                      >{{ affiliate.payment_method }}</span
                    >
                    <span
                      v-html="
                        genPaymentInfo(
                          affiliate.payment_method,
                          affiliate.payment_info
                        )
                      "
                    >
                    </span>
                  </div>
                  <span v-else class="text-danger">No payment details set</span>
                </td>
                <td class="text-right">
                  <button
                    :disabled="pendingPayment.amount == 0"
                    class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3"
                    @click="openModalMarkAsPaid()"
                    v-b-tooltip.hover
                    title="Mark as paid"
                  >
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
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
                          <rect x="0" y="0" width="24" height="24" />
                          <circle
                            fill="#000000"
                            opacity="0.3"
                            cx="12"
                            cy="12"
                            r="10"
                          />
                          <path
                            d="M16.7689447,7.81768175 C17.1457787,7.41393107 17.7785676,7.39211077 18.1823183,7.76894473 C18.5860689,8.1457787 18.6078892,8.77856757 18.2310553,9.18231825 L11.2310553,16.6823183 C10.8654446,17.0740439 10.2560456,17.107974 9.84920863,16.7592566 L6.34920863,13.7592566 C5.92988278,13.3998345 5.88132125,12.7685345 6.2407434,12.3492086 C6.60016555,11.9298828 7.23146553,11.8813212 7.65079137,12.2407434 L10.4229928,14.616916 L16.7689447,7.81768175 Z"
                            fill="#000000"
                            fill-rule="nonzero"
                          />
                        </g></svg
                      ><!--end::Svg Icon-->
                    </span>
                  </button>
                </td>
              </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="4" style="text-align: center">
                    <i class="far fa-folder-open icon-4x"></i>
                    <h6>No pending payments</h6>
                  </td>
                </tr>
              </template>

            </tbody>
        </Datatable>
      </div>
      <!--end::body-->
    </div>
    <PaymentHistory @undoSuccess="loadPendingPayments" ref="paymentHistoryComponent" :showAffiliate="false" :filterAffiliate="id" :showTitle="true"></PaymentHistory>

    <MarkAsPaidModal @createSuccess="createPaymentSuccess" ref="mark_as_paid_modal_component" :markAsPaidForm="markAsPaidForm"></MarkAsPaidModal>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import MarkAsPaidModal from "@/view/pages/payout/partials/MarkAsPaidModal.vue";
import PaymentHistory from "@/view/pages/payout/partials/PaymentHistoryTab.vue";
import Datatable from "@/view/content/Datatable.vue";
import { mapState } from "vuex";

export default {
  components: { MarkAsPaidModal, PaymentHistory, Datatable },
  data() {
    return {
      loadingPendingPaymentTable: false,
      pendingPayment: {
        affiliate:{
          full_name:'',
          payment_method:null,
          payment_info:null
        },
        affiliate_id: 0,
        amount: "",
        timestamp: "",
        total_orders: 0,
      },
      markAsPaidForm: {
        affiliate_id: 0,
        amount: 0,
        payment_method: "paypal",
        payment_info: {},
        total_orders: 0,
        payment_note: "",
        affiliate_note: "",
      },
    };
  },
  computed: {
     ...mapState({
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
    }),
    affiliate() {
      console.log(this.$store.getters.currentDetailAffiliate);
      return this.$store.getters.currentDetailAffiliate;
    },
    id() {
      return this.$route.params.id;
    },
  },
  methods: {
    loadPendingPayments(page = 1) {
      this.loadingPendingPaymentTable = true;
      let resource = "payouts/pending-payments";
      let params = {
        affiliate: this.id,
        paginate: 1,
        sort_field: 'amount',
        sort_direction: 'desc'
      };

      ApiService.query(resource, { params: params }).then(({ data }) => {
        if(data.data.data[0]) {
          this.pendingPayment = data.data.data[0];
        }
        this.loadingPendingPaymentTable = false;
      });
    },
    openModalMarkAsPaid(){
      this.markAsPaidForm = {
        affiliate_id: this.pendingPayment.affiliate_id,
        amount: this.pendingPayment.amount,
        payment_method: this.pendingPayment.affiliate.payment_method,
        payment_info: this.pendingPayment.affiliate.payment_info,
        total_orders: this.pendingPayment.total_orders,
        payment_note: this.markAsPaidForm.payment_note,
        affiliate_note: this.markAsPaidForm.affiliate_note,
      };
      this.$refs['mark_as_paid_modal_component'].$refs["payout_modal"].show();
    },

    createPaymentSuccess() {
      this.pendingPayment =  {
        affiliate:{
          full_name:'',
          payment_method:null,
          payment_info:null
        },
        affiliate_id: 0,
        amount: "",
        timestamp: "",
        total_orders: 0,
      };
      this.$refs['paymentHistoryComponent'].loadPayments();
    },
   genPaymentInfo(methodKey, infos) {
      let html = "";
      if (this.paymentMethodAvailable.length > 0) {
        let currentMethod = this.paymentMethodAvailable.find((method) => {
          return method.key == methodKey;
        });
        let fields = currentMethod.fields;

        if (currentMethod) {
          html += '<table class="table-payment-info">';
          if (fields) {
            for (var i = 0; i < fields.length; i++) {
              html +=
                '<tr><td class="p-0">' +
                '<span class="text-muted">' +
                fields[i].label +
                ":" +
                '</span></td><td class="p-0"><span>' +
                infos[fields[i].model] +
                "</span></td></tr>";
            }
          } else {
            html +=
              '<tr><td class="p-0">' +
              '<span class="text-muted">' +
              "Automatically Generated" +
              "</span></td></tr>";
          }

          html += "</table>";
        }
      }

      return html;
    },

  },
  mounted() {
    this.loadPendingPayments();
  },
};
</script>

<style>
</style>
