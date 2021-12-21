<template>
  <div class="card card-custom card-stretch gutter-b">
    <div class="card-header justify-content-start border-0">
        <i class="fas icon-3x mr-2 fa-chart-bar text-bixgrow"></i>
      <h3 class="card-title font-weight-bolder text-bixgrow">Top Products</h3>
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
              <th class="pr-0" style="min-width: 100px">
                <span> Date </span>
              </th>
            </thead>
            <tbody>
              <template v-if="0">
                <tr>
                  <td>a</td>
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
          <TableLoader1></TableLoader1>
        </template>
      </b-overlay>
    </div>
  </div>
</template>

<script>
import TableLoader1 from "@/view/content/TableLoader1.vue";
import { BOverlay } from "bootstrap-vue";
export default {
  components: { TableLoader1, BOverlay },
  data() {
    return {
      loadTableAffiliates: false,
    };
  },
};
</script>

<style>
</style>
