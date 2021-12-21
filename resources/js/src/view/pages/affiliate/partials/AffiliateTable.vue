<template>
  <div>
    <!--begin::Filter-->
    <div class="row align-items-center filter-container">
      <div class="col-lg-12 col-xl-12">
        <div class="row align-items-center">
          <div class="col-md-3 my-2 my-md-0">
            <DateRange
              @updateTimePicker="updateTimePicker"
              @cancelFilterDate="cancelFilterDate"
            />
          </div>
          <div class="col-md-3 my-2 my-md-0">
            <select
              v-model="statusSelected"
              @change="changeStatusFilter"
              class="form-control form-control-custom"
            >
              <option value="">Status</option>
              <option value="2">Pending</option>
              <option value="1">Approved</option>
              <option value="3">Denied</option>
            </select>
          </div>
          <div class="col-md-3 my-2 my-md-0" v-if="!FilterGroup">
            <select
              v-model="groupSelected"
              @change="changeSelectGroup"
              class="form-control form-control-custom"
            >
              <option value="" selected class="text-muted">
                Filter by program
              </option>

              <option
                v-for="(o, index) in groupSelectOption"
                :value="o.value"
                :o="o"
                :key="index"
              >
                {{ o.text }}
              </option>
            </select>
            <div></div>
          </div>
          <div class="col-md-3 my-2 my-md-0">
            <SearchAff @input="setAffiliateSelected" />
          </div>
        </div>
      </div>
    </div>
    <!--end::Filter-->
    <div class="mt-10 mb-5 collapse show" style="">
      <div class="md-screen">
        <div class="d-flex align-items-center justify-content-between">
          <button
            @click="loadAffiliates()"
            type="button"
            class="btn btn-outline-secondary mr-2"
          >
            <i class="icon-md fas fa-redo-alt"></i> Refresh table
          </button>
          <div class="card-toolbar">
            <button v-if="!FilterGroup"
              @click="openModalAddAffiliate"
              class="btn btn-sm btn-primary font-weight-bold font-size-sm mr-2"
            >
              <span class="svg-icon svg-icon-md">
                <inline-svg src="media/svg/icons/Code/Plus.svg" />
              </span>
              Add affiliate
            </button>
            <button v-if="!FilterGroup"
              @click="openModalImportAffiliate"
              class="
                btn btn-sm btn-secondary
                font-weight-bold font-size-sm
                mr-2
              "
            >
              <span class="svg-icon svg-icon-md">
                <inline-svg src="media/svg/icons/Files/Import.svg" />
              </span>
              Import affiliates
            </button>
            <button v-if="!FilterGroup"
              @click="openModalExportAffiliate"
              class="
                btn btn-sm btn-secondary
                font-weight-bold font-size-sm
              "
            >
              <span class="svg-icon svg-icon-md">
                <inline-svg src="media/svg/icons/Files/Export.svg" />
              </span>
              Export affiliates
            </button>
          </div>
        </div>
      </div>
      <div class="sm-screen">
        <div class="d-flex align-items-center justify-content-between">
          <button
            @click="loadAffiliates()"
            type="button"
            class="btn btn-outline-secondary mr-2"
          >
            <i class="icon-md fas fa-redo-alt"></i>
          </button>
          <div class="card-toolbar">
            <button v-if="!FilterGroup"
              @click="openModalAddAffiliate"
              class="btn btn-sm btn-primary font-weight-bold font-size-sm"
            >
              <span class="svg-icon svg-icon-lg">
                <inline-svg src="media/svg/icons/Code/Plus.svg" />
              </span>
            </button>
            <button v-if="!FilterGroup"
              @click="openModalImportAffiliate"
              class="
                btn btn-sm btn-secondary
                font-weight-bold font-size-sm
              "
            >
              <span class="svg-icon svg-icon-lg">
                <inline-svg src="media/svg/icons/Files/Import.svg" />
              </span>
            </button>
             <button v-if="!FilterGroup"
              @click="openModalExportAffiliate"
              class="
                btn btn-sm btn-secondary
                font-weight-bold font-size-sm
              "
            >
              <span class="svg-icon svg-icon-lg">
                <inline-svg src="media/svg/icons/Files/Export.svg" />
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--begin::Table-->
    <b-overlay opacity="0" :show="loadingAffiliateTable" rounded="sm">
      <div class="table-responsive">
        <table
          class="table table-head-custom table-head-bg table-vertical-center"
          style="min-height: 150px"
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
              <th class="pr-0" style="min-width: 100px">
                <span
                  @click="changeSort('created_at')"
                  v-bind:class="[sortField == 'created_at' ? 'sort-field' : '']"
                >
                  Date joined
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
              <th style="min-width: 100px">
                <span>Name</span>
              </th>
              <th style="min-width: 100px">
                <span> Program </span>
              </th>
              <th style="min-width: 100px">Status</th>
              <th style="min-width: 100px">Source</th>
              <th class="pr-5 text-right" style="min-width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="affiliates.data.length > 0">
              <tr v-for="(aff, i) in affiliates.data" :aff="aff" :key="aff.id">
                <!-- <td class="pl-0">
                  <label class="checkbox checkbox-lg checkbox-single">
                    <input
                      type="checkbox"
                      v-model="affiliateIds"
                      :value="aff.id"
                    />
                    <span></span>
                  </label>
                </td> -->

                <td>
                  <span
                    href="#"
                    class="text-dark-75 d-block"
                    v-html="formatDate(aff.timestamp)"
                  >
                  </span>
                </td>

                <td>
                  <router-link
                    :to="{ name: 'affiliates.edit', params: { id: aff.id } }"
                    class="font-weight-bolder d-block"
                    >{{ aff.full_name }}
                  </router-link>
                  <span class="text-muted">{{ aff.email }}</span>
                </td>

                <td>
                  <span class="text-dark-75 font-weight-bolder d-block">
                    {{ aff.group.name }}
                  </span>
                </td>

                <td class="none-select">
                  <span
                    v-if="aff.status == 1"
                    class="label label-light-primary label-inline"
                  >
                    Approved
                  </span>
                  <span
                    v-else-if="aff.status == 2"
                    class="label label-light-warning label-inline"
                  >
                    Pending
                  </span>
                  <span
                    v-else-if="aff.status == 3"
                    class="label label-light-danger label-inline"
                  >
                    Denied
                  </span>
                  <b-dropdown class="m-md-2 status-dropdown" no-caret>
                    <template #button-content>
                      <span class="svg-icon svg-icon-primary svg-icon-sm">
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
                              d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z"
                              fill="#000000"
                              fill-rule="nonzero"
                              transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "
                            />
                            <rect
                              fill="#000000"
                              opacity="0.3"
                              x="5"
                              y="20"
                              width="15"
                              height="2"
                              rx="1"
                            />
                          </g>
                        </svg>
                      </span>
                    </template>
                    <b-dropdown-item
                      class="approved-item"
                      @click="changeStatus(1, aff.id, i)"
                      >Approved</b-dropdown-item
                    >
                    <b-dropdown-item
                      class="pending-item"
                      @click="changeStatus(2, aff.id, i)"
                      >Pending</b-dropdown-item
                    >
                    <b-dropdown-item
                      class="denied-item"
                      @click="changeStatus(3, aff.id, i)"
                      >Denied</b-dropdown-item
                    >
                  </b-dropdown>
                </td>
                <td>
                  <span class="text-dark-75 d-block">
                    {{ aff.source }}
                  </span>
                </td>
                <td class="pr-0 text-right none-select">
                  <button
                    class="btn btn-icon btn-light btn-hover-primary btn-sm"
                    v-b-tooltip.hover
                    title="View profile"
                    @click="openViewDetailModal(aff)"
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                      <inline-svg src="media/svg/icons/Design/ZoomPlus.svg" />
                    </span>
                  </button>
                  <button
                    class="btn btn-icon btn-light btn-hover-success btn-sm"
                    v-b-tooltip.hover
                    title="Login as affiliate"
                    @click="loginAsAffiliate(aff.id)"
                  >
                    <span class="svg-icon svg-icon-lg svg-icon-success">
                      <inline-svg
                        src="media/svg/icons/Navigation/Sign-in.svg"
                      />
                    </span>
                  </button>
                  <button
                    class="btn btn-icon btn-light btn-hover-danger btn-sm"
                    v-b-tooltip.hover
                    title="Delete"
                    @click="deleteAffiliate(aff.id)"
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
                          <rect x="0" y="0" width="24" height="24"></rect>
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
        </table>
      </div>
      <template #overlay>
        <TableLoader></TableLoader>
      </template>
    </b-overlay>

    <!--end::Table-->

    <!-- start pagination -->
    <div
      v-if="affiliates.data.length > 0"
      class="d-flex justify-content-between align-items-center flex-wrap"
    >
      <div class="d-flex flex-wrap py-2 mr-3">
        <pagination
          :data="affiliates"
          @pagination-change-page="loadAffiliates"
          :limit="3"
          show-disabled
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
          style="width: 75px"
        >
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="30">30</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="text-muted"
          >Showing {{ affiliates.from }} - {{ affiliates.to }} of
          {{ affiliates.total }}</span
        >
      </div>
    </div>
    <!-- start pagination -->

    <!-- start modal show affiliate -->
    <ViewDetailModal
      :affiliate="currentAffiliateView"
      ref="viewDetailModalComponent"
    ></ViewDetailModal>
    <!-- end modal show affiliate -->
    <AddAffiliateModal
      :key="componentKey"
      ref="addAffiliateModalParent"
      @addAffiliateSuccess="closeModalAddAffiliate"
    ></AddAffiliateModal>
    <ImportAffiliates
      :key="componentKey1"
      ref="importAffiliateModalParent"
      @addAffiliateSuccess="closeModalAddAffiliate"
    ></ImportAffiliates>
        <ExportAffiliateModal
      ref="exportAffiliateModalParent" :startDate="startDate" :endDate="endDate" :groupId="groupSelected" :status="statusSelected" :affiliate="affiliateFilterSelected"
    ></ExportAffiliateModal>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import DateRange from "@/view/content/DateRange.vue";
import TableLoader from "@/view/content/TableLoader.vue";
import moment from "moment";
import pagination from "laravel-vue-pagination";
import ViewDetailModal from "@/view/pages/affiliate/partials/ViewDetailModal.vue";
import swal from "sweetalert2";
window.Swal = swal;
import { BOverlay, BDropdown, BDropdownItem } from "bootstrap-vue";
import AddAffiliateModal from "./AddAffiliate.vue";
import ImportAffiliates from "./ImportAffiliates.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import ExportAffiliateModal from './ExportAffiliateModal.vue';
export default {
  props: ["Filter", "FilterGroup"],
  components: {
    DateRange,
    TableLoader,
    pagination,
    ViewDetailModal,
    BOverlay,
    BDropdown,
    BDropdownItem,
    AddAffiliateModal,
    ImportAffiliates,
    SearchAff,
    ExportAffiliateModal
  },
  data() {
    return {
      componentKey1: 1000,
      componentKey: 0,
      loadingAffiliateTable: false,
      affiliates: {
        data: [],
      },
      statusSelected: "",
      sortField: "created_at",
      sortDirection: "desc",
      groupSelected: "",
      paginate: 10,
      allSelected: false,
      affiliateIds: [],
      currentAffiliateView: {},
      idAffiliateView: 0,
      affiliateFilterSelected: null,
      startDate: null,
      endDate: null,
    };
  },
  computed: {
    groupSelectOption() {
      let options = [];
      let groups = this.$store.getters.groups;
      for (var i = 0; i < groups.length; i++) {
        options.push({ value: groups[i].id, text: groups[i].name });
      }
      return options;
    },
    subDomain() {
      return this.$store.getters.subDomain;
    },
  },
  methods: {
    loadAffiliates(page = 1) {
      this.loadingAffiliateTable = true;
      let resource = "affiliates";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };
      if (this.affiliateFilterSelected) {
        params["affiliate"] = this.affiliateFilterSelected.id;
      }
      if (this.FilterGroup) {
        params["group"] = this.FilterGroup;
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

      if (this.statusSelected) {
        params["status"] = this.statusSelected;
      }

      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.affiliates = data.data;
        this.loadingAffiliateTable = false;
      });
    },
    cancelFilterDate() {
      this.startDate = null;
      this.endDate = null;
      this.loadAffiliates();
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

      this.loadAffiliates();
    },
    changeStatusFilter() {
      this.loadAffiliates();
    },

    changeSort(field) {
      if (this.sortField == field) {
        this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";
      } else {
        this.sortDirection = "desc";
        this.sortField = field;
      }
      this.loadAffiliates();
    },

    selectAll: function () {
      this.allSelected = !this.allSelected;
      this.affiliates = [];
      if (this.allSelected) {
        for (var key in this.affiliates.data) {
          this.affiliateIds.push(this.affiliates.data[key].id);
        }
      }
    },

    changeStatus(status, affId, index) {
      this.loadingAffiliateTable = true;
      let payload = {
        affiliate_id: affId,
        status: status,
      };
      ApiService.post("affiliates/change-status", payload).then(({ data }) => {
        this.loadingAffiliateTable = false;
        this.affiliates.data[index].status = status;
      });
    },
    changeSelectGroup() {
      this.loadAffiliates();
    },

    openViewDetailModal(aff) {
      this.currentAffiliateView = aff;
      this.$refs["viewDetailModalComponent"].$refs["viewDetailModal"].show();
    },
    changeGroup(idAffiliate) {
      this.idAffiliateView = idAffiliate;
      this.$refs["viewModalGroup"].$refs["changeGroupModal"].show();
    },
    deleteAffiliate(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "If you delete this Affiliate, all associated data (clicks, conversions, coupons, etc.) will be removed as well.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          ApiService.delete("affiliates/" + id)
            .then(({ data }) => {
              var foundIndex = this.affiliates.data.findIndex(
                (x) => x.id == id
              );
              if (foundIndex > -1) {
                this.affiliates.data.splice(foundIndex, 1);
              }
            })
            .catch(({ response }) => {
              this.$toast.error(response.data.message, { position: "bottom" });
            });
        }
      });
    },

    loginAsAffiliate(id) {
      let resource = `affiliates/${id}/login-as`;
      ApiService.query(resource)
        .then(({ data }) => {
          let token = data.data;
          let domain = process.env.MIX_BASE_DOMAIN;
          let urlLoginAs =
            "https://" +
            this.subDomain +
            "." +
            domain +
            "/login-as?token=" +
            token;
          window.open(urlLoginAs);
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        });
    },
    openModalAddAffiliate() {
      this.$refs["addAffiliateModalParent"].$refs["addAffiliateModal"].show();
    },
    closeModalAddAffiliate() {
      this.componentKey += 1;
      this.loadAffiliates();
    },
    openModalImportAffiliate() {
      this.$refs["importAffiliateModalParent"].$refs[
        "importAffiliateModal"
      ].show();
    },
    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      this.loadAffiliates();
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
    openModalExportAffiliate()
    {
      this.$refs["exportAffiliateModalParent"].$refs[
        "exportAffiliateModal"
      ].show();
    }
  },

  mounted() {
    this.loadAffiliates();
  },
  watch: {
    paginate: function () {
      this.loadAffiliates();
    },
  },
};
</script>

<style>
.status-dropdown {
  margin: 0px !important;
}
.status-dropdown .svg-icon {
  margin-right: 0px !important ;
}
.status-dropdown .dropdown-toggle {
  background: none;
  padding: 0px;
  border: none;
}
.pending-item .dropdown-item {
  color: #ffa800;
}
.approved-item .dropdown-item {
  color: #3699ff;
}
.denied-item .dropdown-item {
  color: #f64e60;
}

@media screen and (max-width: 600px) {
  .status-dropdown .dropdown-menu {
    width: 54vw !important;
  }
}
</style>




