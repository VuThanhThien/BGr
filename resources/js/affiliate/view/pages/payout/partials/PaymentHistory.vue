<template>
  <div class="card card-custom gutter-b">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
      <div class="card-title">
        <h3 class="card-label">{{ $t("list_paid_payment") }}</h3>
      </div>
    </div>
    <div class="card-body">
      <div class="row align-items-center filter-container mb-10">
        <div class="col-lg-12 col-xl-12">
          <div class="row align-items-center">
            <div class="col-md-4 my-2 my-md-0">
              <DateRange
                @updateTimePicker="updateTimePicker"
                @cancelFilterDate="cancelFilterDate"
              />
            </div>
            <!-- <div class="col-md-3 my-2 my-md-0" v-if="!filterAffiliate">
              <v-select
                @search="onSearchAffiliate"
                :options="affiliateOptions"
                label="first_name"
                :filterable="false"
                :value="affiliateFilterSelected"
                @input="setAffiliateSelected"
                placeholder="Type to search Affiliate"
              >
                <template v-slot:no-options="{ search, searching }">
                  <template v-if="searching">
                    <span class="text-muted"
                      >No affiliate found for <em>{{ search }}</em
                      >.</span
                    >
                  </template>
                  <span class="text-muted" v-else
                    >Type to search Affiliate.</span
                  >
                </template>
                <template slot="option" slot-scope="option">
                  <div class="d-center">
                    {{ option.full_name + "(" + option.email + ")" }}
                  </div>
                </template>
                <template slot="selected-option" slot-scope="option">
                  <div class="selected d-center">
                    {{ option.full_name }}
                  </div>
                </template>
              </v-select>
            </div> -->
          </div>
        </div>
      </div>
      <!-- refresh  -->
      <div class="mt-10 mb-5 collapse show" id="kt_datatable_group_action_form">
        <div class="d-flex align-items-center">
          <button
            @click="loadPayments()"
            type="button"
            class="btn btn-outline-secondary mr-2"
          >
            <i class="icon-md fas fa-redo-alt"></i>{{ $t("refresh_table") }}
          </button>
        </div>
      </div>
      <b-overlay opacity="0" :show="loadingPaidPaymentTable" rounded="sm">
        <div class="table-responsive">
          <table
            class="table table-head-custom table-head-bg table-vertical-center"
          >
            <thead>
              <th class="pr-0" style="min-width: 100px">
                <span>{{ $t("date") }}</span>
              </th>
              <th class="pr-0" style="min-width: 100px" v-if="showAffiliate">
                <span>{{ $t("affiliate") }}</span>
              </th>
              <th class="pr-0" style="min-width: 100px">
                <span
                  @click="changeSort('amount')"
                  v-bind:class="[sortField == 'amount' ? 'sort-field' : '']"
                >
                  {{ $t("amount") }}
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
                <span>{{ $t("total_orders") }}</span>
              </th>
              <th class="pr-0" style="min-width: 100px">
                <span> {{ $t("payment_detail") }}</span>
              </th>
              <th class="pr-3 text-right" style="min-width: 100px">
                <span>{{ $t("actions") }}</span>
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
                    <div
                      class="d-flex flex-column flex-grow-1 font-weight-bold"
                    >
                      <a
                        href="#"
                        class="text-dark text-hover-primary mb-1 font-size-lg"
                        >{{ p.affiliate.full_name }}</a
                      >
                      <span class="text-muted">{{ p.affiliate.email }}</span>
                    </div>
                  </td>
                  <td>
                    <span
                      class="
                        text-dark-75
                        font-weight-bolder
                        d-block
                        font-size-lg
                      "
                      >{{ formatMoney(p.amount, format) }}</span
                    >
                  </td>
                  <td>
                    <span
                      class="
                        text-dark-75
                        font-weight-bolder
                        d-block
                        font-size-lg
                      "
                      >{{ p.total_orders }}</span
                    >
                  </td>
                  <td>
                    <div v-if="p.payment_method">
                      <span
                        class="
                          text-dark-75
                          font-weight-bolder
                          d-block
                          font-size-lg
                        "
                        >{{ getNamePaymentMethod(p.payment_method) }}</span
                      >
                      <span
                        v-html="
                          genPaymentInfo(p.payment_method, p.payment_info)
                        "
                      >
                      </span>
                    </div>
                    <span v-else class="text-danger">{{
                      $t("no_payment_details_set")
                    }}</span>
                  </td>
                  <td class="text-right">
                    <!-- <button
                      class="
                        btn btn-icon btn-light btn-hover-primary btn-sm

                      "
                      @click="undoPayout(p.id)"
                      v-b-tooltip.hover
                      title="Undo"
                    >
                      <span class="svg-icon svg-icon-primary svg-icon-2x">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z" fill="#000000" fill-rule="nonzero"/>
                            </g>
                        </svg>
                    </span>
                    </button> -->
                    <button
                      class="
                        btn btn-icon btn-light btn-hover-primary btn-sm
                        mx-3
                      "
                      @click="openModalDetail(p)"
                      v-b-tooltip.hover
                      :title="getTextLocale('details')"
                    >
                      <span class="svg-icon svg-icon-primary svg-icon-2x"
                        ><svg
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
                              d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                              fill="#000000"
                              opacity="0.3"
                            />
                            <path
                              d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                              fill="#000000"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="10"
                              y="9"
                              width="7"
                              height="2"
                              rx="1"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="7"
                              y="9"
                              width="2"
                              height="2"
                              rx="1"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="7"
                              y="13"
                              width="2"
                              height="2"
                              rx="1"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="10"
                              y="13"
                              width="7"
                              height="2"
                              rx="1"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="7"
                              y="17"
                              width="2"
                              height="2"
                              rx="1"
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="10"
                              y="17"
                              width="7"
                              height="2"
                              rx="1"
                            />
                          </g></svg
                        ><!--end::Svg Icon--></span
                      >
                    </button>
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="8" style="text-align: center">
                    <i class="far fa-folder-open icon-4x"></i>
                    <h6>{{ $t("no_data") }}</h6>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <template #overlay>
          <TableLoader></TableLoader>
        </template>
      </b-overlay>
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
            >{{ $t("showing") }} {{ payments.from }} - {{ payments.to }}
            {{ $t("of") }} {{ payments.total }}</span
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
import ApiService from "aff/core/services/api.service";
import { mapState } from "vuex";
import pagination from "laravel-vue-pagination";
import moment from "moment";
import vSelect from "vue-select";
import DateRange from "aff/view/content/DateRange.vue";
import TableLoader from "aff/view/content/TableLoader.vue";
import PaymentDetailModal from "aff/view/pages/payout/partials/PaymentDetailModal.vue";
import { BOverlay } from "bootstrap-vue";
export default {
  components: {
    pagination,
    vSelect,
    DateRange,
    TableLoader,
    PaymentDetailModal,
    BOverlay,
  },
  props: ["filterAffiliate", "showAffiliate"],
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
      let resource = "payouts";
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
    // undoPayout(id){
    //     ApiService.post('payouts/undo', { id: id })
    //     .then(({ data }) => {
    //         this.payments.data = this.payments.data.filter(p=>{
    //             return p.id != id;
    //         });
    //         this.$emit('undoSuccess');
    //     })
    //     .catch((response)=>{
    //         this.$toast.error(response.data.message,{position:'bottom',duration: 4000});
    //     });
    // },
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
      this.endDate = moment(
        this.formatTimeRangeDatePicker(value.endDate),
        "YYYY-MM-DD"
      ).unix();
      this.loadPayments();
    },
    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
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
        let fields = JSON.parse(currentMethod.fields);
        if (currentMethod) {
          html += '<table class="table-payment-info" >';
          if (fields) {
            for (var i = 0; i < fields.length; i++) {
              html +=
                '<tr><td class="p-0" style="border-top:none">' +
                '<span class="text-muted">' +
                fields[i].label +
                ": " +
                '</span></td><td class="p-0" style="border-top:none" ><span>' +
                infos[fields[i].model] +
                "</span></td></tr>";
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
    getNamePaymentMethod(key) {
      if (this.paymentMethodAvailable.length > 0) {
        return this.paymentMethodAvailable.find((method) => {
          return method.key == key;
        }).name;
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

<style>
</style>
