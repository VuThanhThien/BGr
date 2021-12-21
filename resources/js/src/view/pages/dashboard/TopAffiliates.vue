<template>
  <div class="card card-custom card-stretch gutter-b">
    <div class="card-header border-0">
      <div class="card-title">
        <h3 class="card-title font-weight-bolder text-bixgrow">
          Top Affiliates
        </h3>
      </div>
      <div class="card-toolbar">
        <span class="label label-lg label-inline text-muted">{{
          dateRangText
        }}</span>
      </div>
    </div>
    <div class="card-body d-flex flex-column">
      <b-overlay
        class="d-flex flex-column"
        opacity="0"
        :show="loadTableAffiliates"
        rounded="sm"
      >
        <div class="table-responsive">
          <table class="table table-head-custom table-vertical-center">
            <thead>
              <th>
                <span> Rank </span>
              </th>
              <th style="min-width: 20%">
                <span> Affiliate </span>
              </th>
              <th style="min-width: 20%">
                <span
                  @click="changeSort('total_conversion')"
                  v-bind:class="[
                    sortField == 'total_conversion' ? 'sort-field' : '',
                  ]"
                >
                  Conversions
                  <i
                    v-if="sortField == 'total_conversion'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
              <th style="min-width: 20%">
                <span
                  @click="changeSort('sales')"
                  v-bind:class="[sortField == 'sales' ? 'sort-field' : '']"
                >
                  Sales
                  <i v-if="sortField == 'sales'" class="fas fa-arrow-down"></i>
                </span>
              </th>
              <th class="pr-0" style="min-width: 20%">
                <span
                  @click="changeSort('total_commission')"
                  v-bind:class="[
                    sortField == 'total_commission' ? 'sort-field' : '',
                  ]"
                >
                  Commissions
                  <i
                    v-if="sortField == 'total_commission'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>
            </thead>
            <tbody>
              <template v-if="affiliates.length > 0">
                <tr v-for="(aff, i) in affiliates" :key="aff.id">
                  <td class="pl-0 py-4">
                    <div class="symbol symbol-35 symbol-light mr-1">
                      <span class="symbol-label">
                        <i
                          v-if="i + 1 <= 3"
                          :class="[i + 1 <= 3 ? isMedal(i + 1) : '']"
                          class="icon-lg fas fa-medal"
                        ></i>
                        <span v-else>{{ i + 1 }}</span>
                      </span>
                    </div>
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'affiliates.edit',
                        params: { id: aff.id },
                      }"
                      class="font-weight-bolder d-block"
                      >{{ aff.first_name + " " + aff.last_name }}
                    </router-link>
                  </td>
                  <td>
                    <span class="text-dark-75 font-weight-bolder d-block">
                      {{ aff.total_conversion }}
                    </span>
                  </td>
                  <td>
                    <span class="text-dark-75 font-weight-bolder d-block">
                      {{ formatMoney(aff.sales, format) }}
                    </span>
                  </td>
                  <td>
                    <span class="text-dark-75 d-block font-weight-bolder">
                      {{ formatMoney(aff.total_commission, format) }}
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
    </div>
  </div>
</template>

<script>
import TableLoader1 from "@/view/content/TableLoader1.vue";
import { BOverlay } from "bootstrap-vue";
import ApiService from "@/core/services/api.service";
export default {
  props: ["startDate", "endDate", "dateRangText"],
  components: { TableLoader1, BOverlay },
  data() {
    return {
      loadTableAffiliates: false,
      affiliates: {},
      sortField: "total_conversion",
    };
  },
  methods: {
    isMedal(pos) {
      if (pos == 1) {
        return "text-warning";
      } else {
        if (pos == 2) {
          return "text-secondary";
        } else {
          return "text-danger";
        }
      }
    },
    changeSort(field) {
      this.sortField = field;
      this.loadAffiliates();
    },
    loadAffiliates() {
      let resource = "/dashboard/top-affiliate";
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
        this.affiliates = data.data;
      });
    },
  },
  mounted() {
    this.loadAffiliates();
  },
  watch: {
    startDate(newValue, oldValue) {
      if (oldValue != null) {
        this.loadAffiliates();
      }
    },
  },
};
</script>

<style scoped>
.text-secondary {
  color: #a2a4aa !important;
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
