<template>
<div>
  <b-overlay opacity="0.8" :show="loadingStatistics" rounded="sm">
      <div class="row">
        <div class="col-xl-3">
          <!--begin::Stats Widget 25-->
          <div class="card card-custom bg-light-white card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body">
              <span
                class="
                  card-title
                  font-weight-bolder
                  text-warning
                  font-size-h2
                  mb-0
                  mt-6
                  d-block
                "
                >{{
                  formatMoney(commissionStatistics.commission_pending, format)
                }}</span
              >
              <span class="font-weight-bold text-warning font-size-sm"
                >{{$t('pending')}}</span
              >
            </div>
            <!--end::Body-->
          </div>
          <!--end::Stats Widget 25-->
        </div>
        <div class="col-xl-3">
          <!--begin::Stats Widget 26-->
          <div class="card card-custom bg-light-white card-stretch gutter-b">
            <!--begin::ody-->
            <div class="card-body">
              <span
                class="
                  card-title
                  font-weight-bolder
                  text-primary
                  font-size-h2
                  mb-0
                  mt-6
                  d-block
                "
                >{{
                  formatMoney(commissionStatistics.commission_approved, format)
                }}</span
              >
              <span class="font-weight-bold text-primary font-size-sm spinner-primary"
                >{{$t('approved')}}</span
              >
            </div>
            <!--end::Body-->
          </div>
          <!--end::Stats Widget 26-->
        </div>
        <div class="col-xl-3">
          <!--begin::Stats Widget 27-->
          <div class="card card-custom bg-light-white card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body">
              <span
                class="
                  card-title
                  font-weight-bolder
                  text-success
                  font-size-h2
                  mb-0
                  mt-6
                  d-block
                "
                >{{
                  formatMoney(commissionStatistics.commission_paid, format)
                }}</span
              >
              <span class="font-weight-bold text-success font-size-sm"
                >{{$t('paid')}}</span
              >
            </div>
            <!--end::Body-->
          </div>
          <!--end::Stats Widget 27-->
        </div>
        <div class="col-xl-3">
          <!--begin::Stats Widget 27-->
          <div class="card card-custom bg-light-white card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body">
              <span
                class="
                  card-title
                  font-weight-bolder
                  text-danger
                  font-size-h2
                  mb-0
                  mt-6
                  d-block
                "
                >{{
                  formatMoney(commissionStatistics.commission_rejected, format)
                }}</span
              >
              <span class="font-weight-bold text-danger font-size-sm"
                >{{$t('rejected')}}</span
              >
            </div>
            <!--end::Body-->
          </div>
          <!--end::Stats Widget 27-->
        </div>
      </div>
      <template #overlay>
        <TableLoader></TableLoader>
      </template>
    </b-overlay>
  <div class="card card-custom card-stretch gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 py-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label font-weight-bolder text-dark">
          {{ $t("list_conversions") }}
        </span>
      </h3>
      <div class="card-toolbar"></div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-0">
      <div class="row align-items-center filter-container">
        <div class="col-lg-12 col-xl-12">
          <div class="row align-items-center">
            <div class="col-md-4 my-2 my-md-0">
              <DateRange
                @updateTimePicker="updateTimePicker"
                @cancelFilterDate="cancelFilterDate"
              />
            </div>
            <div class="col-md-4 my-2 my-md-0">
              <select
                v-model="statusSelected"
                @change="changeStatus"
                class="form-control form-control-custom"
              >
                <option value="">{{ $t("status") }}</option>
                <option value="2">{{ $t("pending") }}</option>
                <option value="1">{{ $t("approved") }}</option>
                <option value="3">{{ $t("paid") }}</option>
                <option value="4">{{ $t("rejected") }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- refresh  -->
      <div class="mt-10 mb-5 collapse show" id="kt_datatable_group_action_form">
        <div class="d-flex align-items-center">
          <button
            @click="loadCommissions()"
            type="button"
            class="btn btn-outline-secondary mr-2"
          >
            <i class="icon-md fas fa-redo-alt"></i>{{ $t("refresh_table") }}
          </button>
        </div>
      </div>
      <!--begin::Table-->
      <b-overlay opacity="0" :show="loadingCommissionTable" rounded="sm">
        <div class="table-responsive">
          <table
            class="table table-head-custom table-head-bg table-vertical-center"
          >
            <thead>
              <tr class="text-left">
                <!-- <th class="pl-0" style="width: 20px">
                  <label class="checkbox checkbox-lg checkbox-single">
                    <input
                      type="checkbox"
                      v-model="allSelected"
                      @click="selectAll"
                    />
                    <span></span>
                  </label>
                </th> -->
                <th class="pr-0" style="">
                  <span
                    @click="changeSort('created_at')"
                    v-bind:class="[
                      sortField == 'created_at' ? 'sort-field' : '',
                    ]"
                  >
                    {{ $t("date") }}
                    <i
                      v-if="sortDirection == 'asc' && sortField == 'created_at'"
                      class="fas fa-arrow-up"
                    ></i>
                    <i
                      v-if="
                        sortDirection == 'desc' && sortField == 'created_at'
                      "
                      class="fas fa-arrow-down"
                    ></i>
                  </span>
                </th>
                <th style="">
                  <span
                    @click="changeSort('order_name')"
                    v-bind:class="[
                      sortField == 'order_name' ? 'sort-field' : '',
                    ]"
                  >
                    {{ $t("orders") }}
                    <i
                      v-if="sortDirection == 'asc' && sortField == 'order_name'"
                      class="fas fa-arrow-up"
                    ></i>
                    <i
                      v-if="
                        sortDirection == 'desc' && sortField == 'order_name'
                      "
                      class="fas fa-arrow-down"
                    ></i>
                  </span>
                </th>
                <th style="">
                  <span
                    @click="changeSort('total')"
                    v-bind:class="[sortField == 'total' ? 'sort-field' : '']"
                  >
                    {{ $t("total_sales") }}
                    <i
                      v-if="sortDirection == 'asc' && sortField == 'total'"
                      class="fas fa-arrow-up"
                    ></i>
                    <i
                      v-if="sortDirection == 'desc' && sortField == 'total'"
                      class="fas fa-arrow-down"
                    ></i>
                  </span>
                </th>
                <th style="">
                  <span
                    @click="changeSort('commission')"
                    v-bind:class="[
                      sortField == 'commission' ? 'sort-field' : '',
                    ]"
                  >
                    {{ $t("commission") }}
                    <i
                      v-if="sortDirection == 'asc' && sortField == 'commission'"
                      class="fas fa-arrow-up"
                    ></i>
                    <i
                      v-if="
                        sortDirection == 'desc' && sortField == 'commission'
                      "
                      class="fas fa-arrow-down"
                    ></i>
                  </span>
                </th>
                <th style="min-width: 100px">{{ $t("status") }}</th>
                <th style="">{{ $t("tracking_by") }}</th>
                <th class="pr-3 text-right" style="min-width: 150px">
                  {{ $t("actions") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="conversions.data.length > 0">
                <tr v-for="(item, i) in conversions.data" :key="i">
                  <!-- <td class="pl-0">
                    <label class="checkbox checkbox-lg checkbox-single">
                      <input
                        type="checkbox"
                        v-model="conversionIds"
                        :value="item.id"
                      />
                      <span></span>
                    </label>
                  </td> -->

                  <td>
                    <span
                      href="#"
                      class="text-dark-75 d-block font-size-lg"
                      v-html="formatDate(item.timestamp)"
                    >
                    </span>
                  </td>

                  <td>
                    <span
                      class="
                        text-dark-75
                        font-weight-bolder
                        d-block
                        font-size-lg
                      "
                      >{{ item.order_name }}</span
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
                    >
                      {{ formatMoney(item.total, format) }}
                    </span>
                  </td>
                  <td>
                    <span
                      class="
                        text-dark-75
                        font-weight-bolder
                        d-block
                        font-size-lg
                      "
                      >{{ formatMoney(item.commission, format) }}</span
                    >
                  </td>
                  <td>
                    <span
                      v-if="item.status == 1"
                      class="label label-lg label-light-primary label-inline"
                    >
                      {{ $t("approved") }}
                    </span>
                    <span
                      v-else-if="item.status == 2"
                      class="label label-lg label-light-warning label-inline"
                    >
                      {{ $t("pending") }}
                    </span>
                    <span
                      v-else-if="item.status == 3"
                      class="label label-lg label-light-success label-inline"
                    >
                      {{ $t("paid") }}
                    </span>
                    <span
                      v-else
                      class="label label-lg label-light-danger label-inline"
                    >
                      {{ $t("rejected") }}
                    </span>
                  </td>
                  <td>
                    <span class="label label-light-default label-inline">{{
                      $t(trackingType[item.tracking_type - 1].title)
                    }}</span>
                  </td>
                  <td class="pr-0 text-right">
                    <button
                      v-b-tooltip.hover
                      :title="getTextLocale('explanation')"
                      class="btn btn-icon btn-light btn-hover-light btn-sm"
                      @click="openExplanationModal(item)"
                    >
                      <span class="svg-icon svg-icon-lg svg-icon-primary">
                        <inline-svg
                          src="media/svg/icons/General/Clipboard.svg"
                        />
                      </span>
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

      <!--end::Table-->

      <!-- Start pagination -->
      <div
        v-if="conversions.data.length > 0"
        class="d-flex justify-content-between align-items-center flex-wrap"
      >
        <div class="d-flex flex-wrap py-2 mr-3">
          <pagination
            :data="conversions"
            @pagination-change-page="loadCommissions"
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
            >{{ $t("showing") }} {{ conversions.from }} - {{ conversions.to }}
            {{ $t("of") }} {{ conversions.total }}</span
          >
        </div>
      </div>
      <!-- End pagination -->
    </div>
    <ExplanationModal
      :trackingConversionType="trackingConversionType"
      :commission="commsionCurrent"
      :data="currentExplanationData"
      :currentConversionId="currentConversionId"
      :orderId="orderIdCurrent"
      :total="totalCurrent"
      ref="explanationModalComponent"
    ></ExplanationModal>
    <!--end::Body-->
    <!--end::Advance Table Widget 2-->
  </div>
</div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import ExplanationModal from "aff/view/pages/commission/partials/ExplanationModal.vue";
import vSelect from "vue-select";
import ApiService from "aff/core/services/api.service";
import DateRange from "aff/view/content/DateRange.vue";
import TableLoader from "aff/view/content/TableLoader.vue";
import moment from "moment";
import { BOverlay } from "bootstrap-vue";
export default {
  props: ["showAffiliate", "filterAffiliate"],
  components: {
    pagination,
    vSelect,
    DateRange,
    TableLoader,
    ExplanationModal,
    BOverlay,
  },
  data() {
    let startDate = null;
    let endDate = null;
    return {
      currentExplanationData: { line_items: [] },
      loadingCommissionTable: false,
      loadingStatistics: false,
      commissionStatistics: {},
      allSelected: false,
      conversionIds: [],
      conversions: {
        data: [],
      },
      affiliates: [],
      affiliateSelected: null,
      dateRange: { startDate, endDate },
      statusSelected: "",
      paginate: 10,
      sortDirection: "desc",
      sortField: "created_at",
      startDate: null,
      endDate: null,
      trackingType: [
        {
          title: "Link",
          icon: "icon-nm fas fa-link",
        },
        {
          title: "Coupon",
          icon: "icon-nm fas fa-tag",
        },
        {
          title: "Network commission",
          icon: "icon-nm fas fa-user-friends",
        },
        {
          title: "Recruitment bonus",
          icon: "icon-nm fas fa-user-friends",
        },
        {
          title: "Add manual",
          icon: "icon-nm fas fa-user-friends",
        },
      ],
      trackingConversionType: 1,
      commsionCurrent: 0,
      currentConversionId: null,
      orderIdCurrent: null,
      totalCurrent: 0
    };
  },
  methods: {
    openExplanationModal(item) {
      this.currentExplanationData = item.commission_explanation;
      this.trackingConversionType = item.tracking_type;
      this.commsionCurrent = item.commission;
      this.currentConversionId = item.conversion_id;
      this.orderIdCurrent = item.order_id;
      this.totalCurrent = item.total;
      this.$refs["explanationModalComponent"].$refs["explanationModal"].show();
    },
    loadCommissions(page = 1) {
      this.loadingCommissionTable = true;
      let resource = "conversions";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };

      if (this.filterAffiliate) {
        params["affiliate"] = this.filterAffiliate;
      }

      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      if (this.statusSelected) {
        params["status"] = this.statusSelected;
      }

      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.conversions = data.data;
        this.loadingCommissionTable = false;
      });
    },

    selectAll: function () {
      this.allSelected = !this.allSelected;
      this.conversionIds = [];
      if (this.allSelected) {
        for (var key in this.conversions.data) {
          this.conversionIds.push(this.conversions.data[key].id);
        }
      }
    },

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
      this.affiliateSelected = value;
      this.loadCommissions();
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
      this.loadCommissions();
    },
    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
      this.loadCommissions();
    },
    changeStatus(value) {
      this.loadCommissions();
    },
    changeSort(field) {
      if (this.sortField == field) {
        this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";
      } else {
        this.sortDirection = "desc";
        this.sortField = field;
      }
      this.loadCommissions();
    },
    /** */
    loadSaleStatistics() {
      this.loadingStatistics = true;
      let resource = "conversions/commission-statistics";
      ApiService.query(resource).then(({ data }) => {
        this.commissionStatistics = data.data;
        this.loadingStatistics = false;
      });
    },
  },
  mounted() {
    this.loadCommissions();
    this.loadSaleStatistics();
  },

  watch: {
    paginate: function () {
      this.loadCommissions();
      this.loadSaleStatistics();
    },
  },
};
</script>

<style>
.filter-container {
  margin-bottom: 20px;
}
th i {
  font-size: 9px;
}
.form-control {
  height: calc(1.5em + 1.65rem + 2px) !important;
}
</style>
