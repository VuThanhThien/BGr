<template>
  <div>
    <b-modal
      hide-footer
      :size="trackingConversionType != 4 ? 'xl' : 'lg'"
      ref="explanationModal"
      :body-bg-variant="trackingConversionType != 4 && orderId ? 'light' : ''"
      title="Commission details"
      @hidden="hiddenModalExplanation"
    >
      <div
        class="card card-custom card-stretch gutter-b"
        v-if="trackingConversionType != 4 && orderId"
      >
        <b-tabs card nav-class="nav-tabs-line">
          <b-tab title="Commission explanation">
            <div class="form-group">
              <label class="font-weight-bolder font-size-lg text-dark-75"
                >Comment
              </label>
              <div class="row" v-if="!comment">
                <div class="col-md-6 mb-2">
                  <input
                    :disabled="!isAdd"
                    v-model="newComment"
                    type="text"
                    class="form-control"
                    placeholder="Enter your comment"
                  />
                </div>

                <div class="col-md-4 mb-2">
                  <button
                    type="button"
                    v-if="isAdd"
                    @click="saveComment"
                    class="btn btn-primary"
                    :disabled="loading || !newComment"
                  >
                    <b-spinner v-if="loading" small></b-spinner>
                    Save comment
                  </button>
                  <button
                    type="button"
                    v-else
                    @click="editComment"
                    class="btn btn-primary"
                  >
                    Edit
                  </button>
                </div>
              </div>
              <div class="row" v-else>
                <div class="col-md-6 mb-2">
                  <input
                    v-model="newComment"
                    :disabled="isEdit"
                    type="text"
                    class="form-control"
                    placeholder="Enter your comment"
                  />
                </div>

                <div class="col-md-4 mb-2">
                  <button
                    type="button"
                    v-if="!isEdit"
                    @click="saveComment"
                    class="btn btn-primary"
                    :disabled="loading || !newComment"
                  >
                    <b-spinner v-if="loading" small></b-spinner>
                    Save comment
                  </button>
                  <button
                    type="button"
                    v-else
                    @click="editComment"
                    class="btn btn-primary"
                  >
                    Edit
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table
                class="
                  table
                  table-head-custom
                  table-vertical-center
                  table-head-bg
                  table-bordered
                "
              >
                <thead>
                  <tr class="text-left">
                    <th class="pl-7" style="min-width: 100px">
                      <span class="text-dark-75">Type</span>
                    </th>
                    <th style="min-width: 120px">Total conversions</th>
                    <th style="min-width: 100px">Quantity</th>
                    <th style="min-width: 100px">Item name</th>
                    <th style="min-width: 100px">SKU</th>
                    <th style="min-width: 200px">Commission applied</th>
                    <th style="min-width: 101px">Commission</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in data.line_items" :key="index">
                    <td>
                      <span> Item </span>
                    </td>
                    <td>
                      <span
                        >{{ formatMoney(item.price * item.quantity, format) }}
                      </span>
                    </td>
                    <td>
                      <span>
                        {{ item.quantity }}
                      </span>
                    </td>
                    <td>
                      <span>
                        {{ item.name }}
                      </span>
                    </td>
                    <td>
                      <span>
                        {{ item.sku }}
                      </span>
                    </td>
                    <td>
                      <span v-html="genTextCommissionApplied(item)"> </span>
                    </td>
                    <td>
                      <span>
                        {{ formatMoney(item.commission, format) }}
                      </span>
                    </td>
                  </tr>
                  <!-- begin ORDER COMMISION -->
                  <tr v-if="data.commission_type == 2">
                    <td>
                      <span> Order commission </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span>
                        Flat rate per order @
                        {{ formatMoney(data.commission_amount, format) }}
                      </span>
                      <span class="label label-lg font-weight-bold label-inline"
                        >Program commission</span
                      >
                    </td>
                    <td>
                      {{ formatMoney(data.commission_amount, format) }}
                    </td>
                  </tr>
                  <!-- end ORDER COMMISION ROW -->
                  <!-- begin DISCOUNT ROW -->
                  <tr>
                    <td>
                      <span> Discounts </span>
                    </td>
                    <td>
                      <span>
                        {{ formatMoney(-data.total_discounts, format) }}
                      </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span v-if="data.include_discounts">
                        <span v-if="hasProductCommission">
                          Combination of
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Program commission</span
                          >
                          and
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Product commission</span
                          >
                        </span>
                        <span v-else>
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Program commission</span
                          >
                        </span>
                      </span>
                      <span v-else> - </span>
                    </td>
                    <td>
                      <span v-if="data.include_discounts">
                        {{ this.totalDiscountCommission() }}
                      </span>
                      <span v-else class="text-muted"> Not including </span>
                    </td>
                  </tr>
                  <!-- end DISCOUNT ROW -->

                  <!-- begin SHIPPING ROW -->
                  <tr>
                    <td>
                      <span> Shipping </span>
                    </td>
                    <td>
                      <span>
                        {{ formatMoney(data.total_shipping, format) }}
                      </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span v-if="data.shipping_commission">
                        <span
                          class="label label-lg font-weight-bold label-inline"
                          >Program commission</span
                        >
                      </span>
                      <span v-else> - </span>
                    </td>
                    <td>
                      <span v-if="data.shipping_commission">
                        {{ formatMoney(data.shipping_commission, format) }}
                      </span>
                      <span v-else class="text-muted"> Not including </span>
                    </td>
                  </tr>
                  <!-- end SHIPPING ROW -->

                  <!-- begin TAX ROW -->
                  <tr>
                    <td>
                      <span> Tax </span>
                    </td>
                    <td>
                      <span v-if="data.taxes_included">
                        {{ formatMoney(data.total_tax, format) }} (included)
                      </span>

                      <span v-else>
                        {{ formatMoney(data.total_tax, format) }}
                      </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span v-if="data.exclude_tax && !data.taxes_included">
                        -
                      </span>
                      <span v-else>
                        <span v-if="hasProductCommission">
                          Combination of
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Program commission</span
                          >
                          and
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Product commission</span
                          >
                        </span>
                        <span v-else>
                          <span
                            class="label label-lg font-weight-bold label-inline"
                            >Program commission</span
                          >
                        </span>
                      </span>
                    </td>
                    <td>
                      <span
                        v-if="data.exclude_tax && !data.taxes_included"
                        class="text-muted"
                      >
                        Not including
                      </span>
                      <span
                        v-else-if="data.exclude_tax && data.taxes_included"
                        class="text-danger"
                      >
                        {{ formatMoney(-this.totalTaxCommission(), format) }}
                      </span>
                      <span
                        v-else-if="!data.exclude_tax && data.taxes_included"
                        class="text-muted"
                      >
                        included price
                      </span>
                      <span v-else>
                        {{ formatMoney(this.totalTaxCommission(), format) }}
                      </span>
                    </td>
                  </tr>
                  <!-- end TAX ROW -->

                  <!-- begin GRANT TOTAL -->
                  <tr
                    class="
                      bg-primary-o-50
                      font-weight-bolder font-size-lg
                      text-dark-75
                    "
                  >
                    <td>
                      <span> GRAND TOTAL </span>
                    </td>
                    <td>
                      <span>
                        {{ formatMoney(grantTotalSale(), format) }}
                      </span>
                    </td>
                    <td>
                      <span>
                        {{ totalQuantity() }}
                      </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      <span> - </span>
                    </td>
                    <td>
                      {{ formatMoney(data.total_commission, format) }}
                    </td>
                  </tr>
                  <!-- end GRANT TOTAL -->
                </tbody>
              </table>
            </div>
          </b-tab>
          <b-tab v-if="hasNetwork" title="Network explanation" lazy>
            <NetworkExplanation :id="currentConversionId"></NetworkExplanation>
          </b-tab>
        </b-tabs>
      </div>
      <div v-else-if="trackingConversionType != 4 && !orderId">
        <div class="form-group">
          <label class="font-weight-bolder font-size-lg text-dark-75"
            >Comment
          </label>
          <div class="row" v-if="!comment">
            <div class="col-md-6 mb-2">
              <input
                :disabled="!isAdd"
                v-model="newComment"
                type="text"
                class="form-control"
                placeholder="Enter your comment"
              />
            </div>

            <div class="col-md-4 mb-2">
              <button
                type="button"
                v-if="isAdd"
                @click="saveComment"
                class="btn btn-primary"
                :disabled="loading || !newComment"
              >
                <b-spinner v-if="loading" small></b-spinner>
                Save comment
              </button>
              <button
                type="button"
                v-else
                @click="editComment"
                class="btn btn-primary"
              >
                Edit
              </button>
            </div>
          </div>
          <div class="row" v-else>
            <div class="col-md-6 mb-2">
              <input
                v-model="newComment"
                :disabled="isEdit"
                type="text"
                class="form-control"
                placeholder="Enter your comment"
              />
            </div>

            <div class="col-md-4 mb-2">
              <button
                type="button"
                v-if="!isEdit"
                @click="saveComment"
                class="btn btn-primary"
                :disabled="loading || !newComment"
              >
                <b-spinner v-if="loading" small></b-spinner>
                Save comment
              </button>
              <button
                type="button"
                v-else
                @click="editComment"
                class="btn btn-primary"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table
            class="
              table
              table-head-custom
              table-vertical-center
              table-head-bg
              table-bordered
            "
          >
            <thead>
              <tr class="text-left">
                <th class="pl-7" style="min-width: 100px">
                  <span class="text-dark-75">Type</span>
                </th>
                <th style="min-width: 120px">Total conversions</th>
                <th style="min-width: 100px">Quantity</th>
                <th style="min-width: 100px">Item name</th>
                <th style="min-width: 100px">SKU</th>
                <th style="min-width: 200px">Commission applied</th>
                <th style="min-width: 101px">Commission</th>
              </tr>
            </thead>
            <tbody>
              <!-- begin GRANT TOTAL -->
              <tr
                class="
                  bg-primary-o-50
                  font-weight-bolder font-size-lg
                  text-dark-75
                "
              >
                <td>
                  <span> GRAND TOTAL </span>
                </td>
                <td>
                  <span>
                    {{ formatMoney(total, format) }}
                  </span>
                </td>
                <td>
                  <span> 0 </span>
                </td>
                <td>
                  <span> - </span>
                </td>
                <td>
                  <span> - </span>
                </td>
                <td>
                  <span> - </span>
                </td>
                <td>
                  {{ formatMoney(commission, format) }}
                </td>
              </tr>
              <!-- end GRANT TOTAL -->
            </tbody>
          </table>
        </div>
      </div>
      <div v-else>
        <alert-note :type="'alert-default alert-shadow'">
          Bonus for recruiting other affiliates into the program (through
          network link)
        </alert-note>
        <div class="form-group">
          <label class="font-weight-bolder font-size-lg text-dark-75"
            >Comment
          </label>
          <div class="row" v-if="!comment">
            <div class="col-md-6 mb-2">
              <input
                :disabled="!isAdd"
                v-model="newComment"
                type="text"
                class="form-control"
                placeholder="Enter your comment"
              />
            </div>

            <div class="col-md-4 mb-2">
              <button
                type="button"
                v-if="isAdd"
                @click="saveComment"
                class="btn btn-primary"
                :disabled="loading || !newComment"
              >
                <b-spinner v-if="loading" small></b-spinner>
                Save comment
              </button>
              <button
                type="button"
                v-else
                @click="editComment"
                class="btn btn-primary"
              >
                Edit
              </button>
            </div>
          </div>
          <div class="row" v-else>
            <div class="col-md-6 mb-2">
              <input
                v-model="newComment"
                :disabled="isEdit"
                type="text"
                class="form-control"
                placeholder="Enter your comment"
              />
            </div>

            <div class="col-md-4 mb-2">
              <button
                type="button"
                v-if="!isEdit"
                @click="saveComment"
                class="btn btn-primary"
                :disabled="loading || !newComment"
              >
                <b-spinner v-if="loading" small></b-spinner>
                Save comment
              </button>
              <button
                type="button"
                v-else
                @click="editComment"
                class="btn btn-primary"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table
            class="
              table
              table-head-custom
              table-vertical-center
              table-head-bg
              table-bordered
            "
          >
            <thead>
              <tr class="text-left">
                <th class="pl-7" style="min-width: 100px">Commission Type</th>
                <th style="min-width: 100px">Affiliate Recruited</th>
                <th style="min-width: 100px">Commission</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="data">
                <tr>
                  <td style="min-width: 100px">
                    <span class="label label-light-default label-inline"
                      >Recruitment bonus</span
                    >
                  </td>
                  <td>
                    <span>
                      {{ data.name }}
                    </span>
                  </td>
                  <td>
                    <span>
                      {{ formatMoney(commission, format) }}
                    </span>
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td
                    colspan="3"
                    style="text-align: center; color: #b5b5c3 !important"
                  >
                    <i class="far fa-folder-open icon-3x"></i>
                    <h6>No Data</h6>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { BTabs, BTab, BSpinner } from "bootstrap-vue";
import NetworkExplanation from "./NetworkExplanation.vue";
import ApiService from "../../../../core/services/api.service";
export default {
  components: {
    BTabs,
    BTab,
    NetworkExplanation,
    BSpinner,
  },
  props: [
    "data",
    "currentConversionId",
    "hasNetwork",
    "commission",
    "trackingConversionType",
    "comment",
    "orderId",
    "total",
  ],
  data() {
    return {
      hasProductCommission: false,
      loading: false,
      isEdit: true,
      newComment: "",
      isAdd: true,
    };
  },

  methods: {
    genTextCommissionApplied(item) {
      if (!item.is_product_commission && this.data.commission_type == 2) {
        return "-";
      }
      let text = "";
      if (item.is_product_commission) {
        if (item.commission_type == 1) {
          text = "percent of sale";
        } else {
          text = "flat rate";
        }
      } else {
        text = this.genTextCommissionType(
          item.commission_type,
          item.commission_amount,
          this.format
        );
      }
      text +=
        " @ " +
        this.formatCommissionAmount(
          item.commission_type,
          item.commission_amount,
          this.format
        );
      if (item.is_product_commission) {
        this.hasProductCommission = true;
        text +=
          '<div><span class="label label-lg font-weight-bold  label-inline">Product commission</span></div>';
      } else {
        text +=
          '<div><span class="label label-lg font-weight-bold label-inline">Program commission</span></div>';
      }

      return text;
    },
    totalDiscountCommission() {
      var total = 0;
      for (var i = 0; i < this.data.line_items.length; i++) {
        if (this.data.line_items[i].commission_type == 1) {
          total += this.data.line_items[i].discount_commission;
        }
      }
      return this.formatMoney(-total, this.format);
    },

    totalTaxCommission() {
      var total = 0;
      for (var i = 0; i < this.data.line_items.length; i++) {
        total += this.data.line_items[i].tax_commission;
      }
      if (this.data.shipping_tax_commission) {
        total += this.data.shipping_tax_commission;
      }
      return total;
    },
    grantTotalSale() {
      var total =
        parseFloat(this.data.total_shipping) -
        parseFloat(this.data.total_discounts);
      for (var i = 0; i < this.data.line_items.length; i++) {
        total += parseFloat(
          this.data.line_items[i].price * this.data.line_items[i].quantity
        );
      }

      if (!this.data.taxes_included) {
        total += parseFloat(this.data.total_tax);
      }

      return total;
    },
    totalQuantity() {
      var quantity = 0;
      for (var i = 0; i < this.data.line_items.length; i++) {
        quantity += this.data.line_items[i].quantity;
      }
      return quantity;
    },
    saveComment() {
      this.loading = true;
      let resource = `conversions/${this.currentConversionId}/comment`;
      ApiService.post(resource, {
        comment: this.newComment,
      })
        .then(({ data }) => {
          let temp = this.newComment;
          let conId =  this.currentConversionId;
          this.$emit(
            "addCommentSuccess",
            temp, conId
           
          );
          this.isEdit = true;
          this.isAdd = false;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    editComment() {
      this.isEdit = false;
      this.isAdd = true;
    },
    hiddenModalExplanation(){
      this.$emit('hiddenModalExplanation');
    }
  },

  watch: {
    comment(newValue, oldValue) {
      this.newComment = newValue;
    },
  },
};
</script>

<style>
</style>
