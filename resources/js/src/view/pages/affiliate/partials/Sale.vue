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
                >Pending</span
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
                >Approved</span
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
                >Paid</span
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
                >Rejected</span
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
    <ListSale :filterAffiliate="id"></ListSale>
  </div>
</template>

<script>
import ListSale from "@/view/pages/sale/List.vue";
import ApiService from "@/core/services/api.service";
import TableLoader from "@/view/content/TableLoader.vue";
import { BOverlay } from 'bootstrap-vue'
export default {
  data() {
    return {
      loadingStatistics: false,
      commissionStatistics: {},
    };
  },
  components: { ListSale, TableLoader,BOverlay },
  computed: {
    id() {
      return this.$route.params.id;
    },
  },

  methods: {
    loadSaleStatistics() {
      this.loadingStatistics = true;
      let resource = "affiliates/" + this.id + "/commission-statistics";
      ApiService.query(resource).then(({ data }) => {
        this.commissionStatistics = data.data;
        this.loadingStatistics = false;
      });
    },
  },

  mounted() {
    this.loadSaleStatistics();
  },
};
</script>

<style>
</style>
