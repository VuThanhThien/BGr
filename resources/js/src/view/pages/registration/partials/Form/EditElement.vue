<template>
  <div>
    <!-- nút back  -->
    <div class="btn-back" @click="closeOnSelect">
         <a >
             <i class="fas fa-angle-left text-primary"></i>
             <span class="text-primary">Back</span>
         </a>
     </div>
     <div class="col-xl-12 mx-auto">
      <h3 class="text-center mb-10">Edit Element</h3>
    </div>

    <div v-if="!objToEdit.disableEdit" class="d-flex justify-content-between mb-5 pr-5 align-items-center">
        <label class="font-weight-bolder">Enable: </label>
        <b-form-checkbox
            id="using"
            switch
            size="lg"
            style="margin-top: -20px;"
            v-model="objToEdit.visible"
            :disabled="objToEdit.disableEdit"
        ></b-form-checkbox>
    </div>


    <!-- edit label -->
    <label class="font-weight-bolder">Label: </label>
        <b-form-input
            id="field"
            v-model="objToEdit.label"
            class="mb-5"
        ></b-form-input>

    <!-- edit required -->
    <div
    v-if="'required' in objToEdit"
    class="d-flex justify-content-between pr-5 mb-5 align-items-center"
    >
    <label class="font-weight-bolder">Required: </label>
        <b-form-checkbox
        id="required"
        v-model="objToEdit.required"
        switch
        size="lg"
        style="margin-top: -20px;"
        ></b-form-checkbox>
    </div>

    <!-- edit placeholder -->
    <div
    v-if="'placeholder' in objToEdit && objToEdit.type =='input'"
    >
    <label class="font-weight-bolder">Placeholder: </label>
        <b-form-input
            id="placeholder"
            v-model="objToEdit.placeholder"
            class="mb-5"
        ></b-form-input>
    </div>
    <!-- nếu là select -->
    <div v-if="(objToEdit.type == 'select' || objToEdit.type == 'radios') && objToEdit.model !=='country'">
        <div class="col-xl-12 mx-auto">
            <h3 class="text-center mb-10">Edit {{objToEdit.type}}</h3>
        </div>
        <div
        v-for="(obj, i) in objToEdit.values"
        :i="i"
        :key="i"
        class="mb-5"
        >
        <label class="font-weight-bolder">Option {{i + 1}}:</label>
        <b-input-group >
            <b-form-input
                id="selectvalue"
                v-model="obj.name"
            ></b-form-input>
            <b-input-group-append>
            <button class="btn btn-sm btn-primary" @click="onDeleteChoice(i, objToEdit.values)">
                <span class="svg-icon svg-icon-lg svg-icon-white">
                    <inline-svg src="media/svg/icons/Home/Trash.svg" />
                </span>
            </button>
            </b-input-group-append>
        </b-input-group>
        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary btn-md px-10 my-5" @click="addChoice(objToEdit.values)">
                <span class="svg-icon svg-icon-lg svg-icon-white">
                    <inline-svg src="media/svg/icons/Code/Plus.svg" />
                </span>
                Add choice</button>
        </div>
    </div>
    <!-- nếu là textArea -->
    <div v-if="objToEdit.type == 'textArea'">
        <div class="col-xl-12 mx-auto">
            <h3 class="text-center mb-10">Edit Textarea</h3>
        </div>
        <!-- hint  -->
        <div
        v-if="'hint' in objToEdit"
        >
        <label class="font-weight-bolder">Hint:</label>
            <b-form-input
                id="hint"
                v-model="objToEdit.hint"
                class="mb-5"
            ></b-form-input>
        </div>
        <!-- rows -->
        <label class="font-weight-bolder">Height:</label>
            <b-form-input
                id="height"
                v-model="objToEdit.rows"
                class="mb-5"
            ></b-form-input>
        <!-- max textarea -->
        <label class="font-weight-bolder">Max:</label>
            <b-form-input
                id="max"
                v-model="objToEdit.max"
                class="mb-5"
            ></b-form-input>

    </div>
    <!-- Nếu là input và vừa đc thêm mới -->
    <div v-if="objToEdit.type == 'input' && 'recentlyAdded' in objToEdit">
        <label class="font-weight-bolder">Input type:</label>
            <b-form-select
                id="inptype"
                v-model="inpType"
                :options="inptypeOptions"
                @change="changeInpType(inpType)"
            ></b-form-select>

    </div>
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary btn-md px-10 my-5" @click="onApplyChange">Done</button>
    </div>
  </div>
</template>

<script>
import {BFormSelect,BFormInput, BButton,BFormCheckbox,BFormTextarea, BFormGroup, BFormSelectOption, BInputGroup, BInputGroupAppend   } from 'bootstrap-vue'
import { mapGetters , mapActions } from "vuex";
export default {
    components: {
        BFormSelect,BFormInput, BButton,BFormCheckbox,BFormTextarea, BFormGroup, BFormSelectOption, BInputGroup , BInputGroupAppend
    },
    props:['objToEdit'],
    data() {
        return {
            inptypeOptions:[
                { value: 'text', text: 'Text'},
                { value: 'password', text: 'Password'},
                { value: 'email', text: 'Email'},
            ]
        }
    },
    methods: {
        ...mapActions(["changeBaseObj"]),
        closeOnSelect(){
            this.$emit("closeOnSelect")
        },
        onApplyChange(){
            this.$emit("onApplyChange", this.objToEdit)
            this.closeOnSelect();
        },
        addChoice(arr){
            var newChoice = { id: this.makeid(8), name: "New choice" };
            arr.push(newChoice);
            this.$emit("onApplyChange", this.objToEdit);
        },
        onDeleteChoice(index , arr){
            if( !!arr[index]){
                arr.splice(index, 1);
                this.$emit("onApplyChange", this.objToEdit);
            }
        },
        changeInpType(inpType){
            this.objToEdit.inputType = inpType;
            this.$emit("onApplyChange", this.objToEdit)
        }
    },
    computed:{
        inpType:{
        get: function () {
            return this.objToEdit.inputType;
        },
        set: function (newValue) {
            if(newValue){
            this.objToEdit.inputType = newValue;}
        }
        }
    }
}
</script>

<style>
.btn-back{
    cursor: pointer;
}
</style>
