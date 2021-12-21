<template>
  <form @submit.stop.prevent="submit" class="form" id="kt_form">
    <div class="row">
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="my-5">
          <h3 class="card-label font-weight-bolder text-dark mb-10">
            General fields:
          </h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Name:"
            label-for="name"
          >
            <b-form-input
              v-model="$v.currentGroup.name.$model"
              :state="validateState('name')"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Commission type:"
            label-for="commission_type"
          >
            <b-form-select
              @change="selectCommssionType"
              v-model="currentGroup.commission_type"
              :state="validateState('commission_type')"
              :options="commission_type_options"
              id="commission_type"
            ></b-form-select>
            <span
              v-if="currentGroup.commission_type"
              class="form-text text-muted"
            >
              {{
                this.commission_type_options[currentGroup.commission_type]
                  .description
              }}
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Commission amount:"
            label-for="commission_amount"
            v-if="currentGroup.commission_type !== null"
          >
            <b-input-group>
              <template #prepend v-if="currentGroup.commission_type !== 1">
                <b-input-group-text>{{ symbolCurrency }}</b-input-group-text>
              </template>
              <b-form-input
                min="0"
                type="number"
                step="0.01"
                id="commission_amount"
                value="0"
                :max="
                  currentGroup.commission_type == 1 ? 100 : 9007199254740990
                "
                v-model="$v.currentGroup.commission_amount.$model"
                :state="validateState('commission_amount')"
              ></b-form-input>
              <template #append v-if="currentGroup.commission_type === 1">
                <b-input-group-text>%</b-input-group-text>
              </template>
            </b-input-group>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Active:"
            label-for="is_active"
          >
            <b-form-checkbox
              v-model="currentGroup.is_active"
              id="is_active"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When you disable this button, the registration page of this
              program will be locked and no conversions are tracked.
            </span>
          </b-form-group>

          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Private:"
            label-for="is_private"
          >
            <b-form-checkbox
              v-model="currentGroup.is_private"
              id="is_private"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When applied, this program’s sign up page will be locked and you
              have to manually invite the affiliates.
            </span>
          </b-form-group>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="my-5">
          <h3 class="card-label font-weight-bolder text-dark mb-10">
            Commission calculation:
          </h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Include discounts: "
            label-for="include_discounts"
          >
            <b-form-checkbox
              v-model="currentGroup.include_discounts"
              id="include_discounts"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting total discount from the
              order (lower commission when applied)
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Exclude shipping: "
            label-for="exclude_shipping"
          >
            <b-form-checkbox
              v-model="currentGroup.exclude_shipping"
              id="exclude_shipping"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting shipping fee from the
              order (lower commission when applied)
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Exclude taxes: "
            label-for="exclude_taxes"
          >
            <b-form-checkbox
              v-model="currentGroup.exclude_tax"
              id="exclude_taxes"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting taxes from the order
              (lower commission when applied)
            </span>
          </b-form-group>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="my-5">
          <h3 class="card-label font-weight-bolder text-dark mb-10">
            Payment:
          </h3>
            <!--#region available payment method -->
          <b-form-group
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Available payment methods:"
          >
            <multiselect
              v-model="paymentMethodSelected"
              tag-placeholder="Add this as new tag"
              tag-position="bottom"
              placeholder="Choose your payment methods"
              label="name"
              track-by="id"
              :options="listPaymentMethod"
              :multiple="true"
              :taggable="true"
            ></multiselect>
            <span class="form-text text-muted">
              This overrides the payment options offered to this program's affiliates. To remain the default settings, leave the box empty.<br>
              To add your custom payment methods, click <a href="#/settings/payment">here</a>.
            </span>
          </b-form-group>
          <!--#endregion available payment method -->
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="my-5">
          <h3 class="card-label font-weight-bolder text-dark mb-10">
            Further options:
          </h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Cookie duration:"
            label-for="cookie_days"
          >
            <b-input-group append="days">
              <b-form-input
                min="0"
                type="number"
                id="cookie_days"
                v-model="currentGroup.cookie_days"
              ></b-form-input>
            </b-input-group>
            <span class="form-text text-muted">
              The time period that the system can track the referred orders
              after clicking an affiliate link
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Auto-approve order: "
            label-for="auto_approve_order"
          >
            <b-form-checkbox
              v-model="currentGroup.auto_approve_order"
              id="auto_approve_order"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When applied, referred orders are automatically accepted. Please
              do not select this option if you want to manually approve each
              payment.
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Auto-approve affiliate: "
            label-for="auto_approve_affiliate"
          >
            <b-form-checkbox
              v-model="currentGroup.auto_approve_affiliate"
              id="auto_approve_affiliate"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When not applied, you have to manually accept each affiliate after
              their registration.
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Customer bonus:"
            label-for="customer_reward"
          >
            <b-form-select
              v-model="currentGroup.customer_commission_type"
              :options="customer_rewards"
              id="customer_reward"
            ></b-form-select>
            <span
              v-if="currentGroup.customer_commission_type != null"
              class="form-text text-muted"
            >
              {{
                this.customer_rewards[currentGroup.customer_commission_type]
                  .description
              }}
            </span>
          </b-form-group>
          <b-form-group
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label-align-sm="right"
            label-align-lg="right"
            label="Enter the specified coupon:"
            v-if="currentGroup.customer_commission_type == 2"
          >
            <b-form-input
              type="text"
              required
              v-model="currentGroup.defined_coupon_customer"
            ></b-form-input>
            <span
              v-if="currentGroup.customer_commission_type == 2"
              class="form-text text-muted"
            >
              Create a new coupon on Discount section of your Shopify admin
              first, then fill it in here (This coupon must NOT be assigned to
              any affiliates before)
            </span>
          </b-form-group>
        
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="float-right">
          <b-button
            type="submit"
            variant="primary"
            class="font-weight-bolder"
            :disabled="loading"
          >
            <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
              <inline-svg src="media/svg/icons/General/Save.svg" />
            </span>
            <b-spinner v-if="loading" small></b-spinner>
            Save</b-button
          >
        </div>
      </div>
      <div class="col-xl-1"></div>
    </div>
  </form>
</template>

<script>
import { mapActions } from "vuex";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import Multiselect from "vue-multiselect";
import ApiService from "@/core/services/api.service";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BInputGroup,
  BButton,
  BSpinner,
  BFormCheckbox,
  BInputGroupText,
} from "bootstrap-vue";
export default {
  components: {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    BSpinner,
    BFormCheckbox,
    BButton,
    BInputGroupText,
    Multiselect,
  },
  mixins: [validationMixin],
  data() {
    return {
      loading: false,
      commission_type_prepend: "$",
      customer_commission_type_prepend: "$",
      commission_type_selected: null,
      currentGroup:{},
      commission_type_options: [
        {
          value: null,
          text: "Please select an option",
          disabled: true,
          description: "",
        },
        {
          value: 1,
          text: "Percent of sale",
          description:
            "Commission calculated based on percent of total orders amount",
        },
        {
          value: 2,
          text: "Fixed amount per order",
          description: "Fixed commission from every order",
        },
        {
          value: 3,
          text: "Fixed amount per item",
          description: "Fixed commission from every item",
        },
      ],
      customer_rewards: [
        { value: 0, text: "No bonus", description: "No discounts" },
        {
          value: 1,
          text: "Affiliate coupon",
          description:
            "On checkout, affiliate coupon (coupon of the affiliate whose link is clicked) will be applied automatically.",
        },
        {
          value: 2,
          text: "A specified coupon",
          description:
            "On checkout, the specified coupon will be automatically applied for all customers no matter which affiliate link they visit.",
        },
      ],
      paymentMethodSelected: [],
      listPaymentMethod: [],
    };
  },
  validations: {
    currentGroup: {
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

    loadPaymentMethod() {
      return new Promise((resolve,reject) => {
        ApiService.query("settings/payment-methods?fields=id,name")
          .then(({ data }) => {
            resolve(data);
            // this.listPaymentMedthod = data.data;
            // let paymentMethods = this.currentGroup.paymentMethods;
            // console.log(this.currentGroup);
            // console.log(paymentMethods);
            // this.paymentMethodSelected = this.listPaymentMedthod.filter(function(item){
            //   return paymentMethods.indexOf(item.id) === -1;
            // });
            
          })
          .catch(({ response }) => {
            context.commit(SET_ERROR, response.data.errors);
          });
      })
    },

    submit() {
      this.$v.currentGroup.$touch();
      if (this.$v.currentGroup.$anyError) {
        return;
      }
      this.loading = true;
      const id = this.id;
      const group = this.currentGroup;
      group.payment_methods = this.paymentMethodSelected.map((obj) => obj.id)
      this.updateGroup({ id, group })
        .then((data) => {
          if (data) {
            this.$toast.success("Updated", { position: "bottom" });
            this.loading = false;
          } else {
            this.$toast.error(
              "This coupon does not exist in your Shopify store",
              { position: "bottom" }
            );
            this.loading = false;
          }
        })
        .catch((respon) => {
          if (respon.status == 422) {
            let arrError = Object.keys(respon.data.errors);
            this.$toast.error(respon.data.errors[arrError[0]][0], {
              position: "bottom",
            });
            this.loading = false;
          } else {
            this.$toast.error(respon.data.message, { position: "bottom" });
            this.loading = false;
          }
        });
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.currentGroup[name];
      return $dirty ? !$error : null;
    },
    ...mapActions(["loadGroup","updateGroup"]),
  },

  // props: {
  //   currentGroup: {
  //     type: Object,
  //     required: true,
  //   },
  // },

  computed: {
    id() {
      return this.$route.params.id;
    },

    moneyFormat() {
      return this.$store.getters.moneyFormat;
    },

    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
    priceRule() {
      return this.$store.state.layout.settings.price_rule;
    },
    // optionFilter(){
    //     debugger
    //     let arr = this.listMethodArr;
    //     for (var i = 0; i < this.listMethodArr.length; i++) {
    //         const element = this.listMethodArr[i];
    //         for (var j = 0; j < this.methodSelectedArr.length; j++) {
    //             const el = this.methodSelectedArr[j];
    //             if(el.code === element.code){
    //                 arr = arr.splice(i,1);
    //             }
    //         }
    //     }
    //     return arr
    // }
  },
  mounted(){
    Promise.all([this.loadGroup(this.id),this.loadPaymentMethod()]).then((values) => {
      this.currentGroup = values[0].data;
      this.listPaymentMethod = values[1].data;
      let listPaymentSelectedIds = this.currentGroup.payment_methods;
      console.log(listPaymentSelectedIds);
      if(listPaymentSelectedIds) {
        this.paymentMethodSelected = this.listPaymentMethod.filter(function(item){
              return listPaymentSelectedIds.indexOf(item.id) != -1;
            });
      }
      
    });
    
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.col-form-label {
  padding-right: 25px !important;
}
.multiselect,
.multiselect__input,
.multiselect__single {
  font-family: inherit;
  font-size: 1rem !important;
  touch-action: manipulation;
  font-weight: 400;
}
.multiselect__tag {
  background: #3699ff;
}
.multiselect__tag-icon:after {
  content: "\D7";
  color: #fff;
  font-size: 1rem;
}
.multiselect__tag-icon:hover {
  background: #2485e6;
}
.multiselect__option--highlight {
  background: #3699ff;
  outline: none;
  color: #fff;
}
.multiselect__option--selected.multiselect__option--highlight {
  background: #585858;
  color: #fff;
}
.multiselect__option--selected {
  background: #f3f3f3;
  color: #35495e;
  font-weight: 500;
}
.multiselect__option::after {
  content: none !important;
}
</style>
