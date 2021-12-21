<template>
  <div class="card card-custom card-stretch gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-bixgrow">
        Recent sales through affiliates
      </h3>
    <div class="card-toolbar">
        <span class="label label-lg label-inline text-muted">{{dateRangText}}</span>
    </div>
    </div>
    <div class="card-body d-flex flex-column">
      <b-overlay
        class="d-flex flex-column"
        opacity="0"
        :show="loadRecentSale"
        rounded="sm"
      >
        <div class="table-responsive">
          <table class="table table-head-custom table-vertical-center">
            <thead>
              <th  style="min-width: 20%">
                <span> Order </span>
              </th>
              <th  style="min-width: 20%">
                <span> Affiliate </span>
              </th>
              <th  style="min-width: 20%">
                <span
                  @click="changeSort('total')"
                  v-bind:class="[sortField == 'total' ? 'sort-field' : '']"
                >
                  Sales
                  <i v-if="sortField == 'total'" class="fas fa-arrow-down"></i>
                </span>
              </th>
              <th  style="min-width: 20%">
                <span
                  @click="changeSort('commission')"
                  v-bind:class="[
                    sortField == 'commission' ? 'sort-field' : '',
                  ]"
                >
                  Commission
                  <i
                    v-if="sortField == 'commission'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
              <th class="pr-0" style="min-width: 20%">
                <span
                  @click="changeSort('created_at')"
                  v-bind:class="[sortField == 'created_at' ? 'sort-field' : '']"
                >
                  Date
                  <i
                    v-if="sortField == 'created_at'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
            </thead>
            <tbody>
              <template v-if="conversions.length > 0">
                <tr v-for="aff in conversions" :key="aff.id">
                  <td class="text-dark-75 font-weight-bolder">
                    <span>
                      {{ aff.order_name }}
                    </span>
                  </td>
                  <td>
                      <router-link
                    :to="{
                        name: 'affiliates.edit',
                        params: { id: aff.affiliate.id },
                    }"
                    class="font-weight-bolder d-block"
                    >{{ aff.affiliate.full_name }}
                    </router-link>
                  </td>
                  <td class="text-dark-75 d-block font-weight-bolder">
                    <span>
                      {{ formatMoney(aff.total, format) }}
                    </span>
                  </td>
                  <td>
                    <span class="text-dark-75 d-block font-weight-bolder">
                      {{ formatMoney(aff.commission, format) }}
                    </span>
                  </td>
                  <td>
                    <span
                      href="#"
                      class="text-dark-75"
                      v-html="formatDate(aff.timestamp, true, true)"
                    >
                    </span>
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td
                    colspan="5"
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
          <TableLoader1></TableLoader1>
        </template>
      </b-overlay>
      <div class="d-flex justify-content-end">
          <a class="text-primary text-sm font-weight-bold mb-0 icon-move-right mt-5" href="#/sales" style="cursor: pointer;">
            <span class="mb-1">View More</span>
            <i class="text-primary fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import TableLoader1 from "@/view/content/TableLoader1.vue";
import { BOverlay } from "bootstrap-vue";
import ApiService from "@/core/services/api.service";
export default {
  props: ["startDate", "endDate","dateRangText"],
  components: { TableLoader1, BOverlay },
  data() {
    return {
      loadRecentSale: false,
      conversions: {},
      sortField: "created_at",
    };
  },
  methods: {
    changeSort(field) {
      this.sortField = field;
      this.loadConversions();
    },
    loadConversions() {
      let resource = "/dashboard/recent-sale";
      let params = {
        sort_field: this.sortField,
      };
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.conversions = data.data;
      });
    },
  },
  mounted() {
    this.loadConversions();
  },
  watch: {
    startDate(newValue, oldValue) {
      if (oldValue != null) {
        this.loadConversions();
      }
    },
  },
};
</script>

<style scoped>

.text-body {
    color: #67748e!important;
}
.icon-move-right i {
    transition: all .2s cubic-bezier(.34,1.61,.7,1.3);
}
.icon-move-right:hover i{
    margin-left: 5px;
}
@media screen and (max-width: 1366px) {
  .table.table-head-custom thead th {
    font-size: 0.8rem;
  }
  .table td {
  font-size: 12px;
}
}
</style>
