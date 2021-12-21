<template>
  <div id="kt_content_container" class="container">
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
          <h3 class="fw-bolder m-0">{{ $t("profile_details") }}</h3>
        </div>
      </div>
      <div id="kt_account_profile_details" class="collapse show">
        <div class="card-body border-top p-9">
        <form
        id="kt_first_last_name"
        class="form"
        novalidate="novalidate"
        >
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("full_name")
            }}</label>
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
                    :placeholder="getPlaceholder('first_name')"
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
                    :placeholder="getPlaceholder('last_name')"
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
              <span class="required">{{ $t("contact_phone") }}</span>
            </label>
            <div class="col-lg-8 fv-row">
              <input
                type="tel"
                name="phone"
                class="form-control form-control-lg form-control-solid"
                :placeholder="getPlaceholder('phone_number')"
                v-model="currentUser.phone"
              />
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              <span class="required">{{ $t("country") }}</span>
            </label>
            <div class="col-lg-8 fv-row">
              <select
                name="country"
                aria-label="Select a Country"
                data-control="select2"
                class="form-control form-control-solid form-select-sm fw-bold"
                v-model="currentUser.country"
              >
                <option value="null">{{ $t("select_a_country") }}</option>
                <option v-for="c in this.countryArray" :key="c.id" :value="c.id">
                  {{c.name}}
                </option>
              </select>
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("facebook")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                :placeholder="getPlaceholder('facebook_profile_link')"
                v-model="currentUser.facebook"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("youtube")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                :placeholder="getPlaceholder('youtube_channel_link')"
                v-model="currentUser.youtube"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("instagram")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                :placeholder="getPlaceholder('instagram_profile')"
                v-model="currentUser.instagram"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("twitter")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                :placeholder="getPlaceholder('twitter_profile')"
                v-model="currentUser.twitter"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("website")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                v-model="currentUser.website"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("address")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                v-model="currentUser.address"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("city")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
                v-model="currentUser.city"
              >
              </b-form-input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{
              $t("zipcode")
            }}</label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="text"
                class="form-control form-control-lg form-control-solid"
                placeholder=""
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
            {{ $t("save") }}
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
          <h3 class="fw-bolder m-0">{{ $t("payment_settings") }}</h3>
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
                    v-for="(field, i) in JSON.parse(currentMethod.fields)"
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
                {{$t('delete')}}
              </button>
              <button
                v-b-modal.edit_payment_modal
                class="btn btn-md btn-light btn-active-light-primary"
              >
                {{$t('edit')}}
              </button>
            </div>
          </div>
          <button
            v-b-modal.edit_payment_modal
            type="button"
            class="btn btn-secondary mr-2"
            v-if="!currentUser.payment_method"
          >
            {{ $t("setup") }}
          </button>
        </div>
      </div>
      <EditPaymentModal
        :paymentMethodAvailable="paymentMethodAvailable"
        :currentUser="currentUser"
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
          <h3 class="fw-bolder m-0">{{ $t("sign_in_method") }}</h3>
        </div>
      </div>
      <div id="kt_account_signin_method" class="collapse show">
        <div class="card-body border-top p-9">
          <div class="d-flex flex-wrap align-items-center">
            <div id="kt_signin_email">
              <div class="fs-6 font-weight-bolder mb-1">
                {{ $t("email_address") }}
              </div>
              <div class="fw-bold text-gray-600">
                <span v-if="currentUser">{{ currentUser.email }}</span>
              </div>
            </div>
          </div>
          <div class="separator separator-dashed my-6"></div>
          <div class="d-flex flex-wrap align-items-center mb-10">
            <div id="kt_signin_password" v-if="!showChangePassword">
              <div class="fs-6 font-weight-bolder mb-1">
                {{ $t("password") }}
              </div>
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
                        >{{ $t("current_password") }}</label
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
                        {{ $t("newpass_required") }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="fv-row mb-0">
                      <label
                        for="newpassword"
                        class="form-label fs-6 fw-bolder mb-3"
                        >{{ $t("new_password") }}</label
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
                        {{ $t("newpass_required") }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="fv-row mb-0">
                      <label
                        for="confirmpassword"
                        class="form-label fs-6 fw-bolder mb-3"
                        >{{ $t("confirm_new_password") }}</label
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
                        {{ $t("newpass_required") }}
                      </b-form-invalid-feedback>
                      <b-form-invalid-feedback
                        v-if="
                          !$v.passwordForm.password_confirmation.sameAsPassword
                        "
                      >
                        {{ $t("pass_not_match") }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>
                </div>
                <div class="form-text mb-5">
                  {{ $t("reset_password_requirement") }}
                </div>
                <div class="d-flex">
                  <button
                    type="button"
                    class="btn btn-primary me-2 px-6"
                    @click="updatePasswordSubmit"
                    ref="submit_change_password_btn"
                  >
                   <b-spinner v-if="loadingButton" small></b-spinner>
                    {{ $t("update_password") }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-color-gray-400 btn-light-primary px-6 ml-3"
                    @click="showChangePassword = !showChangePassword"
                  >
                    {{ $t("cancel") }}
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
                {{ $t("reset_password") }}
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
import EditPaymentModal from "aff/view/pages/setting/partials/EditPaymentModal.vue";
import ApiService from "@/core/services/api.service";
import { mapState } from "vuex";
import { validationMixin } from "vuelidate";
import { required, minLength, sameAs } from "vuelidate/lib/validators";
import swal from "sweetalert2";
window.Swal = swal;
import { BFormInput, BFormInvalidFeedback, BSpinner } from "bootstrap-vue";
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
    ...mapState({
      currentUser: (state) => state.auth.user,
      paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
    }),
    currentMethod() {
      let method = this.paymentMethodAvailable.find((method) => {
        return method.key == this.currentUser.payment_method;
      });
      return method ? method : { name: "", fields: "[]" };
    },
    countryArray(){
        return country;
    }
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
      // const submitButton = this.$refs["submit_change_password_btn"];
      // submitButton.classList.add("spinner", "spinner-light", "spinner-right");
      this.loadingButton = true;
      ApiService.post("settings/update-password", this.passwordForm).then(
        ({ data }) => {
          this.showChangePassword = !this.showChangePassword;
          // submitButton.classList.remove("spinner","spinner-light","spinner-right");
          this.$toast.success(this.$t('password_updated'), { position: "bottom" });
        }
      ).catch(({response}) => {
         this.$toast.error(response.data.message, { position: "bottom" });
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
      }
        else{
            ApiService.post("settings/update-profile", this.currentUser).then(
                ({ data }) => {
                this.submitLoading = false;
                this.$toast.success(this.$t('profile_updated'), { position: "bottom" });
                }
            );
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
        title: this.$t('are_u_sure'),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.$t('yes_delete'),
        cancelButtonText: this.$t('cancel'),
      }).then((result) => {
        if (result.isConfirmed) {
          let payload = {
            method: null,
            payment_info: null,
          };
          ApiService.post("settings/update-payment-method", payload)
            .then(({ data }) => {
              this.$store.commit("UPDATE_PAYMENT", data.data);
              this.$toast.success(this.$t('payment_setting_updated'), {
                position: "bottom",
              });
            })
            .catch(({ response }) => {
              context.commit(SET_ERROR, response.data.errors);
            });
        }
      });
    },
    getPlaceholder(key) {
      return this.$t(key);
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
