<template>
<div>
  <alert-note :type="'alert-white alert-shadow'">
    Learn more about
    <a class="font-weight-bold" href="https://docs.bixgrow.com/manage/coupons" target="_blank">coupons</a>.
  </alert-note>

  <div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 py-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label font-weight-bolder text-bixgrow">
          Coupon List
        </span>
        <span class="text-muted font-weight-bold font-size-sm mt-3">
            You can assign coupon to affiliate. When those coupons are used, the affiliate receives a commission for the customer's purchase.
            <br>
            (customers do not have to click on affiliate link)
        </span>
      </h3>

    </div>

    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-0">
      <!--start::Filter-->
      <div class="row align-items-center filter-container mb-10">
        <div class="col-lg-12 col-xl-12">
          <div class="row align-items-center">
            <div class="col-md-4 my-2 my-md-0" v-if="!filterAffiliate">
              <SearchAff @input="setAffiliateSelected" />
            </div>
            <div class="col-md-4 my-2 my-md-0">
              <b-form-input
                v-model="search"
                placeholder="Type to search Coupon"
                @input="searchCoupons"
                class="form-control-custom form-control"
              >
              </b-form-input>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 mb-5 collapse show" id="kt_datatable_group_action_form" >
          <!-- Màn lớn  -->
        <div class="md-screen">
            <div class="d-flex align-items-center justify-content-between">
                <button @click="loadCoupons()" type="button" class="btn btn-sm btn-outline-secondary mr-2"><i class="icon-md fas fa-redo-alt"></i> Refresh table</button>
                <div class="card-toolbar" v-if="!filterAffiliate">
                    <button
                    @click="openCouponModal"
                    class="btn btn-sm btn-primary font-weight-bolder font-size-sm"
                    >
                    <span class="svg-icon svg-icon-md">
                        <inline-svg src="media/svg/icons/Code/Plus.svg" />
                    </span>
                    Assign coupon to affiliate
                    </button>
                </div>
            </div>
        </div>
        <!-- màn nhỏ -->
        <div class="sm-screen">
            <div class="d-flex align-items-center justify-content-between">
                <button @click="loadCoupons()" type="button" class="btn btn-sm btn-outline-secondary mr-2"><i class="icon-md fas fa-redo-alt"></i></button>
                <div class="card-toolbar" v-if="!filterAffiliate">
                    <button
                    @click="openCouponModal"
                    class="btn btn-sm btn-success font-weight-bolder font-size-sm"
                    >
                    <span class="svg-icon svg-icon-lg svg-icon-white">
                        <inline-svg src="media/svg/icons/Code/Plus.svg" />
                    </span>
                    </button>
                </div>
            </div>
        </div>
      </div>


      <!--end::Filter-->
      <Datatable :loading="loadingCouponTable">
        <thead>
          <th class="pr-0" style="min-width: 100px">
            <span> Affiliate </span>
          </th>
          <th class="pr-0" style="min-width: 100px">
            <span> Coupon </span>
          </th>

          <th class="pr-0" style="min-width: 100px">
            <span
              @click="changeSort('created_at')"
              v-bind:class="[sortField == 'created_at' ? 'sort-field' : '']"
            >
              Created at
              <i
                v-if="sortDirection == 'asc' && sortField == 'created_at'"
                class="fas fa-arrow-up icon-nm"
              ></i>
              <i
                v-if="sortDirection == 'desc' && sortField == 'created_at'"
                class="fas fa-arrow-down icon-nm"
              ></i>
            </span>
          </th>

          <th class="pr-5 text-right" style="min-width: 100px">
            <span> Actions </span>
          </th>
        </thead>
        <tbody>
          <template v-if="coupons.data.length > 0">
            <tr v-for="(c, i) in coupons.data" :key="i">
              <td>
                <router-link
                  :to="{
                    name: 'affiliates.edit',
                    params: { id: c.affiliate.id },
                  }"
                  class="font-weight-bolder d-block"
                >
                  {{ c.affiliate.full_name }}
                </router-link>

                <span class="text-muted">{{ c.affiliate.email }}</span>
              </td>
              <td>
                <span
                  class="text-dark-75 font-weight-bolder d-block"
                  >{{ c.code }}</span
                >
              </td>
              <td>
                <span
                  href="#"
                  class="text-dark-75 d-block"
                  v-html="formatDate(c.timestamp)"
                >
                </span>
              </td>
              <td class="pr-0 text-right">
                <!-- <button
                  class="btn btn-icon btn-light btn-hover-primary btn-sm"
                  v-b-tooltip.hover
                  title="Edit"
                >
                <span class="svg-icon svg-icon-md svg-icon-primary">
                  <inline-svg src="media/svg/icons/Communication/Write.svg" />
                </span>
                </button> -->

                <button
                  class="btn btn-icon btn-light btn-hover-danger btn-sm"
                  v-b-tooltip.hover
                  title="Delete"
                  @click="deleteCoupon(c.id)"
                >
                  <span class="svg-icon svg-icon-danger svg-icon-lg">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      width="24px"
                      height="24px"
                      viewBox="0 0 24 24"
                      version="1.1"
                    >
                      <g
                        stroke="none"
                        stroke-width="1"
                        fill="none"
                        fill-rule="evenodd"
                      >
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                          d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                          fill="#000000"
                          fill-rule="nonzero"
                        ></path>
                        <path
                          d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                          fill="#000000"
                          opacity="0.3"
                        ></path>
                      </g>
                    </svg>
                  </span>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="8" style="text-align: center;color: #B5B5C3 !important;">
                <i class="far fa-folder-open icon-3x"></i>
                <h6>No Data</h6>
              </td>
            </tr>
          </template>
        </tbody>
      </Datatable>
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
            >Showing {{ coupons.from }} - {{ coupons.to }} of
            {{ coupons.total }}</span
          >
        </div>
      </div>
    </div>
    <AssignCouponModal :key="componentKey" ref="couponModalComponent" @update-coupon="updateCoupon" @close="forceRerender" :price_id="priceRuleId"></AssignCouponModal>
  </div>
    <!-- <div class="card mt-5 mb-xl-10" v-if="!filterAffiliate">

      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#kt_account_connected_accounts"
        aria-expanded="true"
        aria-controls="kt_account_connected_accounts"
      >
        <div class="card-title m-0">
          <h3 class="font-weight-bolder text-bixgrow">Automatic Coupon</h3>
        </div>
      </div>


      <div id="kt_account_connected_accounts" class="collapse show">

        <div class="card-body border-top p-5" >
          <div class="mt-1" v-if="couponcodeAutomatic">
                <div class="fs-4 fw-bolder mb-5"><span class="text font-weight-bolder">Coupon code: </span> <span class="label label-lg label-bixgrow label-inline text font-weight-bolder">{{couponcodeAutomatic}}</span></div>
                       <button
            @click="openAutomaticCouponModal"
            type="button"
            class="btn btn-secondary mr-2"
          >
            Change
          </button>
            </div>

          <button
          v-if="!couponcodeAutomatic"
            @click="openAutomaticCouponModal"
            type="button"
            class="btn btn-secondary mr-2"
          >
            Setup
          </button>
        </div>


      </div>
      <AutomaticCouponModal ref="AutomaticCouponModal" @updatePriceRule="updatePriceRule" :coupon="{couponCode:couponcodeAutomatic}"></AutomaticCouponModal>
    </div> -->
    <div class="d-flex tip-alert">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <Tips>
        Tip: To reduce manual tasks, enable  <a class="font-weight-bold" href="#/settings/coupons" >Automatic Coupons</a>.
      </Tips>
    </div>
    <div class="col-md-3"></div>
  </div>
</div>
</template>

<script>
import vSelect from "vue-select";
import AssignCouponModal from "@/view/pages/coupon/partials/AssignCouponModal.vue";
import ApiService from "@/core/services/api.service";
import swal from "sweetalert2";
import Datatable from "@/view/content/Datatable.vue";
import SearchAff from "@/view/content/SearchAffiliate.vue";
import pagination from "laravel-vue-pagination";
window.Swal = swal;
import { BFormGroup,BFormInput,BTooltip } from 'bootstrap-vue'
export default {
  components: { AssignCouponModal, vSelect, Datatable, SearchAff,pagination,BFormGroup,BFormInput,BTooltip },
  props:['filterAffiliate'],
  data() {
    return {
      componentKey: 0,
      loadingCouponTable: false,
      paginate: 10,
      affiliateOptions: [],
      affiliateFilterSelected: null,
      sortField: "created_at",
      sortDirection: "desc",
      search: "",
      priceRuleId:0,
      couponcodeAutomatic:null,
      coupons: {
        data: [],
      },
    };
  },
  methods: {
      forceRerender() {
      this.componentKey += 1;
     this.loadCoupons();
    },
    // updatePriceRule(id,code){
    //     this.priceRuleId=id;
    //     this.couponcodeAutomatic=code;
    // },
    // getPriceRule(){
    //   let resource=`/settings/get-coupon-automatic`;
    //   ApiService.query(resource).
    //   then(({data})=>{
    //     if(data.data.coupon_automatic)
    //     {
    //       this.priceRuleId=data.data.coupon_automatic.price_rule;
    //       this.couponcodeAutomatic=data.data.coupon_automatic.code;
    //     }
    //   })
    // },
    // openAutomaticCouponModal(){
    //   this.$refs["AutomaticCouponModal"].$refs["automaticCoupon"].show();
    // },
    updateCoupon(e){
      this.coupons.data.unshift(e);
    },
    openCouponModal() {
      this.$refs["couponModalComponent"].$refs["assignCouponModal"].show();
    },

    loadCoupons(page = 1) {
      this.loadingCouponTable = true;
      let resource = "coupons";
      let params = {
        page: page,
        paginate: this.paginate,
        sort_direction: this.sortDirection,
        sort_field: this.sortField,
      };

      if (this.affiliateFilterSelected) {
        params["affiliate"] = this.affiliateFilterSelected.id;
      }
      if(this.filterAffiliate){
        params["affiliate"] =this.filterAffiliate;
      }
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

    onSearchAffiliate(search, loading) {
      if (search.length) {
        loading(true);
        this.searchAffiliate(loading, search, this);
      }
    },

    searchAffiliate: _.debounce((loading, search, vm) => {
      let resource = "affiliates/search?query=" + escape(search);
      ApiService.query(resource).then(({ data }) => {
        vm.affiliateOptions = data;
        loading(false);
      });
    }),

    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      this.loadCoupons();
    },
    changeSort(field) {
      if (this.sortField == field) {
        this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";
      } else {
        this.sortDirection = "desc";
        this.sortField = field;
      }
      this.loadCoupons();
    },
    searchCoupons(event) {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.loadCoupons();
      }, 600);
    },

    deleteCoupon(id) {
        Swal.fire({
        title: "Are you sure?",
        text: "",
        input: 'checkbox',
        inputValue: 0,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        inputPlaceholder:
    'Delete this coupon on Shopify',
      }).then((result) => {
        if (result.isConfirmed) {
          ApiService.delete("coupons/" + result.value+'/'+id)
            .then(({ data }) => {
              if(data.data)
              {
                  this.coupons.data = this.coupons.data.filter((x) => x.id != id);
                   this.$toast.success('Deleted', { position: "bottom" });
              }
              else{
                this.$toast.error('This coupon does not exist in your Shopify store', { position: "bottom" });
              }
              // if (foundIndex > -1) {
              //   this.affiliates.data.splice(foundIndex, 1);
              // }
            })
            .catch(({ response }) => {
               this.$toast.error(response.data.message, { position: "bottom" });
            });
        }
      });
    },
  },

  mounted() {
    this.loadCoupons();
  },
      watch: {
        paginate: function () {
            this.loadCoupons();
        },
    },
    // created(){
    //   this.getPriceRule();
    // }
};
</script>

<style scoped>
tbody tr:first-child td{
    border-top: none !important;
}
@media only screen and (max-width: 768px) {
    .tip-alert{
        display: none !important;
    }
 }
</style>
