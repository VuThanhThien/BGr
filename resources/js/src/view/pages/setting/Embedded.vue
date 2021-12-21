<template>
  <div>
      <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
        <div class="alert-icon">
            <span class="svg-icon svg-icon-primary svg-icon-xl">
                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Compass.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                        <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </div>
        <div class="alert-text">
            <span class="alert-heading" style="font-size: 15px; font-weight: 600">Please let us know if the portal is not working properly!</span>
                    <br>We have tested the embedded version with various Shopify themes, however it may require some additional styling. <br>
                    Please do not hesitate to contact to our support team for assistance. We would be delighted to style it for you.
        </div>
    </div>
    <div class="card card-custom">
      <!--begin::Card header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Embedded Affiliate Portal Settings
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Embed the affiliate portal inside your store.</span
          >
        </div>
      </div>
      <div class="card-body">
        <b-form-group
        id="fieldset-horizontal"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label="Portal page enabled:"
        label-for="is_enable"
        >
        <div class="d-flex flex-column">
        <b-form-checkbox
            v-model="isEnable"
            id="is_enable"
            switch
            size="lg"
            value="1"
            unchecked-value="0"
        ></b-form-checkbox>
        <span class="form-text text-muted mt-5"
            >When enabled, the embedded version of affiliate portal is available at your Shopify store.
        </span>
        </div>
        </b-form-group>

        <b-form-group
        id="fieldset-horizontal"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label="Fullscreen mode:"
        label-for="is_enable1"
        >
        <div class="d-flex flex-column">
        <b-form-checkbox
            v-model="isFullscreen"
            id="checkbox1"
            switch
            size="lg"
            value="1"
            unchecked-value="0"
        ></b-form-checkbox>
        <span class="form-text text-muted mt-5"
            >When enabled, your shop header and footer are
            not displayed and the affiliate portal fills the entire browser window.</span
        >
        </div>
        </b-form-group>

        <b-form-group
        id="fieldset-horizontal"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label="Portal path:"
        label-for="path"
        >
        <div class="d-flex flex-column">
        <b-input-group id="path">
            <template #prepend>
            <b-input-group-text >{{
                pathDefault
            }}</b-input-group-text>
            </template>
            <b-form-input
            type="text"
            id="portal_path"
            class="form-control-custom"
            v-model="pathPage"
            ></b-form-input>
        </b-input-group>
        <span class="form-text text-muted mt-2"
            >Enter your desired portal path here and our system will automatically create the page for you.</span
        >
        </div>
        </b-form-group>
         <b-form-group
        id="fieldset-horizontal"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label="Program:"
        label-for="program"
        >
        <div class="d-flex flex-column">
                <select
                v-model="groupSelected"
                class="form-control form-control-custom"
              >
                <option v-for="(o, index) in groupSelectOption" :value="o.value" :key="index">
                  {{ o.text }}
                </option>
              </select>
        <span class="form-text text-muted mt-2"
            >Select the program that you want to get the affiliate sign up page.</span
        >
        </div>
        </b-form-group>
        <b-form-group
        id="fieldset-horizontal"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label="Additional CSS styles:"
        label-for="textarea-formatter"
        >
         <div class="d-flex flex-column">
        <b-form-textarea
            v-model="styleCSS"
            id="textarea-formatter"
            placeholder="Css..."
            rows="10"
          ></b-form-textarea>
        <span class="form-text text-muted mt-2"
            >Please leave the field empty if you don't know how to style the HTML elements.</span
        >
        </div>
        </b-form-group>
      </div>

      <div class="card-footer d-flex justify-content-between">
        <a :href="urlEmbeddedAffiliatePortal" target="_blank" class="btn btn-secondary btn-sm float-left" v-bind:class="[!isOpenEmbeddedPortal?'disabled':'']">Open embedded affiliate portal</a>
        <LoadingSubmitButton1
          :loading="submitLoading"
          @click="saveChange"
          class="float-right"
        >
          Save Changes
        </LoadingSubmitButton1>
      </div>
    </div>
        <div class="md-screen">
            <div class="d-flex tip-alert mt-5">
            <div class="col-md-2"></div>
            <div class="col-md-8">
            <Tips>
                Learn more about
                <a class="font-weight-bold" href="https://docs.bixgrow.com/settings/embedded-affiliate-portal" target="_blank">Embedded Affiliate Portal</a>.
            </Tips>
            </div>
            <div class="col-md-2"></div>
        </div>
        </div>
  </div>
</template>
<script>
import LoadingSubmitButton1 from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
import { mapGetters } from "vuex";
import { BFormInput,BFormGroup,BFormCheckbox,BFormTextarea,BInputGroupText,BInputGroup  } from 'bootstrap-vue'
export default {
  data() {
    return {
      isFullscreen: 0,
      submitLoading: false,
      pathPage: "",
      isEnable: 0,
      pageId: 0,
      styleCSS:'',
      groupSelected:''
    };
  },
  components: {
    LoadingSubmitButton1, BFormInput,BFormGroup,BFormCheckbox,BFormTextarea,BInputGroupText,BInputGroup
  },
  computed: {
    ...mapGetters(["shopDomain","groups"]),
    pathDefault() {
      return "https://" + this.shopDomain + "/pages/";
    },
    urlEmbeddedAffiliatePortal(){
      return this.pathDefault + this.pathPage;
    },
    isOpenEmbeddedPortal(){
      if(this.pageId==0 || this.isEnable==0)
      {
        return false;
      }
      return true;
    },
    groupSelectOption() {
      let options = [];
      let groups = this.groups;
      for (var i = 0; i < groups.length; i++) {
         if (this.groups[i].is_active==1)
         {
        if(groups[i].is_default==1)
        {
          this.groupSelected=groups[i].id;
           options.push({
          value: groups[i].id,
          text: this.groups[i].name +' (Default Program)',
         });
        }
        else{
           options.push({
          value: groups[i].id,
          text: groups[i].name,
        });
        }
         }
      }
      this.getList();
      return options;
    }
  },
  methods: {
    getList() {
      let resource = `/settings/get-page`;
      ApiService.query(resource).then(({ data }) => {
        if (data.data) {
          this.isFullscreen = data.data.is_fullScreen;
          this.pathPage = data.data.portal_path;
          this.isEnable = data.data.is_enable;
          this.pageId = data.data.page_id;
          this.styleCSS = data.data.css;
          if(typeof  data.data.group_id !== 'undefined')
          {
              this.groupSelected=data.data.group_id;
          }
        }
      });
    },
    saveChange() {
      if (!this.pathPage) {
        this.$toast.error("Portal path empty.", {
          position: "bottom",
        });
        return;
      }
      let resource = `/settings/create-embedded-portal`;
      this.submitLoading = true;
      ApiService.post(resource, {
        path: this.pathPage,
        isEnable: this.isEnable,
        isFullscreen: this.isFullscreen,
        page_id: this.pageId,
        css:this.styleCSS,
        group_id:this.groupSelected
      })
        .then(({ data }) => {
          if(data.data.status) {
            this.pageId=data.data.id;
             this.submitLoading = false;
            this.$toast.success("Updated", {
              position: "bottom",
            });
          } else {
            this.submitLoading = false;
            this.pageId=0;

            this.$toast.error(data.data.message, {
              position: "bottom",
            });
          }
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            this.$toast.error(response.data.errors.path[0], {
              position: "bottom",
            });
            this.submitLoading = false;
          } else {
            this.$toast.error("Errors", {
              position: "bottom",
            });
            this.submitLoading = false;
          }
        });
    },
  },
};
</script>
