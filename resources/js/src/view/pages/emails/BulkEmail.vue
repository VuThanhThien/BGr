<template>
  <div>
    <alert-note :type="'alert-white alert-shadow'">
      Send emails to a large group of affiliates at once.
    </alert-note>
    <div class="row row d-flex">
      <div class="col-lg-12">
        <div class="card card-custom gutter-b">
          <div class="card-header py-3">
            <h3 class="card-title align-items-start flex-column w-100">
              <span class="card-label font-weight-bolder text-bixgrow">
                Bulk Email
              </span>
              <span class="text-muted mt-3 font-weight-bold font-size-sm w-100">
              </span>
            </h3>
          </div>
          <div class="card-body">
            <h6 class="font-weight-bold text-dark mb-4">From</h6>
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Email Address:"
              label-for="from"
            >
              <div class="d-flex flex-column">
                <input
                  type="email"
                  class="form-control"
                  disabled="disabled"
                  :value="fromContactEmail"
                />
                <span
                  class="form-text text-muted mt-2"
                  v-if="!isVerifiedFromEmail"
                >
                  Your affiliates will receive emails from us at
                  <code>{{ fromContactEmail }}</code
                  >. To use your email as a sender email, go
                  <a class="font-weight-bold" href="#/settings/notification"
                    >here</a
                  >.
                </span>
                <span class="form-text text-muted mt-2" v-else>
                  We will send email alerts to your affiliates on behalf of your
                  business email
                  <code>{{ fromContactEmail }}</code
                  >. To update your business's email address, go
                  <a class="font-weight-bold" href="#/settings/notification"
                    >here</a
                  >.
                </span>
              </div>
            </b-form-group>
            <h6 class="font-weight-bold text-dark mb-4 border-top pt-4">To</h6>
            <b-form-group
              id="predefined-filter"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Select predefined filter:"
            >
              <div class="d-flex flex-column">
                <select
                  @change="resetProgramAffiliates()"
                  v-model="predefinedSelected"
                  class="form-control form-control-custom"
                >
                  <option
                    v-for="(o, index) in predefinedFilter"
                    :value="o.value"
                    :key="index"
                  >
                    {{ o.text }}
                  </option>
                </select>
                <span class="form-text text-muted mt-2"
                  >Filters from affiliate manager screen.</span
                >
              </div>
            </b-form-group>
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="E-Mail addresses *"
              label-for="mail_addresses"
              v-if="predefinedSelected == 'specify'"
            >
              <SearchAff @input="setAffiliatesSelected" ref="searchAff" />
            </b-form-group>
            <b-form-group
              id="program"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Program *:"
              v-if="predefinedSelected == 'program'"
            >
              <div class="d-flex flex-column">
                <select
                  v-model="groupSelected"
                  class="form-control form-control-custom"
                >
                  <option
                    v-for="(o, index) in groupSelectOption"
                    :value="o.value"
                    :key="index"
                  >
                    {{ o.text }}
                  </option>
                </select>
                <span class="form-text text-muted mt-2"
                  >When choosing a program, all affiliates on that program will
                  receive your email.</span
                >
              </div>
            </b-form-group>
            <h6 class="font-weight-bold text-dark mb-4 border-top pt-4">
              Mail
            </h6>
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Subject *:"
              label-for="Subject"
            >
              <div class="d-flex flex-column">
                <b-form-input
                  placeholder="Subject"
                  :class="{ 'form-group--error': $v.subject.$error }"
                  v-model="$v.subject.$model"
                ></b-form-input>
                <div
                  class="fv-plugins-message-container"
                  v-if="!$v.subject.required"
                >
                  <div class="fv-help-block">Field is required</div>
                </div>
              </div>
            </b-form-group>
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Content :"
              label-for="Content"
            >
              <editor
                ref="editor"
                v-model="$v.editorData.$model"
                :plugins="myPlugins"
                :toolbar="myToolbar"
                :api-key="editorKey"
                :init="myInit"
              />
            </b-form-group>
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label=""
              label-for="Content"
              ><strong>Tags: </strong>
              <span class="label label-light label-inline mr-2 py-5 m-2"
                ><i class="fas fa-tag"></i> { first_name } :
                {{ first_name }}</span
              >
              <span class="label label-light label-inline mr-2 py-5 m-2">
                <i class="fas fa-tag"></i>{ brand_name } :
                {{ branch_name }}</span
              >
            </b-form-group>
            <div class="form-group justify-content-end">
              <LoadingSubmitButton
                :loading="submitLoading"
                class="float-right"
                @click="sendBulkEmail"
              >
                Send Emails
              </LoadingSubmitButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BFormGroup, BFormInput } from "bootstrap-vue";
import Editor from "@tinymce/tinymce-vue";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import SearchAff from "@/view/content/SearchMultipleAffiliate.vue";
import { mapGetters } from "vuex";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import ApiService from "@/core/services/api.service";
export default {
  mixins: [validationMixin],
  data() {
    return {
      groupSelected: null,
      affiliatesFilterSelected: [],
      submitLoading: false,
      predefinedSelected: "all",
      predefinedFilter: [
        { value: "all", text: "All Affiliates" },
        { value: "specify", text: "* Specify E-Mail Addresses" },
        { value: "program", text: "* Program" },
        { value: "approved", text: "Approved affiliates" },
        { value: "denied", text: "Denied affiliates" },
      ],
      subject: "Subject",
      editorData:
      `<div style="font-family: Arial,Helvetica,sans-serif; text-align: center; background-color: #f1f3f4;">
            <div style="height: 20px;">&nbsp;</div>
            <div style="height: 20px;">&nbsp;</div>
            <div style="margin: 0 auto; max-width: 100%;">
            <h1>{brand_name}</h1>
            </div>
            <div style="height: 20px;">&nbsp;</div>
            <div style="padding: 30px 20px; margin: 0 auto; background-color: #ffffff; width: 600px; max-width: 100%;">
            <div style="padding-bottom: 30px; font-size: 15px; text-align: left;">
            <div style="padding-bottom: 30px; font-size: 17px;">Hi <strong>{first_name}</strong></div>
            ...
            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;">&nbsp;</div>
            <!--end:Email content-->
            <div style="padding-bottom: 10px;">Kind regards,</div>
            </div>
            </div>
            <div style="height: 150px;">&nbsp;</div>
        </div>`,
      first_name: "Affiliate's first name",
      branch_name: "Your brand name",

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
      },
    };
  },
  components: {
    BFormGroup,
    BFormInput,
    editor: Editor,
    LoadingSubmitButton,
    SearchAff,
  },
  methods: {
    resetProgramAffiliates() {
      if (this.predefinedSelected != "specify") {
        this.affiliatesFilterSelected = [];
      }
    },
    setAffiliatesSelected(value) {
      this.affiliatesFilterSelected = value;
      if (value.length > 0) {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.remove(
          "border-error"
        );
      } else {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
          "border-error"
        );
      }
    },
    sendBulkEmail() {
      let lengthAffiliatesFilter = this.affiliatesFilterSelected.length;
      this.submitLoading = true;
      if (
        this.$v.subject.$error ||
        this.$v.editorData.$error ||
        (!lengthAffiliatesFilter && this.predefinedSelected == "specify")
      ) {
        if (!lengthAffiliatesFilter && this.predefinedSelected == "specify") {
          this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
            "border-error"
          );
        }
        this.submitLoading = false;
        return;
      }
      let email = {
        subject: this.subject,
        content: this.editorData,
        predefinedSelected: this.predefinedSelected,
        program: this.groupSelected,
        affiliates: this.affiliatesFilterSelected.length>0? this.affiliatesFilterSelected.map(affiliate =>{
         return affiliate.id;
        }):[],
      };
      let resource = `emailtemplates/bulk-email`;
      ApiService.post(resource, email).then(({ data }) => {
        this.submitLoading = false;
         this.$toast.success('Sent ', { position: "bottom" });
      });
    },
  },
  computed: {
    ...mapGetters(["groups", "fromContactEmail", "isVerifiedFromEmail"]),
    groupSelectOption() {
      let options = [];
      let groups = this.groups;
      for (var i = 0; i < groups.length; i++) {
        if (groups[i].is_default == 1) {
          this.groupSelected = groups[i].id;
          options.push({
            value: groups[i].id,
            text: this.groups[i].name + " (Default Program)",
          });
        } else {
          options.push({
            value: groups[i].id,
            text: groups[i].name,
          });
        }
      }
      return options;
    },
    editorKey(){
        return process.env.MIX_SITE_KEY_EDITOR;
    }
  },
  validations: {
    subject: {
      required,
    },
    editorData: {
      required,
    },
  },
};
</script>
<style scoped>
.tag-editor {
  background-color: #fff;
  padding: 20px 40px;
  border-color: #e8e9f2;
  min-height: 90px;
  border-style: none;
  text-align: left;
  align-items: flex-start;
}
.form-group--error {
  border-color: #f64e60;
}
.error {
  color: #f64e60;
}
</style>

<style>
.border-error {
  border-color: #f64e60 !important;
}
</style>
