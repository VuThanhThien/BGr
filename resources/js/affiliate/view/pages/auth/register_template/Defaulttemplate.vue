<template>
  <div
    :style="{
      'background-color': baseObjToGen.bgAndBorder.bg || '#f3f6f9',

      'border-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '5px',
    }"
    class="mx-auto page-loading main-preview"
  >
    <!-- #region region phần gán css ẩn -->
    <component is="style">
      #form_generator .form-group > label{ color:
      {{ baseObjToGen.customInput.labelColor || "#000" }}; font-size:{{
        baseObjToGen.customInput.fontSize
      }}px; font-weight: {{ baseObjToGen.customInput.fontWeight }}; font-style:
      {{ baseObjToGen.customInput.fontStyle }}; } #form_generator .form-group >
      .field-wrap .wrapper > input{ background-color:
      {{ baseObjToGen.customInput.inputColor }}; border: solid 1px
      {{ baseObjToGen.customInput.borderColor || "#f3f6f9" }}
      }
    </component>
    <component is="style">
      {{ baseObjToGen.customcss }}
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
    <!-- #endregion -->
    <!-- program detail -->
    <div
      class="program-details p-5 apply-font-titleDetail"
      v-if="baseObjToGen.detailObj.isShow == true"
      :style="{
        'background-color': baseObjToGen.detailObj.background || 'transparent',
        color: baseObjToGen.detailObj.color,
        'border-top-left-radius':
          baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-top-right-radius':
          baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
      }"
    >
      <p
        class="font-size-h3 pt-2 font-weight-bolder mb-2 title-benefit"
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

    <div
      class="sign-up-container"
      :style="{
        'border-bottom-left-radius':
          baseObjToGen.bgAndBorder.borderRadius + 'px',
        'border-bottom-right-radius':
          baseObjToGen.bgAndBorder.borderRadius + 'px',
        border: 'solid ' + baseObjToGen.bgAndBorder.borderSize + 'px' || '1px',
        'border-color': baseObjToGen.bgAndBorder.borderColor || '#e8e9f2',
      }"
    >
      <div class="panel-body d-flex flex-column col-lg-8" id="login-signin">
        <div class="brand-logo d-flex justify-content-center mt-5">
          <img
            v-if="programdetail.logo && baseObjToGen.logo.isShow"
            :src="programdetail.logo"
            alt="Logo"
            style="height: auto"
            :style="{ width: baseObjToGen.logo.width + '%' }"
          />
        </div>
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
          <span class="font-weight-bold text-muted font-size-h6">{{
            $t("HAVE_ACC")
          }}</span>
          <router-link
            class="font-weight-bolder text-primary font-size-h6 ml-2"
            :to="{ name: 'login' }"
          >
            {{ $t("SIGN_IN") }}
          </router-link>
        </div>
        <!-- button submit -->
        <div
          class="d-flex mb-10"
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
            @click="onSubmit"
          >
            {{ baseObjToGen.button.submittext || "Submit" }}
          </button>
        </div>
        <div
          class="mb-5"
          style="font-size: smaller"
          v-if="programdetail.is_enable_term_policy == 1"
        >
          {{ $t("by_joining") }}
          <a class="link-term-policy" @click="showTerm">{{ $t("TERM") }}</a>
          {{ $t("and") }}
          <a class="link-term-policy" @click="showPolicy">{{
            $t("PRIVACY")
          }}</a>
        </div>
      </div>
      <!-- content  -->
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
import VueRecaptcha from "vue-recaptcha";
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
    "vue-recaptcha": VueRecaptcha,
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
      sitekey: process.env.MIX_SITE_KEY_CAPTCHA,
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
      if (
        this.verifyCaptcha == false &&
        this.baseObjToGen.captcha.isShow == true
      ) {
        this.$toast.error(this.$t("COMPLETE_CAPTCHA"), {
          position: "bottom",
        });
        return;
      }
      if (this.isValid) {
        //   console.log(a);
        this.$emit("onSubmit", this.baseObjToGen);
      }
    },
    replaceprogramDetail(textContent) {
      if (textContent) {
        var c = this.genTextCommissionType(this.programdetail.reward);
        var d = this.formatCommissionAmount(
          this.programdetail.reward,
          this.programdetail.rewardValue,
          this.programdetail.money_format
        );
        var e = this.programdetail.cookieDays;
        textContent = textContent.replace(/{commission_type}/g, c);
        textContent = textContent.replace(/{commission_amount}/g, d);
        textContent = textContent.replace(/{cookie_days}/g, e);
        textContent = textContent.replace(/{brand_name}/g, this.brand_name);
      } else {
        textContent = "";
      }
      return textContent;
    },
    onVerify: function (response) {
      this.verifyCaptcha = true;
    },
    onExpired: function () {
      this.verifyCaptcha = false;
      this.$toast.error(this.$t("Expired"), {
        position: "bottom",
      });
    },
    showTerm() {
      this.$emit("showTerm");
    },
    showPolicy() {
      this.$emit("showPolicy");
    },
  },
};
</script>

<style lang="scss" scoped>
.link-term-policy {
  cursor: pointer;
  text-decoration: underline;
  color: #b5b5c3;
  &:hover {
    color: #3699ff !important;
    text-decoration: underline !important;
  }
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
.sign-up-container {
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
.detail-info {
  font-size: 14px;
  display: flex;
  flex-wrap: wrap;
  line-height: 2;
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
.program-details {
  display: flex;
  flex-direction: column;
}
.main-preview {
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 7px 16px 0 rgb(63 63 68 / 15%);
}
.submit-btn:hover {
  opacity: 0.8;
  transition: 0.4s;
}
@media screen and (max-width: 1365px) {
  .panel-body {
    width: 100%;
    padding: 1rem;
  }
}
.fullW > button {
  width: 100% !important;
}
@media screen and (max-width: 720px) {
  .sign-up-container {
    flex-direction: column;
    margin: 0;
  }
  .program-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  #login-signin {
    order: 2;
  }
  #program-details {
    order: 1;
  }
}
</style>
