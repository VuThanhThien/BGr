<template>
  <div v-if="!!setting && loading == false && isNotFound == false">
    <div
      v-if="
        this.baseObjToGen.name == 'basicObj' ||
        this.baseObjToGen.name == 'bigrowObj'
      "
      class="login-main basic-main mt-10 mb-10 mx-auto"
    >
      <DefaultTemplate
        ref="mytemplate"
        :baseObjToGen="this.baseObjToGen"
        :programdetail="this.programdetail"
        @onSubmit="onSubmit"
        @showTerm="showTerm"
        @showPolicy="showPolicy"
      />
    </div>
    <div
      v-if="this.baseObjToGen.name == 'template2Obj'"
      class="login-main template2-main mb-10 mt-10 mx-auto d-flex justify-content-center"
    >
      <TemplateTwo
      class="col-md-10 col-sm-12"
        ref="mytemplate"
        :baseObjToGen="this.baseObjToGen"
        :programdetail="this.programdetail"
        @onSubmit="onSubmit"
        @showTerm="showTerm"
        @showPolicy="showPolicy"
      />
    </div>
    <div
      v-if="this.baseObjToGen.name == 'template3Obj'"
      class="login-main mx-auto"
    >
      <TemplateThree
        ref="mytemplate"
        :baseObjToGen="this.baseObjToGen"
        :programdetail="this.programdetail"
        @onSubmit="onSubmit"
        @showTerm="showTerm"
        @showPolicy="showPolicy"
      />
    </div>
    <b-modal ref="termModal" size="xl" hide-footer :title="titleModal">
        <span v-html="this.contentModal"></span>
    </b-modal>
  </div>
  <div class="login-main" v-else-if="loading == true && isNotFound == false">
    <Loader />
  </div>
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
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
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
          <span class="alert-heading" style="font-size: 15px; font-weight: 600">
            This program is no longer active.</span
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { REGISTER } from "aff/core/services/store/auth.module";
import { LOGOUT } from "aff/core/services/store/auth.module";
import Loader from "aff/view/content/Loader.vue";
import ApiService from "@/core/services/api.service";
import DefaultTemplate from "aff/view/pages/auth/register_template/Defaulttemplate.vue";
import TemplateTwo from "aff/view/pages/auth/register_template/TemplateTwo.vue";
import TemplateThree from "aff/view/pages/auth/register_template/TemplateThree.vue";
import { country } from "@/core/config/country";
export default {
  name: "register",
  components: {
    Loader,
    DefaultTemplate,
    TemplateTwo,
    TemplateThree
  },
  data() {
    return {
      baseObjToGen: {},
      loading: false,
      setting: null,
      titleModal: "",
      contentModal:"",
      programdetail: {
        errorMsg: "",
        reward: null,
        rewardValue: null,
        cookieDays: 0,
        logo: "",
        brand_name: "",
        money_format:"",
        is_enable_term_policy: 0,
        term:"",
        policy:"",
        default_affiliate_language:""
      },
      isNotFound: false,
      form: {},
      group: 0,
    };
  },
  computed: {
    slug() {
      return this.$route.params.slug;
    },
  },
  methods: {
    buildForm() {
      this.form = this.baseObjToGen.model;
      for (var propName in this.form) {
        if (this.form[propName] === null || this.form[propName] === undefined) {
          delete this.form[propName];
        }
      }
      this.form.group = this.group;
    },
    /**on click submitButton */
    onSubmit() {
      // clear existing errors
      this.$store.dispatch(LOGOUT);

      // set spinner to submit button
      const submitButton =
        this.$refs["mytemplate"].$refs["kt_login_signup_submit"];
      submitButton.classList.add("spinner", "spinner-light", "spinner-right");

      // dummy delay
      // send register request
      this.buildForm();
      if (this.$route.query.parent) {
        this.form["parent"] = this.$route.query.parent;
      }
      this.$store
        .dispatch(REGISTER, this.form)
        .then(() => {
          submitButton.classList.remove(
            "spinner",
            "spinner-light",
            "spinner-right"
          );
          this.$router.push({ name: "dashboard" });
        })
        .catch((response) => {
            // console.log(response);
          if (
            response.data.errors &&
            response.data.errors.email &&
            response.data.status == "Error"
          ) {
            // let obj = response.data.errors;
            this.programdetail.errorMsg = response.data.errors;
            submitButton.classList.remove(
              "spinner",
              "spinner-light",
              "spinner-right"
            );
          }
        });
    },
    showTerm(){
        this.$refs["termModal"].show();
        this.titleModal = "Terms & Conditions";
        this.contentModal = this.programdetail.term;
    },
    showPolicy(){
        this.titleModal = "Privacy Policy";
        this.contentModal = this.programdetail.policy;
        this.$refs["termModal"].show();
    },
    // Hàm dùng để custom duy nhất trường country
    customCountry(){
        let array = this.baseObjToGen.schema.fields;
        for (let i = 0; i < array.length; i++) {
            if(array[i].model === "country")
            {
                this.baseObjToGen.schema.fields[i].type = "select";
                this.baseObjToGen.schema.fields[i].values = country;
                this.baseObjToGen.schema.fields[i].selectOptions = {
                    noneSelectedText : "Select country"
                };
            }
        }
    }
  },

  created() {
    this.loading = true;
    ApiService.query("register", { params: { slug: this.slug } })
      .then(({ data }) => {
        this.loading = false;
        this.setting = data.data;
        if (!!data.data.group) {
          this.group = data.data.group.id;
          this.baseObjToGen = data.data.group.registration_form;
          this.programdetail.reward = data.data.group.commission_type;
          this.programdetail.rewardValue = data.data.group.commission_amount;
          this.programdetail.cookieDays = data.data.group.cookie_days;
          this.programdetail.logo = data.data.logo;
          this.programdetail.brand_name = data.data.brand_name;
          this.programdetail.money_format = data.data.money_format;
          this.programdetail.is_enable_term_policy = data.data.is_enable_term_policy;
          this.programdetail.term = data.data.term;
          this.programdetail.policy = data.data.policy;
          this.programdetail.default_affiliate_language = data.data.default_affiliate_language;
          if(this.programdetail.default_affiliate_language && this.programdetail.default_affiliate_language != ""){
            localStorage.setItem("language",this.programdetail.default_affiliate_language);
            this.$i18n.locale = this.programdetail.default_affiliate_language;
          };
          if(this.baseObjToGen){
              this.customCountry();
          }
        }
        resolve();
      })
      .catch(({ response }) => {
        if (!!response && response.status == 404) {
          this.isNotFound = true;
        }
      });
  },
  mounted(){
    window.addEventListener('load', function() {

    setInterval(function(){
      let message = { height: document.body.scrollHeight, width: document.body.scrollWidth };
      window.top.postMessage(message, "*");
     }, 1000);
    // window.top refers to parent window

  });
  }
};
</script>
<style lang="scss" scoped>
.login-main {
  height: auto;
}
.signin-wrapper {
  width: 100%;
  text-align: center;
  margin-top: 20px;
  margin-bottom: 15px;
  justify-content: center;
  align-self: center;
  display: flex;
}
.signin-container {
  width: 300px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  border-radius: 25px;
  padding: 1.6rem 2rem 1.6rem 1.6rem;
  display: inline-flex;
  background-color: #fff;
}
.sign-up-container {
  padding-top: 2rem;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
.program-details {
  width: 30%;
  display: flex;
  flex-direction: column;
}
.detail-info {
  display: flex;
  border-bottom: solid 0.5px #b5b5c3;
  flex: 0 1 11% !important;
  align-items: center;
  height: 53.5px;
  &:nth-last-child(1) {
    border-bottom: none;
  }
}
.label-detail {
  font-weight: 600;
}
.content-detail {
  display: inline;
}
.vue-form-gen {
  min-height: 400px;
  width: 100%;
}
.sign-up-container {
  padding-top: 2rem;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
.program-details {
  width: 40%;
  display: flex;
  flex-direction: column;
}
.detail-info {
  display: flex;
  flex: 0 1 11% !important;
  align-items: center;
  height: 53.5px;
  &:nth-last-child(1) {
    border-bottom: none;
  }
}
.label-detail {
  font-weight: 600;
  // width: 150px;
}
.content-detail {
  display: inline;
}
.basic-main{
    width: 600px;
}
.template2-main{
    max-width: 1350px;
}
.fullW > button {
  width: 100% !important;
}
@media screen and (max-width: 1366px) {

  .sign-up-container {
    flex-direction: column;
  }
  .panel-body {
    width: 100%;
    padding: 1rem;
  }
  .program-details {
    width: 90%;
    display: flex;
    flex-direction: column;
    margin-left: auto;
    margin-right: auto;
    gap: 2rem;
    margin: 2rem;
  }
  #login-signin {
    order: 2;
  }
  #program-details {
    order: 1;
  }
}
@media screen and (max-width: 992px) {
    .basic-main{
        width: 100%;
    }
    .template2-main{
        width: 100%;
    }
}

</style>
