<template>
  <div>
     <ListCoupon :filterAffiliate="id"></ListCoupon>
  </div>
</template>

<script>
import ListCoupon from "@/view/pages/coupon/List.vue";
import TableLoader from "@/view/content/TableLoader.vue";
export default {
  data() {
    return {
    };
  },
  components: { ListCoupon, TableLoader },
  computed: {
    id() {
      return this.$route.params.id;
    },
  },
};
</script>

<style>
</style>
