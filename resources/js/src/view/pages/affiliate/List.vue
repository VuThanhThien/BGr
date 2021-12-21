<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
      Learn more about
      <a class="font-weight-bold" href="https://docs.bixgrow.com/manage/affiliates" target="_blank">affiliate</a>.
    </alert-note>

    <div class="card card-custom  gutter-b">
      <!--begin::Header-->
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Affiliate List
          </span>
          <span class="text-muted mt-3 font-weight-bold font-size-sm"> </span>
        </h3>
      </div>
      <div class="card-body py-0">
        <AffiliateTable></AffiliateTable>
      </div>
    </div>
      <div class="d-flex tip-alert">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <Tips>
            Tip: To activate all affiliate after they register, visit
            <a class="font-weight-bold" href="#/programs">Programs</a>
             > Edit > General and switch on <strong> Auto-approve affiliate</strong>.
        </Tips>
    </div>

    <div class="col-md-3"></div>
  </div>
</div>
</template>

<script>
import AffiliateTable from "@/view/pages/affiliate/partials/AffiliateTable.vue";
export default {
    components:{AffiliateTable},
    computed: {
        group() {
        return this.$store.getters.group;
        },
    },
}
</script>

<style scoped>
tbody tr:first-child td{
    border-top: none !important;
}
@media only screen and (max-width: 768px) {
    .tip-alert{
        display: none !important;
    }
 }
</style>
