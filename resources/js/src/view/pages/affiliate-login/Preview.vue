<template>
    <div id="kt_body">

        <!--#region css ẩn -->
        <component is="style">
        #kt_body{background: {{loginLayoutData.form.bgMain}}}
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
                <!--#region form-->
                <div
                    class="login-signin mb-20"
                    :style="{
                    'padding': (loginLayoutData.size.py + 'px ' +loginLayoutData.size.px + 'px')|| '24px 45px',
                    'background': loginLayoutData.form.bg,
                    'border-radius': loginLayoutData.form.borderRadius + 'px'
                    }"
                >
                    <div class="mb-20 title-wrapper">
                        <h3 class="title-text" :style="{'color': loginLayoutData.form.titleColor, 'font-size': loginLayoutData.form.fontSizeTitle + 'px'}" >Sign in</h3>
                        <div class="text-muted font-weight-bold subtitle">
                            Enter your username and password
                        </div>
                    </div>
                    <b-form >
                    <div class="form-group mb-5">
                        <input
                        class="form-control h-auto form-control-solid py-4 px-8"
                        type="text"
                        placeholder="Email"
                        name="username"
                        autocomplete="off"
                        />
                    </div>
                    <div class="form-group mb-5">
                        <input
                        class="form-control h-auto form-control-solid py-4 px-8"
                        type="password"
                        placeholder="Password"
                        name="password"
                        />
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
                        <a class="text-dark-60 text-hover-primary my-3 mr-2"
                        id="kt_login_forgot"
                        >
                        Forgot Password ?
                        </a>
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
                        Sign in
                    </button>
                    </div>
                    <!--end::Action-->
                    </b-form>
                    <div class="mt-10">
                    <span class="opacity-70">Don't have an account yet?</span>
                    <a
                        class="font-weight-bold font-size-3"
                    >
                        Sign up!
                    </a>
                    </div>
                </div>
                <!--#endregion form-->
                </div>
            </div>
            <!--end::Login-->
        </div>
    </div>
</template>

<script>
import {
  BFormGroup,
  BFormInput,
  BForm,
} from "bootstrap-vue";
import {mapState, mapGetters} from "vuex";
import FontPicker from "font-picker-vue";
export default {
    components: {
    BFormGroup,
    BFormInput,
    BForm,
    FontPicker
  },
  computed:{
      ...mapState({
        logo: state => state.layout.settings.logo,
    }),
    ...mapGetters(["loginLayoutData"]),
  },
  data(){
      return{
          titleLogin:{
              name: "titleLogin"
          }
      }
  }
}
</script>

<style scoped>
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
