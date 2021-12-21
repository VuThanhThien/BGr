
<template>
  <div>
    <div class="card card-custom mb-2 mb-xl-5">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Contact Email
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Email address where affiliates can reach out to you</span
          >
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
      <div class="card-body">
        <form class="form">
          <!--begin::Body-->
          <!-- <div class="card-body"> -->
                <!-- branch name  -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-sm="4"
                  label-cols-lg="3"
                  content-cols-sm="8"
                  content-cols-lg="9"
                  label="Shop Name :"
                  label-for="shop_name"
                >
                  <b-form-input
                    id="shop_name"
                    v-model="$v.profileDetail.contactName.$model"
                    :aria-invalid="$v.profileDetail.contactName.$error"
                    :state="validateState('contactName')"
                  ></b-form-input>
                </b-form-group>

                <!-- subdomain  -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-sm="4"
                  label-cols-lg="3"
                  content-cols-sm="8"
                  content-cols-lg="9"
                  label="Email Address :"
                  label-for="email_address"
                >
                  <b-form-input
                    id="email_address"
                    v-model="$v.profileDetail.emailAddress.$model"
                    :aria-invalid="$v.profileDetail.emailAddress.$error"
                    :state="validateState('emailAddress')"
                  ></b-form-input>
                </b-form-group>
          <!--end::Body-->
        </form>
      </div>
      <!--end::Form-->
      <div class="card-footer d-flex align-items-center justify-content-end">
        <div class="card-toolbar float-right">
          <LoadingSubmitButton1
            :loading="submitLoading"
            class="float-right"
            @click="saveProfileDetails"
          >
            Save Changes
          </LoadingSubmitButton1>
        </div>
      </div>
    </div>

    <div class="card card-custom mb-2 mb-xl-5">
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Sender's Email
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Email address from where affiliate receive notification
            emails.</span
          >
        </div>
      </div>
      <div class="card-body">
        <b-modal
          ref="changeGroupModal"
          id="changeGroupModal"
          title="Change From Contact Email"
        >
          <div class="row mb-2">
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              <span>From Contact Name</span>
            </label>
            <div class="col-lg-8">
              <b-form-input
                type="text"
                name="lname"
                v-model="$v.fromEmail.fromContactName.$model"
                :aria-invalid="$v.fromEmail.fromContactName.$error"
                :state="validateState1('fromContactName')"
              >
              </b-form-input>
            </div>
          </div>

          <div class="row mb-2">
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              <span>From Contact Email</span>
            </label>
            <div class="col-lg-8 fv-row">
              <b-form-input
                type="tel"
                name="phone"
                v-model="$v.fromEmail.fromContactEmail.$model"
                :aria-invalid="$v.fromEmail.fromContactEmail.$error"
                :state="validateState1('fromContactEmail')"
              ></b-form-input>
            </div>
          </div>
          <template #modal-footer>
            <div class="w-100">
              <LoadingSubmitButton
                :loading="submitLoading1"
                class="float-right"
                @click="onSaveFromEmail"
              >
                Save
              </LoadingSubmitButton>
            </div>
          </template>
        </b-modal>
        <div class="row">
          <div class="col-xl-10">
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="From :"
              label-for="from"
            >
              <b-form-input
                id="from"
                v-model="shopSettingCurrentAPI.from_contact_email"
                disabled
              ></b-form-input>
              <div class="mt-5">
                <Loader v-if="loading" class="mt-1"></Loader>
                <span
                  class="mb-2"
                  v-if="!isFromEmailVerified"
                  v-html="getEmailWaitVerifid"
                ></span>
              </div>
            </b-form-group>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex align-items-center justify-content-end">
        <div class="card-toolbar float-right">
          <button class="btn btn-primary btn-sm mr-2" v-b-modal.changeGroupModal>
            <span class="svg-icon svg-icon-sm">
              <inline-svg src="media/svg/icons/Navigation/Exchange.svg" />
            </span>
            Change
          </button>
          <button
            type="button"
            v-if="shopSettingCurrentAPI.is_verified_from_email"
            @click="setEmailDefault"
            class="btn btn-secondary btn-sm"
          >
            Use Default
          </button>
        </div>
      </div>
    </div>

    <div class="card card-custom mb-2 mb-xl-5">
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Your Notifications
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Which type of notifications would you like to receive. Sent to
            <span style="font-weight: 700; color: #4a4747">{{
              shopSettingCurrentAPI.contact_email
            }}</span>
            .</span
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
          label=" Affiliate Registration:"
          label-for="new_registrations"
        >
          <div class="d-flex flex-column">
            <b-form-checkbox
              v-model="notify_self_new_registrations"
              id="new_registrations"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="
                changeNotifySelf(
                  'new_registrations',
                  notify_self_new_registrations
                )
              "
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
              >When a new affiliate signs up.
            </span>
          </div>
        </b-form-group>

        <b-form-group
          id="fieldset-horizontal"
          label-cols-sm="4"
          label-cols-lg="3"
          content-cols-sm="8"
          content-cols-lg="9"
          label=" Affiliate Conversion:"
          label-for="new_order"
        >
          <div class="d-flex flex-column">
            <b-form-checkbox
              v-model="notify_self_new_order"
              id="new_order"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeNotifySelf('new_order', notify_self_new_order)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
              >When a new affiliate conversion is recorded.
            </span>
          </div>
        </b-form-group>
      </div>
    </div>

    <div class="card card-custom mb-2 mb-xl-5">
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Affiliate's Notifications
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"
            >Email notifications sent to affiliates |
            <router-link :to="{ name: 'templates' }">Edit Templates</router-link
            >.</span
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
            label="Affiliates Review:"
            label-for="checkbox"
            >
            <div class="d-flex flex-column">
                <b-form-checkbox
                v-model="affiliate_review"
                id="checkbox"
                switch
                size="lg"
                value="1"
                unchecked-value="0"
                @change="changeStatus('affiliate_review', affiliate_review)"
                ></b-form-checkbox>
                <span class="form-text text-muted mt-5"
                    >Sent when an affiliate signs up an account and waits to be reviewed.
                </span>
            </div>
            </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Approved Affiliates  :"
            label-for="checkbox1"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox1"
              switch
              v-model="approved_affiliate"
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('approved_affiliate', approved_affiliate)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >Sent when an affiliate is approved.
            </span>
          </div>
        </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Denied Affiliates :"
            label-for="checkbox2"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox2"
              v-model="denied_affiliate"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('denied_affiliate', denied_affiliate)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >Sent when an affiliate is denied.
            </span>
          </div>
        </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="New Conversion:"
            label-for="checkbox3"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox3"
              v-model="new_conversion"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('new_conversion', new_conversion)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >Sent when a conversion is recorded.
            </span>
          </div>
        </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Approved Conversion:"
            label-for="checkbox4"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox4"
              v-model="approved_conversion"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('approved_conversion', approved_conversion)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >Sent when a conversion is approved.
            </span>
          </div>
        </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Denied Conversion:"
            label-for="checkbox5"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox5"
              v-model="denied_conversion"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('denied_conversion', denied_conversion)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5"
                >Sent when a conversion is rejected.
            </span>
          </div>
        </b-form-group>

            <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Commission Payout:"
            label-for="checkbox6"
            >
            <div class="d-flex flex-column">
            <b-form-checkbox
              id="checkbox6"
              v-model="commission_payout"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              @change="changeStatus('commission_payout', commission_payout)"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5" style="font-size: 0.9rem"
                >Sent when a commission is marked as paid.
            </span>
          </div>
        </b-form-group>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import LoadingSubmitButton1 from "@/view/content/LoadingSubmitButton.vue";
import Loader from "@/view/content/LoaderVue.vue";
import ApiService from "../../../core/services/api.service";
import TableLoader from "@/view/content/TableLoader.vue";
import { validationMixin } from "vuelidate";
import { required, email } from "vuelidate/lib/validators";
import { BFormInput, BFormGroup, BFormCheckbox } from "bootstrap-vue";
import { mapState } from "vuex";
export default {
  data() {
    return {
      notify_self_new_order: 0,
      notify_self_new_registrations: 0,
      loading: false,
      new_conversion: 1,
      commission_payout: 1,
      denied_conversion: 1,
      approved_conversion: 1,
      denied_affiliate: 1,
      approved_affiliate: 1,
      affiliate_review: 1,
      submitLoading: false,
      submitLoading1: false,
      isFromEmailVerified: 1,
      profileDetail: {
        contactName: "",
        emailAddress: "",
      },
      fromEmail: {
        fromContactName: "",
        fromContactEmail: "",
      },
      getEmailWaitVerifid: "",
    };
  },
  mixins: [validationMixin],
  components: {
    LoadingSubmitButton1,
    LoadingSubmitButton,
    TableLoader,
    Loader,
    BFormInput,
    BFormGroup,
    BFormCheckbox,
  },
  computed: {
    ...mapState({
      shopSettingCurrentAPI(state) {
        return Object.assign({}, state.layout.settings);
      },
    }),
    getFromEmailIsVerified() {
      return this.isFromEmailVerified;
    },
  },
  created() {
    this.profileDetail = {
      contactName: this.shopSettingCurrentAPI.contact_name,
      emailAddress: this.shopSettingCurrentAPI.contact_email,
    };
    this.fromEmail = {
      fromContactName: this.shopSettingCurrentAPI.from_contact_name,
      fromContactEmail: this.shopSettingCurrentAPI.from_contact_email,
    };
    this.getListStatus();
    this.isFromEmailVerified =
      this.shopSettingCurrentAPI.is_verified_from_email;
    this.notify_self_new_registrations =
      this.shopSettingCurrentAPI.notify_self_new_registrations;
    this.notify_self_new_order =
      this.shopSettingCurrentAPI.notify_self_new_order;
  },
  watch: {
    shopSettingCurrentAPI() {
      this.profileDetail = {
        contactName: this.shopSettingCurrentAPI.contact_name,
        emailAddress: this.shopSettingCurrentAPI.contact_email,
      };
      this.fromEmail = {
        fromContactName: this.shopSettingCurrentAPI.from_contact_name,
        fromContactEmail: this.shopSettingCurrentAPI.from_contact_email,
      };
      this.isFromEmailVerified =
        this.shopSettingCurrentAPI.is_verified_from_email;
      this.notify_self_new_registrations =
        this.shopSettingCurrentAPI.notify_self_new_registrations;
      this.notify_self_new_order =
        this.shopSettingCurrentAPI.notify_self_new_order;
    },
    getFromEmailIsVerified() {
      this.checkFromEmailIsVerified();
    },
  },
  methods: {
    setEmailDefault() {
      let resource = `/shopsettings/update-default-from-email`;

      ApiService.put(resource).then(({ data }) => {
        this.$store.commit("SET_SHOP_SETTING_UPDATE", data.data);
      });
    },
    getListStatus() {
      ApiService.query("emailtemplates/getEmailTemplateStatusByShopId").then(
        ({ data }) => {
          data.data.forEach((element) => {
            switch (element.slug) {
              case "affiliate_review":
                this.affiliate_review = element.status;
                break;
              case "denied_affiliate":
                this.denied_affiliate = element.status;
                break;
              case "approved_affiliate":
                this.approved_affiliate = element.status;
                break;
              case "new_conversion":
                this.new_conversion = element.status;
                break;
              case "approved_conversion":
                this.approved_conversion = element.status;
                break;
              case "denied_conversion":
                this.denied_conversion = element.status;
                break;
              case "commission_payout":
                this.commission_payout = element.status;
                break;
            }
          });
        }
      );
    },
    changeStatus(slug, status) {
      let resource = `emailtemplates`;
      ApiService.put(resource, {
        slug: slug,
        status: status,
      }).then(({ res }) => {});
    },
    changeNotifySelf(slug, status) {
      let resource = `/settings/update-your-notifications`;
      ApiService.put(resource, {
        type: slug,
        status: status,
      }).then(({ data }) => {
         this.$store.commit("SET_SHOP_SETTING_BY_YOURS_NOTIFICATIONS",{ type: slug,
        status: status});
      });
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.profileDetail[name];
      return $dirty ? !$error : null;
    },
    validateState1(name) {
      const { $dirty, $error } = this.$v.fromEmail[name];
      return $dirty ? !$error : null;
    },
    saveProfileDetails() {
      this.submitLoading = true;
      this.$v.profileDetail.$touch();
      if (this.$v.profileDetail.$anyError) {
        this.submitLoading = false;
        return;
      }
      let resource = `/shopsettings/updateContactEmail`;
      ApiService.put(resource, {
        contact_name: this.profileDetail.contactName,
        contact_email: this.profileDetail.emailAddress,
      }).then(({ data }) => {
        this.$toast.success("Updated", {
          position: "bottom",
        });
        this.$store.commit("SET_SHOP_SETTING_BY_CONTACT_EMAIL", data.data);
        this.submitLoading = false;
      });
    },
    onSaveFromEmail() {
      this.submitLoading1 = true;
      this.$v.fromEmail.$touch();
      if (this.$v.fromEmail.$anyError) {
        this.submitLoading1 = false;
        return;
      }
      if (
        this.shopSettingCurrentAPI.from_contact_email ==
        this.fromEmail.fromContactEmail
      ) {
        this.$toast.error("Change from contact email", {
          position: "bottom",
        });
        this.submitLoading1 = false;
        return;
      }
      let resource = `/shopsettings/updateFromEmail`;
      ApiService.put(resource, {
        from_contact_name: this.fromEmail.fromContactName,
        from_contact_email: this.fromEmail.fromContactEmail,
      }).then(({ data }) => {
        this.getEmailWaitVerifid =
          '(<span class="text-danger ">' +
          data.data.shopSetting.from_contact_email +
                "</span> is pending verification)"+"<p class='text-warning mt-2'>A confirmation email will be sent to this email address. To complete the verification"+
                   " process, please go to your email and click on the verification link.</p>";
        this.$toast.success("Waiting for verification email", {
          position: "bottom",
        });
        this.$store.commit("SET_SHOP_SETTING_UPDATE", data.data);
        this.submitLoading1 = false;
        this.$refs["changeGroupModal"].hide();
      });
    },
    checkFromEmailIsVerified() {
      if (!this.getFromEmailIsVerified) {
        this.loading = true;
        this.getEmailWaitVerifid = "";
        ApiService.query("settings/check-fromemail-verified").then(
          ({ data }) => {
            if (data.data.verified == "pending") {
              this.$store.commit("SET_SHOP_SETTING_WAIT_VERIFI", {
                shopSetting: {
                  is_verified_from_email: 0,
                  from_contact_name: data.data.from_contact_name,
                  from_contact_email: data.data.from_contact_email,
                },
              });
              this.fromEmail = {
                fromContactEmail: data.data.from_contact_email,
                fromContactName: data.data.from_contact_name,
              };
              this.getEmailWaitVerifid =
                '(<span class="text-danger ">' +
                data.data.from_contact_email +
                "</span> is pending verification)"+"<p class='text-warning mt-2'>A confirmation email will be sent to this email address. To complete the verification"+
                   " process, please go to your email and click on the verification link.</p>";
              this.loading = false;
            } else {
              if (data.data.verified == "verifiedDefault") {
                this.$store.commit("SET_SHOP_SETTING_WAIT_VERIFI", {
                  shopSetting: {
                    is_verified_from_email: 0,
                    from_contact_name: data.data.from_contact_name,
                    from_contact_email: data.data.from_contact_email,
                  },
                });
                this.fromEmail = {
                  fromContactName: data.data.from_contact_name,
                  fromContactEmail: null,
                };
                this.loading = false;
              } else {
                this.$store.commit("SET_SHOP_SETTING_WAIT_VERIFI", {
                  shopSetting: {
                    is_verified_from_email: 1,
                    from_contact_name: data.data.from_contact_name,
                    from_contact_email: data.data.from_contact_email,
                  },
                });
                this.fromEmail = {
                  fromContactName: data.data.from_contact_name,
                  fromContactEmail: data.data.from_contact_email,
                };
                this.loading = false;
              }
            }
          }
        );
      }
    },
  },
  validations: {
    profileDetail: {
      contactName: {
        required,
      },
      emailAddress: {
        required,
        email,
      },
    },
    fromEmail: {
      fromContactName: {
        required,
      },
      fromContactEmail: {
        required,
        email,
      },
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
</style>
