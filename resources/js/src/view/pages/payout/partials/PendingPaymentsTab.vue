<template>
  <div class="card card-custom gutter-b">
    <div class="card-body">
      <!--start::Filter-->
      <div class="row align-items-center filter-container mb-10">
        <div class="col-lg-12 col-xl-12">
          <div class="row align-items-center">
            <div class="col-md-3 my-2 my-md-0">
              <DateRange
                ref="dateRange"
                @updateTimePicker="updateTimePicker"
                @cancelFilterDate="cancelFilterDate"
              />
            </div>
            <div class="col-md-3 my-2 my-md-0">
              <select
                v-model="groupSelected"
                @change="changeSelectGroup"
                class="form-control form-control-custom"
              >
                <option value="" selected class="text-muted">
                  Filter by program
                </option>

                <option
                  v-for="o in groupSelectOption"
                  :value="o.value"
                  :o="o"
                  :key="o.value"
                >
                  {{ o.text }}
                </option>
              </select>
            </div>
            <div class="col-md-3 my-2 my-md-0">
              <SearchAff @input="setAffiliateSelected" />
            </div>
            <div class="col-md-3 my-2 my-md-0">
              <b-input-group class="input_gr_txt_custom">
                <template #prepend>
                  <b-input-group-text>{{ symbolCurrency }}</b-input-group-text>
                </template>
                <b-form-input
                  min="0"
                  v-model="minPayout"
                  type="number"
                  id="commission_amount"
                  placeholder="Minimum payout"
                  class="form-control-custom"
                  @input="changeMinPayout"
                ></b-form-input>
              </b-input-group>
            </div>
          </div>
        </div>
      </div>
      <!--end::Filter-->

      <div
        class="mt-10 mb-5 collapse show"
        id="kt_datatable_group_action_form"
        style=""
      >
        <div class="d-flex align-items-center">
          <button
            @click="loadPayments()"
            type="button"
            class="btn btn-sm btn-outline-secondary mr-2"
          >
            <i class="icon-md fas fa-redo-alt"></i> Refresh table
          </button>
        </div>
      </div>

      <!--begin::Table-->
      <Datatable :loading="loadingPendingPaymentTable">
        <thead>
          <th class="pr-0" style="min-width: 100px">
            <span> Affiliate </span>
          </th>
          <th class="pr-0" style="min-width: 100px">
            <span
              @click="changeSort('amount')"
              v-bind:class="[sortField == 'amount' ? 'sort-field' : '']"
            >
              Amount
              <i
                v-if="sortDirection == 'asc' && sortField == 'amount'"
                class="fas fa-arrow-up icon-nm"
              ></i>
              <i
                v-if="sortDirection == 'desc' && sortField == 'amount'"
                class="fas fa-arrow-down icon-nm"
              ></i>
            </span>
          </th>
          <th class="pr-0" style="min-width: 100px">
            <span> Total orders </span>
          </th>
          <th class="pr-0" style="min-width: 100px">
            <span> Payment detail </span>
          </th>
          <th class="pr-5 text-right" style="min-width: 100px">
            <span> Actions </span>
          </th>
        </thead>
        <tbody>
          <template v-if="payments.data.length > 0">
            <tr v-for="(p, i) in payments.data" :key="i" :p="p">
              <td>
                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                  <router-link
                    :to="{
                      name: 'affiliates.edit',
                      params: { id: p.affiliate.id },
                    }"
                    class="text-hover-primary mb-1 font-size-lg"
                    >{{ p.affiliate.full_name }}
                  </router-link>
                  <span class="text-muted">{{ p.affiliate.email }}</span>
                </div>
              </td>
              <td>
                <span
                  class="text-dark-75 font-weight-bolder d-block font-size-lg"
                  >{{ formatMoney(p.amount, format) }}</span
                >
              </td>
              <td>
                <span
                  class="text-dark-75 font-weight-bolder d-block font-size-lg"
                  >{{ p.total_orders }}</span
                >
              </td>
              <td>
                <div v-if="p.affiliate.payment_method">
                  <span
                    class="
                      text-dark-75
                      font-weight-bolder
                      d-block
                      font-size-lg
                      py-2
                    "
                    >{{
                      getNamePaymentMethod(p.affiliate.payment_method)
                    }}</span
                  >
                  <span
                    v-html="
                      genPaymentInfo(
                        p.affiliate.payment_method,
                        p.affiliate.payment_info
                      )
                    "
                  >
                  </span>
                </div>
                <span v-else class="text-danger">No payment details set</span>
              </td>
              <td class="text-right">
                <button
                  v-if="p.affiliate.payment_method != 'discount_coupon'"
                  class="btn btn-icon btn-light btn-hover-primary btn-sm"
                  @click="openModalMarkAsPaid(p, i)"
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
                <button
                  v-if="
                    p.affiliate.payment_method == 'discount_coupon' && !allowSelfGeneratingCoupon
                  "
                  class="btn btn-primary btn-sm"
                  @click="giveDiscountCode(p)"
                  type="button"
                >
                  <b-spinner v-if="loading" small></b-spinner>
                  <span v-if="!loading" > Give Discount Code</span>

                </button>
                  <button
                  v-if="
                    p.affiliate.payment_method == 'discount_coupon' && allowSelfGeneratingCoupon
                  "
                  class="btn btn-primary btn-sm"
                  @click="addCredit(p)"
                  type="button"
                >
                  <b-spinner v-if="loading" small></b-spinner>
                  <span v-if="!loading" > Add credit</span>

                </button>
                <button
                  class="btn btn-icon btn-light btn-hover-primary btn-sm"
                  v-b-tooltip.hover
                  title="Details"
                  @click="openModalOrder(p)"
                >
                  <span class="svg-icon svg-icon-lg svg-icon-primary">
                    <inline-svg src="media/svg/icons/General/Clipboard.svg" />
                  </span>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td
                colspan="8"
                style="text-align: center; color: #b5b5c3 !important"
              >
                <i class="far fa-folder-open icon-3x"></i>
                <h6>No Data</h6>
              </td>
            </tr>
          </template>
        </tbody>
      </Datatable>
      <!--End::Table-->
      <div
        v-if="payments.data.length > 0"
        class="d-flex justify-content-between align-items-center flex-wrap"
      >
        <div class="d-flex flex-wrap py-2 mr-3">
          <pagination
            :data="payments"
            @pagination-change-page="loadPayments"
            :limit="3"
          >
            <span slot="prev-nav">
              <i class="ki ki-bold-arrow-back icon-xs"></i>
            </span>
            <span slot="next-nav">
              <i class="ki ki-bold-arrow-next icon-xs"></i>
            </span>
          </pagination>
        </div>

        <div class="d-flex align-items-center py-3">
          <select
            v-model="paginate"
            class="
              form-control form-control-sm
              font-weight-bold
              mr-4
              border-0
              bg-light
            "
            style="width: 75px"
          >
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="text-muted"
            >Showing {{ payments.from }} - {{ payments.to }} of
            {{ payments.total }}</span
          >
        </div>
      </div>
    </div>
    <MarkAsPaidModal
      @createSuccess="submitCreatePayment"
      ref="mark_as_paid_modal_component"
      :markAsPaidForm="markAsPaidForm"
    ></MarkAsPaidModal>
    <b-modal
      body-bg-variant="light"
      size="lg"
      ref="orderPaymentModal"
      title="Detail payment"
      hide-footer
    >
      <div class="card card-custom card-stretch gutter-b">
        <TablePendingPaymetDetail
          :affiliateId="affiliateID"
        ></TablePendingPaymetDetail>
      </div>
    </b-modal>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import { mapState } from "vuex";
import pagination from "laravel-vue-pagination";
import moment from "moment";
import vSelect from "vue-select";
import DateRange from "@/view/content/DateRange.vue";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import Datatable from "@/view/content/Datatable.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import TablePendingPaymetDetail from "@/view/pages/payout/partials/TablePendingPaymetDetail.vue";
import MarkAsPaidModal from "@/view/pages/payout/partials/MarkAsPaidModal.vue";
import {
  BFormInput,
  BInputGroup,
  BInputGroupText,
  BSpinner,
} from "bootstrap-vue";
export default {
  components: {
    pagination,
    vSelect,
    DateRange,
    LoadingSubmitButton,
    SearchAff,
    TablePendingPaymetDetail,
    MarkAsPaidModal,
    Datatable,
    BFormInput,
    BInputGroup,
    BInputGroupText,
    BSpinner,
  },
  props: [],
  data() {
    let startDate = null;
    let endDate = null;
    return {
      loading: false,
      affiliateID: null,
      loadingPendingPaymentTable: false,
      loadingCreatePaymentBtn: false,
      payments: {
        data: [],
      },
      minPayout: null,
      sortField: "amount",
      sortDirection: "desc",
      dateRange: { startDate, endDate },
      groupSelected: "",
      affiliateOptions: [],
      affiliateFilterSelected: null,
      startDate: null,
      endDate: null,
      paginate: 10,
      currentIndex: 0,
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
      allowSelfGeneratingCoupon: state => state.layout.settings.allow_self_generating_coupon
    }),

    groupSelectOption() {
      let options = [];
      let groups = this.$store.getters.groups;
      for (var i = 0; i < groups.length; i++) {
        options.push({ value: groups[i].id, text: groups[i].name });
      }
      return options;
    },
  },

  methods: {
    async loadPayments(page = 1) {
      this.loadingPendingPaymentTable = true;
      let resource = "payouts/pending-payments";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };

      if (this.affiliateFilterSelected) {
        params["affiliate"] = this.affiliateFilterSelected.id;
      }
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      if (this.groupSelected) {
        params["group"] = this.groupSelected;
      }
      if (this.minPayout) {
        params["min_payout"] = this.minPayout;
      }

      await ApiService.query(resource, { params: params }).then(({ data }) => {
        this.payments = data.data;
        this.loadingPendingPaymentTable = false;
      });
    },

    changeSort(field) {
      if (this.sortField == field) {
        this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";
      } else {
        this.sortDirection = "desc";
        this.sortField = field;
      }
      this.loadPayments();
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
    /**
     * Sự kiện nút mark as paid
     */
    openModalMarkAsPaid(item, index) {
      this.currentIndex = index;
      this.markAsPaidForm = {
        affiliate_id: item.affiliate_id,
        amount: item.amount,
        payment_method: item.affiliate.payment_method,
        payment_info: item.affiliate.payment_info,
        total_orders: item.total_orders,
        payment_note: this.markAsPaidForm.payment_note,
        affiliate_note: this.markAsPaidForm.affiliate_note,
      };
      this.$refs["mark_as_paid_modal_component"].$refs["payout_modal"].show();
    },

    getNamePaymentMethod(key) {
      console.log(key);
      if (this.paymentMethodAvailable.length > 0) {
        return this.paymentMethodAvailable.find((method) => {

          return method.key == key;
        }).name;
      }
      return "";
    },
    /**
     * Bắt sự kiện emit của MarkAsPaidModel tại đây..
     */
    submitCreatePayment() {
      this.loadPayments();
    },
    updateTimePicker(value) {
      this.startDate = moment(
        this.formatTimeRangeDatePicker(value.startDate),
        "YYYY-MM-DD"
      ).unix();
      this.endDate =
        moment(
          this.formatTimeRangeDatePicker(value.endDate),
          "YYYY-MM-DD"
        ).unix() + 86399;

      this.loadPayments();
    },

    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      this.loadPayments();
    },

    searchAffiliate: _.debounce((loading, search, vm) => {
      let resource = "affiliates/search?query=" + escape(search);
      ApiService.query(resource).then(({ data }) => {
        vm.affiliateOptions = data;
        loading(false);
      });
    }),

    onSearchAffiliate(search, loading) {
      if (search.length) {
        loading(true);
        this.searchAffiliate(loading, search, this);
      }
    },
    changeSelectGroup(value) {
      this.loadPayments();
    },

    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
      this.loadPayments();
    },
    changeMinPayout() {
      this.loadPayments();
    },
    openModalOrder(p) {
      this.affiliateID = p.affiliate_id;
      this.$refs["orderPaymentModal"].show();
    },
    giveDiscountCode(p) {
      this.loading = true;
      let resource = `payouts/store-credit`;
      let params = {
        affiliate_id: p.affiliate_id,
        amount: parseFloat(p.amount),
        total_orders: p.total_orders
      };
      ApiService.post(resource, params).then(({ data }) => {
        this.loadPayments();
         this.$toast.success('Payment created', { position: "bottom" });
      }).catch(({response})=>{
        this.$toast.error(response.data.message, { position: "bottom" });
      }).finally(()=>{
           this.loading = false;
      });
    },
    addCredit(p)
    {
      this.loading = true;
      let resource = `payouts/add-credit`;
      let params = {
        affiliate_id: p.affiliate_id,
        amount: parseFloat(p.amount),
        total_orders: p.total_orders
      };
      ApiService.post(resource, params).then(({ data }) => {
        this.loadPayments();
        this.$toast.success('Payment created', { position: "bottom" });
      }).catch(({response})=>{
        this.$toast.error(response.data.message, { position: "bottom" });
      }).finally(()=>{
           this.loading = false;
      });
    }
  },

  mounted() {
    this.loadPayments();
  },

  watch: {
    paginate: function () {
      this.loadPayments();
    },
  },
};
</script>

<style scoped>
.modal-body {
  background: #f3f6f9 !important;
}
.table-payment-info tbody tr td {
  border-top: none !important;
}
</style>
<style lang="scss">
.input_gr_txt_custom {
  height: calc(1.5em + 1.65rem + 2px) !important;
  > .input-group-prepend {
    > .input-group-text {
      border: 1px solid #e4e6ef !important;
    }
  }
}
</style>
