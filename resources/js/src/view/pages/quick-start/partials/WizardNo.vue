<template>
  <div class="col-lg-8 mx-auto">
    <div class="card card-custom mt-10">
      <div class="card-body p-0">
        <!--begin: Wizard-->
        <div
          class="wizard wizard-1"
          id="kt_wizard_v1"
          data-wizard-state="step-first"
          data-wizard-clickable="true"
        >
          <!--begin: Wizard Nav-->
          <div class="wizard-nav d-none">
            <div class="wizard-steps p-8 p-lg-10">
              <div
                class="wizard-step"
                data-wizard-type="step"
                data-wizard-state="current"
              ></div>
              <div class="wizard-step" data-wizard-type="step"></div>
              <div class="wizard-step" data-wizard-type="step"></div>
            </div>
          </div>
          <!--end: Wizard Nav-->

          <!--begin: Wizard Body-->
          <div class="row justify-content-center my-10 px-8">
            <!--begin: Wizard Form-->
            <form class="form">
              <!--begin: Wizard Step 1-->
              <div
                class="pb-5 step-content"
                data-wizard-type="step-content"
                data-wizard-state="current"
              >
                <div class="mx-auto">
                  <h1
                    class="
                      mb-5
                      font-weight-bolder font-size-h1
                      text-center text-dark
                    "
                  >
                    Welcome to BixGrow
                  </h1>
                  <h3 class="font-weight-bold text-center font-size-h5 mb-10">
                    We're delighted to have you join us today! <br />
                    First, let's get started by understanding how affiliate
                    marketing works.
                  </h3>
                  <div class="d-flex align-items-center justify-content-center">
                    <img
                      src="media/getstarted/HowAMWork.jpg"
                      alt=""
                      class="imgWz"
                    />
                  </div>
                </div>
              </div>
              <!--end: Wizard Step 1-->

              <!--begin: Wizard Step 2-->
              <div class="pb-5 step-content" data-wizard-type="step-content">
                <div class="mx-auto p-5">
                  <h1
                    class="
                      mb-5
                      font-weight-bolder font-size-h1
                      text-center text-dark
                    "
                  >
                    Your role (Merchant's role)
                  </h1>
                  <h3 class="font-weight-bold text-center font-size-h5 mb-10">
                    As a merchant, you have three main roles:
                  </h3>
                  <div class="d-flex align-items-center justify-content-center">
                    <img
                      src="media/getstarted/YourRole.jpg"
                      alt=""
                      class="imgWz"
                    />
                  </div>
                </div>
              </div>
              <!--end: Wizard Step 2-->

              <!--begin: Wizard Step 5-->
              <div class="pb-5 step-content" data-wizard-type="step-content">
                <div class=" mx-auto p-5">
                  <h1
                    class="
                      mb-5
                      font-weight-bolder font-size-h1
                      text-center text-dark
                    "
                  >
                    Basic Setup
                  </h1>
                  <h3 class="font-weight-bold text-center font-size-h5 mb-10">
                    Now that you have a basic knowledge of what affiliate
                    marketing is, let's move to the basic setup
                  </h3>
                  <div class="d-flex align-items-center justify-content-center">
                    <img
                      src="media/getstarted/login-visual-1.svg"
                      alt=""
                      class="imgWz"
                    />
                  </div>
                </div>
              </div>
              <!--end: Wizard Step 5-->

              <!--begin: Wizard Actions -->
              <div class="d-flex justify-content-between">
                <div class="mr-2">
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
                </div>
                <div>
                  <button
                    v-on:click="submit"
                    ref="submit"
                    class="btn btn-primary font-weight-bold text-uppercase"
                    data-wizard-type="action-submit"
                  >
                    Next Step
                  </button>
                  <button
                    ref="next"
                    class="btn btn-primary font-weight-bold text-uppercase"
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
          <!--end: Wizard Body-->
        </div>
      </div>
      <!--end: Wizard-->
    </div>
  </div>
</template>



<script>
import KTUtil from "@/assets/js/components/util";
import KTWizard from "@/assets/js/components/wizard";
export default {
  name: "Wizard-1",
  data() {
    return {
      loading: false,
    };
  },
  mounted() {
    var that = this;
    // Initialize form wizard
    const wizard = new KTWizard("kt_wizard_v1", {
      startStep: 1, // initial active step number
      clickableSteps: true, // allow step clicking
    });

    // Validation before going to next page
    wizard.on("beforeNext", function (/*wizardObj*/) {
      // validate the form and use below function to stop the wizard's step
      // wizardObj.stop();
    });

    // Change event
    wizard.on("change", function (wizardObj) {
      setTimeout(() => {
        KTUtil.scrollTop();
      }, 500);
    });
  },
  methods: {
    submit: function (e) {
      e.preventDefault();
      this.$router.push({ name: "wizard.YES" });
    },
  },
};
</script>

<style lang="scss">
@import "@/assets/sass/pages/wizard/wizard-1.scss";
.imgWz {
  max-width: 500px;
  height: auto;
  object-fit: cover;
}
</style>
