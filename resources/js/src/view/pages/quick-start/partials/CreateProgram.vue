<template>
  <form @submit.stop.prevent="submit" class="form" id="kt_form">
    <div class="row">
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="my-5">
          <h3 class="text-primary font-weight-bold mb-10">
            General Information:
          </h3>
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
              v-model="$v.group.commission_type.$model"
              :state="validateState('commission_type')"
              :options="commission_type_options"
              id="commission_type"
            ></b-form-select>
            <span v-if="group.commission_type" class="form-text text-muted">
              {{this.commission_type_options[group.commission_type].description}}
            </span>
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
            <b-input-group
              :prepend="
                $v.group.commission_type.$model == 1 ? '%' : symbolCurrency
              "
            >
              <b-form-input
                min="0"
                type="number"
                step="0.01"
                id="commission_amount"
                value="0"
                v-model="$v.group.commission_amount.$model"
                :state="validateState('commission_amount')"
              ></b-form-input>
            </b-input-group>
          </b-form-group>
          <h3 class="text-primary font-weight-bold mb-10">Commission calculation:</h3>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Include discounts: "
            label-for="include_discounts"
          >
            <b-form-checkbox
              v-model="group.include_discounts"
              id="include_discounts"
              switch
              size="lg"
              value=1
              unchecked-value=0
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting total discount from the order (lower commission when applied)
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Exclude shipping: "
            label-for="exclude_shipping"
          >
            <b-form-checkbox
              v-model="group.exclude_shipping"
              id="exclude_shipping"
              switch
              size="lg"
              value=1
              unchecked-value=0
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting shipping fee from the order (lower commission when applied)
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Exclude taxes: "
            label-for="exclude_taxes"
          >
            <b-form-checkbox
              v-model="group.exclude_tax"
              id="exclude_taxes"
              switch
              size="lg"
              value=1
              unchecked-value=0
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              Commission is calculated after subtracting taxes from the order (lower commission when applied)
            </span>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Cookie duration:"
            label-for="cookie_days"
          >
            <b-input-group append="days">
              <b-form-input
                min="0"
                type="number"
                id="cookie_days"
                v-model="group.cookie_days"
              ></b-form-input>
            </b-input-group>
            <span class="form-text text-muted">
              The time period that the system can track the referred orders after clicking an affiliate link
            </span>
          </b-form-group>
        </div>
      </div>
      <div class="col-xl-1"></div>
    </div>
  </form>
</template>

<script>
import { validationMixin } from "vuelidate";
import { mapActions } from "vuex";
import { required, minLength } from "vuelidate/lib/validators";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BInputGroup,
  BButton,
  BFormCheckbox
} from "bootstrap-vue";
export default {
  components: {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    BButton,
    BFormCheckbox
  },
  mixins: [validationMixin],
  props: {
    group: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      commission_type_prepend: "$",
      customer_commission_type_prepend: "$",
      commission_type_selected: null,
      loading: false,
            commission_type_options: [
        { value: null, text: "Please select an option", disabled: true, description:"" },
        { value: 1, text: "Percent of sale", description: "Commission calculated based on percent of total orders amount" },
        { value: 2, text: "Fixed amount per order", description: "Fixed commission from every order" },
        { value: 3, text: "Fixed amount per item", description: "Fixed commission from every item" },
      ],
      customer_rewards: [
        { value: 0, text: "No bonus", description: "No bonus" },
        { value: 1, text: "Percent of sale", description:"Percent discount from order" },
        { value: 2, text: "Fixed amount per order", description:"Fixed amount discount from order" }
      ]
    };
  },
  computed: {
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
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
    ...mapActions(["updateGroup"]),
    // selectCommssionType(value) {
    //   if (value === 1) {
    //     this.commission_type_prepend = "%";
    //   } else {
    //     this.commission_type_prepend = "$";
    //   }
    // },
    submit() {
      this.$v.group.$touch();
      if (this.$v.group.$anyError) {
        return;
      }
      const id = this.group.id;
      const group = this.group;
      this.updateGroup({ id, group })
        .then(() => {
          this.loading = false;
        })
        .catch((respon) => {
          this.$toast.error(respon.data.message, { position: "bottom" });
          this.loading = false;
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
.form-control {
  height: inherit !important;
}
</style>>

