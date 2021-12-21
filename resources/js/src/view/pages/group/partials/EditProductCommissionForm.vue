<template>
  <form @submit.stop.prevent="submit" class="form" id="kt_form">
    <div class="row">
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="my-5">
          <h3 class="text-dark font-weight-bold mb-10">Set commission:</h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="1"
            label-cols-lg="1"
            content-cols-sm="11"
            content-cols-lg="11"
            label=""
            label-for="commission_type"
          >
            <b-form-select
              @change="selectCommssionType"
              v-model="editItem.commission_type"
              :options="commission_type_options"
              id="commission_type"
            ></b-form-select>
          </b-form-group>

          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="1"
            label-cols-lg="1"
            content-cols-sm="11"
            content-cols-lg="11"
            label=""
            label-for="commission_amount"
          >
            <b-input-group :prepend="commissionTypePrepend">
              <b-form-input
                min="0"
                type="number"
                step="0.01"
                id="commission_amount"
                value="0"
                v-model="editItem.commission_amount"
                class="form-control-custom"
              ></b-form-input>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="float-right mr-1">
          <button type="submit" class="btn btn-primary font-weight-bolder" :disabled="loading">
            <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
              <inline-svg src="media/svg/icons/General/Save.svg" />
            </span>
            <b-spinner v-if="loading" small></b-spinner>
            Save</button
          >
        </div>
      </div>
      <div class="col-xl-1"></div>
    </div>
  </form>
</template>

<script>
import { validationMixin } from "vuelidate";
import ApiService from "@/core/services/api.service";
import { BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner } from 'bootstrap-vue'
export default {
   components:{
     BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner
  },
  mixins: [validationMixin],
  data() {
    return {
      commission_type_prepend: "$",
      commission_type_selected: null,
      commission_type_options: [
        { value: 1, text: "Percent" },
        { value: 3, text: "Flat rate" }
      ],
      loading: false,
    };
  },
  props: ['editItem'],
  computed:{
    commissionTypePrepend(){
      if(this.editItem.commission_type ===1) {
        this.commission_type_prepend = "%";
      } else {
        this.commission_type_prepend = "$";
      }
      return this.commission_type_prepend ;
    }
  },
  methods: {
    selectCommssionType(value) {
      if (value === 1) {
        this.commission_type_prepend = "%";
      } else {
        this.commission_type_prepend = "$";
      }
    },

    updateProduct(payload) {
        ApiService.update('product-commission', payload.id ,payload)
        .then(({ data }) => {
          this.$emit('editSuccess', data.data);
          this.loading = false;
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },


    submit() {

      this.loading = true;
      let payload = {
          id:this.editItem.id,
          commission_type: this.editItem.commission_type,
          commission_amount: this.editItem.commission_amount
      }
      this.updateProduct(payload);

    }
  },
};
</script>

<style scoped>
.my-52 {
  text-align: center;
}
</style>>

