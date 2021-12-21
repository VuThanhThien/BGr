<template>
  <div
    class="card card-custom card-sticky"
    id="kt_page_sticky_card"
    sticky-container
  >
    <div
      class="card-header"
      v-sticky
      sticky-offset="{top: 60, bottom: 30}"
      sticky-side="both"
      on-stick="onStick"
      sticky-z-index="20"
    >
      <div class="card-title">
        <h3 class="card-label">New Program <i class="mr-2"></i></h3>
      </div>
      <div class="card-toolbar">
        <router-link
          to="/groups"
          class="btn btn-light-primary font-weight-bolder mr-2"
        >
          <i class="ki ki-long-arrow-back icon-sm"></i>
          Back
        </router-link>
        <div class="btn-group">
          <button type="button" class="btn btn-primary font-weight-bolder">
            <span class="svg-icon svg-icon-md svg-icon-white">
              <inline-svg src="media/svg/icons/Navigation/Check.svg" />
            </span>
            Save Form
          </button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!--begin::Form-->
      <form class="form" id="kt_form">
        <div class="row">
          <div class="col-xl-1"></div>
          <div class="col-xl-10">
            <div class="my-5">
              <h3 class="text-dark font-weight-bold mb-10">Required fields:</h3>
              <b-form-group
                id="fieldset-horizontal"
                label-cols-sm="4"
                label-cols-lg="3"
                content-cols-sm="8"
                content-cols-lg="9"
                label="Name"
                label-for="name"
              >
                <b-form-input id="name"></b-form-input>
              </b-form-group>
              <b-form-group
                id="fieldset-horizontal"
                label-cols-sm="4"
                label-cols-lg="3"
                content-cols-sm="8"
                content-cols-lg="9"
                label="Commission type"
                label-for="commission_type"
              >
                <b-form-select @change="selectCommssionType" v-model="commission_type_selected" :options="commission_type_options" id="commission_type"></b-form-select>
              </b-form-group>
              <b-form-group
                id="fieldset-horizontal"
                label-cols-sm="4"
                label-cols-lg="3"
                content-cols-sm="8"
                content-cols-lg="9"
                label="Commission amount"
                label-for="commission_amount"
                v-if="commission_type_selected !== null"
              >
                <b-input-group :prepend="commission_type_prepend" >
                    <b-form-input min=0 type="number" id="commission_amount" value=0></b-form-input>
                </b-input-group>
              </b-form-group>
             
            </div>
            <div class="separator separator-dashed my-10"></div>
            <div class="my-52">
              <h3 class="text-dark font-weight-bold mb-10">
                Commission calculation:
              </h3>
             
            </div>
          </div>
          <div class="col-xl-1"></div>
        </div>
      </form>
      <!--end::Form-->
    </div>
  </div>
</template>

<script>
import { BFormGroup,BFormInput,BFormSelect,BInputGroup  } from 'bootstrap-vue'
export default {
  components:{
       BFormGroup,BFormInput,BFormSelect,BInputGroup
  },
    data() {
      return {
        commission_type_prepend:'$',
        commission_type_selected: null,
        commission_type_options: [
          { value: null, text: 'Please select an option', disabled: true },
          { value: 1, text: 'Percent of sale' },
          { value: 2, text: 'Flat rate per order' },
          { value: 2, text: 'Flat rate per item' }
        ]
      }
    },
    methods: {
        selectCommssionType(value) {
            if(value === 1) {
                this.commission_type_prepend = '%';
            }else{
                this.commission_type_prepend = '$';
            }
        }
    }
};
</script>

<style scoped>
.card-header {
  background: #fff !important;
}
</style>