<template>
  <div>
    <b-modal
      hide-footer
      size="xl"
      ref="explanationModal"
      :title = titleModal
    >
      <div
        class="table-responsive"
        v-if="trackingConversionType != 4 && trackingConversionType != 3 && orderId"
      >
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
                <span class="text-dark-75">{{$t('type')}}</span>
              </th>
              <th style="min-width: 120px">{{$t('total_sales')}}</th>
              <th style="min-width: 100px">{{$t('quantity')}}</th>
              <th style="min-width: 100px">{{$t('item_name')}}</th>
              <th style="min-width: 100px">{{$t('sku')}}</th>
              <th style="min-width: 200px">{{$t('commission_applied')}}</th>
              <th style="min-width: 101px">{{$t('commission')}}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in data.line_items"
              :item="item"
              :key="index"
            >
              <td>
                <span>{{$t('item')}}</span>
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
                <span>{{$t('order_commission')}}</span>
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
                  {{$t('flat_rate_order')}}
                  {{ formatMoney(data.commission_amount, format) }}
                </span>
                <span class="label label-lg font-weight-bold label-inline"
                  >{{$t('program_commission')}}</span
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
                <span>{{$t('discounts')}}</span>
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
                <span> - </span>
              </td>
              <td>
                <span v-if="data.include_discounts">
                  {{ this.totalDiscountCommission() }}
                </span>
                <span v-else class="text-muted">{{$t('not_including')}}</span>
              </td>
            </tr>
            <!-- end DISCOUNT ROW -->

            <!-- begin SHIPPING ROW -->
            <tr>
              <td>
                <span>{{$t('shipping')}}</span>
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
                <span> - </span>
              </td>
              <td>
                <span v-if="data.shipping_commission">
                  {{ formatMoney(data.shipping_commission, format) }}
                </span>
                <span v-else class="text-muted">{{$t('not_including')}}</span>
              </td>
            </tr>
            <!-- end SHIPPING ROW -->

            <!-- begin TAX ROW -->
            <tr>
              <td>
                <span>{{$t('tax')}}</span>
              </td>
              <td>
                <span v-if="data.taxes_included">
                  {{ formatMoney(data.total_tax, format) }} ({{$t('included')}})
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
                <span> - </span>
              </td>
              <td>
                <span
                  v-if="data.exclude_tax && !data.taxes_included"
                  class="text-muted"
                >
                  {{$t('not_including')}}
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
                  {{$t('included_price')}}
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
                <span>{{$t('grand_total')}}</span>
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
            <div v-else-if="trackingConversionType != 4 && trackingConversionType != 3 && !orderId">
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
                  <span class="text-dark-75">{{$t('type')}}</span>
                </th>
                <th style="min-width: 120px">{{$t('total_sales')}}</th>
                <th style="min-width: 100px">{{$t('quantity')}}</th>
                <th style="min-width: 100px">{{$t('item_name')}}</th>
                <th style="min-width: 100px">{{$t('sku')}}</th>
                <th style="min-width: 200px">{{$t('commission_applied')}}</th>
                <th style="min-width: 101px">{{$t('commission')}}</th>
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
                  <span> {{$t('grand_total')}} </span>
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
      <div v-else-if="trackingConversionType == 4">
        <alert-note :type="'alert-default alert-shadow'">
          {{$t('recruitment_bonus_explanation_text_note')}}
        </alert-note>
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
                <th class="pl-7" style="min-width: 100px">{{$t('commission_type')}}</th>
                <th style="min-width: 100px">{{$t('affiliate_recruited')}}</th>
                <th style="min-width: 100px">{{$t('commission')}}</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="data">
                <tr>
                  <td style="min-width: 100px">
                    <span class="label label-light-default label-inline"
                      >{{$t('recruitment_bonus')}}</span
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
                    <h6>{{$t('no_data')}}</h6>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <div v-else-if="trackingConversionType == 3">
        <alert-note :type="'alert-default alert-shadow'">
          {{$t('network_commission_explanation_text_note')}}
        </alert-note>
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
                <th class="pl-7" style="min-width: 100px">{{$t('commission_type')}}</th>
                <th style="min-width: 100px">{{$t('order_brought_by')}}</th>
                <th style="min-width: 100px">{{$t('commission')}}</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="networkCommision">
                <tr>
                  <td style="min-width: 100px">
                    <span class="label label-light-default label-inline"
                      >{{$t('network_commission')}}</span
                    >
                  </td>
                  <td>
                    <span>
                      {{ networkCommision.affiliate.full_name }}
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
                    <h6>{{$t('no_data')}}</h6>
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
import ApiService from "aff/core/services/api.service";
export default {
  props: [
    "data",
    "currentConversionId",
    "commission",
    "trackingConversionType",
    "total",
    "orderId"
  ],
  data() {
    return {
      networkCommision: null,
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
          text = this.$t('percent_sale');
        } else {
          text = this.$t('flat_rate');
        }
      } else {
        text = this.genTextCommissionType(
          item.commission_type
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
        text +=
          '<div><span class="label label-lg font-weight-bold  label-inline">'+this.$t('product_commission')+'</span></div>';
      } else {
        text +=
          '<div><span class="label label-lg font-weight-bold label-inline">'+this.$t('program_commission')+'</span></div>';
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
        total += parseFloat(this.data.line_items[i].price);
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
    getNetworkCommission() {
      let resource = `conversions/${this.currentConversionId}/network-commission`;
      ApiService.query(resource).then(({ data }) => {
        this.networkCommision = data.data;
      });
    },
  },
  watch: {
    currentConversionId() {
      if (this.currentConversionId) {
        this.getNetworkCommission();
      }
    },
  },
  computed:{
      titleModal() {
          return this.$t('explanation_conversion');
      }
  }
};
</script>

<style>
</style>
