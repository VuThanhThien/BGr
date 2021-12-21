<template>
  <div
    :style="{
      'background-color': baseObjToGen.bgAndBorder.bg || '#f3f6f9',
      border: 'solid ' + baseObjToGen.bgAndBorder.borderSize + 'px' || '1px',
      'border-color': baseObjToGen.bgAndBorder.borderColor || '#e8e9f2',
      'border-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '5px',
    }"
    class="mx-auto col-md-7 col-sm-12 main-preview"
  >
    <!-- #region css ẩn -->
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
    <!-- #endregion css ẩn -->
    <!-- content  -->
      <div
        class="program-details p-10 apply-font-titleDetail"
        v-if="baseObjToGen.detailObj.isShow == true"
        :style="{
          'background-color': baseObjToGen.detailObj.background || 'transparent',
          'color': baseObjToGen.detailObj.color,
          'border-top-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
          'border-top-right-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        }"
      >
      <!-- title benefit -->
        <p class="title-benefit font-size-h3 font-weight-bolder"
          :class="[baseObjToGen.detailObj.align || 'text-center']" >
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
    <div class="sign-up-container">
      <div class="panel-body d-flex flex-column col-lg-8 col-sm-8">
        <!-- LOGO -->
        <div class="brand-logo d-flex justify-content-center mt-5">
        <img
            v-if="this.logo && baseObjToGen.logo.isShow"
            :src="siteLogo()"
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

        <!-- form-generator -->
        <vue-form-generator
          @validated="onValid"
          ref="vForm"
          id="form_generator"
          :isNewModel="true"
          :schema="baseObjToGen.schema"
          :model="modelClone"
          :options="formOptions"
          class="vue-form-gen"
        >
        </vue-form-generator>
        <vue-recaptcha
            v-if="baseObjToGen.captcha && baseObjToGen.captcha.isShow == true"
            ref="recaptcha"
            :sitekey="sitekey"
            :loadRecaptchaScript="true"
            :theme="baseObjToGen.captcha.theme"
            >
        </vue-recaptcha>

          <div class="pb-10">
            <span class="font-weight-bold text-muted font-size-h6"
              >Already have an account?</span
            >
            <a class="font-weight-bolder text-primary font-size-h6 ml-2" href="#">
              Sign In!
            </a>
          </div>
        <!-- button submit -->
        <div
          class="d-flex mb-10"
          :class="[baseObjToGen.button.buttonAlign || 'justify-content-center']"
        >
          <button
            type="submit"
            ref="kt_login_signup_submit"
            class="btn btn-lg submit-btn btn-primary"
            :style="{
              color: baseObjToGen.button.color || '#fff',
              'background-color':
                baseObjToGen.button.backgroundColor || '#3699ff',
              'border-radius': baseObjToGen.button.borderRadius + 'px',
              'border-color': baseObjToGen.button.borderColor || '#3699ff',
            }"
            @click="validateForm"
          >
            {{ baseObjToGen.button.submittext || "Submit" }}
          </button>
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
import { mapState, mapGetters } from "vuex";
import VueFormGenerator from "vue-form-generator/dist/vfg-core.js";
import VueRecaptcha from 'vue-recaptcha';
import FontPicker from "font-picker-vue";
export default {
  components: {
    BFormSelect,
    BFormInput,
    BSpinner,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    "vue-form-generator": VueFormGenerator.component,
    'vue-recaptcha': VueRecaptcha,
    FontPicker,
  },
  data() {
    return {
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: true,
        validateAsync: true,
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
      sitekey: process.env.MIX_SITE_KEY_CAPTCHA
    };
  },
  methods: {
    validateForm() {
      this.$refs.vForm.validate();
    },
    onValid(vld, err) {
      this.isValid = vld;
      this.errors = err;
    },
    replaceprogramDetail(textContent){
        if(textContent){
            var c = this.genTextCommissionType(this.group.commission_type);
            var d = this.formatCommissionAmount(
              this.group.commission_type,
              this.group.commission_amount,
              this.format
            );
            var e = this.group.cookie_days;
            textContent = textContent.replace(/{commission_type}/g, c);
            textContent = textContent.replace(/{commission_amount}/g, d);
            textContent = textContent.replace(/{cookie_days}/g, e);
            textContent = textContent.replace(/{brand_name}/g, this.brand_name);
        }
        else{textContent = ""};
        return textContent;
    },
    siteLogo() {
      if (!!this.logo) {
        let myLogo = "";
        if (typeof this.logo === "string") {
          myLogo = this.logo;
        }
        return myLogo;
      }
    },
  },
  computed: {
    ...mapGetters(["group","baseObjToGen"]),
    id() {
      return this.$route.params.id;
    },
    modelClone(){
        return Object.assign({},this.baseObjToGen.model)
    },
    format() {
      return this.$store.getters.moneyFormat;
    },
    ...mapState({
      logo: (state) => state.layout.settings.logo,
    }),
  },
};
</script>
<style scoped>
.detail-info {
  border-bottom: none !important;
  font-size: 14px;
  flex-wrap: wrap;
  display: flex;
}
.program-details {
    width: 100% !important;
  display: flex;
  flex-direction: column;
}
.sign-up-container {
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
.main-preview{
    box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 7px 16px 0 rgb(63 63 68 / 15%);
    padding: 0;
}
.submit-btn:hover{
    opacity: 0.8;
}
</style>
