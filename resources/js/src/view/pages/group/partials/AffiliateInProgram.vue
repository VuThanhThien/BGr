<template>
    <div>
        <AffiliateTable :FilterGroup="id"></AffiliateTable>
    </div>
</template>
<script>
import AffiliateTable from "@/view/pages/affiliate/partials/AffiliateTable.vue";
export default {
    components:{
        AffiliateTable
    },
     computed: {
    id() {
      return this.$route.params.id;
    },
  },
}
</script>