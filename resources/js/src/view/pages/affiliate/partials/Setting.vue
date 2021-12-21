<template>
  <div id="kt_content_container">
    <div class="card mb-5 mb-xl-10">
      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details"
        aria-expanded="true"
        aria-controls="kt_account_profile_details"
      >
        <div class="card-title m-0">
          <h3 class="fw-bolder m-0">Profile Details</h3>
        </div>
      </div>
      <div id="kt_account_profile_details" class="collapse show">
        <div class="card-body border-top p-9">
          <form id="kt_first_last_name" class="form" novalidate="novalidate">
            <div class="row mb-6">
              <label class="col-lg-4 col-form-label required fw-bold fs-6"
                >Full Name</label
              >
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-6 fv-row">
                    <b-form-input
                      type="text"
                      name="fname"
                      class="
                        form-control form-control-lg form-control-solid
                        mb-3 mb-lg-0
                      "
                      v-model="$v.currentUser.first_name.$model"
                      placeholder="First Name"
                      :state="validateProfileState('first_name')"
                    >
                    </b-form-input>
                  </div>
                  <div class="col-lg-6 fv-row">
                    <b-form-input
                      type="text"
                      name="lname"
                      class="form-control form-control-lg form-control-solid"
                      v-model="$v.currentUser.last_name.$model"
                      placeholder="Last Name"
                      :state="validateProfileState('last_name')"
                    >
                    </b-form-input>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              <span class="required">Contact Phone</span>
            </label>
            <div class="col-lg-8 fv-row">
              <input
                type="tel"
                name="phone"
                class="form-control form-control-lg form-control-solid"
                placeholder="Phone Number"
                v-model="currentUser.phone"
              />
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              <span class="required">Country</span>
            </label>
            <div class="col-lg-8 fv-row">
              <select
                name="country"
                aria-label="Select a Country"
                data-control="select2"
                class="form-control form-control-solid form-select-sm fw-bold"
                v-model="currentUser.country"
              >
                <option value="null">Select a country</option>
                <option
                  v-for="c in this.countryArray"
                  :key="c.id"
                  :value="c.id"
                >
                  {{ c.name }}
                </option>
              </select>
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Facebook</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder="Facebook profile link"
                value="Keenthemes"
                v-model="currentUser.facebook"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Youtube</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder="Youtube channel link"
                value="Keenthemes"
                v-model="currentUser.youtube"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Instagram</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder="Instagram profile"
                value="Keenthemes"
                v-model="currentUser.instagram"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Twitter</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder="Twitter profile"
                value="Keenthemes"
                v-model="currentUser.twitter"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Website</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                value="Keenthemes"
                v-model="currentUser.website"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Address</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                value="Keenthemes"
                v-model="currentUser.address"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >City</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                value="Keenthemes"
                v-model="currentUser.city"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >Zipcode</label
            >
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                name="company"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                value="Keenthemes"
                v-model="currentUser.zipcode"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6" v-for="(info, index) in currentUser.additional_infos">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              info.label
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                v-model="info.value"
                :key="index"
              >
              </b-form-input>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <LoadingSubmitButton
            :loading="submitLoading"
            @click="submitProfile"
            class="float-right mt-10"
          >
            Save
          </LoadingSubmitButton>
        </div>
      </div>
    </div>
    <div class="card mb-5 mb-xl-10">
      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#kt_account_connected_accounts"
        aria-expanded="true"
        aria-controls="kt_account_connected_accounts"
      >
        <div class="card-title m-0">
          <h3 class="fw-bolder m-0">Payment Settings</h3>
        </div>
      </div>
      <div id="kt_account_connected_accounts" class="collapse show">
        <div class="card-body border-top p-9">
          <div
            v-if="currentUser.payment_method"
            class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6"
          >
            <div class="d-flex flex-column py-2">
              <div class="d-flex align-items-center">
                <img
                  src="/metronic8/demo1/assets/media/svg/card-logos/mastercard.svg"
                  alt=""
                  class="me-4"
                />
                <div>
                  <div class="fs-4 fw-bolder mb-5">
                    <span
                      class="
                        label label-lg label-light-success label-inline
                        text
                        font-weight-bolder
                      "
                      >{{ currentMethod.name }}</span
                    >
                  </div>

                  <div
                    v-for="(field, i) in currentMethod.fields"
                    :field="field"
                    :key="i"
                    class="fs-6 fw-bolder text-gray-600"
                  >
                    <span class="font-weight-bolder">{{ field.label }}</span> :
                    <span class="text-muted">{{
                      mapFieldMethodValue(field.model)
                    }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="btn py-2" style="margin-left: auto">
              <button
                @click="deletePaymentMethod()"
                type="reset"
                class="
                  btn btn-md btn-white btn-active-light-primary
                  me-3
                  text-danger
                "
              >
                Delete
              </button>
              <button
                v-b-modal.edit_payment_modal
                class="btn btn-md btn-light btn-active-light-primary"
              >
                Edit
              </button>
            </div>
          </div>
          <button
            v-b-modal.edit_payment_modal
            type="button"
            class="btn btn-secondary mr-2"
            v-if="!currentUser.payment_method"
          >
            Setup
          </button>
        </div>
      </div>
      <EditPaymentModal
        :paymentMethodAvailable="paymentMethodAvailable"
        :currentUser="currentUser"
        @updateMethodSuccess="updateMethodSuccess"
      ></EditPaymentModal>
    </div>
    <div class="card mb-5 mb-xl-10">
      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#kt_account_signin_method"
      >
        <div class="card-title m-0">
          <h3 class="fw-bolder m-0">Sign-in Method</h3>
        </div>
      </div>
      <div id="kt_account_signin_method" class="collapse show">
        <div class="card-body border-top p-9">
          <div class="d-flex flex-wrap align-items-center">
            <div id="kt_signin_email">
              <div class="fs-6 font-weight-bolder mb-1">Email Address</div>
              <div class="fw-bold text-gray-600">
                <span v-if="currentUser">{{ currentUser.email }}</span>
              </div>
            </div>
          </div>
          <div class="separator separator-dashed my-6"></div>
          <div class="d-flex flex-wrap align-items-center mb-10">
            <div id="kt_signin_password" v-if="!showChangePassword">
              <div class="fs-6 font-weight-bolder mb-1">Password</div>
              <div class="fw-bold text-gray-600">************</div>
            </div>
            <div
              v-if="showChangePassword"
              id="kt_signin_password_edit"
              class="flex-row-fluid"
            >
              <form
                id="kt_signin_change_password"
                class="form"
                novalidate="novalidate"
              >
                <div class="row mb-1">
                  <div class="col-lg-4">
                    <div class="fv-row mb-0">
                      <label
                        for="currentpassword"
                        class="form-label fs-6 fw-bolder mb-3"
                        >Current Password</label
                      >
                      <b-form-input
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        name="currentpassword"
                        id="currentpassword"
                        v-model="$v.passwordForm.current_password.$model"
                        :state="validateState('current_password')"
                      >
                      </b-form-input>
                      <b-form-invalid-feedback
                        v-if="!$v.passwordForm.current_password.required"
                      >
                        Password confirmation is required.
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="fv-row mb-0">
                      <label
                        for="newpassword"
                        class="form-label fs-6 fw-bolder mb-3"
                        >New Password</label
                      >
                      <b-form-input
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        name="newpassword"
                        id="newpassword"
                        v-model="$v.passwordForm.password.$model"
                        :state="validateState('password')"
                      >
                      </b-form-input>
                      <b-form-invalid-feedback
                        v-if="!$v.passwordForm.password.required"
                      >
                        Password confirmation is required.
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="fv-row mb-0">
                      <label
                        for="confirmpassword"
                        class="form-label fs-6 fw-bolder mb-3"
                        >Confirm New Password</label
                      >
                      <b-form-input
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        name="confirmpassword"
                        id="confirmpassword"
                        v-model="$v.passwordForm.password_confirmation.$model"
                        :state="validateState('password_confirmation')"
                        aria-describedby="password_confirmation-live-feedback"
                      >
                      </b-form-input>
                      <b-form-invalid-feedback
                        v-if="!$v.passwordForm.password_confirmation.required"
                      >
                        Password confirmation is required.
                      </b-form-invalid-feedback>
                      <b-form-invalid-feedback
                        v-if="
                          !$v.passwordForm.password_confirmation.sameAsPassword
                        "
                      >
                        The password and confirmation password do not match.
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                </div>
                <div class="form-text mb-5">
                  Password must be at least 8 character and contain symbols
                </div>
                <div class="d-flex">
                  <button
                    type="button"
                    class="btn btn-primary me-2 px-6"
                    @click="updatePasswordSubmit"
                    ref="submit_change_password_btn"
                  >
                     <b-spinner v-if="loadingButton" small></b-spinner>
                    Update Password
                  </button>
                  <button
                    type="button"
                    class="btn btn-color-gray-400 btn-light-primary px-6 ml-3"
                    @click="showChangePassword = !showChangePassword"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>
            <div
              v-if="!showChangePassword"
              id="kt_signin_password_button"
              class="ms-auto"
            >
              <button
                @click="showChangePassword = !showChangePassword"
                class="btn btn-light btn-light-primary"
              >
                Reset password
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingSubmitButton from "aff/view/content/LoadingSubmitButton.vue";
import EditPaymentModal from "@/view/pages/affiliate/partials/EditPaymentModal.vue";
import ApiService from "@/core/services/api.service";
import { mapGetters, mapState } from "vuex";
import { validationMixin } from "vuelidate";
import { required, minLength, sameAs } from "vuelidate/lib/validators";
import swal from "sweetalert2";
window.Swal = swal;
import { BFormInput, BFormInvalidFeedback,BSpinner } from "bootstrap-vue";
import { country } from "@/core/config/country";
export default {
  mixins: [validationMixin],
  data() {
    return {
      showChangePassword: false,
      submitLoading: false,
      passwordForm: {
        current_password: "",
        password: "",
        password_confirmation: "",
      },
      loadingButton: false
    };
  },

  components: {
    EditPaymentModal,
    LoadingSubmitButton,
    BFormInput,
    BFormInvalidFeedback,
    BSpinner
  },

  computed: {
    id() {
      return this.$route.params.id;
    },
    currentUser() {
      return this.$store.getters.currentDetailAffiliate;
    },
    ...mapState({
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
      userChange: (state) => state.affiliate.currentDetailAffiliate,
    }),
    currentMethod() {
      let method = this.paymentMethodAvailable.find((method) => {
        return method.key == this.currentUser.payment_method;
      });
      return method ? method : { name: "", fields: "[]" };
    },
    countryArray() {
      return country;
    },
  },

  methods: {
    mapFieldMethodValue(key) {
      return this.currentUser.payment_info[key];
    },

    updatePasswordSubmit() {
      this.$v.passwordForm.$touch();
      if (this.$v.passwordForm.$anyError) {
        return;
      }
      // set spinner to submit button
    //   const submitButton = this.$refs["submit_change_password_btn"];
    //   submitButton.classList.add("spinner", "spinner-light", "spinner-right");
        this.loadingButton = true;
      ApiService.post(
        `/affliates/${this.id}/update-password`,
        this.passwordForm
      )
        .then(({ data }) => {
          this.showChangePassword = !this.showChangePassword;
        //   submitButton.classList.remove(
        //     "spinner",
        //     "spinner-light",
        //     "spinner-right"
        //   );
          this.$toast.success("Updated", { position: "bottom" });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        //   submitButton.classList.remove(
        //     "spinner",
        //     "spinner-light",
        //     "spinner-right"
        //   );
        }).finally(()=>{
            this.loadingButton = false;
        });
    },

    submitProfile() {
      this.$v.currentUser.$touch();
      this.submitLoading = true;
      if (this.$v.currentUser.$invalid) {
        this.submitLoading = false;
        return;
      } else {
        ApiService.post(
          `/affiliates/${this.id}/update-profile`,
          this.currentUser
        ).then(({ data }) => {
          this.submitLoading = false;
          this.$toast.success("Updated", { position: "bottom" });
        });
      }
    },

    validateState(name) {
      const { $dirty, $error } = this.$v.passwordForm[name];
      return $dirty ? !$error : null;
    },
    validateProfileState(name) {
      const { $dirty, $error } = this.$v.currentUser[name];
      return $dirty ? !$error : null;
    },

    deletePaymentMethod() {
      Swal.fire({
        title: "Are you sure?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          let payload = {
            method: null,
            payment_info: null,
          };
          ApiService.post(
            `/affliates/${this.id}/update-payment-method`,
            payload
          )
            .then(({ data }) => {
              this.userChange.payment_method = payload.method;
              this.userChange.payment_info = payload.payment_info;
              this.$toast.success("Updated", {
                position: "bottom",
              });
            })
            .catch(({ response }) => {});
        }
      });
    },
    updateMethodSuccess(payload) {
      this.userChange.payment_method = payload.method;
      this.userChange.payment_info = payload.payment_info;
    },
  },

  mounted() {},
  validations: {
    passwordForm: {
      current_password: {
        required,
      },
      password: {
        required,
        minLength: minLength(8),
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs("password"),
      },
    },
    currentUser: {
      first_name: {
        required,
      },
      last_name: {
        required,
      },
    },
  },
};
</script>

<style scoped>
#kt_signin_password_button {
  margin-left: auto;
}
</style>
