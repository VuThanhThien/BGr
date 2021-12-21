<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
      Learn more about
      <a class="font-weight-bold" href="https://docs.bixgrow.com/manage/conversions" target="_blank">conversions</a>.

    </alert-note>

  <List :showAffiliate="true"></List>
</div>
</template>

<script>

import List from "@/view/pages/sale/List.vue";

export default {
  components:{List},

};
</script>

<style>

</style>
