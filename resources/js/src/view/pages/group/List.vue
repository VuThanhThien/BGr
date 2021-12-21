<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
      To begin, read the section instructions by clicking
        <a class="font-weight-bold" href="https://docs.bixgrow.com/manage/program" target="_blank">here.</a>
    </alert-note>
    <div class="card card-custom  gutter-b">
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Program list
          </span>
          <span class="text-muted mt-3 font-weight-bold font-size-sm"> </span>
        </h3>
        <div class="card-toolbar">
          <button
            v-b-modal.new_group_modal
            class="btn btn-primary btn-sm font-weight-bolder font-size-sm"
          >
            <span class="svg-icon svg-icon-md">
              <inline-svg src="media/svg/icons/Code/Plus.svg" />
            </span>
            Add New Program
          </button>
          <b-modal size="lg" hide-footer id="new_group_modal" title="Add New Program">
            <CreateForm></CreateForm>
          </b-modal>
        </div>
      </div>
      <div class="card-body py-0">
        <GroupTable></GroupTable>
      </div>
    </div>
      <div class="d-flex tip-alert">
        <div class="col-md-2"></div>
        <div class="col-md-8">
      <Tips>
        Tip: Set up <a class="font-weight-bold" href="https://docs.bixgrow.com/manage/program/multi-level-marketing"
         target="_blank">Multi-level marketing</a> to reach more potential customers and increase revenue.
      </Tips>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
</template>

<script>
import GroupTable from "@/view/pages/group/partials/GroupTable.vue";
import CreateForm from "@/view/pages/group/partials/CreateForm.vue";
export default {
  name: "list",
  components: {
    GroupTable,
    CreateForm,
  },
};
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
