<template>
  <div>

    <!--#region css ẩn -->
    <component is="style">
    body {background: {{loginLayoutData.form.bgMain}}}
    .login-form {width: 100%; max-width: {{ loginLayoutData.size.width || 450 }}px;}
    </component>
    <div class="d-none">
    <font-picker
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="loginLayoutData.form.fontFamily"
        :options="titleLogin"
    >
    </font-picker>
    </div>
    <component is="style">
        {{ loginLayoutData.customcss}}
    </component>
    <!-- #endregion css ẩn -->

    <!-- loader -->
    <div v-if="loading && isExist" style="height: auto">
      <Loader />
    </div>
    <!-- login main -->
    <div
      v-else-if="!loading && isExist"
      id="kt_body">
      <!--begin::Main-->
      <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-center flex-row-fluid apply-font-titleLogin" id="kt_login">
            <div
            class="login-form text-center position-relative overflow-hidden"
            :style="{'margin-top': (loginLayoutData.size.mt || 0) + 'px' }"
            >
            <!-- #region logo -->
            <div
            class="d-flex flex-center logo-wrapper"
            v-if="logo && loginLayoutData.logo.isShow"
            :style="{'margin-bottom': (loginLayoutData.size.gapImg || 0) + 'px' }">
                <img :src="logo"
                style="height: auto"
                :style="{ width: (loginLayoutData.logo.width + '%')|| '75px' }"/>
            </div>
            <!-- #endregion logo -->

            <!--#region Login Sign in form-->
            <div
            class="login-signin mb-20"
            :style="{
            'padding': (loginLayoutData.size.py + 'px ' +loginLayoutData.size.px + 'px')|| '24px 45px',
            'background': loginLayoutData.form.bg,
            'border-radius': loginLayoutData.form.borderRadius + 'px'
            }"
            >
            <div class="mb-20 title-wrapper">
                <h3
                class="title-text"
                :style="{'color': loginLayoutData.form.titleColor, 'font-size': loginLayoutData.form.fontSizeTitle + 'px'}" >
                {{ $t("SIGN_IN") }}
                </h3>
                <div class="text-muted font-weight-bold subtitle">
                {{ $t("enter_acc") }}
                </div>
            </div>
            <b-form @submit.stop.prevent="onSubmit">
                <div
                v-if="this.errorMsg"
                class="
                    d-flex
                    align-items-center
                    bg-light-danger
                    rounded
                    p-5
                    mb-9
                "
                >
                <span class="svg-icon svg-icon-danger mr-5">
                    <span class="svg-icon svg-icon-lg">
                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="24px"
                        height="24px"
                        viewBox="0 0 24 24"
                        version="1.1"
                    >
                        <g
                        stroke="none"
                        stroke-width="1"
                        fill="none"
                        fill-rule="evenodd"
                        >
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                            d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z"
                            fill="#000000"
                        ></path>
                        <path
                            d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z"
                            fill="#000000"
                            opacity="0.3"
                        ></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                    </span>
                </span>
                <div class="d-flex flex-column flex-grow-1 mr-2">
                    <ul
                    href="#"
                    class="font-weight-bold text-danger font-size-lg mb-1"
                    >
                    {{
                        $t(this.errorMsg)
                    }}
                    </ul>
                </div>
                </div>
                <div class="form-group mb-5">
                <input
                    class="form-control h-auto form-control-solid py-4 px-8"
                    :class="{
                    'border-error':
                        (!$v.form.email.email && submit) ||
                        (!$v.form.email.required && submit),
                    }"
                    v-model="$v.form.email.$model"
                    type="text"
                    :placeholder="getTextLocale('email')"
                    name="username"
                    autocomplete="off"
                />
                <div
                    class="invalid-feedback"
                    style="text-align: start"
                    v-if="submit"
                    v-bind:style="{
                    display:
                        $v.form.email.required && $v.form.email.email
                        ? 'none'
                        : 'block',
                    }"
                >
                    {{ $t("valid_email") }}
                </div>
                </div>
                <div class="form-group mb-5">
                <input
                    class="form-control h-auto form-control-solid py-4 px-8"
                    :class="{
                    'border-error':
                        (!$v.form.password.minLength ||
                        !$v.form.password.required) &&
                        submit,
                    }"
                    v-model="$v.form.password.$model"
                    type="password"
                    :placeholder="getTextLocale('password')"
                    name="password"
                />
                <div
                    class="invalid-feedback"
                    style="text-align: start"
                    v-if="submit"
                    v-bind:style="{
                    display:
                        $v.form.password.required &&
                        $v.form.password.minLength
                        ? 'none'
                        : 'block',
                    }"
                >
                    {{ $t("required_pass") }}
                </div>
                </div>

                <div
                class="
                    form-group
                    d-flex
                    flex-wrap
                    justify-content-between
                    align-items-center
                "
                >
                <router-link
                    :to="{ name: 'forgot-password' }"
                    class="text-dark-60 text-hover-primary my-3 mr-2"
                    id="kt_login_forgot"
                >
                    {{ $t("forgot_pass") }} ?
                </router-link>
                </div>
            <div
            class="d-flex"
            :class="[loginLayoutData.button.buttonAlign || 'justify-content-center']"
            >
            <button
                ref="kt_login_signin_submit"
                class="btn btn-lg submit-btn btn-primary"
                :style="{
                color: loginLayoutData.button.color || '#fff',
                'background-color':
                    loginLayoutData.button.backgroundColor || '#3699ff',
                'border-radius': loginLayoutData.button.borderRadius + 'px',
                'border-color': loginLayoutData.button.borderColor || '#3699ff',
                }">
                {{ $t("SIGN_IN") }}
            </button>
            </div>
                <!--end::Action-->
            </b-form>
            <div class="mt-10">
                <span class="opacity-70">{{ $t("have_not_acc") }}?</span>
                <router-link
                class="font-weight-bold font-size-3"
                :to="{ name: 'register' }"
                >
                {{ $t("sign_up") }}!
                </router-link>
            </div>
            </div>
            <!--#endregion Login Sign in form-->
            </div>
        </div>
      </div>
    </div>
    <!-- not active -->
    <div class="login-main" v-else>
      <div class="sign-up-container bg-light-warning">
        <div
          class="alert alert-custom alert-light-warning fade show gutter-b"
          role="alert"
        >
          <div class="alert-icon">
            <span class="svg-icon svg-icon-primary svg-icon-3x">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                width="24px"
                height="24px"
                viewBox="0 0 24 24"
                version="1.1"
              >
                <g
                  stroke="none"
                  stroke-width="1"
                  fill="none"
                  fill-rule="evenodd"
                >
                  <rect x="0" y="0" width="24" height="24"></rect>
                  <path
                    d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                    fill="#000000"
                    opacity="0.3"
                  ></path>
                  <path
                    d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                    fill="#000000"
                    fill-rule="nonzero"
                  ></path>
                </g>
              </svg>
            </span>
          </div>
          <div
            class="alert-text font-weight-bolder font-size-lg text-align-center"
          >
            <span
              class="alert-heading"
              style="font-size: 15px; font-weight: 600"
            >
              {{ $t("not_active_program") }} .</span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import { LOGIN, LOGOUT } from "@/core/services/store/auth.module";
import ApiService from "aff/core/services/api.service";
import { validationMixin } from "vuelidate";
import { email, minLength, required } from "vuelidate/lib/validators";
import FontPicker from "font-picker-vue";
import Loader from "aff/view/content/Loader.vue";
import { locale as en } from "aff/core/config/i18n/en";
import { locale as zh } from "aff/core/config/i18n/zh";
import { locale as es } from "aff/core/config/i18n/es";
import { locale as it } from "aff/core/config/i18n/it";
import { locale as de } from "aff/core/config/i18n/de";
import { locale as fr } from "aff/core/config/i18n/fr";
import { locale as pt } from "aff/core/config/i18n/pt";
import {
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BForm,
} from "bootstrap-vue";
export default {
  components: {
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BForm,
    Loader,
    FontPicker
  },
  mixins: [validationMixin],
  name: "login",
  data() {
    return {
      isExist: true,
      loading: true,
      logo: null,
      submit: false,
      errorMsg: "",
      form: {
        email: null,
        password: null,
      },
      titleLogin:{
        name: "titleLogin"
      },
      loginLayoutData:{
        logo : {
            isShow : true,
            width : 30
        },
        size : {
            width: 450,
            mt: 20,
            px: 45,
            py: 24,
            gapImg: 10,
        },
        form:{
            bg : "#ffffff",
            borderRadius : 10,
            fontFamily: "Poppins",
            fontSizeTitle: 20,
            titleColor: "#000"
        },
        button : {
            buttonAlign : "fullW",
            color : "#fff",
            backgroundColor : "#000000",
            borderColor : "#000000",
            borderRadius : 10,
        },
        customcss : "",
        },
    };
  },
  validations: {
    form: {
      email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(6),
      },
    },
  },
  methods: {
    prepareLogin() {
      ApiService.query("/login").then(({ data }) => {
        if (data.status === "Success") {
            //Neu co logo
          if (data.data.logo) {
            this.logo = data.data.logo;
          }
            //neu co language
          if (
            data.data.default_affiliate_language &&
            data.data.default_affiliate_language != ""
          ) {
            localStorage.setItem(
              "language",
              data.data.default_affiliate_language
            );
            this.$i18n.locale = data.data.default_affiliate_language;
            switch (data.data.default_affiliate_language) {
              case "zh":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  zh
                );
                break;
              case "en":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  en
                );
                break;
              case "es":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  es
                );
                break;
              case "de":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  de
                );
                break;
              case "pt":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  pt
                );
                break;
              case "it":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  it
                );
                break;
              case "fr":
                this.$i18n.setLocaleMessage(
                  data.data.default_affiliate_language,
                  fr
                );
                break;
              default:
                break;
            }
          }
            // neu co layout
          if(!!data.data.login_page_config){
              this.loginLayoutData = data.data.login_page_config;
          }
          this.loading = false;
        } else {
          this.isExist = false;
          this.loading = false;
        }
      });
    },
    resetForm() {
      this.form = {
        email: null,
        password: null,
      };

      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    onSubmit() {
      this.submit = true;
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }

      const email = this.$v.form.email.$model;
      const password = this.$v.form.password.$model;

      // clear existing errors
      this.$store.dispatch(LOGOUT);

      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      submitButton.classList.add("spinner", "spinner-light", "spinner-right");

      // dummy delay
      // send login request
      this.$store
        .dispatch(LOGIN, { email, password })
        .then(() => this.$router.push({ name: "dashboard" }))
        .catch((response) => {
          if (
            response.data &&
            response.data.message &&
            response.status == 403
          ) {
            this.errorMsg = response.data.message;
            submitButton.classList.remove(
              "spinner",
              "spinner-light",
              "spinner-right"
            );
            this.submit = false;
          }
        });
    },
  },
  computed: {
    ...mapState({
      errors: (state) => state.auth.errors,
    }),
  },
  created() {
    this.prepareLogin();
  },
  mounted() {
    // setInterval(function(){
    let message = { height: 700, width: document.body.scrollWidth };
    window.top.postMessage(message, "*");
    // }, 1000);
  },
};
</script>
<style scoped>
.border-error {
  border-color: #f64e60 !important;
}
.spinner.spinner-right {
  padding-right: 3.5rem !important;
}
.form-control {
  display: block;
  width: 100%;
  height: calc(1.5em + 1.3rem + 2px);
  padding: 0.65rem 1rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #3f4254;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #e4e6ef;
  border-radius: 0.42rem;
}
.login-form {
  width: 100%;
  max-width: 450px;
}
.flex-root {
  flex: 1;
}
.flex-column-fluid {
  flex: 1 0 auto;
}
.fullW > button {
  width: 100% !important;
}

</style>
