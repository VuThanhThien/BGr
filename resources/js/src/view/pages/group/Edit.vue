<template>
  <div class="col-xxl-12 order-1 order-xxl-2">
    <div class="card card-custom card-stretch gutter-b">
      <b-tabs v-model="tab" card nav-class="nav-tabs-line group-nav">
        <b-tab lazy title="General"
          ><GeneralTab></GeneralTab
        ></b-tab>
        <b-tab title="Product commission" lazy>
          <ProductCommissionTab></ProductCommissionTab>
        </b-tab>
        <b-tab lazy title="Affiliates in program">
          <AffiliateInProgram></AffiliateInProgram>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import AffiliateInProgram from "@/view/pages/group/partials/AffiliateInProgram.vue";
import GeneralTab from "@/view/pages/group/partials/GeneralTab.vue";
import ProductCommissionTab from "@/view/pages/group/partials/ProductCommissionTab.vue";
import { BTabs, BTab } from "bootstrap-vue";
export default {
  props: ["tabIndex"],
  // data() {
   
  // },
  components: {
    GeneralTab,
    ProductCommissionTab,
    BTabs,
    BTab,
    AffiliateInProgram,
  },

  computed: {
    id() {
      return this.$route.params.id;
    },
    ...mapState({
      group: (state) => state.group.group,
    }),
    tab: {
      get: function () {
        if (!!this.tabIndex) return this.tabIndex;
        else return 0;
      },
      set: function (newValue) {
        return newValue;
      },
    },
  },

  // mounted() {
  //   this.loadGroup(this.id)
  //     .then(() => {
  //       this.currentGroup = Object.assign({}, this.group);
  //     })
  //     .catch((respon) => {
  //       this.$toast.error(respon.data.message, { position: "bottom" });
  //     });
  // },

  // methods: {
  //   ...mapActions(["loadGroup"]),
  // },
};
</script>

<style>
.group-nav > .nav-item > .nav-link.active, .nav-link:hover{
    color: #ff9900;
}
.group-nav .nav-link:hover:not(.disabled), .nav.nav-tabs.nav-tabs-line .nav-link.active, .nav.nav-tabs.nav-tabs-line .show > .nav-link{
    border-bottom: 1px solid #ff9900 !important;
    color : #ff9900 !important;
}
</style>
