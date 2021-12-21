<template>
<div>
  <!--begin::Card-->
  <div class="card card-custom">
    <!--begin::Header-->
    <div class="card-header py-3 d-flex flex-row">
      <div class="card-title align-items-start flex-column">
        <h3 class="card-label font-weight-bolder text-bixgrow">
          Supported payout methods
        </h3>
        <span class="text-muted font-weight-bold font-size-sm mt-3"
          >Select the payment method for all programs by default. To specify payment methods for each program, you can go to program settings and edit. Your affiliates will then have an option to enter payment information for the methods you select.</span
        >
      </div>
    </div>
    <!--end::Header-->
    <div class="card-toolbar mt-6">
        <button
          type="reset"
          class="btn btn-primary btn-sm font-weight-bolder mr-10 float-right"
          @click="openFormModal(null)"

        >
          <span class="svg-icon svg-icon-md">
            <inline-svg src="media/svg/icons/Code/Plus.svg" />
          </span>
          New custom payment method
        </button>
        <CreatePaymentForm :key="componentKey" @close="forceRerender" @updateSuccess="updateListMethod" @createSuccess="appendListMethod" :paymentMethod="paymentMethod" ref="formPaymentMethod"></CreatePaymentForm>
      </div>
    <!--begin::Form-->
      <!--begin::Body-->
    <div class="card-body">

      <Datatable :loading="loadingPaymentMethod">
          <thead>
            <tr class="text-left">
              <th class="pr-0" style="width: 25%">Name</th>
              <!-- <th style="min-width: 200px"></th> -->
              <th style="min-width: 25%">Type</th>
              <th style="min-width: 25%">Status</th>
              <th class="pr-5 text-right">actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="paymentMethods.length > 0">
              <tr v-for="(p, i) in paymentMethods" :p="p" :key="i">
                <td><span
                    class="text-dark-75 font-weight-bolder d-block font-size-lg"
                    >{{p.name}}</span
                  >
                    <span v-if="p.key == 'discount_coupon'" class="text-muted">Allow <a href="javascript:;" @click="openModalSelfCoupon">self-generating coupon</a></span>
                  </td>
                <td>
                  <span v-if="p.type == 0" class="label label-lg label-light-default label-inline">Default</span>
                  <span v-else class="label label-lg label-light-success label-inline">Custom</span>
                </td>
                <td>
                  <b-form-checkbox
                  v-model="p.status"
                  switch
                  size="lg"
                  value=1
                  unchecked-value=0
                  @change="toggleStatus(p.id)"
                  ></b-form-checkbox>
              </td>
                <td class="d-flex justify-content-end">

                  <a @click="openFormModal(p)" v-b-tooltip.hover title="Edit" v-if="p.type == 1" href="javascript:;" class="btn btn-sm btn-light-primary btn-hover-primary btn-icon mr-2" data-toggle="dropdown">
                    <i class="text-primary fas fa-pencil-alt"></i>
                  </a>
                  <a @click="deleteMethod(p.id)" v-b-tooltip.hover title="Delete" v-if="p.type == 1" href="javascript:;" class="btn btn-sm btn-light-danger btn-hover-danger btn-icon mr-2" data-toggle="dropdown">
                    <i class="text-danger fas fa-trash-alt"></i>
                  </a>
                  <button v-b-tooltip.hover title="View" @click="openDetailModal(p)"  class="btn btn-light btn-sm  btn-icon mr-2" >
                    <i class="fas fa-search"></i>
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
      <!--end::Body-->
    <!--end::Form-->
    <DetailPaymentMethodModal :method="currentMethodShow"  ref="detailPaymentMethod"></DetailPaymentMethodModal>
    <SelfGeneratingCoupon :shopSettings="shopSettings" ref="selfGeneratingCoupon"></SelfGeneratingCoupon>
  </div>
  <div class="d-flex tip-alert mt-5">
      <div class="col-md-2"></div>
    <div class="col-md-8">
     <Tips>
        Tip: Add as many payment options as you can.
     </Tips>
      </div>
    <div class="col-md-2"></div>
  </div>
</div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import CreatePaymentForm from "@/view/pages/setting/partials/CreatePaymentForm.vue";
import DetailPaymentMethodModal from "@/view/pages/setting/partials/DetailPaymentMethodModal.vue";
import swal from "sweetalert2";
import Datatable from "@/view/content/Datatable.vue";
window.Swal = swal;
import { BFormCheckbox } from 'bootstrap-vue'
import SelfGeneratingCoupon from "@/view/pages/setting/partials/SelfGeneratingCoupon.vue";
import { mapGetters, mapActions } from 'vuex';
export default {
  components: {
    CreatePaymentForm,
    DetailPaymentMethodModal,
    Datatable,BFormCheckbox,SelfGeneratingCoupon
  },
  data() {

    return {
      componentKey: 0,
      loadingPaymentMethod: false,
      paymentMethods:[],
      paymentMethod:null,
      currentMethodShow:{},
    };
  },
  computed:{
    ...mapGetters(['shopSettings'])
  },
  methods:{
    ...mapActions(["updatePaymentMethodAvailable"]),
    loadPaymentMethod() {
    this.loadingPaymentMethod = true;
    ApiService.query('settings/payment-methods')
    .then(({data}) => {
        this.paymentMethods = data.data;
        this.loadingPaymentMethod = false;
    })
    .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
    });
      },
    toggleStatus(id) {
      ApiService.post('settings/payment-methods/'+id+'/status-toggle')
      .then(({data}) => {
          this.updatePaymentMethodAvailable(this.paymentMethods);
      })
      .catch(({ response }) => {
            context.commit(SET_ERROR, response.data.errors);
      });
    },
    appendListMethod(newPaymentMethod) {
      this.paymentMethods.push(newPaymentMethod);
      this.updatePaymentMethodAvailable(this.paymentMethods);
    },
    updateListMethod(methodUpdated) {
      console.log(methodUpdated);
      var foundIndex = this.paymentMethods.findIndex((x) => x.id == methodUpdated.id);
      console.log(foundIndex);
      if (foundIndex > -1) {
        this.paymentMethods[foundIndex].name = methodUpdated.name;
        this.paymentMethods[foundIndex].fields = methodUpdated.fields;
      }
      this.updatePaymentMethodAvailable(this.paymentMethods);
    },
    deleteMethod(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert the paid payments using this method!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          ApiService.delete("settings/payment-methods/" + id)
            .then(({ data }) => {
              var foundIndex = this.paymentMethods.findIndex((x) => x.id == id);
              if (foundIndex > -1) {
                this.paymentMethods.splice(foundIndex, 1);
                this.updatePaymentMethodAvailable(this.paymentMethods);
              }
            })
            .catch(({ response }) => {
              context.commit(SET_ERROR, response.data.errors);
            });
        }
      });
    },

    openFormModal(method=null){
      this.paymentMethod = method;
      this.$refs['formPaymentMethod'].showModal();
    },

    openDetailModal(paymentMethod) {
      this.currentMethodShow = paymentMethod;

      this.$refs['detailPaymentMethod'].$refs['detailPaymentMethodModal'].show();
    },
    forceRerender() {
      this.componentKey += 1;
    },
    openModalSelfCoupon()
    {
      this.$refs['selfGeneratingCoupon'].$refs['self_generating_coupon'].show();
    }
  },

  mounted(){
    this.loadPaymentMethod();
  }
}
</script>

<style scoped>
tbody > tr:first-child td{
    border-top: none !important;
}
  .custom-switch{
    bottom:8px !important;
  }
  /* #new_custom_payment_method_modal .modal-content .modal-body{
    max-height: 700px;
    overflow: auto;
  } */
    @media only screen and (max-width: 768px) {
    .tip-alert{
        display: none !important;
    }
    }
</style>
