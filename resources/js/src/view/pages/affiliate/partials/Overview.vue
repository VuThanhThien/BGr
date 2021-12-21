<template>
  <div class="row">
    <div class="col-xl-6">
      <!--begin::Nav Panel Widget 2-->
      <div class="card card-custom card-stretch gutter-b">
        <div class="card-header align-items-center border-0">
          <h3 class="card-title align-items-start flex-column">
            <span class="font-weight-bolder text-dark">Profile</span>
          </h3>
        </div>
        <!--begin::Body-->
        <div class="card-body pt-0">
          <!--begin::Contact-->
          <div class="pb-6">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="font-weight-bold mr-2">Name:</span>
              <a href="#" class="text-muted text-hover-primary">{{
                affiliate.full_name
              }}</a>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="font-weight-bold mr-2">Email:</span>
              <a href="#" class="text-muted text-hover-primary">{{
                affiliate.email
              }}</a>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="font-weight-bold mr-2">Status:</span>
              <a href="#" class="text-muted text-hover-primary">
                <span
                  v-if="affiliate.status == 1"
                  class="label label-lg label-light-primary label-inline"
                  >Approved</span
                >
                <span
                  v-else-if="affiliate.status == 2"
                  class="label label-lg label-light-warning label-inline"
                  >Pending</span
                >
                <span
                  v-else-if="affiliate.status == 3"
                  class="label label-lg label-light-warning label-inline"
                  >Denied</span
                >
              </a>
            </div>

            <div
              v-if="affiliate.phone"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Phone:</span>
              <span class="text-muted">{{ affiliate.phone }}</span>
            </div>

            <div
              v-if="affiliate.company"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Company:</span>
              <span class="text-muted">{{ affiliate.company }}</span>
            </div>

            <div
              v-if="affiliate.country"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Country:</span>
              <span class="text-muted">{{ getCountryNameByCode(affiliate.country) }}</span>
            </div>
            <div
              v-if="affiliate.city"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">City:</span>
              <span class="text-muted">{{ affiliate.city }}</span>
            </div>
            <div
              v-if="affiliate.address"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Address:</span>
              <span class="text-muted">{{ affiliate.address }}</span>
            </div>
            <div
              v-if="affiliate.state"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">State:</span>
              <span class="text-muted">{{ affiliate.state }}</span>
            </div>
            <div
              v-if="affiliate.zipcode"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Zipcode:</span>
              <span class="text-muted">{{ affiliate.zipcode }}</span>
            </div>
            <div
              v-if="affiliate.facebook"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Facebook:</span>
              <span class="text-muted">{{ affiliate.facebook }}</span>
            </div>
            <div
              v-if="affiliate.youtube"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Youtube:</span>
              <span class="text-muted">{{ affiliate.youtube }}</span>
            </div>
            <div
              v-if="affiliate.instagram"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Instagram:</span>
              <span class="text-muted">{{ affiliate.instagram }}</span>
            </div>
            <div
              v-if="affiliate.twitter"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Twitter:</span>
              <span class="text-muted">{{ affiliate.twitter }}</span>
            </div>
            <div
              v-if="affiliate.website"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <span class="font-weight-bold mr-2">Website:</span>
              <span class="text-muted">{{ affiliate.website }}</span>
            </div>
            <div
              class="d-flex align-items-center justify-content-between mb-2"
              v-if="affiliate.personal_detail"
            >
              <span class="font-weight-bold mr-2">Personal detail:</span>
              <span class="text-muted"> {{ affiliate.personal_detail }}</span>
            </div>
            <template v-if="affiliate.additional_infos">
              <div
                class="d-flex align-items-center justify-content-between mb-2"
                v-for="(additional_info, i) in affiliate.additional_infos"
                :key="i"
              >
                <span class="font-weight-bold mr-2"
                  >{{ additional_info.label }}:</span
                >
                <span class="text-muted"> {{ additional_info.value }}</span>
              </div>
            </template>
          </div>
          <!--end::Contact-->
          <!--begin::Contact-->
          <!--end::Contact-->
          <!-- <a
            href="#"
            class="
              btn btn-light-success
              font-weight-bold
              py-3
              px-6
              mb-2
              text-center
              btn-block
            "
            >Edit profile</a
          > -->
        </div>
        <!--end::Body-->
      </div>
      <!--end::Nav Panel Widget 2-->
    </div>
    <div class="col-xl-6">
      <!--begin::Nav Panel Widget 3-->
      <div class="card card-custom bg-light-success gutter-b">
        <div class="card-header align-items-center border-0">
          <h3 class="card-title align-items-start flex-column">
            <span class="font-weight-bolder text-dark">Program</span>
          </h3>
        </div>
        <!--begin::Body-->
        <div class="card-body d-flex align-items-center pt-0">
          <div class="py-2">
            <h3 class="text-success font-weight-bolder" v-if="this.group">
              {{ this.group.name }}
            </h3>
            <h5 class="text-success mb-3" v-if="this.group">
              {{ genTextCommissionType(this.group.commission_type) }}
              {{
                formatCommissionAmount(
                  this.group.commission_type,
                  this.group.commission_amount,
                  format
                )
              }}
            </h5>
            <button
              class="btn btn-success font-weight-bold py-2 px-6"
              v-b-modal.changeGroupModal
            >
              Change
            </button>
          </div>
        </div>
        <b-modal
          ref="changeGroupModal"
          id="changeGroupModal"
          title="Change Program"
        >
          <select
            v-model="selectedGroupId"
            class="form-control form-control-custom"
          >
            <option value="" disabled>Choose Program</option>
            <option v-for="item in this.groups" :key="item.id" :value="item.id">
              {{ item.name }}
            </option>
          </select>
          <template #modal-footer>
            <div class="w-100">
              <LoadingSubmitButton
                :loading="submitLoading"
                class="float-right"
                @click="onSaveGroup"
              >
                Save
              </LoadingSubmitButton>
            </div>
          </template>
        </b-modal>
        <!--end::Body-->
      </div>
      <div class="card card-custom gutter-b">
        <!--begin::Body-->
        <div class="card-body pt-0 pt-5">
          <span class="font-weight-bolder text-dark"
            >Default affiliate link</span
          >
          <div class="d-flex mt-1">
            <input
              type="text"
              disabled
              class="form-control form-control-solid me-3 flex-grow-1"
              ref="aff_link_input"
              name="search"
              v-model="affLink"
            />
            <button
              class="btn btn-light fw-bolder flex-shrink-0 ml-1"
              @click="copy('aff_link_input', $event)"
            >
              Copy
            </button>
            <button
              @click="openModalDefaultAffiliate"
              class="
                btn btn-icon btn-light btn-hover-primary
                fw-bolder
                flex-grow-1 flex-shrink-0
                ml-1
              "
              v-b-tooltip.hover
              title="Customize default affiliate link"
            >
              <span class="svg-icon svg-icon-md svg-icon-primary">
                <inline-svg src="media/svg/icons/Communication/Write.svg" />
              </span>
            </button>
          </div>
          <div class="mt-5">
            <button
              v-if="!affiliate.affiliates_custom_link"
              class="btn btn-primary font-weight-bold py-2 px-6"
              v-b-modal.affiliates_custom_link
            >
              Add custom affiliate link
            </button>
            <span
              v-if="affiliate.affiliates_custom_link"
              class="font-weight-bolder text-dark"
              >Custom Affiliate Link
            </span>
            <div class="d-flex mt-1" v-if="affiliate.affiliates_custom_link">
              <input
                type="text"
                disabled
                class="form-control form-control-solid me-3 flex-grow-1"
                ref="affiliates_custom_link_aff"
                v-model="affiliatesCustomlinkFull"
              />
              <button
                class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                @click="copy('affiliates_custom_link_aff', $event)"
              >
                Copy
              </button>
              <button
                @click="openModalEditAffiliatesCustomLinkAff"
                class="
                  btn btn-icon btn-light btn-hover-primary
                  fw-bolder
                  flex-grow-1 flex-shrink-0
                  ml-1
                "
                v-b-tooltip.hover
                title="Edit custom link"
              >
                <span class="svg-icon svg-icon-md svg-icon-primary">
                  <inline-svg src="media/svg/icons/Communication/Write.svg" />
                </span>
              </button>
              <b-modal
                id="edit_affiliates_custom_link_aff"
                size="lg"
                title="Edit custom affiliate link"
                hide-footer
                ref="editAffiliatesCustomLinkAff"
              >
                <div class="card card-custom card-stretch gutter-b">
                  <div class="card-body">
                    <alert-note :type="'alert-default'">
                      Edit a clean-looking url by changing the affiliate link to your preference.
                   The generated custom affiliate link will be redirected to the default affiliate link.
                    To allow your affiliates to edit this link, go <router-link :to="{ name: 'settings.advanced' }">here</router-link
            >.
                    </alert-note>
                    <div class="form-group">
                      <label>Affiliate Link</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{
                            linkShopDomain
                          }}</span>
                        </div>
                        <input
                          style="height: auto"
                          type="text"
                          v-model="pathCustomAffiliateLink"
                          class="form-control"
                          placeholder="Enter your custom path here"
                          :class="{ 'border-error': !isPathValid }"
                        />
                      </div>
                      <div
                        class="invalid-feedback"
                        style="text-align: center"
                        v-bind:style="{
                          display: isPathValid ? 'none' : 'block',
                        }"
                      >
                        Path can't be blank
                      </div>
                    </div>
                    <div class="w-100">
                      <LoadingSubmitButton
                        :loading="submitLoading"
                        class="float-right"
                        @click="saveEditCustomAffiliateLink"
                      >
                        Save
                      </LoadingSubmitButton>
                    </div>
                  </div>
                </div>
              </b-modal>
            </div>
          </div>
          <b-modal
            id="new_referral_link"
            size="lg"
            ref="customizeReferralLink"
            title="Customize Default Affiliate Link"
            hide-footer
          >
            <div class="card card-custom card-stretch gutter-b">
              <div class="card-body pt-0">
                <alert-note :type="'alert-default'">
                  Change the default affiliate link. It is helpful when you want
                  the affiliate to promote a specific product.
                </alert-note>
                <div class="d-flex mt-3">
                  <input
                    type="text"
                    class="form-control me-3 flex-grow-1"
                    ref="primary_aff_link"
                    name="search"
                    v-model="primaryAffLink"
                    :class="{ 'border-error': !isLinkValid }"
                  />
                  <button
                    type="button"
                    class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                  >
                    {{ "?bg_ref="+affiliate.group_id+':' + affiliate.hash_code }}
                  </button>
                </div>
                <div
                  class="invalid-feedback"
                  v-bind:style="{
                    display: isLinkValid ? 'none' : 'block',
                  }"
                >
                  Field is required and valid link
                </div>
                <span class="d-block my-2 text-muted">{{
                  previewCustomizeLink
                }}</span>
                <div class="w-100">
                  <LoadingSubmitButton
                    :loading="submitLoading1"
                    class="float-right mt-3"
                    @click="savePrimaryAffLink(primaryAffLink)"
                  >
                    Save
                  </LoadingSubmitButton>
                </div>
              </div>
            </div>
          </b-modal>
          <b-modal
            id="affiliates_custom_link"
            size="lg"
            title="Add custom affiliate link"
            hide-footer
            ref="affiliatesCustomLink"
          >
            <div class="card card-custom card-stretch gutter-b">
              <div class="card-body">
                <alert-note :type="'alert-default'">
                  Create a clean-looking url by changing the affiliate link to your preference.
                   The generated custom affiliate link will be redirected to the default affiliate link.
                    To allow your affiliates to edit this link, go <router-link :to="{ name: 'settings.advanced' }">here</router-link
            >.
                </alert-note>
                <div class="form-group">
                  <label>Affiliate Link</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{ linkShopDomain }}</span>
                    </div>
                    <input
                      style="height: auto"
                      type="text"
                      v-model="pathCustomAffiliateLink"
                      class="form-control"
                      placeholder="Enter your custom path here"
                      :class="{ 'border-error': !isPathValid }"
                    />
                  </div>
                  <div
                    class="invalid-feedback"
                    style="text-align: center"
                    v-bind:style="{
                      display: isPathValid ? 'none' : 'block',
                    }"
                  >
                    Path can't be blank
                  </div>
                </div>
                <div class="w-100">
                  <LoadingSubmitButton
                    :loading="submitLoading"
                    class="float-right"
                    @click="saveCustomAffiliateLink"
                  >
                    Save
                  </LoadingSubmitButton>
                </div>
              </div>
            </div>
          </b-modal>
        </div>
        <!--end::Body-->
      </div>
      <!--end::Nav Panel Widget 3-->
    </div>
  </div>
</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
  components: {
    LoadingSubmitButton,
  },
  computed: {
    ...mapGetters(["defaultAffiliateLink", "shopDomain"]),
    id() {
      return this.$route.params.id;
    },
    affiliate() {
      return this.$store.getters.currentDetailAffiliate;
    },
    groups() {
      return this.$store.getters.groups;
    },
    group() {
      return this.groups.find((g) => g.id == this.affiliate.group_id);
    },
    affLink() {
      let affLink = this.affiliate.primary_aff_link
        ? this.affiliate.primary_aff_link
        : this.defaultAffiliateLink;
      return this.generateLinkAffiliate(affLink) +this.affiliate.group_id+':'+ this.affiliate.hash_code;
    },
    previewCustomizeLink() {
      if (this.primaryAffLink) {
        return (
          this.generateLinkAffiliate(this.primaryAffLink) +this.affiliate.group_id+':'+
          this.affiliate.hash_code
        );
      } else {
        return this.affLink;
      }
    },
    linkShopDomain() {
      return "https://" + this.shopDomain + "/";
    },
    affiliatesCustomlinkFull() {
      return (
        "https://" + this.shopDomain + this.affiliate.affiliates_custom_link
      );
    },
  },
  data(){
    return{
      submitLoading1:false,
      submitLoading: false,
      selectedGroupId: "",
      isLinkValid: true,
      primaryAffLink: "",
      pathCustomAffiliateLink: "",
      isPathValid: true,
    };
  },
  methods: {
    ...mapActions(["loadAffiliate"]),
    ...mapMutations([
      "UPDATE_GROUP_BY_AFFILIATE_COUNT",
      "SET_CURRENT_DETAIL_AFFILIATE",
      "updateAffiliatesCustomLink",
    ]),
    onSaveGroup() {
      this.submitLoading = true;
      let resource = `affiliates/${this.id}/change-group`;
      let groupId = this.selectedGroupId != 0 ? this.selectedGroupId : 0;
      if (this.selectedGroupId) {
        ApiService.post(resource, { group_id: groupId }).then(({ data }) => {
          this.submitLoading = false;
          this.$refs["changeGroupModal"].hide();
          this.UPDATE_GROUP_BY_AFFILIATE_COUNT({
            idGroupCurrent: this.affiliate.group_id,
            idGroupChange: groupId,
            countAffiliate: 1,
          });
          this.loadAffiliate(this.id)
            .then(({ res }) => {
              this.$toast.success("Changed", {
                position: "bottom",
              });
            })
            .catch((err) => {
              this.$toast.error(err.data.message, { position: "bottom" });
            });
        });
      } else {
        this.$toast.error("Please select group", { position: "bottom" });
        this.submitLoading = false;
      }
    },
    isValidHttpUrl(string) {
      let url;

      try {
        url = new URL(string);
      } catch (_) {
        return false;
      }

      return true;
    },
    savePrimaryAffLink(link) {
      this.submitLoading1 = true;
      if (!this.isValidHttpUrl(link)) {
        this.submitLoading1 = false;
        this.isLinkValid = false;
        return;
      }
      this.isLinkValid = true;
      let resource = `affiliates/${this.id}/set-primary-aff-link`;
      ApiService.post(resource, {
        primary_aff_link: link,
      })
        .then(({ data }) => {
          this.SET_CURRENT_DETAIL_AFFILIATE(data.data);
          this.$toast.success("Updated", {
            position: "bottom",
          });
          this.$refs["customizeReferralLink"].hide();
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        })
        .finally(() => {
          this.submitLoading1 = false;
        });
    },
    saveCustomAffiliateLink() {
      this.submitLoading = true;
      if (!this.pathCustomAffiliateLink) {
        this.submitLoading = false;
        this.isPathValid = false;
        return;
      }
      this.isPathValid = true;
      let resource = `affiliates/${this.id}/set-custom-aff-link`;
      ApiService.post(resource, {
        path: this.pathCustomAffiliateLink,
        target: this.affLink,
      })
        .then(({ data }) => {
          if (data.data.status) {
            this.updateAffiliatesCustomLink({
              affiliates_custom_link: data.data.path,
            });
            this.$refs["affiliatesCustomLink"].hide();
            this.$toast.success("Redirect created", { position: "bottom" });
          } else {
            this.$toast.error(data.data.message, { position: "bottom" });
          }
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            let arrError = Object.keys(response.data.errors);
            if (arrError.length) {
              this.$toast.error(response.data.errors[arrError[0]][0], {
                position: "bottom",
              });
            }
          }
        })
        .finally(() => {
          this.submitLoading = false;
        });
    },
    openModalEditAffiliatesCustomLinkAff() {
      this.pathCustomAffiliateLink = this.affiliate.affiliates_custom_link;
      this.pathCustomAffiliateLink = this.pathCustomAffiliateLink.slice(1);
      this.$refs["editAffiliatesCustomLinkAff"].show();
    },
    saveEditCustomAffiliateLink() {
      this.submitLoading = true;
      if (!this.pathCustomAffiliateLink) {
        this.submitLoading = false;
        this.isPathValid = false;
        return;
      }
      this.isPathValid = true;
      let resource = `affiliates/${this.id}/update-custom-aff-link`;
      ApiService.put(resource, {
        path: this.pathCustomAffiliateLink,
      })
        .then(({ data }) => {
          if (data.data.status) {
            this.updateAffiliatesCustomLink({
              affiliates_custom_link: data.data.path,
            });
            this.$refs["editAffiliatesCustomLinkAff"].hide();
            this.$toast.success("Updated", { position: "bottom" });
          } else {
            this.$toast.error(data.data.message, { position: "bottom" });
          }
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            let arrError = Object.keys(response.data.errors);
            if (arrError.length) {
              this.$toast.error(response.data.errors[arrError[0]][0], {
                position: "bottom",
              });
            }
          }
        })
        .finally(() => {
          this.submitLoading = false;
        });
    },
    openModalDefaultAffiliate() {
      this.primaryAffLink = this.affiliate.primary_aff_link
        ? this.affiliate.primary_aff_link
        : this.defaultAffiliateLink;
      this.$refs["customizeReferralLink"].show();
    },
  },
};
</script>

<style>
.card-rounded {
  padding: 0px !important;
}
</style>
<style scoped>
.border-error {
  border-color: #f64e60 !important;
}
</style>
