<template>
  <div
    :style="{
      'background-color': baseObjToGen.bgAndBorder.bg || '#f3f6f9',
      'border-radius': baseObjToGen.bgAndBorder.borderRadius + 'px' || '5px',
    }"
    class="mx-auto"
    style="box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 7px 16px 0 rgb(63 63 68 / 15%); max-width: 600px"
  >
    <component is="style">
      #form_generator .form-group > label{ color:
      {{ baseObjToGen.customInput.labelColor || "#000" }}; font-size:{{
        baseObjToGen.customInput.fontSize
      }}px; font-weight: {{ baseObjToGen.customInput.fontWeight }}; font-style:
      {{ baseObjToGen.customInput.fontStyle }}; }
      #form_generator .form-group > .field-wrap .wrapper > input{
          background-color: {{baseObjToGen.customInput.inputColor}}
      }
    </component>

    <div class="sign-up-container p-5">
      <div class="panel-body d-flex flex-column col-lg-12 col-sm-8">
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
        <!-- title -->
        <p
          class="apply-font-titlemain"
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
              <!-- content  -->
      <div
        class="program-details mb-10 px-10 pb-10 apply-font-titleDetail"
        v-if="baseObjToGen.detailObj.isShow == true"
        :style="{
          'background-color':
            baseObjToGen.detailObj.background || 'transparent',
          color: baseObjToGen.detailObj.color,
        }"
      >
        <p
          class="font-size-h3 pt-10 font-weight-bolder mb-10 mb-lg-15"
          :class="[baseObjToGen.detailObj.align || 'text-center']"
        >
          {{ baseObjToGen.detailObj.mainHeading }}
        </p>
        <div
          class="detail-info mb-3"
          v-for="(item, i) in baseObjToGen.detailObj.detailText"
          :key="i"
          :i="i"
        >
          <div class="label-detail col-6">{{ item.label }}</div>
          <div class="content-detail col-6">
            <i
              class="fas fa-check-circle"
              :style="{ color: baseObjToGen.detailObj.checkmark }"
            ></i>
            {{ replaceprogramDetail(item.content) }}
          </div>
        </div>
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
        <div
          class="d-flex"
          :class="[
            baseObjToGen.titleAndDescription.title.align || 'text-center',
          ]"
        >
          <div class="top-signup text-right d-flex justify-content-end pb-10">
            <span class="font-weight-bold text-muted font-size-h5"
              >Already have an account?</span
            >
            <a class="font-weight-bolder text-primary font-size-h5 ml-2">
              Sign In!
            </a>
          </div>
        </div>
        <!-- button submit -->
        <div
          class="d-flex mb-10"
          :class="[baseObjToGen.button.buttonAlign || 'justify-content-center']"
        >
          <button
            type="submit"
            ref="kt_login_signup_submit"
            class="btn btn-lg"
            :class="baseObjToGen.button.classSubmitBtn || 'btn-primary'"
            :style="{
              color: baseObjToGen.button.color || '#fff',
              'background-color':
                baseObjToGen.button.backgroundColor || '#3699ff',
              'border-radius': baseObjToGen.button.borderRadius + 'px',
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
import { mapState } from "vuex";
import VueFormGenerator from "vue-form-generator/dist/vfg-core.js";
export default {
  components: {
    BFormSelect,
    BFormInput,
    BSpinner,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    "vue-form-generator": VueFormGenerator.component,
  },
  props: ["baseObjToGen", "group"],
  data() {
    return {
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: true,
        validateAsync: true,
      },
    };
  },
  methods: {
    validateForm() {
      this.$refs.vForm.validate();
    },
    onValid(vld, err) {
      this.isValid = vld;
      this.errors = err;
      //   console.log("Validation result: ", vld, ", Errors:", JSON.stringify(err));
    },
    replaceprogramDetail(textContent){
        if(textContent){
            var c = this.genTextCommissionType(this.group.commission_type);
            if(this.group.commission_type == 1) {
              var d = this.group.commission_amount+'%';
            }else{
              var d = this.formatCommissionAmount(
                this.group,
                this.group.commission_amount,
                this.format
              );
            }


            var e = this.group.cookie_days;
            textContent = textContent.replaceAll(/{commission_type}/g, c);
            textContent = textContent.replaceAll(/{commission_amount}/g, d);
            textContent = textContent.replaceAll(/{cookie_days}/g, e);
            textContent = textContent.replaceAll(/{brand_name}/g, this.brand_name);
        }
        else{textContent = "<p></p>"};
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
    // format() {
    //   return this.$store.getters.moneyFormat;
    // },
    ...mapState({
      logo: (state) => state.layout.settings.logo,
      format: (state) => state.layout.moneyFormat,
    }),
  },
};
</script>
<style scoped>
.detail-info {
  border-bottom: none !important;
  font-size: 14px;
  flex: 0 1 5% !important;
  flex-wrap: wrap;
  display: flex;
}
.program-details {
    width: 100% !important;
  display: flex;
  flex-direction: column;
  border: 1px solid rgb(212, 212, 212);
  border-radius: 10px;
}
.sign-up-container {
  display: flex;
  gap: 2rem;
  justify-content: space-evenly;
}
</style>
