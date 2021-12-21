<template>
  <div class="card card-custom gutter-b">
    <!--begin::Header-->
    <alert-note :type="'alert-default'">
      Special commission which is applied on specific products or collections. Other products are still based on default commission of the program.
       <br/>
         Learn more about
        <a class="font-weight-bold" target="_blank" href="https://docs.bixgrow.com/manage/program/product-commission">Product Commission.</a>
    </alert-note>
    <div class="card-header border-0 py-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label font-weight-bolder text-dark">
          List Product
        </span>
      </h3>

    </div>

    <div class=" mb-5 d-flex justify-content-between" id="kt_datatable_group_action_form" style="">
      <div class="d-flex align-items-center">
        <button @click="loadProducts()" type="button" class="btn btn-outline-secondary mr-2"><i class="icon-md fas fa-redo-alt"></i> Refresh table</button>
      </div>
      <div class="d-flex align-items-center" v-if="lengthSelected > 0">
        <button
        class="btn font-weight-bolder btn-light-danger mr-2"
        type="button"
        @click="bulkDelete"
        >
        Delete selected ({{ lengthSelected }})
        </button>
    </div>
        <div class="card-toolbar">
        <button
          v-b-modal.new_product_modal
          class="btn font-weight-bolder font-size-sm btn-primary btn-sm"
        >
          <span class="svg-icon svg-icon-md">
            <inline-svg src="media/svg/icons/Code/Plus.svg" />
        </span
          >New Product Commission
        </button>
        <b-modal
          size="lg"
          hide-footer
          id="new_product_modal"
          title="Add New Product Commission"
          ref="product_commission"
        >
          <CreateProductCommissionForm @createSuccess="loadProducts"></CreateProductCommissionForm>
        </b-modal>
        <b-modal
          size="lg"
          hide-footer
          ref="edit_product_modal"
          :title="edit_modal_title"
        >
          <EditForm
            :edit-item="edit_item"
            @editSuccess="updateRowAfterEdit"
          ></EditForm>
        </b-modal>
      </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-0">
      <div class="row align-items-center filter-container">
        <div class="col-lg-9 col-xl-8">
          <div class="row align-items-center"></div>
        </div>
      </div>
      <!--begin::Table-->
      <div class="table-responsive">
        <Datatable :loading="loadingCommissionTable">
          <thead>
            <tr class="text-left">
              <th class="pl-5" style="width: 20px">
                <label class="checkbox checkbox-lg checkbox-single">
                  <input
                    type="checkbox"
                    v-model="allSelected"
                    @click="selectAll"
                  />
                  <span></span>
                </label>
              </th>
              <th class="pr-0" style="min-width: 150px">
                <span
                  @click="changeSort('created_at')"
                  v-bind:class="[sortField == 'created_at' ? 'sort-field' : '']"
                >
                  Date
                  <i
                    v-if="sortDirection == 'asc' && sortField == 'created_at'"
                    class="fas fa-arrow-up"
                  ></i>
                  <i
                    v-if="sortDirection == 'desc' && sortField == 'created_at'"
                    class="fas fa-arrow-down"
                  ></i>
                </span>
              </th>

              <th style="min-width: 150px">
                <span> Title </span>
              </th>
              <th style="min-width: 150px">Commission</th>
              <th style="min-width: 150px">Collection</th>
              <th class="pr-5 text-right" style="min-width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="products.data && products.data.length > 0">
              <tr v-for="(item, i) in this.products.data" :key="i">
                <td class="pl-5">
                  <label class="checkbox checkbox-lg checkbox-single">
                    <input
                      type="checkbox"
                      v-model="productIds"
                      :value="item.id"
                    />
                    <span></span>
                  </label>
                </td>
                <td>
                  <span
                    href="#"
                    class="text-dark-75 d-block font-size-lg"
                    v-html="formatDate(item.timestamp)"
                  >
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="symbol symbol-50 symbol-light mr-4">
                      <span class="symbol-label">
                        <img v-if="item.image_url" :src="item.image_url" class="h-100 align-self-end" alt="">
                        <span v-else class="svg-icon svg-icon-default svg-icon-3x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"/>
                            </g>
                        </svg>
                        </span>
                      </span>
                    </div>
                    <div>
                      <a :href="getLinkProduct(item)" target="_blank" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ item.product_title + "( " + item.variant_title + " )" }}</a>
                    </div>
                  </div>


                </td>
                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ genTextCommissionType(item.commission_type) }}</span
                  >
                  <span
                    class="label label-lg label-inline font-weight-bold py-4 label-light-success"
                    >{{
                      formatCommissionAmount(
                        item.commission_type,
                        item.commission_amount,
                        format
                      )
                    }}</span
                  >
                </td>
                <td>
                  <span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{ item.collection?item.collection.title:'' }}</span
                  >
                </td>

                <td class="pr-0 text-right">


                <button

                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                @click="edit(item)"
                v-b-tooltip.hover
                title="Edit"
              >
               <span class="svg-icon svg-icon-md svg-icon-primary">
                  <inline-svg src="media/svg/icons/Communication/Write.svg" />
                </span>
              </button>
               <button

                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                @click="deleteProduct(item.id)"
                v-b-tooltip.hover
                title="Delete"
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
      </div>
      <!--end::Table-->
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex flex-wrap py-2 mr-3">
          <pagination
            :data="products"
            @pagination-change-page="loadProducts"
            :limit="3"
          >
          <span slot="prev-nav">
              <i class="ki ki-bold-arrow-back icon-xs"></i>
          </span>
            <span slot="next-nav">
                <i class="ki ki-bold-arrow-next icon-xs"></i>
            </span>
          </pagination>
        </div>
        <div class="d-flex align-items-center py-3">
          <select
            v-model="paginate"
            class="form-control form-control-sm font-weight-bold mr-4 border-0 bg-light"
            style="width: 75px"
          >
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="text-muted"
            >Showing {{ products.from }} - {{ products.to }} of
            {{ products.total }}</span
          >
        </div>
      </div>
    </div>
    <!--end::Body-->
    <!--end::Advance Table Widget 2-->
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import pagination from "laravel-vue-pagination";
import moment from "moment";
import swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import CreateProductCommissionForm from "@/view/pages/group/partials/CreateProductCommissionForm.vue";
import EditForm from "@/view/pages/group/partials/EditProductCommissionForm.vue";
import Datatable from "@/view/content/Datatable.vue";
import { BButton } from 'bootstrap-vue'
window.Swal = swal;
export default {
  data() {
    return {
      loadingCommissionTable: false,
      allSelected: false,
      productIds: [],
      products: {},
      paginate: 10,
      sortDirection: "desc",
      sortField: "created_at",
      edit_item: {},
      edit_modal_title: "",
    };
  },
  components: { pagination, CreateProductCommissionForm, EditForm, Datatable,BButton },
  computed: {
    id() {
      return this.$route.params.id;
    },
    lengthSelected() {
      return this.productIds.length;
    },
    shopifyDomain() {
      return this.$store.getters.shopShopifyDomain;
    }
  },
  methods: {
    commissionTypeText(type) {
      if (type == 1) {
        return "Percent";
      } else {
        return "Flat rate";
      }
    },
    selectAll: function () {
      this.allSelected = !this.allSelected;
      this.productIds = [];
      if (this.allSelected) {
        for (var key in this.products.data) {
          this.productIds.push(this.products.data[key].id);
        }
      }
    },
    loadProducts(page = 1) {
      this.$refs["product_commission"].hide();
      this.loadingCommissionTable = true;
      let resource =
        "product-commission" +
        "?group_id=" +
        this.id +
        "&page=" +
        page +
        "&paginate=" +
        this.paginate +
        "&sort_direction=" +
        this.sortDirection +
        "&sort_field=" +
        this.sortField;

      ApiService.query(resource)
        .then(({ data }) => {
          this.products = data.data;

        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        })
        .finally(() => {
          this.loadingCommissionTable = false;
        });
    },
    formatDate(timestamp) {
      let date = moment.unix(timestamp);
      return (
        "<span>" +
        date.fromNow() +
        "<br><small class='text-muted'>" +
        date.format("lll") +
        "</small></span>"
      );
    },
    deleteProduct(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          ApiService.delete("product-commission/" + id)
            .then(({ data }) => {
              var foundIndex = this.products.data.findIndex((x) => x.id == id);
              if (foundIndex > -1) {
                this.products.data.splice(foundIndex, 1);
              }
            })
            .catch(({ response }) => {
              context.commit(SET_ERROR, response.data.errors);
            });
        }
      });
    },
    bulkDelete() {
      ApiService.post("product-commission/bulk-delete", {ids:this.productIds})
        .then(({ data }) => {
          for (var i = 0; i < this.productIds.length; i++) {
            let foundIndex = this.products.data.findIndex((x) => x.id == this.productIds[i]);
            if (foundIndex > -1) {
              this.products.data.splice(foundIndex, 1);
            }
          }
          this.productIds = [];
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    },
    edit(item) {
      this.edit_item = {
        id: item.id,
        commission_type: item.commission_type,
        commission_amount: item.commission_amount,
      };
      this.edit_modal_title =
        item.product_title + "( " + item.variant_title + " )";
      this.$refs["edit_product_modal"].show();
    },

    updateRowAfterEdit(product) {
      var foundIndex = this.products.data.findIndex((x) => x.id == product.id);
      if (foundIndex > -1) {
        this.products.data[foundIndex].commission_type =
          product.commission_type;
        this.products.data[foundIndex].commission_amount =
          product.commission_amount;
      }
      this.$refs["edit_product_modal"].hide();
    },

    getLinkProduct(product){
      return 'https://'+this.shopifyDomain+'/admin/products/'+product.product_id+'/variants/'+product.variant_id;
    },
    changeSort(field) {
      if (this.sortField == field) {
        this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";
      } else {
        this.sortDirection = "desc";
        this.sortField = field;
      }
      this.loadProducts();
    },
  },

  mounted() {
    this.loadProducts();
  },

  watch: {
    paginate: function () {
      this.loadProducts();
    },
  },
};
</script>

