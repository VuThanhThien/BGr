    <template>
  <!--begin::Card-->
  <div class="card card-custom">
    <!--begin::Header-->
    <div class="card-header py-3">
      <div class="card-title align-items-start flex-column">
        <h3 class="card-label font-weight-bolder text-bixgrow">Advanced</h3>
        <span class="text-muted font-weight-bold font-size-sm mt-1"
          >Advanced settings</span
        >
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
        label="Default affiliate link:"
        label-for="default_affiliate_link"
      >
        <div class="d-flex flex-column">
          <b-form-input
            id="default_affiliate_link"
            v-model="$v.shopSettingCurrent.default_affiliate_link.$model"
            :class="{
              'form-group--error':
                $v.shopSettingCurrent.default_affiliate_link.$error,
            }"
            :state="validateState('default_affiliate_link')"
          ></b-form-input>
          <div
            class="fv-plugins-message-container"
            v-if="
              !$v.shopSettingCurrent.default_affiliate_link.required ||
              !$v.shopSettingCurrent.default_affiliate_link.url
            "
          >
            <div class="fv-help-block">Field is required and valid link</div>
          </div>
          <span class="form-text text-muted mt-2"
            >Default affiliate link is a page on your store where affiliate
            links are firstly directed to. You can change the link to other
            pages in here If you want.
          </span>
        </div>
      </b-form-group>
      <b-form-group
        id="fieldset-horizontal-1"
        label-cols-sm="4"
        label-cols-lg="3"
        content-cols-sm="8"
        content-cols-lg="9"
        label=" Allow affiliates to edit custom link: "
        label-for="new_registrations"
      >
        <b-form-checkbox
          v-model="shopSettingCurrent.allow_affiliates_custom_link"
          id="new_registrations"
          switch
          size="lg"
          value="1"
          unchecked-value="0"
        ></b-form-checkbox>
      </b-form-group>
    </div>
    <!--end::Form-->
    <div class="card-footer d-flex align-items-center justify-content-end">
      <div class="card-toolbar float-right">
        <LoadingSubmitButton :loading="submitLoading" @click="updateSettting">
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
import { required, minLength, url } from "vuelidate/lib/validators";
import { mapState } from "vuex";
import { BFormInput, BFormGroup, BFormCheckbox } from "bootstrap-vue";
export default {
  mixins: [validationMixin],
  components: {
    LoadingSubmitButton,
    BFormInput,
    BFormGroup,
    BFormCheckbox,
  },
  data() {
    return {
      submitLoading: false,
      shopSettingCurrent: {
        default_affiliate_link: "",
        allow_affiliates_custom_link: 0,
      },
    };
  },
  validations: {
    shopSettingCurrent: {
      default_affiliate_link: {
        required,
        url,
      },
    },
  },
  computed: {
    ...mapState({
      shopSettingCurrentAPI(state) {
        return Object.assign({}, state.layout.settings);
      },
    }),
  },
  created() {
    this.shopSettingCurrent = this.shopSettingCurrentAPI;
  },
  watch: {
    shopSettingCurrentAPI() {
      this.shopSettingCurrent = this.shopSettingCurrentAPI;
    },
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.shopSettingCurrent[name];
      return $dirty ? !$error : null;
    },
    updateSettting() {
      this.submitLoading = true;
      this.$v.shopSettingCurrent.$touch();
      if (this.$v.shopSettingCurrent.$anyError) {
        this.submitLoading = false;
        return;
      }
      ApiService.put("/settings/default-affiliate-link", {
        default_affiliate_link: this.shopSettingCurrent.default_affiliate_link,
        allow_affiliates_custom_link:
          this.shopSettingCurrent.allow_affiliates_custom_link,
      }).then(({ data }) => {
        this.submitLoading = false;
        this.$store.commit("UPDATE_DEFAULT_AFFILIATE_LINK", {
          default_affiliate_link:
            this.shopSettingCurrent.default_affiliate_link,
          allow_affiliates_custom_link:
            this.shopSettingCurrent.allow_affiliates_custom_link,
        });
        this.$toast.success("Updated", {
          position: "bottom",
        });
      });
    },
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
