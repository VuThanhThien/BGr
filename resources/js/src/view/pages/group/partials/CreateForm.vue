<template>
  <form @submit.stop.prevent="submit" class="form" id="kt_form">
    <div class="row">
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="my-5">
          <h3 class="text-bixgrow font-weight-bold mb-10">Required fields:</h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Name:"
            label-for="name"
          >
            <b-form-input
              v-model="$v.group.name.$model"
              :state="validateState('name')"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Commission type:"
            label-for="commission_type"
          >
            <b-form-select
              @change="selectCommssionType"
              v-model="$v.group.commission_type.$model"
              :state="validateState('commission_type')"
              :options="commission_type_options"
              id="commission_type"
            ></b-form-select>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Commission amount:"
            label-for="commission_amount"
            v-if="group.commission_type !== null"
          >
            <b-input-group :prepend="$v.group.commission_type.$model == 1 ? '%' : symbolCurrency ">
              <b-form-input
                min="0"
                type="number"
                step="0.01"
                id="commission_amount"
                value="0"
                :max="group.commission_type ==1? 100 : 9007199254740990"
                v-model="$v.group.commission_amount.$model"
                :state="validateState('commission_amount')"
              ></b-form-input>
            </b-input-group>
          </b-form-group>

        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="float-right">
          <b-button type="submit" variant="primary" :disabled="loading">
            <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
                <inline-svg src="media/svg/icons/General/Save.svg" />
            </span>
            <b-spinner v-if="loading" small></b-spinner>
            Save and go to setup</b-button
          >
        </div>
      </div>
      <div class="col-xl-1"></div>
    </div>
  </form>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import ApiService from "@/core/services/api.service";
import { BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner,BButton   } from 'bootstrap-vue'
export default {
  components:{
     BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner,BButton
  },
  mixins: [validationMixin],
  data() {
    return {
      commission_type_prepend: "$",
      commission_type_selected: null,
      commission_type_options: [
        { value: null, text: "Please select an option", disabled: true },
        { value: 1, text: "Percent of sale" },
        { value: 2, text: "Fixed amount per order" },
        { value: 3, text: "Fixed amount per item" },
      ],
      group: {
        name: "",
        commission_type: null,
        commission_amount: 0,
        is_active: 0
      },
      loading: false
    };
  },
   computed: {
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    }
  },
  
  validations: {
    group: {
      name: {
        required,
      },
      commission_type: {
        required,
      },
      commission_amount: {
        required,
      },
    },
  },
  methods: {
    selectCommssionType(value) {
      if (value === 1) {
        this.commission_type_prepend = "%";
      } else {
        this.commission_type_prepend = "$";
      }
    },
    submit() {
      this.$v.group.$touch();
      if (this.$v.group.$anyError) {
        return;
      }
      this.loading = true;
      ApiService.post("groups", this.group)
      .then(({data} ) => {
        this.loading = false;
        this.$store.commit('PREPEND_ITEM_TO_GROUPS', data.data);
        this.$router.push({name:'groups.edit', params:{id:data.data.id}});
      })
      .catch(({ response }) => {
        context.commit(SET_ERROR, response.data.errors);
      });


    },
    validateState(name) {
      const { $dirty, $error } = this.$v.group[name];
      return $dirty ? !$error : null;
    },
  },
};
</script>

<style scoped>
.my-52 {
  text-align: center;
}
.form-control{
  height: inherit !important;
}
</style>>

