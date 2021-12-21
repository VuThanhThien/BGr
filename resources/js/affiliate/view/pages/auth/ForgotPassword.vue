<template>
<div class="row" style=" margin-top: 7rem;">
<div class="col-md-4 offset-md-4 ">
   <div class="card card-custom">
 <div class="card-header d-flex flex-row">
  <div class="card-title align-items-start flex-column">
   <h3 class="font-size-h1 mt-2">
  {{$t('forgot_pass')}}
   </h3>
   <span class="text-muted font-weight-bold font-size-lg mt-3"
          > {{$t('enter_mail_forgot_pass')}} .</span
        >
  </div>
 </div>
 <div class="card-body">
             <div v-if="this.errorMsg" class="d-flex align-items-center bg-light-danger rounded p-5 mb-9">
          <span class="svg-icon svg-icon-danger mr-5">
              <span class="svg-icon svg-icon-lg">
                  <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <rect x="0" y="0" width="24" height="24"></rect>
                          <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                          <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                      </g>
                  </svg>
                  <!--end::Svg Icon-->
              </span>
          </span>
          <div class="d-flex flex-column flex-grow-1 mr-2">
              <ul href="#" class="text-danger font-weight-bold font-size-h6 mt-2">{{this.errorMsg}}</ul>
          </div>
        </div>
                     <div v-if="this.successMsg" class="d-flex align-items-center bg-light-success rounded p-5 mb-9">
          <span class="svg-icon svg-icon-success mr-5">
              <span class="svg-icon svg-icon-lg">
                  <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <rect x="0" y="0" width="24" height="24"></rect>
                          <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                          <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                      </g>
                  </svg>
                  <!--end::Svg Icon-->
              </span>
          </span>
          <div class="d-flex flex-column flex-grow-1 mr-2">
              <ul href="#" class="text-success font-weight-bold font-size-h6 mt-2">
                {{this.successMsg}}</ul>
          </div>
        </div>
          <b-form-input
            class="form-control form-control-solid h-auto py-5 px-6"
            id="example-input-1"
            name="example-input-1"
            v-model="$v.email.$model"
            :placeholder="getTextLocale('email')"
          ></b-form-input>
 </div>
    <div class="card-footer d-flex justify-content-end">
        <router-link :to="{name: 'login'}" class="btn btn-secondary font-weight-bold">{{$t('cancel')}}</router-link>
         <a @click="onSubmit" class="ml-3 btn btn-primary font-weight-bold"  ref="kt_login_signin_submit">{{$t('send_pass')}}</a>
 </div>
</div>
</div>
</div>


</template>
<script>
import { validationMixin } from "vuelidate";
import { email,minLength, required } from "vuelidate/lib/validators";
import { BFormInput} from 'bootstrap-vue'
import ApiService from 'aff/core/services/api.service';
export default {
    components:{
    BFormInput
  },
  mixins: [validationMixin],
  data() {
    return {
      successMsg:'',
      errorMsg :'',
        email: '',
    };
  },
  validations: {
      email: {
        required,
        email
    }
  },
  methods: {
    onSubmit() {
      this.successMsg='';
        this.errorMsg='';
      if (!this.$v.email.required) {
          this.errorMsg= this.$t('valid_email');
          return;
      }
      if(!this.$v.email.email)
      {
          this.errorMsg= this.$t('valid_email');
          return;
      }

      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      submitButton.classList.add("spinner", "spinner-light", "spinner-right");

      // dummy delay
      // send login request
      let resource=`/password/reset`;
      ApiService.post(resource,{email:this.email}).then(({data})=>{
        if(data.status)
        {
         this.errorMsg='';
        this.successMsg=data.msg;
        }
        else{
              this.errorMsg='';
              this.errorMsg=data.msg;
        }
      }).finally(()=>  submitButton.classList.remove(
        "spinner",
        "spinner-light",
        "spinner-right"
      ))
      // this.$store.dispatch(LOGIN, { email, password })
      //   .then(() => this.$router.push({ name: "dashboard" }))
      //   .catch((response) => {
      //     if(response.data && response.data.message && response.status == 403)
      //     {
      //       this.errorMsg = response.data.message;
      //       submitButton.classList.remove(
      //       "spinner",
      //       "spinner-light",
      //       "spinner-right"
      //     );
      //     this.$toast.error(response.data.message,{position:'bottom',duration: 4000});
      //     }
      //   });

    }
  }
};
</script>
