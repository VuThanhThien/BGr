<template>
    <b-overlay opacity="0" :show="loading" rounded="sm">
        <div class="table-responsive">
            <table class="table table-head-custom table-head-bg table-vertical-center">
                <slot></slot>
            </table>
        </div>
        <template #overlay>
            <TableLoader></TableLoader>
        </template>
    </b-overlay>
</template>

<script>
import TableLoader from "@/view/content/TableLoader.vue";
import { BOverlay } from 'bootstrap-vue'
export default {
    components:{TableLoader,
     BOverlay
    },
    props:['loading']
}
</script>

<style>

</style>
