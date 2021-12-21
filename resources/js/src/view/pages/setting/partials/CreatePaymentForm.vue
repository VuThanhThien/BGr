<template>
<b-modal ref="new_custom_payment_method_modal"
scrollable  size="xl"
id="new_custom_payment_method_modal"
@hidden="resetPaymentForm"
:title="this.paymentMethod?'Edit payment method':'New custom payment method'">
  <div class="row">
    <div class="col-lg-6 col-md-6">
      <div class="form-group">
        <label class="font-weight-bolder">Payment method: </label>
        <b-form-input
          v-model="$v.form.method_name.$model"
          required
          placeholder="Bank tranfer or Paypal..."
          :state="validateState('method_name')"
        ></b-form-input>
        <div v-if="submited && !$v.form.method_name.required" class="fv-plugins-message-container"><div  class="fv-help-block">Method name required</div></div>
      </div>
      <div class="fields-container">
        <div class="field-box mb-3" v-for="(field, index) in form.fields" :key="index">
          <div class="form-group">
            <label class="font-weight-bolder">Field label: </label>
            <b-form-input
            placeholder="Bank account number, email address, etc."
             v-model="field.label">
             </b-form-input>
          </div>
          <div class="form-group">
            <label class="font-weight-bolder">Field type: </label>
            <b-form-select
              class="form-control"
              required
              :options="fieldTypeOptions"
              v-model="field.type"
            ></b-form-select>
          </div>
          <div class="form-group" v-if="field.type == 'select'">
            <label class="font-weight-bolder">Options (one per line): </label>
           <b-form-textarea
            v-model="field.options"
            rows="1"
            max-rows="3"
            @change="handle(field)"
            ></b-form-textarea>
          </div>
          <div class="form-group">
            <label class="font-weight-bolder">Required field: </label>
            <b-form-checkbox
              v-model="field.required"
              switch
              size="lg"
              value=true
              unchecked-value=false
              @change="changeRequired(field)"
            >
            </b-form-checkbox>
          </div>
          <div style="text-align:right;">
              <button class="btn btn-light-danger font-weight-bold mr-2" @click="deleteField(index)"><i class="icon-md far  fa-trash-alt"></i> Delete</button>
          </div>
        </div>
      </div>
      <div class="mt-7">
        <b-button
          class="btn btn-success font-weight-bolder font-size-sm"
          @click="addField()"
        >
          <span class="svg-icon svg-icon-md svg-icon-white">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
            <inline-svg src="media/svg/icons/Code/Plus.svg" />
            <!--end::Svg Icon--> </span
          >Add field
        </b-button>
      </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <h4 class="text-center">Form preview</h4>
        <div class="form-preview-box">
            <h4>{{form.method_name}}</h4>
            <div>
                <vue-form-generator :schema="schema" :model="model" >
                </vue-form-generator>
            </div>
        </div>

    </div>
  </div>
  <template #modal-footer>
    <div class="w-100">
      <b-button
        v-if="!paymentMethod"
        variant="primary"
        size="md"
        class="float-right"
        @click="submit"
      >
        <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
          <inline-svg src="media/svg/icons/General/Save.svg" />
        </span>
        <b-spinner v-if="loading" small></b-spinner>
        Save
      </b-button>
      <b-button
        v-else
        variant="primary"
        size="md"
        class="float-right"
        @click="update"
      >
        <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
          <inline-svg src="media/svg/icons/General/Save.svg" />
        </span>
        <b-spinner v-if="loading" small></b-spinner>
        Update
      </b-button>
    </div>
  </template>
</b-modal>
</template>

<script>
import VueFormGenerator from 'vue-form-generator'
import ApiService from "@/core/services/api.service";
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from "vuelidate";
import {BFormSelect,BFormInput,BSpinner,BButton,BFormCheckbox,BFormTextarea  } from 'bootstrap-vue'
export default {
    mixins: [validationMixin],
  components: {
    "vue-form-generator": VueFormGenerator.component,
    BFormSelect,BFormInput,BSpinner,BButton,BFormCheckbox,BFormTextarea
  },
  data() {
    return {
      form: {
        method_name: "",
        fields: []
      },
      model:{},
      submited:false,
      loading:false,
      fieldTypeOptions: [
        { value: "input", text: "Text box" },
        { value: "textArea", text: "Paragraph" },
        { value: "checkbox", text: "Checkbox" },
        { value: "select", text: "Select" },
      ],

    };
  },
  validations: {
    form: {
      method_name:{
        required
      }

    }
  },
  computed:{
     schema() {
         let schema = {
             fields:this.form.fields
         }
        return schema;
     }
  },
  methods: {

    addField() {

      let modelId = this.makeid(8);
      this.model[modelId] = '';
      let defaultField = {
        label: "",
        type: "input",
        inputType: "text",
        model:modelId,
        required: false,
        options:'',
        values:[],
        validator: ["required"],
        selectOptions:{
            hideNoneSelectedText:true
        }

      };
      this.form.fields.push(defaultField);
    },

    handle(field) {
        field.values = field.options.split("\n");
    },

    normalizeFields(fields) {
      for(var i=0; i<fields.length; i++) {
        if(fields[i].type == 'input') {
          fields[i] = {
            type: "input",
            inputType: "text",
            label: fields[i].label,
            model: fields[i].model,
            required: fields[i].required,
            validator: "string"
          }
        }else if(fields[i].type == 'textArea') {
          fields[i] = {
            type: "textArea",
            label: fields[i].label,
            model: fields[i].model,
            required: fields[i].required,
            validator: "string"
          }
        }else if(fields[i].type == 'checkbox') {
          fields[i] = {
            type: "checkbox",
            label: fields[i].label,
            model: fields[i].model
          }
        }
      }
      return fields;
    },

    deleteField(index) {
        let modelId = this.form.fields[index].model;
        this.form.fields.splice(index, 1);
        delete this.model[modelId];
    },

    changeRequired(field) {
      field.required = field.required == 'true' ? true : false;
    },
    submit(bvModalEvt) {
        bvModalEvt.preventDefault();
        this.submited = true;
        this.loading = true;
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
            this.loading = false;
            return;
        }else{
            this.form.fields = this.normalizeFields(this.form.fields);
             ApiService.post("settings/payment-methods", this.form)
            .then(({data} ) => {
              this.$emit('createSuccess', data.data);
              this.loading = false;
              this.$refs['new_custom_payment_method_modal'].hide();
            })
            .catch(({ response }) => {
              this.loading = false;
              context.commit(SET_ERROR, response.data.errors);

            });
        }

    },
    update(bvModalEvt) {
      bvModalEvt.preventDefault();
        this.submited = true;
        this.loading = true;
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
            return;
        }else{
             ApiService.put("settings/payment-methods", this.form)
            .then(({data} ) => {
              this.$emit('updateSuccess', data.data);
              this.loading = false;
              this.$refs['new_custom_payment_method_modal'].hide();
            })
            .catch(({ response }) => {
              this.loading = false;
              context.commit(SET_ERROR, response.data.errors);

            });
        }
    },
    showModal() {
      this.$refs['new_custom_payment_method_modal'].show();
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    resetPaymentForm(){
        this.$emit("close");
    }

  },
  props: ['paymentMethod'],
  watch: {
    paymentMethod: function (newVal, oldVal) {
      // watch it
      if(newVal) {
        this.form.method_name = newVal.name;
        this.form.fields = newVal.fields;
        this.form.id = newVal.id;
      }else{
        this.form = {
          method_name: "",
          fields: []
        }
      }
    }

  },

};
</script>

<style>
.field-box {
  padding: 25px;
  background: #f3f3f3;
}
.vue-form-generator .wrapper {
    padding: 0px !important;
    width: 100%;
}
.form-preview-box{
    padding: 20px;
    background: #F3F6F9;
}
</style>
