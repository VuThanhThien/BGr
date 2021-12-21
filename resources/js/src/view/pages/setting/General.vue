    <template>
  <!--begin::Card-->
  <div class="card card-custom">
    <!--begin::Header-->
    <b-overlay opacity="0" :show="loadingSettingTable" rounded="sm">
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">General</h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >General settings</span
          >
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
      <div class="card-body">
        <form class="form">
          <!--begin::Body-->
          <!-- <div class="card-body"> -->
          <!-- logo  -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Logo:"
            label-for="logo"
            class="d-flex align-items-center"
          >
            <div
              class="image-input image-input-empty image-input-outline"
              id="kt_user_edit_avatar"
            >
              <img
                v-if="shopSettingCurrent.logo"
                class="image-input-wrapper"
                :src="shopSettingCurrent.logo"
              />
              <img
                v-else
                class="image-input-wrapper"
                src="media/svg/icons/Home/Picture.svg"
              />
              <label
                class="
                  btn
                  btn-xs
                  btn-icon
                  btn-circle
                  btn-white
                  btn-hover-text-primary
                  btn-shadow
                "
                data-action="change"
                data-toggle="tooltip"
                title=""
                data-original-title="Change avatar"
              >
                <i class="fa fa-pen icon-sm text-muted">
                  <b-form-file
                    ref="logoInputFile"
                    accept="image/*"
                    v-model="avatar"
                    style="display: none"
                    @change="previewImage"
                  ></b-form-file>
                </i>
              </label>
              <span
                v-if="shopSettingCurrent.logo"
                class="
                  btn
                  btn-xs
                  btn-icon
                  btn-circle
                  btn-white
                  btn-hover-text-primary
                  btn-shadow
                  del-img
                "
                @click="changeLogoDefault"
              >
                <i class="ki ki-close icon-sm text-muted"></i>
              </span>
            </div>
          </b-form-group>
          <!-- branch name  -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Brand name:"
            label-for="branch_name"
          >
            <b-form-input
              id="branch_name"
              v-model="$v.shopSettingCurrent.brand_name.$model"
              :class="{
                'form-group--error': $v.shopSettingCurrent.brand_name.$error,
              }"
              :state="validateState('brand_name')"
            ></b-form-input>
            <div
              class="fv-plugins-message-container"
              v-if="submited && !$v.shopSettingCurrent.brand_name.required"
            >
              <div class="fv-help-block">Field is required</div>
            </div>
          </b-form-group>
          <!-- subdomain  -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Subdomain :"
            label-for="subdomain"
          >
            <b-form-input
              id="subdomain"
              v-model="$v.shopSettingCurrent.sub_domain.$model"
              :class="{
                'form-group--error': $v.shopSettingCurrent.sub_domain.$error,
              }"
              :state="validateState('sub_domain')"
            ></b-form-input>
            <!-- <span  class="form-text text-muted">
              aaa
            </span> -->
            <div
              class="fv-plugins-message-container"
              v-if="submited && !$v.shopSettingCurrent.sub_domain.required"
            >
              <div class="fv-help-block">Field is required</div>
            </div>
          </b-form-group>

          <!-- favicon  -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Favicon Url:"
            label-for="favicon"
          >
            <b-form-input
              id="favicon"
              placeholder="Favicon Url"
              v-model="$v.shopSettingCurrent.favicon.$model"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Record pending order:"
            label-for="is_enable"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
                v-model="shopSettingCurrent.is_record_pending_order"
                switch
                size="lg"
                value="1"
                unchecked-value="0"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >When enabled, pending orders on Shopify will be automatically recorded in Conversions tab (you do not have to mark as paid on Shopify)..
            </span>
            </div>
          </b-form-group>
          <!--end::Body-->
        </form>
      </div>
      <!--end::Form-->
      <div class="card-footer d-flex align-items-center justify-content-end">
        <div class="card-toolbar float-right">
          <LoadingSubmitButton :loading="submitLoading" @click="updateSettting">
            Save Changes
          </LoadingSubmitButton>
        </div>
      </div>
      <template #overlay>
        <TableLoader></TableLoader>
      </template>
    </b-overlay>
  </div>
</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "../../../core/services/api.service";
import TableLoader from "@/view/content/TableLoader.vue";
import { validationMixin } from "vuelidate";
import { required, url } from "vuelidate/lib/validators";
import { mapState } from "vuex";
import { BOverlay, BFormInput, BFormGroup, BFormFile, BFormCheckbox } from "bootstrap-vue";
export default {
  mixins: [validationMixin],
  components: {
    LoadingSubmitButton,
    TableLoader,
    BOverlay,
    BFormInput,
    BFormGroup,
    BFormFile,
    BFormCheckbox
  },
  data() {
    return {
      submited: false,
      loadingSettingTable: false,
      submitLoading: false,
      avatar: null,
      image: null,
      shopSettingCurrent: {
        brand_name: "",
        sub_domain: "",
        favicon: "",
        logo: null,
        is_record_pending_order: 0
      },
    };
  },
  validations: {
    shopSettingCurrent: {
      brand_name: {
        required,
      },
      sub_domain: {
        required,
      },
      favicon: {
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
    changeLogoDefault() {
      this.shopSettingCurrent.logo = null;
      this.shopSettingCurrent.path_name = null;
      this.$refs["logoInputFile"].reset();
      this.image = null;
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.shopSettingCurrent[name];
      return $dirty ? !$error : null;
    },
    updateSettting() {
      this.submitLoading = true;
      this.submited = true;
      this.$v.shopSettingCurrent.$touch();
      if (!this.$v.shopSettingCurrent.favicon.url) {
          this.$toast.error("Favicon field must be a valid link.", {
          position: "bottom",
        });
        this.submitLoading = false;
        return;
      }
      if (this.$v.shopSettingCurrent.$anyError) {
        this.submitLoading = false;
        return;
      }
      if (
        !this.shopSettingCurrent.sub_domain.match(
          /^[a-z0-9][a-z0-9\-]*[a-z0-9]$/
        )
      ) {
        this.$toast.error("Subdomain invalid.", {
          position: "bottom",
        });
        this.submitLoading = false;
        return;
      }
      if (this.image != null) {
        if (this.validateSize(this.image)) {
          let formData = new FormData();
          formData.append("logo", this.image);
          ApiService.post("/shopsettings/uploadLogo", formData).then(
            ({ data }) => {
              this.shopSettingCurrent.logo = data.data.path;
              this.shopSettingCurrent.path_name = data.data.path_name;
              ApiService.put("/shopsettings", {
                brand_name: this.shopSettingCurrent.brand_name,
                logo: this.shopSettingCurrent.path_name,
                sub_domain: this.shopSettingCurrent.sub_domain,
                favicon: this.shopSettingCurrent.favicon,
                path_name: this.shopSettingCurrent.path_name,
              })
                .then(({ data }) => {
                  this.$refs["logoInputFile"].reset();
                  this.image = null;
                  this.submitLoading = false;
                  console.log(this.shopSettingCurrent.logo);
                  this.$store.commit("UPDATE_GENERAL_SHOP_SETTING", {
                    brand_name: this.shopSettingCurrent.brand_name,
                    logo: this.shopSettingCurrent.logo,
                    sub_domain: this.shopSettingCurrent.sub_domain,
                    favicon: this.shopSettingCurrent.favicon,
                    path_name: this.shopSettingCurrent.path_name,
                  });
                  this.$toast.success("Updated", {
                    position: "bottom",
                  });
                })
                .catch(({ response }) => {
                  if (response.status == 422) {
                    this.$toast.error(response.data.errors.sub_domain[0], {
                      position: "bottom",
                    });
                  } else {
                    this.$toast.error("Errors", {
                      position: "bottom",
                    });
                  }
                })
                .finally(() => (this.submitLoading = false));
            }
          );
        } else {
          this.submitLoading = false;
          this.$toast.error("File size exceeds 2 MiB", {
            position: "bottom",
          });
        }
      } else {
        ApiService.put("/shopsettings", {
          brand_name: this.shopSettingCurrent.brand_name,
          sub_domain: this.shopSettingCurrent.sub_domain,
          favicon: this.shopSettingCurrent.favicon,
          path_name: this.shopSettingCurrent.path_name,
          logo: this.shopSettingCurrent.path_name,
          is_record_pending_order: this.shopSettingCurrent.is_record_pending_order
        })
          .then(({ data }) => {
            this.$refs["logoInputFile"].reset();
            this.image = null;
            this.submitLoading = false;
             this.$store.commit("UPDATE_NO_LOGO_SHOP_SETTING", {
                brand_name: this.shopSettingCurrent.brand_name,
                sub_domain: this.shopSettingCurrent.sub_domain,
                favicon: this.shopSettingCurrent.favicon,
              });
            this.$toast.success("Updated", {
              position: "bottom",
            });
          })
          .catch(({ response }) => {
            if (response.status == 422) {
              this.$toast.error(response.data.errors.sub_domain[0], {
                position: "bottom",
              });
            } else {
              this.$toast.error("Errors", {
                position: "bottom",
              });
            }
          })
          .finally(() => (this.submitLoading = false));
      }
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        this.shopSettingCurrent.logo = URL.createObjectURL(input.files[0]);
        this.image = input.files[0];
      }
    },
    validateSize(file) {
      const fileSize = file.size / 1024 / 1024; // in MiB
      if (fileSize < 2) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>

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
