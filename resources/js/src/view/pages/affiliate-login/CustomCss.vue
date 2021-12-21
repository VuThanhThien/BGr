<template>
<div>
    <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">Show your logo</h3>
    </div>
    <div class="d-flex justify-content-around">
        <div class="col-xl-4 d-flex flex-column">
            <label class="font-weight-bolder">Enable: </label>
            <span class="switch switch-icon mb-1">
            <label style="margin-top: -20px;">
                <b-form-checkbox
                v-model="loginLayoutData.logo.isShow"
                id="is_private"
                switch
                size="lg"
                :disabled="haveNoLogo"
                ></b-form-checkbox>
                <span></span>
            </label>
            </span>
            <span class="text-muted mb-5" v-if="haveNoLogo">You need to upload a logo first. Click <a href="#/settings/general">here</a> to upload.</span>
        </div>
        <div class="col-lg-4">
            <label class="font-weight-bolder mt-2">Logo size: </label>
            <b-input-group append="%">
                <b-form-input
                id="width"
                type="number"
                v-model="loginLayoutData.logo.width"
                ></b-form-input>
            </b-input-group>
            <b-form-input
                id="width"
                type="range"
                class="mb-3"
                v-model="loginLayoutData.logo.width"
            ></b-form-input>
        </div>
    </div>

    <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">CustomCss</h3>
    </div>
    <b-form-textarea
        id="required"
        v-model="loginLayoutData.customcss"
        placeholder="Enter code css here..."
        rows="10"
    ></b-form-textarea>
</div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import FontPicker from "font-picker-vue";
import {
  BFormInput,
  BFormGroup,
  BInputGroup,
  BFormSelect,
  BFormSelectOption,
  BInputGroupAppend,
  BFormCheckbox,
  BFormTextarea
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
  BFormSelectOption,
  BFormCheckbox,
  BFormTextarea
},
computed:{
    ...mapGetters(["loginLayoutData"]),
    ...mapState({
      logo: (state) => state.layout.settings.logo,
    }),
    haveNoLogo() {
      return !!this.logo ? false : true;
    },
},
data(){
    return{

    }
},
methods:{

}
}
</script>


