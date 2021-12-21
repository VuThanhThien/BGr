<template>
  <form @submit.stop.prevent="submitProduct" class="form" >
    <div class="row">
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="my-5">
          <h3 class="text-bixgrow font-weight-bold mb-10">Apply on:</h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="1"
            label-cols-lg="1"
            content-cols-sm="11"
            content-cols-lg="11"
            label=""
            label-for="get_product_type"
          >
            <b-form-select
              v-model="form.data_type"
              :options="get_product_options"
              id="get_product_type"
            ></b-form-select>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="1"
            label-cols-lg="1"
            content-cols-sm="11"
            content-cols-lg="11"
            label=""
            label-for="name"
          >
            <v-select
              @search="onSearchProduct"
              :options="products"
              label="title"
              :filterable="false"
              :value="productSelected"
              @input="setProductSelected"
              :placeholder="form.data_type == 'product'?'Type product name to search' : 'Type collection name to search'"
              v-model="form.product"
              :class="{ 'form-group--error': $v.form.product.$error, 'select-product-to-add' : !$v.form.product.$error }"
              >
              <template slot="no-options">
              Type to search Product..
              </template>
              <template slot="option" slot-scope="option">
                <div class="d-center">
                  {{ option.title}}
                  </div>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                  {{ option.title  }}
                </div>
              </template>
            </v-select>
             <div :class="{ 'span-text-error': $v.form.product.$error }" class="text-muted" style="display: none">Field is required.</div>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="1"
            label-cols-lg="1"
            content-cols-sm="11"
            content-cols-lg="11"
            label=""
            label-for="variant_options"
            v-if="variant_options.length>1"
          >
            <b-form-select
              v-model="variant_selected"
              :options="variant_options"
              id="variant_options"
            ></b-form-select>
          </b-form-group>

        </div>
        <div class="my-5">
          <h3 class="text-bixgrow font-weight-bold mb-10">Set commission:</h3>
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
              v-model="form.commission_type"
              :state="validateState('commission_type')"
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
            v-if="form.commission_type !== null"
          >
            <b-input-group :prepend="commission_type_prepend">
              <b-form-input
                min="0"
                type="number"
                step="0.01"
                id="commission_amount"
                value="0"
                v-model="form.commission_amount"
                :state="validateState('commission_amount')"
              ></b-form-input>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="float-right">
            <button
            type="submit"
            class="btn btn-primary font-weight-bolder"
            :disabled="loading"
            >
            <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
                <inline-svg src="media/svg/icons/Code/Plus.svg" />
            </span>
            <b-spinner v-if="loading" small></b-spinner>
            Add
            </button>
        </div>
      </div>
      <div class="col-xl-1"></div>
    </div>
  </form>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import vSelect from 'vue-select';
import ApiService from "@/core/services/api.service";
import { BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner } from 'bootstrap-vue'
export default {
  mixins: [validationMixin],
  components: {vSelect,BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner  },

  data() {
    return {
      commission_type_prepend: "$",
      commission_type_selected: null,
      get_product_options: [
        { value: 'product', text: "Specified product" },
        { value: 'collection', text: "Collection" },
      ],
      commission_type_options: [
        { value: null, text: "Please select an option", disabled: true },
        { value: 1, text: "Percent of sale" },
        { value: 3, text: "Fixed amout per item" }
      ],
      variant_selected: 'all',
      form: {
        commission_type: null,
        commission_amount: 0,
        group_id: this.$route.params.id,
        data:{

        },
        data_type:'product',
        product: null
      },
      products:[],
      productSelected: null,
      variant_options: [
        {value:'all', text:"All"}
      ],
      loading: false
    };
  },
  validations: {
    form: {
      commission_type: {
        required,
      },
      commission_amount: {
        required,
      },
      product:{
        required,
      }
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
    submitProduct() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.loading = true;
      if(this.variant_selected != 'all') {
        this.form.data.variants = [this.variant_selected];
      }
      ApiService.post('product-commission', this.form)
        .then(({ data }) => {
          this.loading = false;
          this.$emit('createSuccess');
        })
        .catch(({ response }) => {
          try {
            context.commit(SET_ERROR, response.data.errors);
          } catch (e) {
            // console.log(e);
            // console.log("check product input");
          }
        });
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    onSearchProduct(search, loading) {
      if(search.length) {
        loading(true);
        this.searchProduct(loading, search, this);
      }
    },

    searchProduct: _.debounce((loading, search, vm) => {
      let resource = 'product-commission/search-shopify-product?query='+escape(search);
      if(vm.form.data_type == 'collection') {
        resource = 'product-commission/search-shopify-collection?query='+escape(search);
      }
      ApiService.query(resource)
      .then(({data}) => {
          vm.products = data;
          loading(false);
      })
      .catch(({response}) =>{
      this.$toast.error('Can not get product data, please try again.', { position: "bottom" });
      console.log(response);
      })
    },350),
    setVariantOptions(variants) {
      this.variant_options = [{value:'all', text:"All"}];
      this.variant_options.push();
      for(var i=0; i < variants.length; i++) {
        this.variant_options.push({value:variants[i], text: variants[i].title});
      }
    },
    setProductSelected(value) {
      this.productSelected = value;
      if(value){
        if(this.form.data_type == 'product') {
          this.variant_selected = 'all';
          this.setVariantOptions(value.variants);
          this.form.data.product = {id:value.id, title:value.title};
          this.form.data.variants = value.variants;
        }else{
          this.form.data.collection = value;
        }
      }
    }
  },
};
</script>

<style >
.my-52 {
  text-align: center;
}
.form-group--error >.vs__dropdown-toggle{
  border: 1px solid #F64E60;
  border-color: #F64E60 !important;
}
.span-text-error{
  display: block !important;
  color: #F64E60 !important;
}
.select-product-to-add > .vs__dropdown-toggle{
    padding: 5px 0px !important;
    background-color: #ffffff !important;
    border: 1px solid #E4E6EF !important;
    color: #3F4254 !important;
}
</style>

