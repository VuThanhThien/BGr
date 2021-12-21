<template>
  <div class="px-10">
    <b-overlay opacity="0" :show="loading" rounded="sm">
      <div class="table-responsive">
        <table class="table table-head-custom table-vertical-center">
          <thead>
            <th class="pr-0" style="min-width: 100px">
              <span> Date </span>
            </th>
            <th class="pr-0" style="min-width: 100px">
              <span> Order </span>
            </th>
            <th class="pr-0" style="min-width: 100px">
              <span> Type </span>
            </th>
            <th class="pr-0" style="min-width: 100px">
              <span> Total sale </span>
            </th>
            <th class="pr-0" style="min-width: 100px">
              <span> Commission </span>
            </th>
          </thead>
          <tbody>
            <template v-if="orders.data.length > 0">
              <tr v-for="(o, i) in orders.data" :o="o" :key="i">
                <!-- thời gian  -->
                <td>
                  <span
                    href="#"
                    class="text-dark-75 d-block font-size-lg"
                    v-html="formatDate(o.timestamp)"
                  >
                  </span>
                </td>
                <!-- ordername  -->
                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ o.order_name }}</span
                  >
                </td>
                <!-- type  -->
                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ typeOfPayment(o.level) }}</span
                  >
                </td>
                <!-- total sale  -->
                <td>
                  <span
                    class="text-success font-weight-bolder d-block font-size-lg"
                    >{{ formatMoney(o.total, format) }}</span
                  >
                </td>
                <!-- commission  -->
                <td>
                  <span
                    class="text-success font-weight-bolder d-block font-size-lg"
                    >{{ formatMoney(o.commission, format) }}</span
                  >
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
    <div
      v-if="orders.data.length > 0"
      class="d-flex justify-content-between align-items-center flex-wrap"
    >
      <div class="d-flex flex-wrap py-2 mr-3">
        <pagination
          :data="orders"
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
          >Showing {{ orders.from }} - {{ orders.to }} of
          {{ orders.total }}</span
        >
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import pagination from "laravel-vue-pagination";
import TableLoader from "@/view/content/TableLoader.vue";
import { BOverlay } from "bootstrap-vue";

export default {
  components: { pagination, BOverlay, TableLoader },
  data() {
    return {
      paginate: 10,
      loading: false,
      orders: {
        data: [],
      },
    };
  },
  props: ["affiliateId"],
  methods: {
    async loadCommissions(page = 1) {
      let resource = "conversions";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: "desc",
        sort_field: "created_at",
      };

      if (this.affiliateId) {
        params["affiliate"] = this.affiliateId;
      }
      params["status"] = 1;
      this.loading = true;
      await ApiService.query(resource, { params: params }).then(({ data }) => {
        this.orders = data.data;
        this.loading = false;
      });
    },
    typeOfPayment(level) {
      if (level == 0) {
        return "Conversion";
      } else if (level > 0) {
        return "Network";
      } else return "";
    },
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

<style>
</style>
