<template>
  <b-modal ref="detailPaymentMethodModal"  scrollable  size="lg"  title="Detail payment method">
  <div>
       <div class="form-preview-box">
            <h4>{{method.name}}</h4>
            <div>
                <vue-form-generator :schema="{fields:method.fields}" :model="model" :options="formOptions" >
                </vue-form-generator>
            </div>
        </div>
  </div>
    <template #modal-footer="{ hide }">
      <button class="btn btn-default" @click="hide()">
          Close
      </button>
      
     
    </template>
  
</b-modal>
</template>

<script>
import VueFormGenerator from 'vue-form-generator'
// import 'vue-form-generator/dist/vfg.css'
export default {
    data(){
        return {
            model:{},
            formOptions: {
                validateAfterChanged: true
            },
        }
    },
    props:['method'],
   
    components: {
        "vue-form-generator": VueFormGenerator.component
    },
   
}
</script>

<style>

</style>