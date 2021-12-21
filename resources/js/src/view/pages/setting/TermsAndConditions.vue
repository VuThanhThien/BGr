<template>

    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header py-3">
        <div class="card-title d-flex flex-column align-items-start">
            <div class="d-flex align-items-center">
                <h3 class="card-label font-weight-bolder text-bixgrow">Enable:</h3>
                <b-form-checkbox
                class="mb-5"
                id="enable"
                switch
                size="lg"
                value="1"
                unchecked-value="0"
                v-model="status"
                ></b-form-checkbox>
            </div>
        <span class="text-muted font-weight-bold font-size-sm mt-1">When enabled, Terms & Conditions and Privacy Policy will be included in Affiliate registration form</span>
        </div>
        <div class="p-5">
            <button class="btn-primary btn btn-sm" @click="getSampleTerm">Use Sample</button>
        </div>
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <div class="card-body">
            <h3 class="card-label font-weight-bolder text-bixgrow">Terms & Conditions</h3>
            <div v-if="loading == true">
                <Loader />
            </div>
            <editor
            v-else
            :class="{ 'editor-term-error': $v.editorDataTerm.$error }"
            ref="editor"
            v-model="$v.editorDataTerm.$model"
            :plugins="myPlugins"
            :toolbar="myToolbar"
            :api-key="editorKey"
            :init="myInit"
            />
            <h3 class="card-label font-weight-bolder text-bixgrow mt-20">Privacy Policy</h3>
            <div v-if="loading == true">
                <Loader />
            </div>
            <editor
            v-else
            :class="{ 'editor-policy-error': $v.editorDataPrivacy.$error }"
            ref="editor"
            v-model="$v.editorDataPrivacy.$model"
            :plugins="myPlugins"
            :toolbar="myToolbar"
            :api-key="editorKey"
            :init="myInit"
            />
        </div>
        <!--end::Form-->
        <div class="card-footer d-flex align-items-center justify-content-end">
        <div class="card-toolbar float-right">
            <LoadingSubmitButton :loading="submitTerm" @click="saveTermCondition">
            Save Changes
            </LoadingSubmitButton>
        </div>
        </div>
    </div>

</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "../../../core/services/api.service";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import Editor from '@tinymce/tinymce-vue'
import { mapState } from "vuex";
import { BFormCheckbox } from "bootstrap-vue";
import Loader from "@/view/content/LoaderVue.vue";
export default {
  mixins: [validationMixin],
  components: {
    LoadingSubmitButton,
    BFormCheckbox,
    'editor': Editor,
    Loader
  },
  data() {
    return {
      submitTerm: false,
      submitPolicy: false,
      editorDataTerm:'<p></p>',
      editorDataPrivacy:'<p></p>',
      myPlugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount emoticons'
            ],
      myToolbar:
            'undo redo | formatselect | bold italic forecolor backcolor | code \
            alignleft aligncenter alignright alignjustify | image |charmap emoticons\
            bullist numlist outdent indent | removeformat',
      myInit:{
        convert_urls : true,
        height:300,
        automatic_uploads: true,
        relative_urls : true,
        menubar: true,
        selector: 'textarea#local-upload',
        images_upload_handler: function (blobInfo, success, failure) {
            var image;
            image = new FormData();
            image.append('image', blobInfo.blob(), blobInfo.filename());
            ApiService.post('categorys/image-editor', image).
            then(({data})=>{
                success(data.data.location);
            }).catch(({ response }) => {
                failure('Something went wrong: ' + response);
                return;
            })
        }
      },
      status: 0,
      loading: true,
    };
  },
  validations: {
    editorDataTerm:{
      required
    },
    editorDataPrivacy:{
      required
    }
  },
 computed: {
      ...mapState({
      brand_name: (state) => state.layout.settings.brand_name,
      contact_email: (state) => state.layout.settings.contact_email,
    }),
    editorKey(){
        return process.env.MIX_SITE_KEY_EDITOR;
    }
 },
   created(){
    ApiService.query(`/settings/term_policy`).then(({ data }) => {
        if(data ){
            this.editorDataTerm = data.data.term;
            this.editorDataPrivacy = data.data.policy;
            this.status = data.data.is_enable_term_policy;
            this.loading = false;
        }
      });
  },
  methods: {
    putTermAndPrivacy(){
    ApiService.put("/settings/term_policy", {
        term : this.editorDataTerm,
        policy: this.editorDataPrivacy,
        status: this.status,
      }).then(({ data }) => {
        this.submitTerm = false;
        this.submitPolicy = false;
        this.$toast.success("Updated", {
          position: "bottom",
        });
      });
    },
    saveTermCondition() {
      this.submitTerm = true;
      this.$v.editorDataTerm.$touch();
      if (this.$v.editorDataTerm.$anyError) {
        this.submitTerm = false;
        return;
      }
      this.$v.editorDataPrivacy.$touch();
      if (this.$v.editorDataPrivacy.$anyError) {
        this.submitPolicy = false;
        return;
      }
    this.putTermAndPrivacy();
    },

    replaceTermCondition(textContent){
        var brandName = this.brand_name;
        var contactEmail ='<a title="'+ this.contact_email +'" href="mailto: '+this.contact_email+'" target="_blank" rel="noopener">'+  this.contact_email +'</a>';
        if(textContent){
            textContent = textContent.replaceAll(/{brand_name}/g, brandName);
            textContent = textContent.replaceAll(/{contact_email}/g, contactEmail);
        }
        else{textContent = "<p></p>"};
        return textContent;
    },
    getSampleTerm(){
        ApiService.query('/settings/sample_term_policy').then(({ data }) => {
        if(!!data ){
            this.editorDataTerm = this.replaceTermCondition(data.data.term);
            this.editorDataPrivacy = this.replaceTermCondition(data.data.policy);
        }
      });
    },
  },
};
</script>


<style lang="scss" scoped>
.control-label{
    padding-top: 7px;
    margin-bottom: 0;
    text-align: right;
}
.form-group{
  display: flex;
  align-items: center;
}
.main-show{
  background-color: #ffffff;
  padding: 3rem 4rem 1rem 4rem;
}
.form-group--error{
  border-color: #F64E60 ;
}
.error{
  color: #F64E60 ;
}

</style>
<style>
.editor-term-error ~ .tox-tinymce, .editor-policy-error ~ .tox-tinymce{
  border: 1px solid #F64E60 !important;
}
@media only screen and (max-width: 768px) {
    .form-group{
        display: block !important;
    }
    .control-label{
        text-align: left !important;
    }
    }
@media screen and (max-width: 600px){
    .main-show{
        padding: 1rem !important;
    }
}
</style>

