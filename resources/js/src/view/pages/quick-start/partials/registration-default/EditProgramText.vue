<template>
  <div class="form-group d-flex flex-column">
    <label class="font-weight-bolder font-size-h4">Heading Text</label>
    <b-form-input
      id="title3"
      class=" mt-5"
      v-model="baseObjToGen.titleAndDescription.title.text"
    ></b-form-input>
    <label class="font-weight-bolder font-size-h4 mt-10">Benefits Text</label>
    <b-form-input
      id="title4"
      class="mt-5"
      v-model="baseObjToGen.detailObj.mainHeading"
    ></b-form-input>
    <div
      v-for="(obj, i) in baseObjToGen.detailObj.detailText"
      :i="i"
      :key="i"
      class="mt-5"
    >
      <div class="d-flex justify-content-between">
        <b-form-input v-model="obj.label" class="mr-3"></b-form-input>
        <b-form-input v-model="obj.content"></b-form-input>
      </div>
    </div>

    <div role="alert" class="alert alert-custom alert-light-primary mt-5">
        <div class="d-flex">
            <span class="svg-icon svg-icon-primary svg-icon-2x pr-2">
                <inline-svg src="media/svg/icons/Home/Bulb1.svg" />
            </span>
        </div>
        <div class="alert-text text-dark">
            <div class="alert-heading" style="font-size: 15px; font-weight: 600">
                Use program details in your registration form
            </div>
        You can use the below variables:<br/>
        <ul style="padding-left: 20px">
            <li>commission type: {commission_type}</li>
            <li>commission amount: {commission_amount}</li>
            <li>cookie days: {cookie_days}</li>
        </ul>
        </div>
    </div>

    <label class="font-weight-bolder font-size-h4 mt-10">Color</label>
    <b-form-group
      id="fieldset-horizontal"
      class="col-xl-12 mx-auto"
      label-cols-lg="8"
      content-cols-lg="4"
      label="Background Color"
      label-for="swatch"
    >
      <color-picker
        id="swatch"
        v-model="baseObjToGen.bgAndBorder.bg"
      ></color-picker>
    </b-form-group>
    <b-form-group
      id="fieldset-horizontal"
      class="col-xl-12 mx-auto"
      label-cols-lg="8"
      content-cols-lg="4"
      label="Benefit Field"
      label-for="swatch"
    >
      <color-picker v-model="baseObjToGen.detailObj.background"></color-picker>
    </b-form-group>
    <b-form-group
      id="fieldset-horizontal"
      class="col-xl-12 mx-auto"
      label-cols-lg="8"
      content-cols-lg="4"
      label="Title Color"
      label-for="swatch"
    >
      <color-picker
        v-model="baseObjToGen.titleAndDescription.title.color"
      ></color-picker>
    </b-form-group>
    <b-form-group
      id="fieldset-horizontal"
      class="col-xl-12 mx-auto"
      label-cols-lg="8"
      content-cols-lg="4"
      label="Benefit Text Color"
      label-for="swatch"
    >
      <color-picker
        id="swatch2"
        v-model="baseObjToGen.detailObj.color"
      ></color-picker>
    </b-form-group>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
import { BFormInput, BFormGroup } from "bootstrap-vue";
export default {
  components: { BFormInput, BFormGroup},
  props: ["baseObjToGen", "groupID"],
  methods: {
    ...mapActions(["changeBaseObj", "updateRegistration"]),
    onApplyChange() {
      let obj = this.baseObjToGen;
      var id = this.groupID;
      this.changeBaseObj(obj);
      this.updateRegistration({ id, obj });
    },
  },
};
</script>

<style>
</style>
