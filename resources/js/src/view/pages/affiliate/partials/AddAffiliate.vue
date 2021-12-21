<template>
  <div>
    <b-modal
      size="xl"
      hide-footer
      ref="addAffiliateModal"
      body-bg-variant="light"
      title="Add affiliate"
      @hide="closeModalAddAffiliate"
    >
      <alert-note :type="'alert-white alert-shadow'">
        Manually add each affiliate by filling their basic information.
      </alert-note>
      <div class="card card-custom card-stretch gutter-b">
        <div class="card-body">
          <label for="input-coupon-code-4">Program</label>
          <select
            class="form-control form-control-custom"
            v-model="$v.form.groupSelected.$model"
          >
            <option
              v-for="(o, index) in groupSelectedOptions"
              :value="o.value"
              :o="o"
              :key="index"
            >
              {{ o.text }}
            </option>
          </select>

          <div class="row">
            <div class="col-md-6">
              <label for="input-coupon-code" class="mt-4">First name:</label>
              <b-form-input
                id="input-coupon-code"
                aria-describedby="input-live-feedback"
                v-model="$v.form.firstName.$model"
                :state="validateState('firstName')"
                trim
              ></b-form-input>
              <b-form-invalid-feedback id="input-live-feedback">
                First name is required!
              </b-form-invalid-feedback>
            </div>
            <div class="col-md-6">
              <label for="input-coupon-code-last" class="mt-4"
                >Last name:</label
              >
              <b-form-input
                id="input-coupon-code-last"
                aria-describedby="input-live-feedback-1"
                v-model="$v.form.lastName.$model"
                :state="validateState('lastName')"
                trim
              ></b-form-input>
              <b-form-invalid-feedback id="input-live-feedback-1">
                Last name is required!
              </b-form-invalid-feedback>
            </div>
          </div>

          <label for="input-coupon-code-1" class="mt-4">Email</label>
          <b-form-input
            id="input-coupon-code-1"
            aria-describedby="input-live-feedback-2"
            v-model="$v.form.email.$model"
            :state="validateState('email')"
            trim
          ></b-form-input>
          <b-form-invalid-feedback id="input-live-feedback-2">
            The email must be a valid email address!
          </b-form-invalid-feedback>
          <div class="mt-0">
            <label for="input-coupon-code-pass" class="mt-4">Password</label>
            <b-form-select
              class="mb-4"
              v-model="form.passwordType"
              :options="password_type_options"
              id="discount_type"
            ></b-form-select>
            <b-form-input
              v-if="form.passwordType"
              id="input-coupon-code-pass"
              aria-describedby="input-live-feedback-5"
              v-model="$v.form.password.$model"
              :state="validateState('password')"
              trim
              placeholder="Enter password"
            ></b-form-input>
            <b-form-invalid-feedback
              v-if="form.passwordType"
              id="input-live-feedback-5"
            >
              Password must have at least 6 letters!
            </b-form-invalid-feedback>
            <span
              v-if="form.passwordType"
              class="text-muted font-weight-bold font-size-sm mt-2"
              >The affiliates will use this default password as their temporary
              password.</span
            >
            <div class="checkbox-inline mt-4">
              <label class="checkbox checkbox-outline checkbox-success">
                <input
                  type="checkbox"
                  v-model="form.isSendNotification"
                  value="true"
                />
                <span></span>
                Send invitation email.
              </label>
            </div>
            <span class="text-muted font-weight-bold font-size-sm"
              >When selected, an invitation email with a log in link and
              password will be sent to an affiliate.</span
            >
            <div class="w-100 mt-3 mb-10">
              <ImageButton
                :loading="submitLoading1"
                @click="saveAffiliate"
                class="float-right"
              >
                Save
              </ImageButton>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import {
  BFormInput,
  BFormInvalidFeedback,
  BFormSelect,
  BFormCheckbox,
} from "bootstrap-vue";
import { mapGetters } from "vuex";
import { validationMixin } from "vuelidate";
import { required, email, minLength } from "vuelidate/lib/validators";
import ApiService from "@/core/services/api.service";
export default {
  data() {
    return {
      show: true,
      submitLoading1: false,
      form: {
        isSendNotification: true,
        groupSelected: "",
        firstName: "",
        lastName: "",
        email: "",
        password: "",
        passwordType: 1,
      },
      password_type_options: [
        { value: 0, text: "Auto generated password" },
        { value: 1, text: "Set default password" },
      ],
    };
  },
  mixins: [validationMixin],
  components: {
    ImageButton,
    BFormInput,
    BFormInvalidFeedback,
    BFormSelect,
    BFormCheckbox,
  },
  computed: {
    ...mapGetters(["groups"]),
    groupSelectedOptions() {
      let options = [];
      for (let i = 0; i < this.groups.length; i++) {
        if (this.groups[i].is_active==1) {
          if (this.groups[i].is_default) {
            this.form.groupSelected = this.groups[i].id;
            options.push({
              text: this.groups[i].name + " (Default Program)",
              value: this.groups[i].id,
            });
          } else {
            options.push({
              text: this.groups[i].name,
              value: this.groups[i].id,
            });
          }
        }
      }
      return options;
    },
  },
  validations: {
    form: {
      firstName: {
        required,
      },
      lastName: {
        required,
      },
      email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(6),
      },
      groupSelected: {
        required,
      },
    },
  },
  methods: {
    onReset() {
      // Reset our form values
      this.form.firstName = "";
      this.form.lastName = "";
      this.form.email = null;
      this.form.password = "";
      this.form.passwordType = 1;
      this.form.isSendNotification = true;
      // Trick to reset/clear native browser form validation state
      this.$v.$reset();
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    saveAffiliate() {
      this.submitLoading1 = true;
      this.form.isSendNotification
        ? (this.form.isSendNotification = 1)
        : (this.form.isSendNotification = 0);
      if (this.form.passwordType == 0) {
        this.form.password = "123456";
      }
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.submitLoading1 = false;
        return;
      }
      let resource = `register`;
      ApiService.post(resource, this.form)
        .then(({ data }) => {
          this.onReset();
          this.$toast.success("Added", { position: "bottom" });
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            let arrError = Object.keys(response.data.errors);
            if (arrError.length) {
              this.$toast.error(response.data.errors[arrError[0]][0], {
                position: "bottom",
              });
            } else {
              this.$toast.error(response.data.message, { position: "bottom" });
            }
          }
        })
        .finally(() => {
          this.submitLoading1 = false;
        });
    },
    closeModalAddAffiliate() {
      this.$emit("addAffiliateSuccess");
    },
  },
};
</script>
