<template>
  <div class="main page-loading" :style="{
        'border-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        }">
    <!-- #region phần gán css ẩn  -->
    <component is="style">
      #form_generator .form-group > label{ color:
      {{ baseObjToGen.customInput.labelColor || "#000" }}; font-size:{{
        baseObjToGen.customInput.fontSize
      }}px; font-weight: {{ baseObjToGen.customInput.fontWeight }}; font-style:
      {{ baseObjToGen.customInput.fontStyle }}; }
      #form_generator .form-group > .field-wrap .wrapper > input{
          background-color: {{baseObjToGen.customInput.inputColor}};
          border: solid 1px {{baseObjToGen.customInput.borderColor || "#f3f6f9"}}
      }
    </component>
    <component is="style">
        {{ baseObjToGen.customcss}}
    </component>
    <div class="d-none">
      <font-picker
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="baseObjToGen.customInput.labelFamily"
        :options="labelInput"
      >
      </font-picker>
    </div>
    <div class="d-none">
      <font-picker
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="baseObjToGen.detailObj.fontfamily"
        :options="detailLabel"
      >
      </font-picker>
    </div>
    <div class="d-none">
      <font-picker
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="baseObjToGen.titleAndDescription.title.font_family"
        :options="titlemain"
      >
      </font-picker>
    </div>
    <!-- #endregion phần gán css ẩn  -->
    <!-- ASIDE -->
    <div
      v-if="baseObjToGen.name == 'template2Obj'"
      class="register-aside col-md-6 px-10"
      :style="{ 'background-image': 'url(' + baseObjToGen.aside.bgImg2 + ')',
       'background-color': baseObjToGen.aside.bgAside2,
       'border-top-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-bottom-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        }"
    >
      <div
        id="overlay"
        :style="{'background-color': 'rgba(0,0,0,0.' + baseObjToGen.aside.overlay2 || 0 + ')',
        'border-top-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-bottom-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        }"
      ></div>
      <span class="w-100" style="z-index: 4;overflow: hidden;" v-html="replaceprogramDetail(baseObjToGen.aside.textContent)"></span>
    </div>
    <!-- End ASIDE -->
    <div
      :style="{
        'background-color': baseObjToGen.bgAndBorder.bg || '',
        border: 'solid ' + baseObjToGen.bgAndBorder.borderSize + 'px' || '1px',
        'border-color': baseObjToGen.bgAndBorder.borderColor || '',
        'border-top-right-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-bottom-right-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
      }"
      class="col-md-6 sign-up-container pb-10"
    >
      <!-- LOGO -->
      <div class="brand-logo d-flex justify-content-center mt-5">
        <img
          v-if="programdetail.logo && baseObjToGen.logo.isShow"
          :src="programdetail.logo"
          alt="Logo"
          style="height: auto"
          :style="{ width: baseObjToGen.logo.width + '%' }"
        />
      </div>

      <div class="panel-body d-flex flex-column" id="login-signin">
        <!-- title -->
        <p
          class="apply-font-titlemain title-preview"
          :class="[
            baseObjToGen.titleAndDescription.title.align || 'text-center',
            baseObjToGen.titleAndDescription.title.font_family || '',
          ]"
          :style="{
            color: baseObjToGen.titleAndDescription.title.color,
            'font-size':
              baseObjToGen.titleAndDescription.title.font_size + 'px' || '14px',
            'font-weight': baseObjToGen.titleAndDescription.title.font_weight,
            'font-style': baseObjToGen.titleAndDescription.title.font_style,
          }"
        >
          {{ baseObjToGen.titleAndDescription.title.text }}
        </p>

        <!-- benefit text  -->
        <div
          class="program-details p-5 mb-10 apply-font-titleDetail"
          v-if="baseObjToGen.detailObj.isShow == true"
          :style="{
            'background-color':
              baseObjToGen.detailObj.background || 'transparent',
            color: baseObjToGen.detailObj.color,
          }"
        >
          <p
            class="font-size-h3 font-weight-bolder mb-5 mt-2 title-benefit"
            :class="[baseObjToGen.detailObj.align || 'text-center']"
          >
            {{ baseObjToGen.detailObj.mainHeading }}
          </p>
        <!-- #region benefit -->
        <table class="table-benefit">
        <tr
          class="detail-info mb-3"
          v-for="(item, i) in baseObjToGen.detailObj.detailText"
          :key="i"
          :i="i"
        >
          <td class="label-detail col-4 pl-2">{{ item.label }}</td>
          <td class="col-8 content-detail">
            <i
              class="fas fa-check-circle"
              :style="{ color: baseObjToGen.detailObj.checkmark }"
            ></i>
            {{ replaceprogramDetail(item.content) }}
          </td>
        </tr>
        </table>
        <!-- #endregion benefit -->
        </div>
        <!-- Error card -->
        <div
          v-if="programdetail.errorMsg"
          class="d-flex flex-column bg-light-danger rounded mb-9"
        >
          <ul class="alert alert-custom p-5 alert-light-danger fade show flex-column ml-5"
            role="alert"
          >
            <li v-for="(error, i) in programdetail.errorMsg" :key="i" :i="i" class="alert-text" style="align-self: flex-start;">{{ error[0] }}</li>
          </ul>
        </div>
        <!-- form-generator -->
        <vue-form-generator
          @validated="onValid"
          ref="vForm"
          id="form_generator"
          :isNewModel="true"
          :schema="baseObjToGen.schema"
          :model="baseObjToGen.model"
          :options="formOptions"
          class="vue-form-gen"
        >
        </vue-form-generator>
        <!-- LOGIN -->
        <vue-recaptcha
            v-if="baseObjToGen.captcha && baseObjToGen.captcha.isShow == true"
            ref="recaptcha"
            @verify="onVerify"
            @expired="onExpired"
            :sitekey="sitekey"
            :loadRecaptchaScript="true"
            :theme="baseObjToGen.captcha.theme"
            >
        </vue-recaptcha>

          <div class="pb-5 pt-5">
            <span class="font-weight-bold text-muted font-size-h6"
              >{{$t('HAVE_ACC')}}</span
            >
            <router-link
              class="font-weight-bolder text-primary font-size-h6 ml-2"
              :to="{ name: 'login' }"
            >
              {{$t('SIGN_IN')}}
            </router-link>
          </div>
        <!-- button submit -->
        <div
          class="d-flex"
          :class="[baseObjToGen.button.buttonAlign || 'justify-content-center']"
        >
          <button
            class="btn btn-lg submit-btn btn-primary"
            ref="kt_login_signup_submit"
            :style="{
              color: baseObjToGen.button.color || '#fff',
              'background-color':
                baseObjToGen.button.backgroundColor || '#3699ff',
              'border-radius': baseObjToGen.button.borderRadius + 'px',
              'border-color': baseObjToGen.button.borderColor || '#3699ff',
            }"
            @click.stop.prevent="onSubmit"
          >
            {{ baseObjToGen.button.submittext || "Submit" }}
          </button>
        </div>
        <div class="mt-5" style="font-size: smaller;" v-if="programdetail.is_enable_term_policy == 1">
            {{$t('by_joining')}}
            <a class="link-term-policy" @click="showTerm">{{$t('TERM')}}</a>
            {{$t('and')}}
            <a class="link-term-policy" @click="showPolicy">{{$t('PRIVACY')}}</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  BFormSelect,
  BFormInput,
  BSpinner,
  BButton,
  BFormCheckbox,
  BFormTextarea,
} from "bootstrap-vue";
import VueFormGenerator from "vue-form-generator/dist/vfg-core.js";
import FontPicker from "font-picker-vue";
import VueRecaptcha from 'vue-recaptcha';
export default {
  components: {
    BFormSelect,
    BFormInput,
    BSpinner,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    FontPicker,
    "vue-form-generator": VueFormGenerator.component,
    'vue-recaptcha': VueRecaptcha,
  },
  props: ["baseObjToGen", "programdetail"],
  data() {
    return {
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: false,
        validateAsync: false,
      },
      labelInput: {
        name: "labelInput",
      },
      detailLabel: {
        name: "titleDetail",
      },
      titlemain: {
        name: "titlemain",
      },
      verifyCaptcha: false,
      sitekey: process.env.MIX_SITE_KEY_CAPTCHA
    };
  },
  methods: {
    /**handle lỗi */
    onValid(vld, err) {
      this.isValid = vld;
      this.errors = err;
      // console.log("Validation result: ", vld, ", Errors:", JSON.stringify(err));
    },
    onSubmit() {
      this.$refs.vForm.validate();
      if(this.verifyCaptcha == false && this.baseObjToGen.captcha.isShow == true){
          this.$toast.error(this.$t('COMPLETE_CAPTCHA'), {
            position: "bottom",
        });
        return;
      };
      if (this.isValid ) {
        this.$emit("onSubmit", this.baseObjToGen);
      }
    },
    replaceprogram(param) {
      if (param == "{commission_type}") {
        return this.genTextCommissionType(this.programdetail.reward);
      }
      if (param == "{commission_amount}") {
        return this.formatCommissionAmount(
          this.programdetail.reward,
          this.programdetail.rewardValue,
          this.programdetail.money_format
        );
      }
      if (param == "{cookie_days}") {
        return this.programdetail.cookieDays;
      }
      return param;
    },
    replaceprogramDetail(textContent){
        if(textContent){
            var c = this.genTextCommissionType(this.programdetail.reward);
            var d = this.formatCommissionAmount(this.programdetail.reward, this.programdetail.rewardValue, this.programdetail.money_format);
            var e = (this.programdetail.cookieDays);
            textContent = textContent.replace(/{commission_type}/g, c);
            textContent = textContent.replace(/{commission_amount}/g, d);
            textContent = textContent.replace(/{cookie_days}/g, e);
            textContent = textContent.replace(/{brand_name}/g, this.programdetail.brand_name);
        }
        else{textContent = "<p></p>"};
        return textContent;
    },
    onVerify: function (response) {
        this.verifyCaptcha  = true;
    },
    onExpired: function () {
    this.verifyCaptcha = false;
      this.$toast.error(this.$t('Expired'), {
            position: "bottom",
        });
    },
    showTerm(){
        this.$emit("showTerm");
    },
    showPolicy(){
        this.$emit("showPolicy");
    }
  },

};
</script>

<style lang="scss" scoped>
.link-term-policy{
    cursor: pointer;
    text-decoration: underline;
    color: #b5b5c3;
    &:hover{
        color: #3699ff !important;
        text-decoration: underline !important;
    }
}
#overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 2;
  box-shadow: 0 0 0 0 rgb(63 63 68 / 6%), -5px 6px 8px 0 rgb(63 63 68 / 19%);
}
.main {
  display: flex !important;
}
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
  border-radius: 25px;
  padding: 1.6rem 2rem 1.6rem 1.6rem;
  display: inline-flex;
  background-color: #fff;
}

.program-details {
  width: 100%;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  display: flex;
  flex-direction: column;
border-radius: 10px;
}
.content-detail {
  display: inline;
}
.vue-form-gen {
  min-height: 400px;
  width: 100%;
}

.label-detail {
  font-weight: 600;
  // width: 150px;
}
.content-detail {
  display: inline;
}
.fullW > button {
  width: 100% !important;
}
.register-aside {
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
  display: flex;
}
.sign-up-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  box-shadow: 0 0 0 0 rgb(63 63 68 / 6%), 6px 6px 8px 0 rgb(63 63 68 / 19%);
}
.panel-body {
  border-radius: 5px;
  padding: 0 40px 0 40px;
  .wrapper {
    padding: 0 !important;
  }
  width: 100%;
}
.detail-info {
  display: flex;
  font-size: 14px;
  flex: auto !important;
  align-items: center;
  // height: 40px !important ;
  padding: 8px 0px;
  &:nth-last-child(1) {
    border-bottom: none;
  }
}
.submit-btn:hover{
    opacity: 0.8;
}
@media screen and (max-width: 783px) {
  .sign-up-container {
    margin: 0;
    box-shadow: none !important;
      border-radius: 0px !important;

  }
  .signin-container {
    width: auto;
  }
    .main {
    display: flex !important;
    flex-direction: column;
  }
  .register-aside {
    min-height: 400px;
    box-shadow: none !important;
      border-radius: 0px !important;

  }
  #overlay{
      box-shadow: none !important;
      border-radius: 0px !important;
  }
  .panel-body{
      padding: 0 5px 0 5px;
  }
}
@media screen and (max-width: 1366px) {

  .sign-up-container {
    flex-direction: column;
  }
}
</style>
