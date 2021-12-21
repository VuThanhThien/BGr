    <template>
  <div>
    <div
      class="alert alert-custom alert-white alert-shadow fade show gutter-b"
      role="alert"
    >
      <div class="alert-icon">
        <span class="svg-icon svg-icon-primary svg-icon-xl">
          <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Compass.svg-->
          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            width="24px"
            height="24px"
            viewBox="0 0 24 24"
            version="1.1"
          >
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <rect x="0" y="0" width="24" height="24"></rect>
              <path
                d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                fill="#000000"
                opacity="0.3"
              ></path>
              <path
                d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                fill="#000000"
                fill-rule="nonzero"
              ></path>
            </g>
          </svg>
          <!--end::Svg Icon-->
        </span>
      </div>
      <div class="alert-text">
        <span class="alert-heading" style="font-size: 15px; font-weight: 600"
          >Choose the language for your affiliate portal here.</span
        >
        <br />Affiliates will view their accounts in the language you select or they can pick one from the list.
      </div>
    </div>
    <div class="card card-custom">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Affiliate account language
          </h3>
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
      <div class="card-body">
        <!-- branch name  -->
        <b-form-group
          id="fieldset-horizontal"
          label-cols-sm="4"
          label-cols-lg="3"
          content-cols-sm="8"
          content-cols-lg="9"
          label="Choose language:"
          label-for="language"
        >
          <div class="d-flex flex-column">
            <select
              v-model="defaultAffiliateLanguage"
              class="form-control form-control-custom"
            >
              <option
                v-for="(lang, index) in languages"
                :value="lang.value"
                :key="index"
              >
                {{ lang.text }}
              </option>
            </select>
            <span class="form-text text-muted mt-2"
              >Select the default language that will be displayed in affiliate account. You can edit your
              own version by clicking
              <router-link :to="{ name: 'translations' }"
                >here</router-link
              ></span
            >
          </div>
        </b-form-group>
        <b-form-group
          id="fieldset-horizontal-1"
          label-cols-sm="4"
          label-cols-lg="3"
          content-cols-sm="8"
          content-cols-lg="9"
          label=" Allow affiliate to select language: "
          label-for="allowLanguage"
        >
          <div class="d-flex flex-column">
            <b-form-checkbox
              v-model="enableAffiliateLanguage"
              id="allowLanguage"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
              >Allow affiliates to select their preferred language from a
              drop-down menu on their account.
            </span>

          </div>
        </b-form-group>
        <b-form-group
          id="fieldset-horizontal-2"
          label-cols-sm="4"
          label-cols-lg="3"
          content-cols-sm="8"
          content-cols-lg="9"
          label=" Auto detect language: "
          label-for="autoDetectLanguage"
        >
          <div class="d-flex flex-column">
            <b-form-checkbox
              v-model="autoDetectLanguage"
              id="autoDetectLanguage"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
              >Language is automatically detected based on the affiliate's
              browser language. If the affiliate's browser language is not one of the languages listed above, the default language will be
              the one you selected above.
            </span>
          </div>
        </b-form-group>
      </div>
      <!--end::Form-->
      <div class="card-footer d-flex align-items-center justify-content-end">
        <div class="card-toolbar float-right">
          <LoadingSubmitButton :loading="submitLoading" @click="saveChange">
            Save Changes
          </LoadingSubmitButton>
        </div>
      </div>
    </div>
  </div>
  <!--begin::Card-->
</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
import { BFormGroup, BFormCheckbox } from "bootstrap-vue";
export default {
  components: {
    LoadingSubmitButton,
    BFormGroup,
    BFormCheckbox,
  },
  data() {
    return {
      submitLoading: false,
      defaultAffiliateLanguage: "en",
      enableAffiliateLanguage: 0,
      autoDetectLanguage: 0,
      languages: [
        { text: "English", value: "en" },
        { text: "Spanish", value: "es" },
        { text: "Chinese", value: "zh" },
        { text: "German", value: "de" },
        { text: "French", value: "fr" },
        { text: "Portuguese", value: "pt" },
        { text: "Italian", value: "it" },
      ],
    };
  },
  computed: {},
  methods: {
    saveChange() {
      this.submitLoading = true;
      let resource = `/settings/affiliate-language`;
      let params = {
        default_affiliate_language: this.defaultAffiliateLanguage,
        enable_affiliate_language: this.enableAffiliateLanguage,
        auto_detect_language: this.autoDetectLanguage,
      };
      ApiService.put(resource, params).then(({ data }) => {
        this.submitLoading = false;
        this.$store.commit("UPDATE_AFFILIATE_LANGUAGE", params);
        this.$toast.success("Updated", {
          position: "bottom",
        });
      });
    },
    getAffiliateLanguage() {
      let resource = `/settings/affiliate-language`;
      ApiService.query(resource).then(({ data }) => {
        if (data.data) {
          this.defaultAffiliateLanguage = data.data.default_affiliate_language;
          this.enableAffiliateLanguage = data.data.enable_affiliate_language;
          this.autoDetectLanguage = data.data.auto_detect_language;
        }
      });
    },
  },
  created() {
    this.getAffiliateLanguage();
  },
};
</script >

<style scoped>
.form-group--error {
  border-color: #f64e60;
}
.error {
  color: #f64e60;
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
</style>
