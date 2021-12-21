<template>
  <div class="card card-custom gutter-b">
    <div v-if="showTitle" class="card-header align-items-center border-0">
      <h3 class="card-title align-items-start flex-column">
        <span class="font-weight-bolder text-bixgrow">Payment History</span>
      </h3>
    </div>
    <div class="card-body">
      <div class="row align-items-center filter-container mb-10">
        <div class="col-lg-12 col-xl-12">
          <div class="row align-items-center">
            <div class="col-md-4 my-2 my-md-0">
              <DateRange
                ref="dateRange"
                @updateTimePicker="updateTimePicker"
                @cancelFilterDate="cancelFilterDate"
              />
            </div>
            <div class="col-md-4 my-2 my-md-0" v-if="!filterAffiliate">
              <SearchAff @input="setAffiliateSelected" />
            </div>
          </div>
        </div>
      </div>
      <Datatable :loading="loadingPaidPaymentTable">
        <thead>
          <th class="pr-0" style="min-width: 100px">
            <span> Date </span>
          </th>
          <th class="pr-0" style="min-width: 100px" v-if="showAffiliate">
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
            <tr v-for="(p, i) in payments.data" :p="p" :key="i">
              <td>
                <span
                  href="#"
                  class="text-dark-75 d-block font-size-lg"
                  v-html="formatDate(p.timestamp)"
                >
                </span>
              </td>
              <td v-if="showAffiliate">
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
                <div v-if="p.payment_method">
                  <span class="text-dark-75 font-weight-bolder d-block font-size-lg" v-if="getNamePaymentMethod(p.payment_method) !== 'payment_not_found'">{{
                  getNamePaymentMethod(p.payment_method)
                }}</span>
                <span v-else class="text-danger">Payment cannot be found</span>
                  <span
                    v-html="genPaymentInfo(p.payment_method, p.payment_info)"
                  >
                  </span>
                </div>
                <span v-else class="text-danger">No payment details set</span>
              </td>
              <td class="text-right">
                <button
                  class="btn btn-icon btn-light btn-hover-info btn-sm"
                  @click="undoPayout(p.id)"
                  v-b-tooltip.hover
                  title="Undo"
                >
                  <span class="svg-icon svg-icon-info svg-icon-2x">
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
                        <path
                          d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z"
                          fill="#000000"
                          fill-rule="nonzero"
                        />
                      </g>
                    </svg>
                  </span>
                </button>
                <button
                  class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3"
                  @click="openModalDetail(p)"
                  v-b-tooltip.hover
                  title="Details"
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
    <PaymentDetailModal
      ref="modal_detail_component"
      :currentPayment="currentPaymentShowDetail"
    ></PaymentDetailModal>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import { mapState } from "vuex";
import pagination from "laravel-vue-pagination";
import moment from "moment";
import vSelect from "vue-select";
import DateRange from "@/view/content/DateRange.vue";
import Datatable from "@/view/content/Datatable.vue";
import PaymentDetailModal from "@/view/pages/payout/partials/PaymentDetailModal.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
export default {
  components: {
    pagination,
    vSelect,
    DateRange,
    PaymentDetailModal,
    SearchAff,
    Datatable,
  },
  props: ["filterAffiliate", "showAffiliate","showTitle"],
  data() {
    let startDate = null;
    let endDate = null;
    return {
      loadingPaidPaymentTable: false,
      currentPaymentShowDetail: {},
      payments: {
        data: [],
      },
      sortField: "date",
      sortDirection: "desc",
      dateRange: { startDate, endDate },
      affiliateOptions: [],
      affiliateFilterSelected: null,
      startDate: null,
      endDate: null,
      paginate: 10,
    };
  },

  computed: {
    ...mapState({
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
    }),
  },

  methods: {
    loadPayments(page = 1) {
      this.loadingPaidPaymentTable = true;
      let resource = "payouts/paid-payments";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };

      if (this.affiliateFilterSelected) {
        params["affiliate"] = this.affiliateFilterSelected.id;
      }
      if (this.filterAffiliate) {
        params["affiliate"] = this.filterAffiliate.id;
      }
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }

      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.payments = data.data;
        this.loadingPaidPaymentTable = false;
      });
    },
    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
      this.loadPayments();
    },
    undoPayout(id) {
      ApiService.post("payouts/undo", { id: id })
        .then(({ data }) => {
          this.payments.data = this.payments.data.filter((p) => {
            return p.id != id;
          });
          this.$emit("undoSuccess");
        })
        .catch(({response}) => {
          this.$toast.error(response.data.message, {
            position: "bottom",
            duration: 4000,
          });
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
    genPaymentInfo(methodKey, infos) {
      let html = "";
      if (this.paymentMethodAvailable.length > 0) {
        let currentMethod = this.paymentMethodAvailable.find((method) => {
          return method.key == methodKey;
        });
        let fields = currentMethod?.fields || null;
        if (currentMethod)
        {
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
    getNamePaymentMethod(key) {
      if (this.paymentMethodAvailable.length > 0) {
        let a = this.paymentMethodAvailable.find((method) => {
          return method.key == key;
        });
        return a?.name || "payment_not_found";
      }
      return "";
    },
    openModalDetail(item) {
      this.currentPaymentShowDetail = item;
      this.$refs["modal_detail_component"].$refs["detail_payment_modal"].show();
    },
  },
  mounted() {
    this.loadPayments();
  },
};
</script>

<style scoped>
tbody tr:first-child td {
  border-top: none !important;
}
</style>
