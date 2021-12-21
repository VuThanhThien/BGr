<template>
<div>
    <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">Size of login form</h3>
    </div>
    <div class="d-flex justify-content-around">
        <div class="col-xl-4">
            <label class="font-weight-bolder mt-2">Content: </label>
            <b-input-group append="px">
                <b-form-input
                id="width"
                type="number"
                min="450"
                max="800"
                v-model="loginLayoutData.size.width"
                ></b-form-input>
            </b-input-group>
            <b-form-input
                id="width"
                type="range"
                class="mb-3"
                min="450"
                max="800"
                v-model="loginLayoutData.size.width"
            ></b-form-input>

            <label class="font-weight-bolder mt-2">Margin top: </label>
            <b-form-input
                id="title2"
                type="number"
                v-model="loginLayoutData.size.mt"
            ></b-form-input>

            <label class="font-weight-bolder mt-2">Margin bottom of logo: </label>
            <b-form-input
                id="title2"
                type="number"
                v-model="loginLayoutData.size.gapImg"
            ></b-form-input>
        </div>
        <div class="col-xl-4">
            <label class="font-weight-bolder mt-2">Padding left/right: </label>
            <b-form-input
                id="title2"
                type="number"
                v-model="loginLayoutData.size.px"
            ></b-form-input>

            <label class="font-weight-bolder mt-11">Padding top/bottom: </label>
            <b-form-input
                id="title2"
                type="number"
                v-model="loginLayoutData.size.py"
            ></b-form-input>
        </div>
    </div>
</div>
</template>

<script>
import { mapGetters } from "vuex";
import {
  BFormInput,
  BFormGroup,
  BInputGroup,
  BFormSelectOption,
  BInputGroupAppend
} from "bootstrap-vue";
export default {
components:{
  BFormInput,
  BFormGroup,
  BInputGroup,
  BFormSelectOption,
  BInputGroupAppend
},
computed:{
    ...mapGetters(["loginLayoutData"]),
}
}
</script>

<style>

</style>
