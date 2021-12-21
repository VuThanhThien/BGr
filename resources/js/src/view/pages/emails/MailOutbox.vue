<template>
  <div>
    <alert-note :type="'alert-white alert-shadow'">
      All of your emails' detailed status.
    </alert-note>
    <div class="row row d-flex">
      <div class="col-lg-12">
        <div class="card card-custom gutter-b">
          <div class="card-header border-0 py-3">
            <h3 class="card-title align-items-start flex-column w-100">
              <span class="card-label font-weight-bolder text-bixgrow">
                Mail outbox
              </span>
              <span class="text-muted mt-3 font-weight-bold font-size-sm w-100">
              </span>
            </h3>
          </div>
          <div class="card-body py-5">
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
                      class="form-control form-control-custom"
                      @change="changeStatusFilter"
                    >
                      <option value="">Status</option>
                      <option value="Pending">Pending</option>
                      <option value="Delivery">Delivered</option>
                      <option value="Bounce">Bounced</option>
                      <option value="Open">Opened</option>
                      <option value="Click">Clicked</option>
                      <option value="Reject">Rejected</option>
                      <option value="Complaint">Complained</option>
                    </select>
                  </div>
                  <div class="col-md-4 my-2 my-md-0">
                    <SearchAff @input="setAffiliateSelected" />
                  </div>
                </div>
              </div>
            </div>
            <!--end::Filter-->
            <div class="mt-10 mb-5 collapse show" style="">
              <div class="d-flex align-items-center justify-content-between">
                <button
                  type="button"
                  class="btn btn-outline-secondary mr-2"
                  @click="loadMailOutbox"
                >
                  <i class="icon-md fas fa-redo-alt"></i> Refresh table
                </button>
              </div>
            </div>
            <!--begin::Table-->
            <b-overlay opacity="0" :show="loadingMailOutboxTable" rounded="sm">
              <div class="table-responsive">
                <table
                  class="
                    table table-head-custom table-head-bg table-vertical-center
                  "
                  style="min-height: 150px"
                >
                  <thead>
                    <tr class="text-left">
                      <!-- <th class="pl-0" style="width: 20px">
                <label class="checkbox checkbox-lg checkbox-single">
                  <input
                    type="checkbox"
                  />
                  <span></span>
                </label>
              </th> -->
                      <th class="pr-0" style="min-width: 100px">
                        <span> Email type </span>
                      </th>
                      <th style="min-width: 100px">
                        <span> Sender</span>
                      </th>
                      <th style="min-width: 100px">
                        <span> To recipients </span>
                      </th>
                      <th style="min-width: 100px">Status</th>
                      <th style="min-width: 100px">Mail subject</th>
                      <!-- <th style="min-width: 100px">Error message</th> -->
                      <th style="min-width: 100px">
                        <span
                          @click="changeSort('created_at')"
                          v-bind:class="[
                            sortField == 'created_at' ? 'sort-field' : '',
                          ]"
                          >Sent at
                          <i
                            v-if="
                              sortDirection == 'asc' &&
                              sortField == 'created_at'
                            "
                            class="fas fa-arrow-up"
                          ></i>
                          <i
                            v-if="
                              sortDirection == 'desc' &&
                              sortField == 'created_at'
                            "
                            class="fas fa-arrow-down"
                          ></i>
                        </span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="emails.data.length > 0">
                      <tr
                        v-for="email in emails.data"
                        :email="email"
                        :key="email.id"
                      >
                        <!-- <td class="pl-0">
                  <label class="checkbox checkbox-lg checkbox-single">
                    <input
                      type="checkbox"
                      :value="email.id"
                    />
                    <span></span>
                  </label>
                </td> -->
                        <td style="min-width: 100px; width: 10%">
                          <span class="text-dark-75 d-block">
                            {{ convertEmailType(email.email_type) }}
                          </span>
                        </td>
                        <td style="min-width: 100px; width: 20%">
                          <span class="text-dark-75 d-block">
                            {{ email.from }}
                          </span>
                        </td>
                        <td style="min-width: 100px; width: 20%">
                          <span class="text-dark-75 d-block">
                            {{ email.to }}
                          </span>
                          <router-link
                            v-if="email.affiliate"
                            :to="{
                              name: 'affiliates.edit',
                              params: { id: email.affiliate.id },
                            }"
                            class="font-weight-bolder d-block"
                            >{{ email.affiliate.full_name }}
                          </router-link>
                        </td>
                        <td style="min-width: 100px; width: 10%">
                          <div class="d-flex">
                            <span
                              class="svg-icon svg-icon-danger svg-icon-md"
                              v-b-tooltip.hover
                              :title="email.error_message"
                              v-if="email.status == 'Bounce'"
                            >
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
                                  <rect
                                    fill="#000000"
                                    x="11"
                                    y="10"
                                    width="2"
                                    height="7"
                                    rx="1"
                                  />
                                  <rect
                                    fill="#000000"
                                    x="11"
                                    y="7"
                                    width="2"
                                    height="2"
                                    rx="1"
                                  />
                                </g>
                              </svg>
                            </span>
                            <span
                              v-if="email.status == 'Delivery'"
                              class="label label-light-success label-inline"
                            >
                              Delivered
                            </span>
                            <span
                              v-else-if="email.status == 'Pending'"
                              class="label label-light-warning label-inline"
                            >
                              {{ email.status }}
                            </span>
                            <span
                              v-else-if="email.status == 'Bounce'"
                              class="label label-light-danger label-inline"
                            >
                              Bounced
                            </span>
                            <span
                              v-else-if="email.status == 'Open'"
                              class="label label-light-primary label-inline"
                            >
                              Opened
                            </span>
                            <span
                              v-else-if="email.status == 'Reject'"
                              class="label label-light-danger label-inline"
                            >
                              Rejected
                            </span>
                            <span
                              v-else-if="email.status == 'Click'"
                              class="label label-light-warning label-inline"
                            >
                              Clicked
                            </span>
                            <span
                              v-else-if="email.status == 'Complaint'"
                              class="label label-light-warning label-inline"
                            >
                              Complained
                            </span>
                          </div>
                        </td>
                        <td style="min-width: 100px; width: 30%">
                          <span class="text-dark-75 d-block">
                            {{ email.subject }}
                          </span>
                        </td>
                        <td style="min-width: 100px; width: 10%">
                          <span
                            href="#"
                            class="text-dark-75 d-block"
                            v-html="formatDate(email.timestamp)"
                          >
                          </span>
                        </td>
                        <!-- <td class="pr-0 text-center none-select">
                          <button
                            class="
                              btn btn-icon btn-light btn-hover-danger btn-sm
                            "
                            v-b-tooltip.hover
                            title="Delete"
                            @click="deleteMailOutbox(email.id)"
                          >
                            <span class="svg-icon svg-icon-danger svg-icon-lg">
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
                                  <rect
                                    x="0"
                                    y="0"
                                    width="24"
                                    height="24"
                                  ></rect>
                                  <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000"
                                    fill-rule="nonzero"
                                  ></path>
                                  <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000"
                                    opacity="0.3"
                                  ></path>
                                </g>
                              </svg>
                            </span>
                          </button>
                        </td> -->
                      </tr>
                    </template>
                    <template v-else>
                      <tr>
                        <td
                          colspan="7"
                          style="text-align: center; color: #b5b5c3 !important"
                        >
                          <i class="far fa-folder-open icon-3x"></i>
                          <h6>No Data</h6>
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

            <!-- start pagination -->
            <div
              v-if="emails.data.length > 0"
              class="
                d-flex
                justify-content-between
                align-items-center
                flex-wrap
              "
            >
              <div class="d-flex flex-wrap py-2 mr-3">
                <pagination
                  :data="emails"
                  :limit="3"
                  show-disabled
                  @pagination-change-page="loadMailOutbox"
                  size="default"
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
                >
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="30">30</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <span class="text-muted"
                  >Showing {{ emails.from }} - {{ emails.to }} of
                  {{ emails.total }}</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TableLoader from "@/view/content/TableLoader.vue";
import { BOverlay } from "bootstrap-vue";
import DateRange from "@/view/content/DateRange.vue";
import { mapGetters } from "vuex";
import ApiService from "../../../core/services/api.service";
import moment from "moment";
import pagination from "laravel-vue-pagination";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import swal from "sweetalert2";
window.Swal = swal;
export default {
  data() {
    return {
      emails: {
        data: [],
      },
      statusSelected: "",
      loadingMailOutboxTable: false,
      sortField: "created_at",
      sortDirection: "desc",
      paginate: 10,
      affiliateFilterSelected: null,
    };
  },
  components: {
    DateRange,
    BOverlay,
    TableLoader,
    pagination,
    SearchAff,
  },
  methods: {
    loadMailOutbox(page = 1) {
      this.loadingMailOutboxTable = true;
      let resource = `mail-outbox`;
      let params = {
        page: page,
        paginate: this.paginate,
        sort_field: this.sortField,
        sort_direction: this.sortDirection,
      };
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      if (this.statusSelected) {
        params["status"] = this.statusSelected;
      }
      if (this.affiliateFilterSelected) {
        params["affiliate"] = this.affiliateFilterSelected.id;
      }
      ApiService.query(resource, {
        params: params,
      }).then(({ data }) => {
        this.emails = data.data;
        this.loadingMailOutboxTable = false;
      });
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
        vm.affiliateOptions = data;
        loading(false);
      });
    }),

    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      this.loadMailOutbox();
    },
    changeSort(field) {
      if (field == this.sortField) {
        this.sortDirection = this.sortDirection == "desc" ? "asc" : "desc";
      } else {
        this.sortField = field;
        this.sortDirection = "desc";
      }
      this.loadMailOutbox();
    },
    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
      this.loadMailOutbox();
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

      this.loadMailOutbox();
    },
    changeStatusFilter() {
      this.loadMailOutbox();
    },
    convertEmailType(emailType) {
      emailType = emailType.replace("_", " ");
      return emailType[0].toUpperCase() + emailType.slice(1);
    },
    // deleteMailOutbox(id){
    //    Swal.fire({
    //     title: "Are you sure?",
    //     text: "Delete mail!",
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Yes, delete it!",
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       ApiService.delete("mail-outbox/" + id)
    //         .then(({ data }) => {
    //           var foundIndex = this.emails.data.findIndex((x) => x.id == id);
    //           if (foundIndex > -1) {
    //             this.emails.data.splice(foundIndex, 1);
    //           }
    //         })
    //         .catch(({ response }) => {
    //           this.$toast.error(response.data.message, { position: "bottom" });
    //         });
    //     }
    //   });
    // }
  },
  watch: {
    paginate: function () {
      this.loadMailOutbox();
    },
  },
  mounted() {
    this.loadMailOutbox();
  },
};
</script>
<style scoped>
</style>

<style>
</style>
