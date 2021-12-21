<template>
  <div>
    <div class="row">
      <div class="col-xl-4">
        <div class="card card-custom gutter-b">
          <div class="card-header mx-auto pt-5">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label font-weight-bolder text-dark"
                >Network Above</span
              >
            </h3>
          </div>
          <div class="card-body mx-auto align-items-center">
            <div class="mb-4">
              <span
                v-if="parentAffiliate"
                class="label label-lg label-light-primary label-inline"
              >
                {{ parentAffiliate.full_name }} ({{ parentAffiliate.email }})
              </span>
              <button
                v-if="parentAffiliate"
                class="btn btn-icon btn-light btn-hover-danger btn-sm"
                v-b-tooltip.hover
                title="Delete"
                @click="setParent(true)"
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
            </div>
            <b-button variant="primary" v-b-modal.setParentModal>
              <span class="navi-icon">
                <span class="svg-icon svg-icon-lg">
                  <i class="fas fa-users icon-md"></i>
                </span>
              </span>
              Set Parent
            </b-button>

            <b-modal
              ref="setParentModal"
              id="setParentModal"
              title="Set Parent"
            >
              <SearchAff @input="getAffiliate" :filterAffiliate="id" />
              <template #modal-footer>
                <div class="w-100">
                  <LoadingSubmitButton
                    :loading="submitLoading"
                    class="float-right"
                    @click="setParent"
                  >
                    Save
                  </LoadingSubmitButton>
                </div>
              </template>
            </b-modal>
          </div>
        </div>
        <!--end::List Widget 1-->
      </div>
      <div class="col-xl-4">
        <!--begin::List Widget 1-->
        <div class="card card-custom gutter-b card-stretch">
          <div class="card-header mx-auto pt-5">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label font-weight-bolder text-dark"
                >Total Commission</span
              >
              <!-- <span class="text-muted mt-3 font-weight-bold font-size-sm"
              >Pending 10 tasks</span
            > -->
            </h3>
          </div>
          <div class="card-body mx-auto d-flex align-items-center">
            <span
              class="
                card-title
                font-weight-bolder
                text-primary
                font-size-h1
                mb-0
                d-block
              "
              >{{ formatMoney(totalCommission, format) }}</span
            >
          </div>
        </div>
        <!--end::List Widget 1-->
      </div>

      <div class="col-xl-4">
        <!--begin::List Widget 1-->
        <div class="card card-custom gutter-b card-stretch">
          <div class="card-header mx-auto pt-5">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label font-weight-bolder text-dark"
                >Total signups</span
              >
              <!-- <span class="text-muted mt-3 font-weight-bold font-size-sm"
              >Pending 10 tasks</span
            > -->
            </h3>
          </div>
          <div class="card-body mx-auto d-flex align-items-center">
            <span
              class="
                card-title
                font-weight-bolder
                text-primary
                font-size-h1
                mb-0
                d-block
              "
              >{{ totalDownline }}</span
            >
          </div>
        </div>
        <!--end::List Widget 1-->
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card card-custom gutter-b card-stretch">
          <div class="card-body">
            <Datatable :loading="loadingCouponTable">
              <thead>
                <th class="pr-0" style="min-width: 100px">
                  <span>#Level </span>
                </th>
                <th class="pr-0" style="min-width: 100px">
                  <span>Network Below </span>
                </th>
                <th class="pr-0" style="min-width: 100px">
                  <span>Total Sales</span>
                </th>
                <th class="pr-0 text-right" style="min-width: 100px">
                  <span> Revenue </span>
                </th>
                <th class="pr-5 text-right" style="min-width: 100px">
                  <span> Commission </span>
                </th>
              </thead>
              <tbody>
                <template v-if="levelInfos.length > 0">
                  <tr v-for="level in levelInfos" :key="level.level">
                    <td>
                      <div class="font-weight-bolder font-size-lg d-block">
                        {{ level.level }}
                      </div>
                      <!-- <span class="text-muted"></span> -->
                    </td>
                    <td>
                      <span
                        class="
                          text-dark-75
                          font-weight-bolder
                          d-block
                          font-size-lg
                        "
                        >{{ level.total_signup }}</span
                      >
                    </td>
                    <td>
                      <span
                        v-if="level.statistics"
                        href="#"
                        class="text-dark-75 d-block font-size-lg"
                      >
                        {{ level.statistics.total_conversion }}
                      </span>
                      <span
                        v-else
                        href="#"
                        class="text-dark-75 d-block font-size-lg"
                      >
                        0
                      </span>
                    </td>
                    <td>
                      <span
                        href="#"
                        class="text-dark-75 d-block font-size-lg text-right"
                      >
                        <span
                          v-if="level.statistics"
                          href="#"
                          class="text-dark-75 d-block font-size-lg"
                        >
                          {{
                            formatMoney(level.statistics.total_revenue, format)
                          }}
                        </span>
                        <span
                          v-else
                          href="#"
                          class="text-dark-75 d-block font-size-lg"
                        >
                          {{ formatMoney(0, format) }}
                        </span>
                      </span>
                    </td>
                    <td>
                      <span
                        href="#"
                        class="text-dark-75 d-block font-size-lg text-right"
                      >
                        <span
                          v-if="level.statistics"
                          href="#"
                          class="text-dark-75 d-block font-size-lg"
                        >
                          {{
                            formatMoney(
                              level.statistics.total_commission,
                              format
                            )
                          }}
                        </span>
                        <span
                          v-else
                          href="#"
                          class="text-dark-75 d-block font-size-lg"
                        >
                          {{ formatMoney(0, format) }}
                        </span>
                      </span>
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
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card card-custom-gutter-b card-stretch mt-5">
           <!--begin::Header-->
          <div class="card-header border-0 py-5">
            <h3 class="card-title align-items-start flex-column">
                Affiliate tree

            </h3>
            <span data-v-233bafc8="" class="text-muted mt-3 font-weight-bold font-size-sm">Display maximally 200 affiliates for each node.</span>
          </div>

          <!--end::Header-->
          <div class="card-body tree">

            <ul v-if="this.treeData !== null">
              <TreeView
                class="item"
                :item="treeData"
              ></TreeView>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Datatable from "@/view/content/Datatable.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import TreeView from "@/view/content/TreeView.vue";
import ApiService from "@/core/services/api.service";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import { BButton } from "bootstrap-vue";
export default {
  components: {
    Datatable,
    SearchAff,
    LoadingSubmitButton,
    BButton,
    TreeView
  },
  data() {
    return {
      loadingCouponTable: false,
      affiliateFilterSelected: null,
      submitLoading: false,
      totalCommission: 0,
      totalDownline: 0,
      levelInfos: [],
      treeData: null,
    };
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
    parentAffiliate() {
      return this.$store.getters.currentDetailAffiliate.parent;
    },
    affiliate() {
      return this.$store.getters.currentDetailAffiliate;
    },
  },
  methods: {
    getAffiliate(e) {
      if (!!e) {
        this.affiliateFilterSelected = e;
      } else {
        this.submitLoading = true;
      }
    },
    setParent(isDelete = false) {
      this.submitLoading = true;
      let resource = "affiliates/" + this.id + "/set-parent";
      let parentId = 0;
      if (!isDelete && this.affiliateFilterSelected) {
        parentId = this.affiliateFilterSelected.id;
      }

      if (
        (this.affiliateFilterSelected && this.affiliateFilterSelected.id) ||
        isDelete
      ) {
        ApiService.post(resource, { parent_id: parentId })
          .then(({ data }) => {
            this.submitLoading = false;
            this.$refs["setParentModal"].hide();
            this.$store.commit("SET_CURRENT_DETAIL_AFFILIATE", data.data);
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.message, { position: "bottom" });
            this.submitLoading = false;
          });
      } else {
        this.$toast.error("Please select affiliate", { position: "bottom" });
        this.submitLoading = false;
      }
    },

    loadDataStatistics() {
      let resource = "affiliates/" + this.id + "/network-statistics";
      ApiService.query(resource)
        .then(({ data }) => {
          this.totalCommission = data.data.totalCommission;
          this.totalDownline = data.data.totalDownline;
          this.levelInfos = data.data.levelInfos;
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        });
    },

    loadDownlines(id) {
      let resource = "affiliates/" + id + "/downlines";
      ApiService.query(resource)
        .then(({ data }) => {
            this.treeData = data.data;
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        });
    },
  },

  created() {
    this.loadDataStatistics();
    this.loadDownlines(this.id);
  },
};
</script>

<style scoped>
tbody tr:first-child td {
  border-top: none !important;
}
</style>
