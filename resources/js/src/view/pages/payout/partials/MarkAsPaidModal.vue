<template>
  <b-modal
      body-bg-variant="light"
      size="lg"
      ref="payout_modal"
      title="Create payment"
    >
      <div class="card card-custom card-stretch mt-10">
        <div class="card-body">
          <h5 class="card-title align-items-start flex-column">
            Step 1: Check the following Commission amount and Payout details:
          </h5>
          <div class="row">
            <div class="col-lg-6">
              <h6 class="text-dark font-weight-bold">Amount:</h6>
              <b-input-group class="input-group-solid">
                <template #prepend>
                  <b-input-group-text>{{ symbolCurrency }}</b-input-group-text>
                </template>
                <b-form-input
                  id="input-amount"
                  v-model="markAsPaidForm.amount"
                  aria-describedby="input-live-help input-live-feedback"
                  trim
                  class="form-control-solid"
                  type="number"
                ></b-form-input>
              </b-input-group>
            </div>
            <div class="col-lg-6">
              <h6 class="text-dark font-weight-bold">Payment info:</h6>
              <div v-if="markAsPaidForm.payment_method">

                <div class="payment-info-container">
                  <span class="label label-lg label-light-success label-inline">{{
                    getNamePaymentMethod(markAsPaidForm.payment_method)
                  }}</span
                  ><br />
                  <span
                    v-html="
                      genPaymentInfo(
                        markAsPaidForm.payment_method,
                        markAsPaidForm.payment_info
                      )
                    "
                  >
                  </span>
                </div>
              </div>
              <span v-else class="text-danger">No payment details set</span>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-custom card-stretch mt-10">
        <div class="card-body">
          <h5 class="card-title align-items-start flex-column">
            Step 2: With the above information, complete the transaction outside
            of the app:
          </h5>
        </div>
      </div>

      <div class="card card-custom card-stretch mt-10">
        <div class="card-body">
          <h5 class="card-title align-items-start flex-column">
            Step 3: Make a note for yourself or your affiliate and confirm
            your transaction:
          </h5>
          <div class="row">
            <div class="col-lg-6 col-xs-12">
              <h6 class="text-dark font-weight-bold">Payment note (optional):</h6>
              <b-form-textarea
                aria-describedby="input-live-help input-live-feedback"
                trim
                class="form-control-solid"
                placeholder="Payout note or transaction ID (only you can see this)."
                rows="4"
                max-rows="6"
                v-model="markAsPaidForm.payment_note"
              ></b-form-textarea>
            </div>
            <div class="col-lg-6 col-xs-12">
              <h6 class="text-dark font-weight-bold">Affiliate note (optional):</h6>
              <b-form-textarea
                aria-describedby="input-live-help input-live-feedback"
                trim
                class="form-control-solid"
                placeholder="Payout note to your affiliates, you may in clude pay out info or transaction details (your affiliates can see this)."
                rows="4"
                max-rows="6"
                v-model="markAsPaidForm.affiliate_note"
              ></b-form-textarea>
            </div>
          </div>
        </div>
      </div>
         <div class="card card-custom card-stretch mt-10">
        <div class="card-body">
            <div class="checkbox-list">
            <label class="checkbox">
              <input type="checkbox" v-model="confirm"/>
              <span></span>
              <div class="text-muted">I guarantee that payout will be transferred outside of this app (on the affiliate account, the commission condition will be set as paid)</div>
            </label>
          </div>
        </div>
        </div>
      <template #modal-footer>
        <div class="w-100">
          <LoadingSubmitButton class="float-right" @click="submitCreatePayment" :loading="loadingCreatePaymentBtn"  :disabled=" (isConfirm == false)">Create Payment</LoadingSubmitButton>
        </div>
      </template>
    </b-modal>
</template>

<script>
import ApiService from "@/core/services/api.service";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import { mapState } from "vuex";
import { BFormInput,BFormSelect,BInputGroup,BInputGroupText,BFormTextarea } from 'bootstrap-vue'
export default {
    components: { LoadingSubmitButton,BFormInput,BFormSelect,BInputGroup,BInputGroupText,BFormTextarea },

    props:['markAsPaidForm'],

    data(){
        return {
            confirm: false,
            loadingCreatePaymentBtn:false,
        }
    },
    computed: {
        ...mapState({
            paymentMethodAvailable: (state) => state.layout.paymentMethodAvailable,
        }),
        isConfirm(){
      return (this.confirm == true) ? true : false;
    }
    },
    methods:{
        submitCreatePayment() {
            if(this.isConfirm == false){
                return
                }
            else{
            this.loadingCreatePaymentBtn = true;
            ApiService.post("/payouts/single-payout", this.markAsPaidForm)
            .then(
                ({ data }) => {
                    // this.payments.data.splice(this.currentIndex, 1);
                    this.$refs['payout_modal'].hide();
                    this.$toast.success('Payment created',{position:'bottom'});
                    this.$emit('createSuccess');
                }
            )
            .catch(({ response }) => {
                this.$toast.error(response.data.message,{position:'bottom',duration: 6000});
            })
            .finally(() => {
                this.loadingCreatePaymentBtn = false;
            });
            }
        },
        genPaymentInfo(methodKey, infos) {
            let html = "";
            if (this.paymentMethodAvailable.length > 0) {
                let currentMethod = this.paymentMethodAvailable.find((method) => {
                return method.key == methodKey;
                });
                if (currentMethod) {
                let fields = currentMethod.fields;
                html += '<table class="table-payment-info mt-2">';
                for (var i = 0; i < fields.length; i++) {
                    html += '<tr><td>'+
                    '<span class="text-muted">' +
                    fields[i].label +
                    ":" + '</span></td><td><span>'+
                    infos[fields[i].model] +
                    "</span></td>";

                }
                html += "</table>";
                }
            }

            return html;
        },
        getNamePaymentMethod(key) {
            let method = this.paymentMethodAvailable.find((method) => {
                return method.key == key;
                })
            if (this.paymentMethodAvailable.length > 0 && !!method) {
                return method.name;
            }
            else{
                return "";
            }
        },
    }
}
</script>

<style scoped>
.not_confirm ~ span{
  border: 1px solid red !important;
}
</style>
