<template>
  <div class="card card-custom gutter-b">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
      <div class="card-title">
        <h3 class="card-label">{{ $t('store_credit')}}</h3>
      </div>
    </div>
    <div class="card-body">
      <div class="row justify-content-center" v-if="allowSelfGeneratingCoupon">
        <div class="col-md-6 mb-4">
          <div class="card card-stretch">
            <div class="card-body">
              <h4 style="font-size: 1.2rem" class="mb-4">{{ $t('store_credit')}}</h4>
              <!-- <h4 style="font-size: 1.2rem" class="mb-4">Use Your Credit</h4>
              <div class="font-size-h2 m-2 text-dark font-weight-bolder">
                {{ formatMoney(123, format) }}
              </div>
              <div class="row">
                <div class="col-xl-6">
                  <form action="">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{
                            symbolCurrency
                          }}</span>
                        </div>
                        <input
                          type="number"
                          required
                          class="form-control"
                          style="height: auto"
                          placeholder="Spend"
                        />
                      </div>
                      <span class="form-text text-muted"
                        >Minimum amount: {{ formatMoney(123, format) }}, Maximum
                        amount: {{ formatMoney(123, format) }}</span
                      >
                    </div>
                    <button
                      v-if="credit_walets.options.allow_self_generating_coupon"
                      class="btn btn-primary btn-sm"
                      @click="getCoupon(p)"
                      type="button"
                    >
                      <b-spinner v-if="loading" small></b-spinner>
                      <span v-if="!loading"> Get coupon</span>
                    </button>
                  </form>
                </div>
                <div class="col-xl-6">
                  <p v-if="credit_walets.options.allow_self_generating_coupon">
                    Get discount code worth
                    <span> {{ formatMoney(123, format) }}</span>
                  </p>
                </div>
              </div> -->
              <div class="text-center">
                <div>{{ $t('total_blance')}}</div>
                <div class="display-4">{{formatMoney(total_amount_walet,format)}}</div>
                <div class="row mb-2">
                  <div class="col text-center">{{ $t('minimum')}}: {{formatMoney(minAmount,format)}}</div>
                  <div class="col text-center">{{ $t('maximum')}}: {{formatMoney(maxAmount,format)}}</div>
                </div>
                <form @submit.stop.prevent="submit">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">{{
                          symbolCurrency
                        }}</span>
                      </div>
                      <input
                        :min="minAmount"
                        type="number"
                        required
                        v-model="discountAmount"
                        class="form-control"
                        style="height: auto"
                        :max="maxAmount"
                        step="any"
                      />
                    </div>
                  </div>
                  <div>{{$t('get_coupon_code_worth')}}</div>
                  <p class="display-4">{{formatMoney(conversionFactorAmount,format)}}</p>
                  <button
                    :disabled="(minAmount>maxAmount || total_amount_walet== 0 || loading ) "
                    class="btn btn-primary btn-sm"
                    type="submit"
                  >
                    <b-spinner v-if="loading" small></b-spinner>
                    <span> {{$t('get_coupon')}}</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-custom gutter-b border-0">
        <!--begin::Header-->

        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-0">
          <h4 style="font-size: 1.2rem" class="mt-6">{{$t('generated_coupon')}}</h4>
          <!--start::Filter-->

          <div
            class="mt-5 mb-5 collapse show"
            id="kt_datatable_group_action_form"
          >
            <!-- Màn lớn  -->
            <div class="row">
              <div class="col-md-4">
                <div class="mb-2">
                  <b-form-input
                    v-model="search"
                    :placeholder="$t('type_to_search_coupon')"
                    @input="searchCoupons"
                    class="form-control"
                  >
                  </b-form-input>
                </div>
              </div>
            </div>
            <!-- màn nhỏ -->
          </div>

          <!--end::Filter-->
          <b-overlay opacity="0" :show="loadingCouponTable" rounded="sm">
            <div class="row" v-if="coupons.data.length > 0">
              <div
                class="col-md-4"
                v-for="(cou, index) in coupons.data"
                :key="index"
              >
                <div class="mb-2">
                  <div class="card card-stretch">
                    <div class="card-body">
                      <p class="bg-light p-2 rounded" v-html="$t('here_is_your_coupon_code_main',{amount:formatMoney(cou.discount_amount, format)})">
                        <!-- Use the following code at checkout to get
                        <span
                          class="text-dark-75 font-weight-bolder font-size-lg"
                          >{{ formatMoney(cou.discount_amount, format) }}</span
                        >
                        discount. -->
                      </p>
                      <div>
                        <span
                          class="
                            word-break
                            btn
                            d-block
                            text-center
                            bg-light
                            py-2
                            text-monospace
                            mt-2
                          "
                          style="
                            border: 1px dashed;
                            width: 100%;
                            letter-spacing: 2px;
                            font-size: 20px;
                            cursor: default;
                            user-select: all;
                          "
                          :ref="cou.code"
                        >
                          {{ cou.code }}
                        </span>
                      </div>
                      <span
                        @click="copyText(cou.code, $event)"
                        class="mt-4 btn btn-light font-weight-bolder w-100 py-3"
                        > {{ $t("copy") }}</span
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else style="text-align: center; color: #b5b5c3 !important">
              <i class="far fa-folder-open icon-3x"></i>
              <h6>{{$t('no_data')}}</h6>
            </div>
            <template #overlay>
              <TableLoader></TableLoader>
            </template>
          </b-overlay>
          <div
            v-if="coupons.data.length > 0"
            class="d-flex justify-content-between align-items-center flex-wrap"
          >
            <div class="d-flex flex-wrap py-2 mr-3">
              <pagination
                :data="coupons"
                @pagination-change-page="loadCoupons"
                :limit="3"
              >
              </pagination>
            </div>
            <div class="d-flex align-items-center py-3">
              <select
                v-model="paginate"
                class="
                  form-control form-control-sm
                  font-weight-bold
                  mr-4
                  border-0
                  bg-light
                "
                style="width: 75px"
              >
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <span class="text-muted"
                >{{$t('showing')}} {{ coupons.from }} - {{ coupons.to }} {{$t('of')}}
                {{ coupons.total }}</span
              >
            </div>
          </div>
        </div>
      </div>
      <!-- refresh  -->
    </div>
    <DiscountCodeDetailModal :couponCodeCurrent="couponCodeCurrent" ref="detail_discount_code_modal"></DiscountCodeDetailModal>
  </div>
</template>

<script>
import ApiService from "aff/core/services/api.service";
import pagination from "laravel-vue-pagination";
import TableLoader from "aff/view/content/TableLoader.vue";
import { BOverlay, BFormInput,BSpinner } from "bootstrap-vue";
import Datatable from "aff/view/content/Datatable.vue";
import { mapGetters } from "vuex";
import DiscountCodeDetailModal from "./DiscountCodeDetailModal.vue"
export default {
  components: {
    pagination,
    TableLoader,
    BOverlay,
    Datatable,
    BFormInput,
    BSpinner,
    DiscountCodeDetailModal
  },
  data() {
    return {
      loadingCouponTable: false,
      sortField: "created_at",
      sortDirection: "desc",
      paginate: 10,
      coupons: {
        data: [],
      },
      search: "",
      loading: false,
      total_amount_walet: 0,
      discountAmount: null,
      couponCodeCurrent:{
        coupon_code:'',
        dicount_amount: 0,
        remaining_balance: 0
      }
    };
  },

  computed: {
    ...mapGetters(["symbolCurrency","allowSelfGeneratingCoupon","autopayDiscountCode"]),
    minAmount(){
      return this.autopayDiscountCode.min_amount;
    },
    maxAmount(){
      let amount = this.total_amount_walet;
      if(this.autopayDiscountCode.max_amount && this.autopayDiscountCode.max_amount<= this.total_amount_walet)
      {
        amount = this.autopayDiscountCode.max_amount;
      }
      return amount;
    },
    conversionFactorAmount(){
      return this.autopayDiscountCode.conversion_factor * this.discountAmount;
    }
  },

  methods: {
    loadCoupons(page = 1) {
      this.loadingCouponTable = true;
      let resource = "payouts/store-credit-coupons";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };
      if (this.search) {
        params["search"] = this.search;
      }
      ApiService.query(resource, { params: params })
        .then(({ data }) => {
          this.coupons = data.data;
        })
        .finally(() => {
          this.loadingCouponTable = false;
        });
    },
    searchCoupons(event) {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.loadCoupons();
      }, 600);
    },
    changeSort(field) {
      if (field == this.sortField) {
        this.sortDirection = this.sortDirection == "desc" ? "asc" : "desc";
      } else {
        this.sortField = field;
        this.sortDirection = "desc";
      }
      this.loadCoupons();
    },
    copyText(ref, e) {
      if (e) {
        let a = e.target.textContent;
        e.target.textContent = this.$t("copied");
        setTimeout(() => {
          e.target.textContent = a;
        }, 1500);
      }
      this.selectText(this.$refs[ref][0]);
    },
    getStoreCreditWalet()
    {
      let resource = `payouts/store-credit-walet`;
      ApiService.query(resource).then(({data})=>{
        this.total_amount_walet = data.data;
      })
    },
    submit()
    {
      this.loading = true;
      let resource = `payouts/autopay/discount-code`;
      ApiService.post(resource,{
        amount: this.discountAmount 
      }).then(({data})=>{
        this.couponCodeCurrent = data.data;
        this.discountAmount = null;
        this.$refs['detail_discount_code_modal'].$refs['detail_discount_code_modal'].show();
        this.total_amount_walet = data.data.remaining_balance;
        this.loadCoupons();
      }).catch(({response})=>{
          this.$toast.error(response.data.message, {
            position: "bottom",
            duration: 4000,
          });
      }).finally(()=>{
        this.loading = false;
      })
    }
  },
  mounted() {
    this.loadCoupons();
    this.getStoreCreditWalet();
  },
  watch: {
    paginate() {
      this.loadCoupons();
    },
  },
};
</script>

<style>
</style>
<style scoped>
.card {
  box-shadow: none !important;
  border: 1px solid #ebedf3;
}
.display-4 {
    font-weight: 600 !important;
}
</style>