<template>
  <div>
    <!-- #region title and description -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Title</h3>
      </div>
      <label class="font-weight-bolder">Alignment: </label>
      <b-form-select
        class="mb-5"
        id="align"
        v-model="selectedTitleMainAlign"
        :options="titleMainAlign"
        @change="changeTitleMainAlign(selectedTitleMainAlign)"
      ></b-form-select>
      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Color: </label>
        <color-picker
          :position="colorPanelPosition"
          v-model="baseObjToGen.titleAndDescription.title.color"
        ></color-picker>
      </div>

      <label class="font-weight-bolder">Font-family: </label>
      <font-picker
        class="mb-5"
        id="font-picker-titlemain"
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="fontFamilyMain"
        :options="optionsTitleMain"
        @change="changeFontFamilyMain"
      >
      </font-picker>

      <label class="font-weight-bolder">Font-size: </label>
      <b-form-input
        id="size"
        type="range"
        class="mb-3"
        v-model="baseObjToGen.titleAndDescription.title.font_size"
      ></b-form-input>
      <b-input-group class="mb-5" append="px">
        <b-form-input
          id="size"
          type="number"
          min="8"
          max="36"
          step="2"
          v-model="baseObjToGen.titleAndDescription.title.font_size"
        ></b-form-input>
      </b-input-group>

      <label class="font-weight-bolder">Style: </label>
      <b-form-select
        id="font-weight"
        v-model="selectedFontWeight"
        :options="FontWeightTitle"
        @change="changeFontWeight(selectedFontWeight)"
      ></b-form-select>
    </div>
    <!-- #endregion title and description -->
    <!-- #region Logo-->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Logo</h3>
      </div>

      <div class="d-flex justify-content-between pr-5 mb-5 align-items-center">
        <label class="font-weight-bolder">Enable: </label>
        <span class="switch switch-icon mb-1">
          <label style="margin-top: -20px;">
            <b-form-checkbox
              v-model="baseObjToGen.logo.isShow"
              id="is_private"
              switch
              size="lg"
              :disabled="haveNoLogo"
            ></b-form-checkbox>
            <span></span>
          </label>
        </span>
      </div>
    <span class="text-muted mb-5" v-if="haveNoLogo">You need to upload a logo first. Click <a href="#/settings/general">here</a> to upload.</span>

      <label class="font-weight-bolder">Size: </label>
      <b-form-input
        id="padding"
        type="range"
        class="mb-3"
        v-model="baseObjToGen.logo.width"
      ></b-form-input>
      <b-input-group append="%">
        <b-form-input
          id="padding"
          type="number"
          min="10"
          max="100"
          v-model="baseObjToGen.logo.width"
        ></b-form-input>
      </b-input-group>
    </div>
    <!-- #endregion Logo -->
    <!-- #region benefit text -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Benefits</h3>
      </div>

      <label class="font-weight-bolder">Alignment: </label>
      <b-form-select
        id="align"
        class="mb-5"
        v-model="selectedTitleBenefitAlign"
        :options="titleBenefitAlign"
        @change="changeTitleBenefitAlign(selectedTitleBenefitAlign)"
      ></b-form-select>

      <label class="font-weight-bolder">Font-family: </label>
      <font-picker
        class="mb-5"
        id="apply-font-titleDetail"
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="fontFamilyBenefit"
        :options="optionsTitleDetail"
        @change="changeFontFamilyBenefit"
      >
      </font-picker>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Background color: </label>
        <color-picker
          :position="colorPanelPosition"
          v-model="baseObjToGen.detailObj.background"
        ></color-picker>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Text color: </label>
        <color-picker
          id="swatch2"
          :position="colorPanelPosition"
          v-model="baseObjToGen.detailObj.color"
        ></color-picker>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Checkmark color: </label>
        <color-picker
          id="swatch3"
          :position="colorPanelPosition"
          v-model="baseObjToGen.detailObj.checkmark"
        ></color-picker>
      </div>
    </div>
    <!-- #endregion benefit text -->
    <!-- #region Background and border -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Background and border</h3>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Background color: </label>
        <color-picker
          id="swatch"
          :position="colorPanelPosition"
          v-model="baseObjToGen.bgAndBorder.bg"
        ></color-picker>
      </div>

      <label class="font-weight-bolder">Border size: </label>
      <b-form-input
        id="size"
        type="range"
        min="0"
        max="50"
        class="mb-3"
        v-model="baseObjToGen.bgAndBorder.borderSize"
      ></b-form-input>
      <b-input-group append="px" class="mb-5">
        <b-form-input
          id="size"
          type="number"
          min="0"
          max="50"
          v-model="baseObjToGen.bgAndBorder.borderSize"
        ></b-form-input>
      </b-input-group>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Border color: </label>
        <color-picker
          id="swatch2"
          :position="colorPanelPosition"
          v-model="baseObjToGen.bgAndBorder.borderColor"
        ></color-picker>
      </div>
      <label class="font-weight-bolder">Border radius: </label>
      <b-form-input
        id="radius"
        type="range"
        class="mb-3"
        min="0"
        max="50"
        v-model="baseObjToGen.bgAndBorder.borderRadius"
      ></b-form-input>
      <b-input-group append="px" class="mb-5">
        <b-form-input
          id="radius"
          type="number"
          min="0"
          max="50"
          v-model="baseObjToGen.bgAndBorder.borderRadius"
        ></b-form-input>
      </b-input-group>
    </div>
    <!-- #endregion Background and border -->
    <!-- #region Inputs -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Inputs</h3>
      </div>

      <label class="font-weight-bolder">Label Font-family: </label>
      <font-picker
        id="font-picker-labelInput"
        class="mb-5"
        :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
        :active-font="fontFamilyInput"
        :options="labelInput"
        @change="changeFontFamilyInput"
      >
      </font-picker>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Label color: </label>
        <color-picker
          id="swatch"
          :position="colorPanelPosition"
          v-model="baseObjToGen.customInput.labelColor"
        ></color-picker>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Input-field color: </label>
        <color-picker
          id="inputcolor"
          :position="colorPanelPosition"
          v-model="baseObjToGen.customInput.inputColor"
        ></color-picker>
      </div>

      <div v-if="!!baseObjToGen.customInput.borderColor" class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Input-border color: </label>
        <color-picker
          id="input-border-color"
          :position="colorPanelPosition"
          v-model="baseObjToGen.customInput.borderColor"
        ></color-picker>
      </div>

      <label class="font-weight-bolder">Label size: </label>
      <b-form-input
        id="size"
        type="range"
        class="mb-3"
        v-model="baseObjToGen.customInput.fontSize"
      ></b-form-input>
      <b-input-group append="px" class="mb-5">
        <b-form-input
          id="size"
          type="number"
          min="8"
          max="36"
          step="2"
          v-model="baseObjToGen.customInput.fontSize"
        ></b-form-input>
      </b-input-group>

      <label class="font-weight-bolder">Label style: </label>
      <b-form-select
        id="font-weight"
        class="mb-5"
        v-model="selectedFontWeightInput"
        :options="FontWeightInput"
        @change="changeFontWeightInput(selectedFontWeightInput)"
      ></b-form-select>
    </div>
    <!-- #endregion Inputs -->
    <!-- #region Aside Không mở cho basic-->
    <div class="card p-5 pb-0 mb-1" v-if="baseObjToGen.name !== 'basicObj'">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Aside</h3>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Background-image: </label>
        <div
          class="image-input image-input-empty image-input-outline"
          id="kt_user_edit_avatar"
        >
          <img v-if="bgImg" class="image-input-wrapper" :src="bgImg" />
          <img
            v-else
            class="image-input-wrapper"
            src="media/svg/icons/Home/Picture.svg"
          />
          <label
            class="
              btn
              btn-xs
              btn-icon
              btn-circle
              btn-white
              btn-hover-text-primary
              btn-shadow
            "
            data-action="change"
            data-toggle="tooltip"
            title=""
            data-original-title="Change image"
          >
            <i class="fa fa-pen icon-sm text-muted">
              <b-form-file
                ref="file-input"
                accept="image/*"
                v-model="image"
                style="display: none"
                @change="previewImage"
              ></b-form-file>
            </i>
          </label>
          <span
            v-if="bgImg"
            class="
              btn
              btn-xs
              btn-icon
              btn-circle
              btn-white
              btn-hover-text-primary
              btn-shadow
              del-img
            "
            @click="resetAsideImg()"
          >
            <i class="ki ki-close icon-sm text-muted"></i>
          </span>
        </div>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">(or) Background-color: </label>
        <color-picker
          :position="colorPanelPosition"
          class="mb-5"
          id="swatch6"
          v-model="bgAside"
        ></color-picker>
      </div>

      <label class="font-weight-bolder">Overlay: </label>
      <b-form-input
        id="radius"
        type="range"
        class="mb-3"
        min="0"
        max="9"
        v-model="overlay"
      ></b-form-input>
      <b-form-input
        id="radius"
        type="number"
        min="0"
        max="10"
        v-model="overlay"
      ></b-form-input>
    </div>
    <!-- #endregion Aside -->
    <!-- #region button -->
    <div class="card p-5 pb-10 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-10">Edit Button</h3>
      </div>

      <label class="font-weight-bolder">Alignment: </label>
      <b-form-select
        id="font-weight"
        class="mb-5"
        v-model="btnAlign"
        :options="btnOptions"
        @change="changeBtnAlign(btnAlign)"
      ></b-form-select>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Text color: </label>
        <color-picker
          id="swatch1"
          :position="colorPanelPosition"
          v-model="baseObjToGen.button.color"
        ></color-picker>
      </div>
      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Background color: </label>
        <color-picker
          id="swatch2"
          :position="colorPanelPosition"
          v-model="baseObjToGen.button.backgroundColor"
        ></color-picker>
      </div>

      <div class="d-flex justify-content-between mb-5 align-items-center">
        <label class="font-weight-bolder">Border color: </label>
        <color-picker
          id="swatch1"
          :position="colorPanelPosition"
          v-model="baseObjToGen.button.borderColor"
        ></color-picker>
      </div>

      <label class="font-weight-bolder">Border radius: </label>
      <b-form-input
        id="title2"
        type="range"
        min="0"
        max="20"
        v-model="baseObjToGen.button.borderRadius"
      ></b-form-input>
    </div>
    <!-- #endregion button -->
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
import FontPicker from "font-picker-vue";
import ApiService from "@/core/services/api.service";
import {
  BFormSelect,
  BFormInput,
  BSpinner,
  BButton,
  BFormCheckbox,
  BFormTextarea,
  BOverlay,
  BFormGroup,
  BInputGroup,
  BFormSelectOption,
  BInputGroupAppend,
  BFormFile,
} from "bootstrap-vue";
export default {
  components: {
    BFormSelect,
    BFormInput,
    BSpinner,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    BOverlay,
    BFormGroup,
    BInputGroup,
    FontPicker,
    BFormSelectOption,
    BInputGroupAppend,
    BFormFile,
  },
  data() {
    return {
      colorPanelPosition: { left: "-220px", top: 0 },
      labelInput: {
        name: "labelInput",
      },
      FontWeightInput: [
        { value: "normal", text: "Normal" },
        { value: "bold", text: "Bold" },
        { value: "italic", text: "Italic" },
      ],
      image: null,
      optionsTitleMain: {
        name: "titlemain",
      },
      optionsTitleDetail: {
        name: "titleDetail",
      },
      titleMainAlign: [
        { value: "text-center", text: "Text align center" },
        { value: "text-left", text: "Text align left" },
        { value: "text-right", text: "Text align right" },
      ],
      titleBenefitAlign: [
        { value: "text-center", text: "Text align center" },
        { value: "text-left", text: "Text align left" },
        { value: "text-right", text: "Text align right" },
      ],
      FontWeightTitle: [
        { value: "normal", text: "Normal" },
        { value: "bold", text: "Bold" },
        { value: "italic", text: "Italic" },
      ],
      btnOptions: [
        { value: "justify-content-start", text: "Left" },
        { value: "justify-content-center", text: "Center" },
        { value: "justify-content-end", text: "Right" },
        { value: "fullW", text: "Full Width" },
      ],
    };
  },
  methods: {
    ...mapActions(["changeBaseObj"]),
    /** Font title */
    changeFontFamilyMain(e) {
      this.fontFamilyMain = e.family;
      this.baseObjToGen.titleAndDescription.title.font_family = e.family;
    },
    /**font benefit text */
    changeFontFamilyBenefit(e) {
      this.fontFamilyBenefit = e.family;
      this.baseObjToGen.detailObj.fontfamily = e.family;
    },
    changeTitleMainAlign(align) {
      this.baseObjToGen.titleAndDescription.title.align = align;
    },
    changeTitleBenefitAlign(align) {
      this.baseObjToGen.detailObj.align = align;
    },
    changeFontWeight(font_weight) {
      if (font_weight) {
        this.baseObjToGen.titleAndDescription.title.font_style = font_weight;
        this.baseObjToGen.titleAndDescription.title.font_weight = font_weight;
      }
    },
    changeFontFamilyInput(e) {
      this.fontFamilyInput = e.family;
      this.baseObjToGen.customInput.labelFamily = e.family;
    },
    changeFontWeightInput(font_weight) {
      if (font_weight) {
        this.baseObjToGen.customInput.fontStyle = font_weight;
        this.baseObjToGen.customInput.fontWeight = font_weight;
      }
    },
    /** validate size ảnh aside */
    validateSize(file) {
      const fileSize = file.size / 1024 / 1024; // in MiB
      if (fileSize <= 2) {
        return { status: true, size: fileSize };
      } else {
        return { status: false, size: fileSize };
      }
    },
    /**call api Upload ảnh */
    saveImage() {
      if (this.image) {
        let file = this.validateSize(this.image);
        if (file.status) {
          let resource = `/groups/uploadFile`;
          let formData = new FormData();
          formData.append("logo", this.image);
          ApiService.post(resource, formData).then(({ data }) => {
            this.$refs["file-input"].reset();
            this.image = null;
            this.bgImg = data.data.path;
            let obj = this.baseObjToGen;
            this.changeBaseObj(obj);
          });
        } else {
          this.$toast.error("File size exceeds 2 MiB", {
            position: "bottom",
          });
        }
      } else {
        let obj = this.baseObjToGen;
        this.changeBaseObj(obj);
      }
    },
    /** bắt sự kiện change ảnh thì lưu luôn vào db */
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        let obj = this.baseObjToGen;
        //preview
        this.bgImg = URL.createObjectURL(input.files[0]);
        this.image = input.files[0];
        this.changeBaseObj(obj);
      }
      this.saveImage();
    },
    /** xóa ảnh */
    resetAsideImg() {
      this.bgImg = null;
      this.image = null;
      let obj = this.baseObjToGen;
      this.changeBaseObj(obj);
      this.$refs["file-input"].reset();
    },
    changeBtnAlign(align) {
      this.baseObjToGen.button.buttonAlign = align;
    },
  },
  computed: {
    ...mapGetters(["baseObjToGen"]),
    id() {
      return this.$route.params.id;
    },
    ...mapState({
      logo: (state) => state.layout.settings.logo,
    }),
    haveNoLogo() {
      return !!this.logo ? false : true;
    },
    fontFamilyInput: {
      get: function () {
        return this.baseObjToGen.customInput.labelFamily || "Poppins";
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.customInput.labelFamily = newValue;
        }
      },
    },
    selectedFontWeightInput: {
      get: function () {
        return this.baseObjToGen.customInput.fontStyle || "normal";
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.customInput.fontStyle = newValue;
        }
      },
    },
    bgAside: {
      get: function () {
        if (this.baseObjToGen.name == "template2Obj")
          return this.baseObjToGen.aside.bgAside2 || "";
        if (this.baseObjToGen.name == "template3Obj")
          return this.baseObjToGen.aside.bgAside3 || "";
      },
      set: function (newValue) {
        if (newValue) {
          if (this.baseObjToGen.name == "template2Obj")
            this.baseObjToGen.aside.bgAside2 = newValue;
          if (this.baseObjToGen.name == "template3Obj")
            this.baseObjToGen.aside.bgAside3 = newValue;
        }
      },
    },
    bgImg: {
      get: function () {
        if (this.baseObjToGen.name == "template2Obj")
          return this.baseObjToGen.aside.bgImg2 || "";
        if (this.baseObjToGen.name == "template3Obj")
          return this.baseObjToGen.aside.bgImg3 || "";
      },
      set: function (newValue) {
        if (this.baseObjToGen.name == "template2Obj")
          this.baseObjToGen.aside.bgImg2 = newValue;
        if (this.baseObjToGen.name == "template3Obj")
          this.baseObjToGen.aside.bgImg3 = newValue;
      },
    },
    overlay: {
      get: function () {
        if (this.baseObjToGen.name == "template2Obj")
          return this.baseObjToGen.aside.overlay2 || 0;
        if (this.baseObjToGen.name == "template3Obj")
          return this.baseObjToGen.aside.overlay3 || 0;
      },
      set: function (newValue) {
        if (newValue) {
          if (this.baseObjToGen.name == "template2Obj")
            this.baseObjToGen.aside.overlay2 = newValue;
          if (this.baseObjToGen.name == "template3Obj")
            this.baseObjToGen.aside.overlay3 = newValue;
        }
      },
    },
    selectedTitleMainAlign: {
      get: function () {
        return (
          this.baseObjToGen.titleAndDescription.title.align || "text-center"
        );
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.titleAndDescription.title.align = newValue;
        }
      },
    },
    selectedTitleBenefitAlign: {
      get: function () {
        return this.baseObjToGen.detailObj.align || "text-center";
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.detailObj.align = newValue;
        }
      },
    },
    selectedFontWeight: {
      get: function () {
        return (
          this.baseObjToGen.titleAndDescription.title.font_style || "normal"
        );
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.titleAndDescription.title.font_style = newValue;
        }
      },
    },
    fontFamilyMain: {
      get: function () {
        return (
          this.baseObjToGen.titleAndDescription.title.font_family || "Poppins"
        );
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.titleAndDescription.title.font_family = newValue;
        }
      },
    },
    fontFamilyBenefit: {
      get: function () {
        return this.baseObjToGen.detailObj.fontfamily || "Poppins";
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.detailObj.fontfamily = newValue;
        }
      },
    },
    btnAlign: {
      get: function () {
        return this.baseObjToGen.button.buttonAlign || "text-center";
      },
      set: function (newValue) {
        if (newValue) {
          this.baseObjToGen.button.buttonAlign = newValue;
        }
      },
    },
  },
};
</script>

<style lang="scss" scoped>
.sign-up-container {
  border: 1px solid #e8e9f2;
  background-color: #f3f6f9;
  box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
  border-radius: 10px;
}
.del-img {
  position: absolute;
  bottom: -10px;
  right: -10px;
}
.image-input img {
  width: 120px;
  height: 120px;
}
.card {
  box-shadow: none !important;
  border-radius: unset !important;
}
</style>
