<template>
  <div class="ibox main-show rounded">
     <b-overlay opacity="0" :show="loadingEmailTemplateTable" rounded="sm">
        <div class="form-group">
            <div class="col-sm-10">
                <p class="font-italic text-primary">
                {{ notifyText}}
                </p>
            </div>
        </div>
        <div class="form-group">
        <div
            class="col-sm-2 control-label navi-text font-weight-bolder font-size-lg"
        >
            Status:
        </div>
        <div class="col-sm-10">
            <span class="switch switch-icon mb-5">
            <label>
                <b-form-checkbox
                v-model="status"
                id="is_private"
                switch
                size="lg"
                value=1
                unchecked-value=0
                ></b-form-checkbox>
                <span></span>
            </label>
            </span>
        </div>
        </div>
        <div class="form-group">
        <div
            class="col-sm-2 control-label navi-text font-weight-bolder font-size-lg"
        >
            Subject:
        </div>
        <div class="col-sm-10 pr-0" >
            <input type="text" class="form-control" :class="{ 'form-group--error': $v.subject.$error }" v-model="$v.subject.$model" />
            <div class="fv-plugins-message-container" v-if="!$v.subject.required">
                <div class="fv-help-block">Field is required</div>
            </div>
        </div>
        </div>

        <editor
        :class="{ 'editor-error': $v.editorData.$error }"
        :plugins="myPlugins"
        :toolbar="myToolbar"
        ref="editor"
        v-model="$v.editorData.$model"
        :api-key="editorKey"
        :init="myInit"
        />
        <div class="fv-plugins-message-container" v-if="!$v.editorData.required">
            <div class="fv-help-block">Field is required</div>
        </div>
        <div class="d-flex justify-content-end">
        <LoadingSubmitButton  :loading="submitLoading" @click="saveEmailTemplate" class="float-right mt-10">
            Save
        </LoadingSubmitButton>
        </div>
        <template #overlay>
            <TableLoader></TableLoader>
        </template>
    </b-overlay>
  </div>
</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";

import ApiService from "@/core/services/api.service";
import TableLoader from "@/view/content/TableLoader.vue";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import Editor from '@tinymce/tinymce-vue'
import { BOverlay,BFormCheckbox } from 'bootstrap-vue'
export default {
    mixins: [validationMixin],
  components:{
    BFormCheckbox,
    BOverlay,
    TableLoader,
    LoadingSubmitButton,
    'editor': Editor
    },

    data() {
    return {
      submitLoading: false,
      loadingEmailTemplateTable:false,
			status: 0,
			subject: '',
      editorData: "<p>Hello world</p>",
      default_when_send_mail: "Sent when an affiliate registers an account, waiting to be reviewed.",
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
        height:500,
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
      }
    };
  },
  validations:{
    subject:{
      required
    },
    editorData:{
      required
    }
  },
	watch: {
    '$route': 'loadEmailTemplate'
  },
	computed:{
    slug() {
      return this.$route.params.slug;
    },
    notifyText(){
      if(this.$route.params.slug == "affiliate_review"){
        return "Sent when an affiliate signs up an account and waits to be reviewed.";
      }
      else if(this.$route.params.slug == "approved_affiliate"){
        return "Sent when an affiliate is approved."
      }
      else if(this.$route.params.slug == "denied_affiliate"){
        return "Sent when an affiliate is denied."
      }
      else if(this.$route.params.slug == "new_conversion"){
        return "Sent when a conversion is recorded."
      }
      else if(this.$route.params.slug == "approved_conversion"){
        return "Sent when a conversion is approved."
      }
      else if(this.$route.params.slug == "denied_conversion"){
        return "Sent when a conversion is rejected."
      }
      else if(this.$route.params.slug == "commission_payout"){
        return "Sent when a commission is marked as paid."
      }
      else if(this.$route.params.slug == "invitation_affiliate")
      {
        return "Sent to affiliates when they are manually added, imported to the program or signup in checkout pop-up."
      }
      else
      return this.default_when_send_mail;
    },
    editorKey(){
        return process.env.MIX_SITE_KEY_EDITOR;
    }
	},
	methods:{
        hehe(){
            console.log("aaaa");
        },
    saveEmailTemplate(){
      this.submitLoading=true;
      if (this.$v.subject.$error || this.$v.editorData.$error) {
         this.submitLoading=false;
        return;
      }
      let email={
        subject:this.subject,
        content:this.editorData,
        status:this.status,
        slug:this.slug,
      }
      ApiService.post('emailtemplates',email).
        then(({data})=>{
          this.$store.dispatch('getListStatus');
          this.submitLoading=false;
          this.$toast.success('Updated',{position:'bottom'});
        })

    },
	async loadEmailTemplate() {
      this.loadingEmailTemplateTable=true;
      let slug=this.slug;
      let resource=`emailtemplates/${slug}`;
      await ApiService.query(resource).
      then(({data})=>{
        this.status=data.data.status;
        this.subject=data.data.subject;
        this.editorData=data.data.content;
        this.loadingEmailTemplateTable=false;
      }
      )
    }
	},
    created(){
    this.loadEmailTemplate();
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
.editor-error ~ .tox-tinymce{
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
