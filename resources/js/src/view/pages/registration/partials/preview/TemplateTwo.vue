<template>
  <div class="main">
    <!-- ASIDE -->
    <div
      v-if="baseObjToGen.aside.bgImg2 && baseObjToGen.name == 'template2Obj'"
      class="register-aside col-md-6 px-10"
      :style="{ 'background-image': 'url(' + baseObjToGen.aside.bgImg2 + ')', 'background-color': baseObjToGen.aside.bgAside2, 'border-top-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-bottom-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px', }"
    >
      <div
        id="overlay"
        :style="{'background-color': 'rgba(0,0,0,0.' + baseObjToGen.aside.overlay2 || 0 + ')',
        }"
      ></div>
      <span class="w-100" style="z-index: 4;overflow: hidden;" v-html="replaceprogramDetail(baseObjToGen.aside.textContent)"></span>
    </div>
    <div
      v-if="!baseObjToGen.aside.bgImg2 && baseObjToGen.name == 'template2Obj'"
      class="register-aside col-md-6 px-10"
      :style="{ 'background-color': baseObjToGen.aside.bgAside2,
      'border-top-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px',
        'border-bottom-left-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '0px', }"
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
    <div
      :style="{
        'background-color': baseObjToGen.bgAndBorder.bg || '#f3f6f9',
        border: 'solid ' + baseObjToGen.bgAndBorder.borderSize + 'px' || '1px',
        'border-color': baseObjToGen.bgAndBorder.borderColor || '#e8e9f2',
        'border-top-right-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '5px',
        'border-bottom-right-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '5px',

      }"
      class="mx-auto col-md-6 sign-up-container pb-10"
    >
    <!-- #region css ẩn -->
      <component is="style">
        #form_generator .form-group > label{ color:
        {{ baseObjToGen.customInput.labelColor || "#000" }}; font-size:{{
          baseObjToGen.customInput.fontSize
        }}px; font-weight: {{ baseObjToGen.customInput.fontWeight }};
        font-style: {{ baseObjToGen.customInput.fontStyle }}; font-family:
        {{ baseObjToGen.customInput.labelFamily }}
        }
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
      <!-- LOGO -->
      <div class="brand-logo d-flex justify-content-center">
        <img
          v-if="this.logo && baseObjToGen.logo.isShow"
          :src="siteLogo()"
          alt="Logo"
          style="height: auto"
          :style="{ width: baseObjToGen.logo.width + '%' }"
        />
      </div>

      <div class="panel-body d-flex flex-column">
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

        <!-- content  -->
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
            class="font-size-h3 font-weight-bolder mb-5 ml-2 title-benefit"
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
          class="d-flex"
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
import VueRecaptcha from 'vue-recaptcha';
import VueFormGenerator from "vue-form-generator/dist/vfg-core.js";
import { mapState, mapGetters } from "vuex";
import FontPicker from "font-picker-vue";
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
      // console.log("Validation result: ", vld, ", Errors:", JSON.stringify(err));
    },
    replaceprogramDetail(textContent){
        if(textContent){
            var c = this.genTextCommissionType(this.group.commission_type);
            var d = this.formatCommissionAmount(
              this.group,
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
      brand_name: (state) => state.layout.settings.brand_name,
    }),
  },
};
</script>

<style lang="scss" scoped>
#overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: inherit;
  box-shadow: 0 0 0 0 rgb(63 63 68 / 6%), -5px 6px 8px 0 rgb(63 63 68 / 19%);
  z-index: 2;
}
.main {
  display: flex;
}
.register-aside {
  background-repeat: no-repeat;
  background-size: cover;
  display: flex;
  background-position: center center;
}
.sign-up-container {
  padding: 1rem 2rem 0 2rem;
  display: flex;
  flex-direction: column;
  box-shadow: 0 0 0 0 rgb(63 63 68 / 6%), 0 6px 8px 0 rgb(63 63 68 / 19%);
  gap: 2rem;
}
.panel-body {
  border-radius: 5px;
  .wrapper {
    padding: 0 !important;
  }
  width: 100%;
}
.program-details {
  width: 100%;
  display: flex;
  flex-direction: column;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  border-radius: 10px;

}
.detail-info {
  display: flex;
  font-size: 14px;
  border-bottom: none !important;
  flex: auto !important;
  &:nth-last-child(1) {
    border-bottom: none;
  }
}
.submit-btn:hover{
    opacity: 0.8;
}
@media screen and (max-width: 800px) {
  .main {
    display: flex !important;
    flex-direction: column;
  }
  .register-aside {
    min-height: 400px;
  }
  .sign-up-container{
      padding: 1rem 0 0 0;
  }
}
</style>
