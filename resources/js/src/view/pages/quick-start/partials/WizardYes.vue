<template>
  <div class="col-lg-10 mx-auto">
    <!--begin: Wizard-->
    <div
      class="wizard wizard-1"
      id="kt_wizard_v1"
      data-wizard-state="step-first"
      data-wizard-clickable="true"
      style="min-height: 100vh"
    >
      <div class="card card-custom mb-10 mt-10 p-5">
        <div class="card-body p-0">
          <!--begin: Wizard Nav-->
          <div class="wizard-nav">
            <div class="wizard-steps p-lg-5">
              <div
                class="wizard-step"
                data-wizard-type="step"
                data-wizard-state="current"
              >
                <div class="wizard-label">
                  <span class="svg-icon svg-icon-3x wizard-icon">
                    <inline-svg src="media/svg/icons/General/Settings-2.svg" />
                  </span>
                  <h3 class="wizard-title">1. Default program</h3>
                </div>
                <span class="svg-icon wizard-arrow">
                  <inline-svg
                    src="media/svg/icons/Navigation/Angle-right.svg"
                  />
                </span>
              </div>
              <div class="wizard-step" data-wizard-type="step">
                <div class="wizard-label">
                  <span class="svg-icon svg-icon-3x wizard-icon">
                    <inline-svg src="media/svg/icons/Navigation/Sign-in.svg" />
                  </span>
                  <h3 class="wizard-title">2. Affiliate sign-up page</h3>
                </div>
                <span class="svg-icon wizard-arrow">
                  <inline-svg
                    src="media/svg/icons/Navigation/Angle-right.svg"
                  />
                </span>
              </div>
              <div class="wizard-step" data-wizard-type="step">
                <div class="wizard-label">
                  <span class="svg-icon svg-icon-3x wizard-icon">
                    <inline-svg
                      src="media/svg/icons/Layout/Layout-top-panel-6.svg"
                    />
                  </span>
                  <h3 class="wizard-title">3. Payment options</h3>
                </div>
                <span class="svg-icon wizard-arrow">
                  <inline-svg
                    src="media/svg/icons/Navigation/Angle-right.svg"
                  />
                </span>
              </div>
              <div class="wizard-step" data-wizard-type="step">
                <div class="wizard-label">
                  <span class="svg-icon svg-icon-3x wizard-icon">
                    <inline-svg src="media/svg/icons/Files/Upload.svg" />
                  </span>
                  <h3 class="wizard-title">4. Logo</h3>
                </div>
                <span class="svg-icon wizard-arrow">
                  <inline-svg
                    src="media/svg/icons/Navigation/Angle-right.svg"
                  />
                </span>
              </div>
              <div class="wizard-step" data-wizard-type="step">
                <div class="wizard-label">
                  <span class="svg-icon svg-icon-3x wizard-icon">
                    <inline-svg src="media/svg/icons/Files/File-done.svg" />
                  </span>
                  <h3 class="wizard-title">5. Summary</h3>
                </div>
                <i class="wizard-arrow last flaticon2-next"></i>
              </div>
            </div>
          </div>
          <!--end: Wizard Nav-->
        </div>
      </div>
      <div class="card card-custom">
        <div class="card-body p-0">
          <!--begin: Wizard Body-->
          <div class="row justify-content-center mb-10 px-8 mb-lg-15 px-lg-10">
            <div class="col-xl-12">
              <!--begin: Wizard Form-->
              <form class="form">
                <!--begin: Wizard Step 1-->
                <div
                  class="pb-5 step-content"
                  data-wizard-type="step-content"
                  data-wizard-state="current"
                >
                  <div class="col-lg-12 mb-5 mx-auto p-5">
                    <div class="form-group">
                      <div
                        role="alert"
                        class="alert alert-custom alert-default"
                      >
                        <div class="alert-icon">
                          <span
                            class="svg-icon svg-icon-3x svg-icon-primary px-2"
                          >
                            <inline-svg
                              src="media/svg/icons/Code/Warning-1-circle.svg"
                            />
                          </span>
                        </div>
                        <div class="alert-text">
                          <span
                            class="alert-heading text-primary"
                            style="font-size: 15px; font-weight: 600"
                            >Create your first program!</span
                          >
                          <br />You can use programs to handle different kinds of
                          affiliates and commission. Each affiliate can only
                          belong to one program. You are free to run as many
                          programs as you need. The default program's basic
                          settings will be configured here.
                        </div>
                      </div>
                    </div>
                    <CreateProgram
                      ref="createProGram"
                      v-if="this.currentGroup"
                      :group="this.currentGroup"
                    />
                  </div>
                  <div class="d-flex tip-alert ">
                    <div class="col-md-2"></div>
                    <div
                      class="col-md-8 md-screen">
                      <Tips :type="'alert-outline-secondary'">
                            Tip: You can create more programs and explore more
                        advanced settings later.
                        </Tips>
                    </div>
                    <div class="col-md-2"></div>
                  </div>
                </div>
                <!--end: Wizard Step 1-->

                <!--begin: Wizard Step 2-->
                <div class="pb-5 step-content" data-wizard-type="step-content">
                  <div class="col-lg-12 mt-5 mx-auto">
                    <div role="alert" class="alert alert-custom alert-default">
                      <div class="alert-icon">
                        <span
                          class="svg-icon svg-icon-2x svg-icon-primary px-2"
                        >
                          <inline-svg
                            src="media/svg/icons/Code/Warning-1-circle.svg"
                          />
                        </span>
                      </div>
                      <div class="alert-text">
                        <span
                          class="alert-heading text-primary"
                          style="font-size: 15px; font-weight: 600"
                          >Customize your registration page!</span
                        >
                        <br />Each program has one affiliate registration page.
                        Users can sign up for your affiliate program by visiting
                        the registration page. It's a good idea to customize the
                        text and color of this page to match your shop branding.
                        As you make any changes, you'll see a preview appear
                        below.
                        <br/>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 mt-5 mx-auto">
                    <div role="alert" class="alert alert-custom alert-default">
                      <div class="alert-icon">
                        <span class="svg-icon-success svg-icon-2x svg-icon px-2">
                          <inline-svg src="media/svg/icons/Home/Bulb1.svg" />
                        </span>
                      </div>
                      <div class="text-success alert-heading">
                        <b>Tip:</b> You can choose more sign-up form templates, custom
                        form fields and add program details, brand description,
                        etc later.
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 mx-auto">
                    <Registration
                      ref="registration"
                      v-if="this.baseObjToGen"
                      :TemplateObj="this.baseObjToGen"
                      :group="this.currentGroup"
                    />
                  </div>

                </div>
                <!--end: Wizard Step 2-->

                <!--begin: Wizard Step 3-->
                <div class="pb-5 step-content" data-wizard-type="step-content">
                  <div class="p-5 col-lg-12 mb-5 mx-auto">
                    <div class="form-group mb-0">
                      <div
                        role="alert"
                        class="alert alert-custom alert-default"
                      >
                        <div class="alert-icon">
                          <span
                            class="svg-icon svg-icon-3x svg-icon-primary px-2"
                          >
                            <inline-svg
                              src="media/svg/icons/Code/Warning-1-circle.svg"
                            />
                          </span>
                        </div>
                        <div class="alert-text">
                          <span
                            class="alert-heading text-primary"
                            style="font-size: 15px; font-weight: 600"
                            >Choose the payment methods you accept for commission payouts</span>
                          <br />You can pay affiliates in a variety of ways, choose your preferred method here.
                          After that, your affiliates will select one option to be their payment method.
                        </div>
                      </div>
                    </div>
                    <b-overlay
                      opacity="0"
                      :show="loadingPaymentMethod"
                      rounded="sm"
                    >
                      <div class="table-responsive">
                        <table class="table table-vertical-center">
                          <thead>
                            <tr class="text-left">
                              <th class="pr-0" style="width: 40%">Name</th>
                              <th style="min-width: 40%">Type</th>
                              <th style="min-width: 20%">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-if="paymentMethods.length > 0">
                              <tr
                                v-for="(p, i) in paymentMethods"
                                :p="p"
                                :key="i"
                              >
                                <td>
                                  <span
                                    class="
                                      text-dark-75
                                      font-weight-bolder
                                      d-block
                                      font-size-lg
                                    "
                                    >{{ p.name }}</span
                                  >
                                </td>
                                <td>
                                  <span
                                    v-if="p.type == 0"
                                    class="
                                      label
                                      label-lg
                                      label-light-default
                                      label-inline
                                    "
                                    >Default</span
                                  >
                                  <span
                                    v-else
                                    class="
                                      label
                                      label-lg
                                      label-light-success
                                      label-inline
                                    "
                                    >Custom</span
                                  >
                                </td>
                                <td>
                                  <b-form-checkbox
                                    v-model="p.status"
                                    switch
                                    size="lg"
                                    value="1"
                                    unchecked-value="0"
                                    @change="toggleStatus(p.id)"
                                  ></b-form-checkbox>
                                </td>
                              </tr>
                            </template>
                            <template v-else>
                              <tr>
                                <td
                                  colspan="8"
                                  style="
                                    text-align: center;
                                    color: #b5b5c3 !important;
                                  "
                                >
                                  <i class="far fa-folder-open icon-3x"></i>
                                  <h6>No Data</h6>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                        </table>
                      </div>
                      <template #overlay>
                        <TableLoader></TableLoader>
                      </template>
                    </b-overlay>
                  </div>
                  <div class="md-screen">
                  <div class="d-flex tip-alert">
                    <div class="col-md-2"></div>
                    <div
                      class="
                        col-md-8
                        alert alert-custom alert-outline-secondary alert-shadow
                        fade
                        show
                      "
                      role="alert"
                      style="border-radius: 999px"
                    >
                      <div class="alert-icon">
                        <span class="svg-icon-primary svg-icon">
                          <inline-svg src="media/svg/icons/Home/Bulb1.svg" />
                        </span>
                      </div>
                      <div class="alert-text">
                        Tip: You can add a custom payment method later if you
                        want.
                      </div>
                    </div>
                    <div class="col-md-2"></div>
                  </div>
                  </div>
                </div>
                <!--end: Wizard Step 3-->

                <!--begin: Wizard Step 4-->
                <div class="pb-5 step-content" data-wizard-type="step-content">
                  <div class="col-lg-10 mb-20 mx-auto p-5">
                    <div class="form-group mb-0">
                      <div
                        role="alert"
                        class="alert alert-custom alert-default"
                      >
                        <div class="alert-icon">
                          <span
                            class="svg-icon svg-icon-3x svg-icon-primary px-2"
                          >
                            <inline-svg
                              src="media/svg/icons/Code/Warning-1-circle.svg"
                            />
                          </span>
                        </div>
                        <div class="alert-text">
                          <span
                            class="alert-heading text-primary"
                            style="font-size: 15px; font-weight: 600"
                            >Upload your shop logo</span
                          >
                          <br />Upload your shop logo here. It will be displayed
                          in your affiliate account.
                          <br/>
                          (Note: Maximum size for file upload is 2MB)
                        </div>
                      </div>
                    </div>
                    <div class="d-flex justify-content-center p-10">
                      <div
                        class="
                          image-input image-input-empty image-input-outline
                        "
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
                    </div>
                  </div>
                </div>
                <!--end: Wizard Step 4-->

                <!--begin: Wizard Step 5-->
                <div class="pb-5 step-content" data-wizard-type="step-content">
                  <div class="col-lg-12 my-20 mx-auto">
                    <div
                      class="card-body d-flex flex-column align-items-center"
                    >
                      <span
                        class="
                          svg-icon-success svg-icon-3x
                          font-weight-bolder
                          svg-icon
                          mb-10
                          d-flex
                          align-items-center
                        "
                      >
                        <inline-svg
                          src="media/svg/icons/Code/Done-circle.svg"
                        />
                        <span class="font-size-h1 text-success text-uppercase"
                          >Congratulations!</span
                        >
                      </span>
                      <span
                        class="font-weight-bold font-size-h4 mb-20 text-center"
                      >
                        You've finished the basic setup. You can start using the
                        app now, there are lots of additional settings to
                        configure but your affiliate program is ready to run now.
                      </span>
                    </div>
                  </div>
                </div>
                <!--end: Wizard Step 5-->

                <!--begin: Wizard Actions -->
                <div class="d-flex justify-content-around pt-10">
                    <button
                      class="
                        btn btn-light-primary
                        font-weight-bold
                        text-uppercase
                      "
                      data-wizard-type="action-prev"
                    >
                      Previous
                    </button>
                  <div>
                    <button
                      v-on:click="submit"
                      ref="submit"
                      class="
                        btn btn-success
                        font-weight-bold
                        text-uppercase
                      "
                      data-wizard-type="action-submit"
                    >
                      Finish
                    </button>
                    <button
                      ref="next"
                      class="
                        btn btn-primary
                        font-weight-bold
                        text-uppercase
                      "
                      data-wizard-type="action-next"
                    >
                      Next Step
                    </button>
                  </div>
                </div>
                <!--end: Wizard Actions -->
              </form>
              <!--end: Wizard Form-->
            </div>
          </div>
          <!--end: Wizard Body-->
        </div>
      </div>
      <!--end: Wizard-->
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import TableLoader from "@/view/content/TableLoader.vue";
import ApiService from "@/core/services/api.service";
import { BFormCheckbox, BFormFile, BOverlay } from "bootstrap-vue";
import CreateProgram from "@/view/pages/quick-start/partials/CreateProgram.vue";
import Registration from "@/view/pages/quick-start/partials/registration-default/Registration.vue";
import KTUtil from "@/assets/js/components/util";
import KTWizard from "@/assets/js/components/wizard";
import Swal from "sweetalert2";
import Datatable from "@/view/content/Datatable.vue";
export default {
  name: "Wizard-1",
  components: {
    CreateProgram,
    Registration,
    BFormCheckbox,
    Datatable,
    BFormFile,
    BOverlay,
    TableLoader,
  },
  data() {
    return {
      currentGroup: null,
      id: null,
      baseObjToGen: null,
      paymentMethods: [],
      loadingPaymentMethod: false,
      avatar: null,
      image: null,
      shopSettingCurrent: {
        logo: null,
      },
    };
  },
  created() {
    /**Lấy group default */
    ApiService.query("/groups/default-group").then(({ data }) => {
      this.currentGroup = data.data;
      this.id = data.data.id;
      this.baseObjToGen = data.data.registration_form;
      var obj = this.baseObjToGen;
      this.changeBaseObj(obj);
    });
  },
  mounted() {
    var that = this;
    // Initialize form wizard
    const wizard = new KTWizard("kt_wizard_v1", {
      startStep: 1, // initial active step number
      clickableSteps: true, // allow step clicking
    });

    // Validation before going to next page
    wizard.on("beforeNext", function (wizardObj) {
      // validate the form and use below function to stop the wizard's step
    });

    // Change event
    wizard.on("change", function (wizardObj) {
      /**lấy bước hiện tại */
      var a = wizardObj.getStep();
      /**Bước kế tiếp */
      var b = wizardObj.getNewStep();
      /**Hiện tại bc 1 thì submitprogram */
      if (a == 1) {
        try {
          wizardObj.stop();
          that.submitProgram();
          wizardObj.resume();
        } catch (error) {
          console.log(error);
        }
      }
      /**hiện tại bc 2 thì apply change text */
      if (a == 2) {
        try {
          const nextButton = that.$refs["next"];
          nextButton.classList.add("spinner", "spinner-light", "spinner-right");
          setTimeout(() => {
            nextButton.classList.remove(
              "spinner",
              "spinner-light",
              "spinner-right"
            );
          }, 500);
          that.$refs.registration.$refs.editProgramText.onApplyChange();
        } catch (error) {
          console.log(error);
        }
      }
      /**Chuẩn bị tới bước 3 thì load method */
      if (b == 3) {
        const nextButton = that.$refs["next"];
        nextButton.classList.add("spinner", "spinner-light", "spinner-right");
        setTimeout(() => {
          nextButton.classList.remove(
            "spinner",
            "spinner-light",
            "spinner-right"
          );
        }, 500);
        that.loadPaymentMethod();
      }
      /**Hiện tại bc 4 thì upload logo */
      if (a == 4) {
        try {
          that.uploadLogo();
        } catch (error) {
          console.log(error);
        }
      }
      /**Lên đầu trang */
      setTimeout(() => {
        KTUtil.scrollTop();
      }, 500);
    });
  },
  methods: {
    ...mapActions(["changeBaseObj", "updateRegistration"]),
    submit: function (e) {
      const submitButton = this.$refs["submit"];
      submitButton.classList.add("spinner", "spinner-light", "spinner-right");
      e.preventDefault();
      ApiService.put("/settings/done_started")
        .then(() => {
          this.$router.push("/dashboard");
          submitButton.classList.remove(
            "spinner",
            "spinner-light",
            "spinner-right"
          );
        })
        .catch(({ response }) => {
          console.log(response);
        });
      Swal.fire({
        title: "",
        text: "You have successfully completed the quick onboarding!",
        icon: "success",
        confirmButtonClass: "btn btn-secondary",
      });
    },
    submitProgram() {
      const nextButton = this.$refs["next"];
      nextButton.classList.add("spinner", "spinner-light", "spinner-right");
      setTimeout(() => {
        nextButton.classList.remove(
          "spinner",
          "spinner-light",
          "spinner-right"
        );
      }, 500);
      this.$refs.createProGram.submit();
    },
    loadPaymentMethod() {
      this.loadingPaymentMethod = true;
      ApiService.query("settings/payment-methods")
        .then(({ data }) => {
          this.paymentMethods = data.data;
          this.loadingPaymentMethod = false;
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    toggleStatus(id) {
      ApiService.post("settings/payment-methods/" + id + "/status-toggle")
        .then(({ data }) => {})
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    changeLogoDefault() {
      this.shopSettingCurrent.logo = null;
      this.$refs["logoInputFile"].reset();
      this.image = null;
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
    uploadLogo() {
      if (this.image != null) {
        if (this.validateSize(this.image)) {
          let formData = new FormData();
          formData.append("logo", this.image);
          ApiService.post("/logos/upload-logo", formData).then(({ data }) => {
            this.shopSettingCurrent.logo = data.data.path;
            this.shopSettingCurrent.path_name = data.data.path_name;
            ApiService.put(
              "/shopsettings/upload-logo-shop",
              this.shopSettingCurrent
            )
              .then(({ data }) => {
                console.log(data.data);
                this.$store.commit("UPDATE_LOGO_SET_SHOP_SETTING", {
                  path_name: data.data.path_name,
                  logo: data.data.logo
                });
                this.$toast.success("Upload your logo successfully", {
            position: "bottom",
          });
              })
              .catch(({ response }) => {
                /** */
              });
          });
        } else {
          this.$toast.error("File size exceeds 2 MiB", {
            position: "bottom",
          });
        }
      }
    },
  },
  computed: {
    is_skip_started() {
      return this.$store.state.layout.settings.is_skip_started;
    },
    is_done_started() {
      return this.$store.state.layout.settings.is_done_started;
    },
  },
};
</script>
<style lang="scss" >
@import "@/assets/sass/pages/wizard/wizard-1.scss";
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="done"]
  .wizard-label
  .wizard-title,
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="current"]
  .wizard-label
  .wizard-title {
  color: #ff9900 !important;
}
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="done"]
  .wizard-label
  .wizard-icon.svg-icon
  svg
  g
  [fill],
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="current"]
  .wizard-label
  .wizard-icon.svg-icon
  svg
  g
  [fill],
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="done"]
  .wizard-arrow.svg-icon
  svg
  g
  [fill],
.wizard.wizard-1
  .wizard-nav
  .wizard-steps
  .wizard-step[data-wizard-state="current"]
  .wizard-arrow.svg-icon
  svg
  g
  [fill] {
  fill: #ff9900 !important;
}

</style>
<style scoped>
.table-responsive {
  overflow-x: unset !important;
}
tbody > tr td {
  border-top: none !important;
}
.custom-switch {
  bottom: 8px !important;
}
.card {
  border-radius: 1.42rem;
  padding: 5px;
  border: 1px solid rgb(212, 212, 212);
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
