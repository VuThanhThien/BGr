    <template>
  <!--begin::Card-->
  <div>
    <!-- <h3 class="fw-bolder my-2">Payout service</h3> -->

    <div class="row" v-show="false">
      <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
        <!--begin::Card-->
        <div class="card card-custom gutter-b card-stretch">
          <!--begin::Body-->
          <div class="card-body pt-4">
            <!--begin::Toolbar-->

            <!--end::Toolbar-->
            <!--begin::User-->
            <div class="d-flex align-items-end mb-3">
              <!--begin::Pic-->
              <div class="d-flex align-items-center">
                <!--begin::Pic-->
                <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                  <div class="symbol symbol-circle symbol-lg-75">
                    <img src="/media/logos/paypal.png" alt="image" />
                  </div>
                </div>
                <!--end::Pic-->
                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <a
                    href="#"
                    class="
                      text-dark
                      font-weight-bold
                      text-hover-primary
                      font-size-h4
                      mb-0
                    "
                    >Paypal</a
                  >
                </div>
                <!--end::Title-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::User-->
            <!--begin::Desc-->
            <p class="mb-7">
              Pay affiliates fast and automatically via PayPal integration.
            </p>
            <!--end::Desc-->
            <button
              class="
                btn btn-block btn-sm btn-light-warning
                font-weight-bolder
                text-uppercase
                py-4
              "
              @click="openPaypalSetupModal"
              >Setup
            </button>
          </div>
          <!--end::Body-->
        </div>
        <!--end::Card-->
      </div>
    </div>

    <div class="card card-custom">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-bixgrow">
            Email marketing services
          </h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
      <div class="card-body">
        <div class="row d-flex flex-wrap mb-4">
          <div class="col-xl-6 col-xxl-4 mb-2">
            <div class="card card-custom card-integration">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                  <img
                    class="img-integration"
                    src="/media/logos/klaviyo.png"
                    alt="Klaviyo"
                    title="Klaviyo"
                  />
                  <div class="ml-2">
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <h5 style="font-size: 1.25rem">Klaviyo</h5>
                      <div v-if="klaviyo_api_key">
                        <div v-if="!loadingButton">
                          <b-form-checkbox
                            id="active_klaviyo"
                            class="mb-10 mr-5"
                            v-model="klaviyoSyncEnabled"
                            switch
                            size="lg"
                            value="1"
                            unchecked-value="0"
                          ></b-form-checkbox>
                        </div>
                        <div v-else>
                          <div class="mb-10 custom-control">
                            <Loading></Loading>
                          </div>
                        </div>
                      </div>
                    </div>

                    <p>
                      Sync your affiliate list to your Klaviyo account to
                      automate email marketing campaigns.
                    </p>
                  </div>
                </div>
              </div>
              <div
                class="
                  card-footer
                  p-4
                  d-flex
                  align-items-center
                  justify-content-end
                "
              >
                <div v-if="klaviyo_api_key">
                  <button
                    :disabled="!klaviyo_sync_enabled || loadingReSync"
                    type="button"
                    @click="reSyncKlaviyo"
                    class="btn-sm btn btn-primary mr-2 mt-2 mb-2"
                  >
                    <b-spinner v-if="loadingReSync" small></b-spinner>
                    Re-Sync
                  </button>
                  <button
                    :disabled="!klaviyo_sync_enabled"
                    @click="openKlaviyoModal"
                    type="button"
                    class="btn-sm btn btn-secondary mt-2 mb-2"
                  >
                    Re-Configure
                  </button>
                </div>
                <div v-else>
                  <button
                    @click="openKlaviyoModal"
                    type="button"
                    class="btn-sm btn btn-secondary mt-2 mb-2"
                  >
                    Connect
                  </button>
                </div>
              </div>
            </div>
            <KlaviyoModal
              :key="keyKlaviyo"
              @hiddenKlaviyoModal="hiddenKlaviyoModal"
              ref="KlaviyoModal"
            ></KlaviyoModal>

            <PaypalSetupModal ref="paypalSetupModal"></PaypalSetupModal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import TableLoader from "@/view/content/TableLoader.vue";
import { BOverlay, BFormCheckbox, BSpinner } from "bootstrap-vue";
import KlaviyoModal from "@/view/pages/setting/partials/KlaviyoModal.vue";
import PaypalSetupModal from "@/view/pages/setting/partials/PaypalSetupModal.vue";
import { mapActions, mapGetters } from "vuex";
import Loading from "@/view/content/LoaderVue.vue";
import ApiService from "../../../core/services/api.service";
export default {
  components: {
    BOverlay,
    TableLoader,
    KlaviyoModal,
    BFormCheckbox,
    Loading,
    BSpinner,
    PaypalSetupModal,
  },
  data() {
    return {
      loadingButton: false,
      keyKlaviyo: 0,
      loadingReSync: false,
    };
  },
  methods: {
    ...mapActions(["toggleKlaviyoSyncEnabled"]),
    openKlaviyoModal() {
      this.$refs["KlaviyoModal"].$refs["klaviyoSetup"].show();
    },
    hiddenKlaviyoModal() {
      this.keyKlaviyo++;
    },
    reSyncKlaviyo() {
      this.loadingReSync = true;
      let resource = `/klaviyo/sync_klaviyo`;
      ApiService.put(resource)
        .then(({ data }) => {
          if (data.data.status == "ok") {
            this.$toast.success(data.data.message, { position: "bottom" });
          }
        })
        .finally(() => {
          this.loadingReSync = false;
        });
    },

    //Paypal setup methods
    openPaypalSetupModal() {
      this.$refs["paypalSetupModal"].$refs["paypalSetupModal"].show();
    }

  },
  computed: {
    ...mapGetters(["klaviyo_sync_enabled", "klaviyo_api_key"]),
    klaviyoSyncEnabled: {
      get() {
        return this.klaviyo_sync_enabled;
      },
      set(newValue) {
        this.loadingButton = true;
        this.toggleKlaviyoSyncEnabled().then(() => {
          this.loadingButton = false;
          if (newValue == 1) {
            this.$toast.success("Activated", { position: "bottom" });
          } else {
            this.$toast.success("Deactivated", { position: "bottom" });
          }
        });
      },
    },
  },
};
</script>
<style scoped>
.card-integration {
  /* box-shadow: none !important; */
  border: 1px solid #ebedf3;
}
.img-integration {
  width: 50px !important;
}
</style>