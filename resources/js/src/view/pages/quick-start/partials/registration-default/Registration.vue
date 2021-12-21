<template>
  <div class="d-flex row justify-content-between">
    <div class="col-xl-4 border border-secondary mt-10 pt-5 mr-10 rounded">
      <EditProgramText ref="editProgramText" v-if="this.group.id" :groupID="this.group.id" :baseObjToGen="this.TemplateObj"/>
    </div>
    <div class="flex-grow-1 mt-10 pt-5" style="min-height: 700px">
        <DefaultPreviewStart
        ref="defaultP"
          :baseObjToGen="TemplateObj"
          :group="this.group"
        />
    </div>
  </div>
</template>

<script>
import DefaultPreviewStart from "@/view/pages/quick-start/partials/registration-default/DefaultPreviewStart.vue";
import EditProgramText from "@/view/pages/quick-start/partials/registration-default/EditProgramText.vue";
export default {
  components: {  DefaultPreviewStart, EditProgramText },
  props: ["TemplateObj","group"],
};
</script>


<style lang="scss" >
.font-picker ul.expanded {
  z-index: 3;
}
.one-colorpicker {
  .color-block {
    border: 2px solid #d9d9d9;
  }
}
.vue-form-gen {
  min-height: 400px;
  width: 100%;
}
.panel-body {
  border-radius: 5px;
  .wrapper {
    padding: 0 !important;
  }
  width: 50%;
}
.sign-up-container {
  padding-top: 2rem;
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
.program-details {
  width: 40%;
  display: flex;
  flex-direction: column;
}
.detail-info {
  display: flex;
  border-bottom: solid 0.5px #b5b5c3;
  flex: 0 1 11% !important;
  align-items: center;
  height: 53.5px;
  &:nth-last-child(1) {
    border-bottom: none;
  }
}
.label-detail {
  font-weight: 600;
  width: 150px;
}
.content-detail {
  display: inline;
}
@media screen and (max-width: 600px) {
  .panel-body {
    width: 100%;
    padding: 1rem;
  }
}
.fullW > button {
  width: 100% !important;
}
</style>

