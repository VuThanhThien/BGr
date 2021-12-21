<template>
  <div>
    <b-modal
      hide-footer
      size="lg"
      ref="addConversion"
      title="Add Conversion"
      body-bg-variant="light"
      @hidden="hiddenModalConversion"
    >
      <!-- Note -->
      <alert-note :type="'alert-white alert-shadow'">
        You can manually add new conversions and assign them to affiliates. Read
        more details
        <a
          href="https://docs.bixgrow.com/conversion/manually-add-a-conversion"
          target="_blank"
          >here</a
        >.
      </alert-note>

      <div class="card card-custom card-stretch gutter-b">
        <div class="card-body">
          <form @submit.stop.prevent="addConversion">
            <div class="form-group">
              <label for="exampleSelect1">Select type</label>
              <select class="form-control" v-model="type" id="exampleSelect1">
                <option
                  v-for="(t, index) in typeSelectedOptions"
                  :key="index"
                  :value="t.value"
                >
                  {{ t.text }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label>Affiliate <span class="text-danger">*</span> </label>
              <SearchAff @input="setAffiliateSelected" ref="searchAff" />
            </div>
            <div v-if="type == 0">
              <div class="form-group">
                <label>Order Number <span class="text-danger">*</span></label>
                <input
                  required
                  type="text"
                  v-model="orderId"
                  class="form-control"
                  placeholder="Enter order number"
                />
              </div>
            </div>
            <div v-else>
              <div class="form-group">
                <label
                  >Commission Amount <span class="text-danger">*</span></label
                >
                <div class="input-group">
                  <input
                    type="number"
                    v-model="commission"
                    class="form-control"
                    placeholder="Enter commission amount"
                    aria-describedby="basic-addon2"
                    min="0"
                    step="any"
                    required
                  />
                  <div class="input-group-append">
                    <span class="input-group-text">{{ symbolCurrency }}</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Total Sale</label>
                <div class="input-group">
                  <input
                    v-model="total"
                    type="number"
                    class="form-control"
                    placeholder="Enter total sale"
                    min="0"
                    step="any"
                  />
                  <div class="input-group-append">
                    <span class="input-group-text">{{ symbolCurrency }}</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Note</label>
                <input
                  v-model="comment"
                  class="form-control"
                  placeholder="Enter note"
                />
              </div>
            </div>

            <div class="float-right">
              <button
                type="submit"
                class="btn btn-sm btn-primary"
                :disabled="loading"
              >
                <b-spinner v-if="loading" small></b-spinner>
                Add conversion
              </button>
            </div>
          </form>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import SearchAff from "@/view/content/SearchAffiliate.vue";
import { BSpinner } from "bootstrap-vue";
import ApiService from "@/core/services/api.service";
import swal from "sweetalert2";
window.Swal = swal;
export default {
  components: {
    SearchAff,
    BSpinner,
  },
  props: [],
  data() {
    return {
      loading: false,
      type: 0,
      typeSelectedOptions: [
        { value: 0, text: "Use Order number" },
        { value: 1, text: "Fixed amount" },
      ],
      affiliateFilterSelected: null,
      orderId: null,
      comment: null,
      commission: null,
      total: null,
    };
  },

  methods: {
    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      if (value) {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.remove(
          "border-error"
        );
      } else {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
          "border-error"
        );
      }
    },
    addConversion() {
      this.loading = true;
      if (!this.affiliateFilterSelected) {
        this.$refs.searchAff.$refs.v_select.$refs.toggle.classList.add(
          "border-error"
        );
        this.loading = false;
        return;
      }
      let resource = `conversions`;
      if (!this.type) {
        let params = {
          order_id: this.orderId,
          affiliate_id: this.affiliateFilterSelected.id,
          type: 0,
        };
        ApiService.post(resource, params)
          .then(({ data }) => {
            if (data.data.status == "error") {
              this.$toast.error(JSON.stringify(data.data.message), {
                position: "bottom",
              });
            }
            if (data.data.status == "ok") {
              this.addConvesionSuccess();
              this.$toast.success(data.data.message, {
                position: "bottom",
              });
            }
            if (
              data.data.status == "this_affiliate_exists" ||
              data.data.status == "other_affiliate_exists"
            ) {
              let message = "";
              if (data.data.status == "this_affiliate_exists") {
                message =
                  "This conversion has been assigned to this affiliate, are you sure that you want to update the conversion?";
              } else {
                message =
                  "This conversion has already been assigned to another affiliate, are you sure that you want to change to this affiliate?";
              }
              Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Replace!",
              }).then((result) => {
                if (result.isConfirmed) {
                  params["is_replace"] = 1;
                  ApiService.post(resource, params)
                    .then(({ data }) => {
                      this.addConvesionSuccess();
                      this.$toast.success(data.data.message, {
                        position: "bottom",
                      });
                    })
                    .catch(({ response }) => {
                      this.$toast.error(response.data.message, {
                        position: "bottom",
                      });
                    })
                    .finally(() => {
                      this.loading = false;
                    });
                } else {
                  this.$refs["addConversion"].hide();
                }
              });
            }
          })
          .finally(() => {
            this.loading = false;
          });
      } else {
        let params = {
          affiliate_id: this.affiliateFilterSelected.id,
          type: 1,
          total: this.total,
          commission: this.commission,
          comment: this.comment,
        };
        ApiService.post(resource, params)
          .then(({ data }) => {
            if (data.data.status == "ok") {
              this.addConvesionSuccess();
              this.$toast.success(data.data.message, {
                position: "bottom",
              });
            }
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    addConvesionSuccess() {
      this.$emit("addConvesionSuccess");
      this.$refs["addConversion"].hide();
    },
    hiddenModalConversion() {
      this.$emit("hiddenModalConversion");
    },
  },
  computed: {
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
  },
};
</script>

<style>
.border-error {
  border-color: #f64e60 !important;
}
</style>
