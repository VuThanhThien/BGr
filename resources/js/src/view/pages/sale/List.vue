<template>
  <div>
    <div class="card card-custom gutter-b">
      <!--begin::Header-->
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Conversion List
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
                  <option value="">Status</option>
                  <option value="2">Pending</option>
                  <option value="1">Approved</option>
                  <option value="3">Paid</option>
                  <option value="4">Rejected</option>
                </select>
              </div>
              <div class="col-md-4 my-2 my-md-0" v-if="!filterAffiliate">
                <SearchAff @input="setAffiliateSelected" />
              </div>
            </div>
          </div>
        </div>
        <!-- refresh  -->
        <div class="mb-5 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <button
              @click="loadCommissions()"
              type="button"
              class="btn btn-sm btn-outline-secondary mr-2"
            >
              <i class="icon-md fas fa-redo-alt"></i> Refresh table
            </button>
            <b-dropdown
              id="dropdown-2"
              size="sm"
              class="m-md-2"
              :disabled="!lengthSelected"
            >
              <template #button-content class="btn-sm">
                Change Status
                <span v-if="lengthSelected">({{ lengthSelected }})</span>
              </template>
              <b-dropdown-item @click="approveConverstions(conversionIds)"
                >Approved</b-dropdown-item
              >
              <b-dropdown-item @click="denyConverstions(conversionIds)"
                >Rejected</b-dropdown-item
              >
            </b-dropdown>
          </div>
          <div class="card-toolbar">
            <button
              @click="openAddConversionModal"
              class="btn btn-sm btn-primary font-weight-bold font-size-sm"
            >
              <span class="svg-icon svg-icon-md">
                <inline-svg src="media/svg/icons/Code/Plus.svg" />
              </span>
              Add conversion
            </button>
          </div>
        </div>

        <!--begin::Table-->
        <Datatable :loading="loadingCommissionTable">
          <thead>
            <tr class="text-left">
              <th class="pl-5" style="width: 20px">
                <label class="checkbox checkbox-single">
                  <input
                    type="checkbox"
                    v-model="allSelected"
                    @click="selectAll"
                  />
                  <span></span>
                </label>
              </th>
              <th class="pr-0" style="">
                <span
                  @click="changeSort('created_at')"
                  v-bind:class="[sortField == 'created_at' ? 'sort-field' : '']"
                >
                  Date
                  <i
                    v-if="sortDirection == 'asc' && sortField == 'created_at'"
                    class="fas fa-arrow-up"
                  ></i>
                  <i
                    v-if="sortDirection == 'desc' && sortField == 'created_at'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>

              <th style="">
                <span
                  @click="changeSort('order_name')"
                  v-bind:class="[sortField == 'order_name' ? 'sort-field' : '']"
                >
                  Order
                  <i
                    v-if="sortDirection == 'asc' && sortField == 'order_name'"
                    class="fas fa-arrow-up"
                  ></i>
                  <i
                    v-if="sortDirection == 'desc' && sortField == 'order_name'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
              <th v-if="showAffiliate" style="">Affiliate</th>
              <th style="">
                <span
                  @click="changeSort('total')"
                  v-bind:class="[sortField == 'total' ? 'sort-field' : '']"
                >
                  Total sales
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
                  v-bind:class="[sortField == 'commission' ? 'sort-field' : '']"
                >
                  Commission
                  <i
                    v-if="sortDirection == 'asc' && sortField == 'commission'"
                    class="fas fa-arrow-up"
                  ></i>
                  <i
                    v-if="sortDirection == 'desc' && sortField == 'commission'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
              <th style="">Status</th>
              <th style="">Tracking by</th>
              <th class="pr-5 text-right" style="">Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="conversions.data.length > 0">
              <tr v-for="item in conversions.data" :item="item" :key="item.id">
                <td class="pl-5">
                  <label class="checkbox checkbox-single">
                    <input
                      type="checkbox"
                      v-model="conversionIds"
                      :value="item.id"
                    />
                    <span></span>
                  </label>
                </td>

                <td>
                  <span
                    href="#"
                    class="text-dark-75 d-block"
                    v-html="formatDate(item.timestamp)"
                  >
                  </span>
                </td>
                <td>
                  <span class="text-dark-75 font-weight-bolder">{{
                    item.order_name
                  }}</span>
                </td>
                <td v-if="showAffiliate">
                  <div class="d-flex flex-column w-100 mr-2">
                    <div
                      class="
                        d-flex
                        align-items-center
                        justify-content-between
                        mb-2
                      "
                    >
                      <router-link
                        :to="{
                          name: 'affiliates.edit',
                          params: { id: item.affiliate.id },
                        }"
                        class="font-weight-bolder d-block"
                        >{{ item.affiliate.full_name }}
                      </router-link>
                    </div>
                  </div>
                </td>

                <td>
                  <span class="text-success font-weight-bolder d-block">
                    {{ formatMoney(item.total, format) }}
                  </span>
                </td>

                <td>
                  <span class="text-success font-weight-bolder d-block">{{
                    formatMoney(item.commission, format)
                  }}</span>
                  <span
                    v-if="item.network[0] && item.network[0].total_commission"
                    class="text-muted"
                  >
                    +{{ formatMoney(item.network[0].total_commission, format) }}
                  </span>
                </td>
                <td>
                  <span
                    v-if="item.status == 1"
                    class="label label-light-primary label-inline"
                  >
                    Approved
                  </span>
                  <span
                    v-else-if="item.status == 2"
                    class="label label-light-warning label-inline"
                  >
                    Pending
                  </span>
                  <span
                    v-else-if="item.status == 3"
                    class="label label-light-success label-inline"
                  >
                    Paid
                  </span>
                  <span v-else class="label label-light-danger label-inline">
                    Rejected
                  </span>
                </td>
                <td>
                  <span class="label label-light-default label-inline">{{
                    trackingType[item.tracking_type - 1].title
                  }}</span>
                </td>
                <td class="pr-0 text-right none-select">
                  <button
                    v-if="item.status == 2"
                    v-b-tooltip.hover
                    title="Approve"
                    ref="approbeBtn"
                    class="btn btn-icon btn-light btn-hover-success btn-sm"
                    @click.once="approve($event, item.id)"
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-success">
                      <inline-svg src="media/svg/icons/Navigation/Check.svg" />
                    </span>
                  </button>
                  <button
                    v-if="item.status == 2"
                    v-b-tooltip.hover
                    title="Reject"
                    class="btn btn-icon btn-light btn-hover-danger btn-sm"
                    @click.once="reject($event, item.id)"
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-danger">
                      <inline-svg src="media/svg/icons/Navigation/Close.svg" />
                    </span>
                  </button>

                  <button
                    v-if="item.status == 1 || item.status == 4"
                    v-b-tooltip.hover
                    title="Undo"
                    class="btn btn-icon btn-light btn-hover-info btn-sm"
                    @click="undo($event, item.id)"
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-info">
                      <inline-svg src="media/svg/icons/Text/Undo.svg" />
                    </span>
                  </button>

                  <button
                    v-b-tooltip.hover
                    title="Explanation"
                    class="btn btn-icon btn-light btn-hover-primary btn-sm"
                    @click="openExplanationModal(item)"
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
                  colspan="9"
                  style="text-align: center; color: #b5b5c3 !important"
                >
                  <i class="far fa-folder-open icon-3x"></i>
                  <h6>No Data</h6>
                </td>
              </tr>
            </template>
          </tbody>
        </Datatable>

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
              >Showing {{ conversions.from }} - {{ conversions.to }} of
              {{ conversions.total }}</span
            >
          </div>
        </div>
        <!-- End pagination -->
      </div>
      <ExplanationModal
        :trackingConversionType="trackingConversionType"
        :commission="commsionCurrent"
        :data="currentExplanationData"
        ref="explanationModalComponent"
        :hasNetwork="hasNetwork"
        :currentConversionId="currentIdConversion"
        :comment="commentCurrent"
        :orderId="orderIdCurrent"
        :total="totalCurrent"
        @addCommentSuccess="addCommentSuccess"
        @hiddenModalExplanation="hiddenModalExplanation"
        :key="keyExplanation"
      ></ExplanationModal>
      <AddConversion
        :key="keyAddConversion"
        @addConvesionSuccess="addConvesionSuccess"
        @hiddenModalConversion="hiddenModalConversion"
        ref="addConversionComponent"
      />
      <!--end::Body-->
      <!--end::Advance Table Widget 2-->
    </div>
    <div class="d-flex tip-alert">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <Tips>
          Tip: To automatically approve all conversions recorded, visit
          <a class="font-weight-bold" href="#/programs">Programs</a>
          > Edit > General and switch on <strong> Auto-approve order</strong>.
        </Tips>
      </div>
      <div class="col-md-3"></div>
    </div>
    <!-- modal xin sao review -->
    <FirstOrderModal ref="rate-bixgrow" />
  </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import vSelect from "vue-select";
import ApiService from "@/core/services/api.service";
import DateRange from "@/view/content/DateRange.vue";
import Datatable from "@/view/content/Datatable.vue";
import FirstOrderModal from "@/view/pages/quick-start/get-rate-star/FirstOrderModal.vue";
import moment from "moment";
import ExplanationModal from "@/view/pages/sale/partials/ExplanationModal.vue";
import AddConversion from "@/view/pages/sale/partials/AddConversion.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import { BDropdown, BDropdownItem } from "bootstrap-vue";
import { mapGetters } from "vuex";
export default {
  props: ["showAffiliate", "filterAffiliate"],
  components: {
    pagination,
    vSelect,
    Datatable,
    ExplanationModal,
    SearchAff,
    BDropdown,
    BDropdownItem,
    DateRange,
    FirstOrderModal,
    AddConversion,
  },

  data() {
    return {
      hasNetwork: true,
      currentIdConversion: 0,
      currentExplanationData: { line_items: [] },
      loadingCommissionTable: false,
      allSelected: false,
      conversionIds: [],
      conversions: {
        data: [],
      },
      affiliates: [],
      affiliateSelected: null,
      dateRangText: "Filter by date",
      showDateRange: true,
      statusSelected: "",
      paginate: 10,
      sortDirection: "desc",
      sortField: "created_at",
      startDate: null,
      endDate: null,
      dataArray: {
        network: [{ total_commission: "" }],
      },
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
      keyAddConversion: 0,
      commentCurrent: null,
      orderIdCurrent: null,
      totalCurrent: 0,
      keyExplanation: 10000
    };
  },
  computed: {
    ...mapGetters(["is_feedback"]),
    lengthSelected() {
      return this.conversionIds.length;
    },
  },
  methods: {
    loadCommissions(page = 1) {
      this.commentCurrent = null;
      this.loadingCommissionTable = true;
      let resource = "conversions";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };

      if (this.affiliateSelected) {
        params["affiliate"] = this.affiliateSelected.id;
      }

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
      params["level"] = 0;

      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.conversions = data.data;
        // this.calcuNetworkCommission(this.conversions.data[0]);
        this.loadingCommissionTable = false;
      });
    },
    selectAll: function () {
      this.allSelected = !this.allSelected;
      this.conversionIds = [];
      if (this.allSelected) {
        this.conversionIds = this.conversions.data.map((x) => x.id);
        // for (var key in this.conversions.data) {
        // this.conversionIds.push(this.conversions.data[key].id);
        // }
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
      this.endDate =
        moment(
          this.formatTimeRangeDatePicker(value.endDate),
          "YYYY-MM-DD"
        ).unix() + 86399;

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

    approve(e, id) {
      e.currentTarget.classList.add("spinner", "spinner-primary");
      e.currentTarget.classList.remove("btn-hover-success", "btn-light");
      e.target.classList.add("d-none");
      ApiService.put("conversions/" + id + "/approve")
        .then(({ data }) => {
          var foundIndex = this.conversions.data.findIndex(
            (x) => x.id == data.data.id
          );
          this.conversions.data[foundIndex].status = data.data.status;
          e.target.classList.remove("d-none");
          if (
            data.data.showPopupFeedback == true &&
            this.is_feedback == false
          ) {
            this.$refs["rate-bixgrow"].$refs["rate-star"].show();
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    reject(e, id) {
      e.currentTarget.classList.add("spinner", "spinner-primary");
      e.currentTarget.classList.remove("btn-hover-danger", "btn-light");
      e.target.classList.add("d-none");
      ApiService.put("conversions/" + id + "/reject")
        .then(({ data }) => {
          var foundIndex = this.conversions.data.findIndex(
            (x) => x.id == data.data.id
          );
          this.conversions.data[foundIndex].status = data.data.status;
          e.target.classList.remove("d-none");
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    undo(e, id) {
      e.currentTarget.classList.add("spinner", "spinner-primary");
      e.currentTarget.classList.remove("btn-hover-info", "btn-light");
      e.target.classList.add("d-none");
      ApiService.put("conversions/" + id + "/undo")
        .then(({ data }) => {
          var foundIndex = this.conversions.data.findIndex(
            (x) => x.id == data.data.id
          );
          this.conversions.data[foundIndex].status = data.data.status;
          e.target.classList.remove("d-none");
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    openExplanationModal(item) {
      this.currentExplanationData = item.commission_explanation;
      this.currentIdConversion = item.id;
      this.hasNetwork = item.network.length > 0;
      this.trackingConversionType = item.tracking_type;
      this.commsionCurrent = item.commission;
      this.commentCurrent = item.comment;
      this.orderIdCurrent = item.order_id;
      this.totalCurrent = item.total;
      this.$refs["explanationModalComponent"].$refs["explanationModal"].show();
    },
    // calcuNetworkCommission(dataArray){
    //   console.log(dataArray.network[0].total_commission);
    //   return dataArray.network[0].total_commission;
    // }
    approveConverstions(listId) {
      let resource = `conversions/approve-converstions`;
      let params = {
        conversionsId: listId,
      };
      ApiService.put(resource, params).then(({ data }) => {
        for (let i = 0; i < data.data.length; i++) {
          let foundIndex = this.conversions.data.findIndex(
            (x) => x.id == data.data[i].id
          );
          this.conversions.data[foundIndex].status = data.data[i].status;
        }
        if (data.data.showPopupFeedback == true) {
          this.$refs["rate-bixgrow"].$refs["rate-star"].show();
        }
      });
    },
    denyConverstions(listId) {
      let resource = `conversions/deny-converstions`;
      let params = {
        conversionsId: listId,
      };
      ApiService.put(resource, params).then(({ data }) => {
        for (let i = 0; i < data.data.length; i++) {
          let foundIndex = this.conversions.data.findIndex(
            (x) => x.id == data.data[i].id
          );
          this.conversions.data[foundIndex].status = data.data[i].status;
        }
      });
    },
    openAddConversionModal() {
      this.$refs["addConversionComponent"].$refs["addConversion"].show();
    },
    addConvesionSuccess() {
      this.loadCommissions();
    },
    hiddenModalConversion() {
      this.keyAddConversion++;
    },
    addCommentSuccess(comment, convesrionId) {
      let find = this.conversions.data.find((con) => {
        return con.id == convesrionId;
      });
      if (find) {
        find.comment = comment;   
      }
    },
    hiddenModalExplanation(){
      this.commentCurrent = null;
      this.keyExplanation ++;
    }
  },
  mounted() {
    this.loadCommissions();
  },

  watch: {
    paginate: function () {
      this.loadCommissions();
    },
  },
};
</script>

<style scoped>
tbody tr:first-child td {
  border-top: none !important;
}
.filter-container {
  margin-bottom: 20px;
}
th i {
  font-size: 9px;
}
@media only screen and (max-width: 768px) {
  .tip-alert {
    display: none !important;
  }
}
</style>
