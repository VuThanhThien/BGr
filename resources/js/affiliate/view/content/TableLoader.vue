<template>
   <div class="text-center">
        <div style="padding:5px;box-shadow: 0 10px 16px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);background:#fff;">
        <b-spinner variant="primary" small></b-spinner > {{$t('please_wait')}}...</div>

    </div>
</template>

<script>
import { BSpinner } from 'bootstrap-vue'
export default {
    components:{
     BSpinner
  },
  name: "TableLoader",
  props: {

  }
};
</script>
