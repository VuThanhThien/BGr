<template>
<div>
    <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">Style of login form</h3>
    </div>
    <div class="d-flex justify-content-around">
        <div class="col-xl-4 d-flex flex-column">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <label class="font-weight-bolder mt-2">Background: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.form.bgMain"
                    ></color-picker>
                </div>
                <div class="d-flex flex-column">
                    <label class="font-weight-bolder mt-2">Form: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.form.bg"
                    ></color-picker>
                </div>
                <div class="mr-10 d-flex flex-column">
                    <label class="font-weight-bolder mt-2">Title: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.form.titleColor"
                    :position="colorPanelPosition"
                    ></color-picker>
                </div>
            </div>

            <label class="font-weight-bolder mt-3">Border Radius: </label>
            <b-input-group append="px">
                <b-form-input
                id="width"
                type="number"
                v-model="loginLayoutData.form.borderRadius"
                ></b-form-input>
            </b-input-group>
            <b-form-input
                id="width"
                type="range"
                class="mb-3"
                v-model="loginLayoutData.form.borderRadius"
            ></b-form-input>
        </div>
        <div class="col-xl-4">
            <label class="font-weight-bolder mt-2">Font-family: </label>
            <font-picker
                id="font-picker-titlemain"
                :api-key="'AIzaSyATWHa7sa_wkuyqb9oXWO64NyJGX3RuQEY'"
                :active-font="fontFamilyMain"
                :options="optionsTitleMain"
                @change="changeFontFamilyMain"
            >
            </font-picker>

            <label class="font-weight-bolder mt-2">Font-size Title: </label>
            <b-input-group append="px">
                <b-form-input
                id="width"
                type="number"
                v-model="loginLayoutData.form.fontSizeTitle"
                ></b-form-input>
            </b-input-group>
            <b-form-input
                id="width"
                type="range"
                class="mb-3"
                v-model="loginLayoutData.form.fontSizeTitle"
            ></b-form-input>
        </div>
    </div>
    <!--#region button -->
    <div class="col-xl-12 mx-auto mt-20">
        <h3 class="text-center mb-5">Style of button</h3>
    </div>
    <div class="d-flex justify-content-around">
        <div class="col-xl-4 d-flex flex-column">
            <label class="font-weight-bolder">Alignment: </label>
            <b-form-select
                id="font-weight"
                class="mb-5"
                v-model="btnAlign"
                :options="btnOptions"
                @change="changeBtnAlign(btnAlign)"
            ></b-form-select>

            <div class="d-flex justify-content-between mt-6">
                <div class="d-flex flex-column">
                    <label class="font-weight-bolder">Text: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.button.color"
                    ></color-picker>
                </div>
                <div class="d-flex flex-column">
                    <label class="font-weight-bolder">Border: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.button.borderColor"
                    ></color-picker>
                </div>
                <div class="mr-10 d-flex flex-column">
                    <label class="font-weight-bolder">Background: </label>
                    <color-picker
                    id="login-bg"
                    v-model="loginLayoutData.button.backgroundColor"
                    :position="colorPanelPosition"
                    ></color-picker>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <label class="font-weight-bolder">Border Radius: </label>
            <b-input-group append="px">
                <b-form-input
                id="width"
                type="number"
                min="0"
                max="40"
                v-model="loginLayoutData.button.borderRadius"
                ></b-form-input>
            </b-input-group>
            <b-form-input
                id="width"
                type="range"
                class="mb-3"
                min="0"
                max="40"
                v-model="loginLayoutData.button.borderRadius"
            ></b-form-input>
        </div>
    </div>
    <!-- #endregion -->
</div>
</template>

<script>
import { mapGetters } from "vuex";
import FontPicker from "font-picker-vue";
import {
  BFormInput,
  BFormGroup,
  BInputGroup,
  BFormSelect,
  BFormSelectOption,
  BInputGroupAppend
} from "bootstrap-vue";
export default {
components:{
  BFormInput,
  BFormGroup,
  BInputGroup,
  BFormSelectOption,
  BInputGroupAppend,
  FontPicker,
  BFormSelect,
  BFormSelectOption
},
computed:{
    ...mapGetters(["loginLayoutData"]),
    fontFamilyMain: {
      get: function () {
        return (
          this.loginLayoutData.form.fontFamily || "Poppins"
        );
      },
      set: function (newValue) {
        if (newValue) {
          this.loginLayoutData.form.fontFamily = newValue;
        }
      },
    },
    btnAlign: {
      get: function () {
        return this.loginLayoutData.button.buttonAlign || "text-center";
      },
      set: function (newValue) {
        if (newValue) {
          this.loginLayoutData.button.buttonAlign = newValue;
        }
      },
    },
},
data(){
    return{
        colorPanelPosition: { left: "-220px", top: 0 },
        optionsTitleMain: {
            name: "titleLogin",
        },
        btnOptions: [
            { value: "justify-content-start", text: "Left" },
            { value: "justify-content-center", text: "Center" },
            { value: "justify-content-end", text: "Right" },
            { value: "fullW", text: "Full Width" },
        ],
    }
},
methods:{
    /** Font title */
    changeFontFamilyMain(e) {
      this.fontFamilyMain = e.family;
      this.loginLayoutData.form.fontFamily = e.family;
    },
    changeBtnAlign(align) {
      this.loginLayoutData.button.buttonAlign = align;
    },
}
}
</script>

<style lang="scss">
.font-picker {
  width: 100% !important;
  box-shadow: none !important;
  border: 1px solid #e4e6ef !important;
  border-radius: 0.42rem !important;
  .dropdown-button {
    background-color: #fff !important;
  }
}
.one-colorpicker {
  .color-block {
    border: 2px solid #d9d9d9;
  }
}
</style>
