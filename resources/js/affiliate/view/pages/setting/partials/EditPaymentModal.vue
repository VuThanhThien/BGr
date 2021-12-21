<template>
  <b-modal
    ref="edit_payment_modal"
    size="md"
    id="edit_payment_modal"
    :title="getTextLocale('payment_mode')"
    @show="resetModal"
  >
    <div>
      <b-form-select
        @change="selectMethod"
        v-model="methodSelected"
        :options="method_options"
      ></b-form-select>
      <vue-form-generator
        v-if="methodSelected != 'discount_coupon'"
        id="form-generate"
        @validated="onValid"
        ref="vForm"
        :schema="schema"
        :model="model"
        :isNewModel="true"
        :options="formOptions"
        tag="div"
      >
      </vue-form-generator>
    </div>
    <template #modal-footer>
      <div class="w-100">
        <LoadingSubmitButton
          :loading="loading"
          @click="submit"
          class="float-right mt-10"
        >
          {{ $t("save") }}
        </LoadingSubmitButton>
      </div>
    </template>
  </b-modal>
</template>

<script>
import { mapState } from "vuex";
import VueFormGenerator from "vue-form-generator";
import ApiService from "aff/core/services/api.service";
import LoadingSubmitButton from "aff/view/content/LoadingSubmitButton.vue";
import { BFormSelect } from "bootstrap-vue";
export default {
  props: ["currentUser"],
  components: {
    "vue-form-generator": VueFormGenerator.component,
    LoadingSubmitButton,
    BFormSelect,
  },
  data() {
    return {
      loading: false,
      model: null,
      schema: null,
      methodSelected: null,
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: false,
        validateAsync: true,
      },
      isValid: null,
      errors: null,
    };
  },

  computed: {
    ...mapState({
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
      setting: (state) => state.layout.affiliateSetting,
    }),

    method_options() {
      let options = [];
      if (this.paymentMethodAvailable) {
        for (var i = 0; i < this.paymentMethodAvailable.length; i++) {
          options.push({
            value: this.paymentMethodAvailable[i].key,
            text: this.paymentMethodAvailable[i].name,
          });
        }
      }
      return options;
    },
  },
  methods: {
    onValid(vld, err) {
      this.isValid = vld;
      this.errors = err;
      // console.log("Validation result: ", vld, ", Errors:", JSON.stringify(err));
    },
    submit() {
      if (this.model) {
        this.$refs.vForm.validate();
      }
      else
      {
        this.isValid = true;
      }
      if (this.isValid) {
        this.loading = true;
        let payload = {
          method: this.methodSelected,
          payment_info: this.model,
        };
        ApiService.post("settings/update-payment-method", payload)
          .then(({ data }) => {
            this.$store.commit("UPDATE_PAYMENT", data.data);
            this.$toast.success("Payment setting updated", {
              position: "bottom",
            });
            this.$refs.edit_payment_modal.hide();
            this.loading = false;
          })
          .catch(({ response }) => {
            context.commit(SET_ERROR, response.data.errors);
          });
      }
    },
    selectMethod(value) {
      let fields = JSON.parse(
        this.paymentMethodAvailable.find((method) => {
          return method.key == value;
        }).fields
      );
      if (fields) {
        let model = [];
        this.schema = {
          fields: fields,
        };

        this.updateModel(fields);
      } else {
        this.model = null;
      }
    },
    updateModel(fields) {
      let model = [];
      let paymentInfo = this.currentUser.payment_info;
      for (var i = 0; i < fields.length; i++) {
        let val = "";
        if (paymentInfo) {
          val = paymentInfo[fields[i].model];
        }
        model.push([fields[i].model, val]);
      }
      this.model = Object.fromEntries(model);
    },
    resetModal() {
      if (this.currentUser.payment_method) {
        this.methodSelected = this.currentUser.payment_method;
        let currentMethod = this.paymentMethodAvailable.find((method) => {
          return method.key == this.currentUser.payment_method;
        });
        if (currentMethod.fields) {
          this.schema = {
            fields: JSON.parse(currentMethod.fields),
          };
          this.updateModel(JSON.parse(currentMethod.fields));
        }
      }
    },
  },
};
</script>

<style>
.field-box {
  padding: 25px;
  background: #f3f3f3;
}
#form-generate {
  background: #f3f6f9;
  margin-top: 15px;
  padding: 15px;
}
#form-generate .wrapper {
  padding: 0px !important;
  width: 100%;
}
#form-generate .form-group.required label::after {
    content: "*";
    font-weight: normal;
    color: red;
    font-size: 1em;
}
.form-preview-box {
  padding: 20px;
  background: #f3f6f9;
}
</style>
