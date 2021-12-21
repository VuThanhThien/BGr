<template>
<div class="row" style=" margin-top: 3rem;">
<div class="col-md-4 offset-md-4 ">
   <div class="card card-custom">
 <div class="card-header d-flex flex-row">
  <div class="card-title align-items-start flex-column">
   <h3 class="font-size-h1 mt-2">
  {{$t('reset_pass')}}
   </h3>
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
          <div class="d-flex flex-column flex-grow-1 mr-2" v-html="errorMsg">

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
        <div class="d-flex flex-column flex-grow-1 mr-2" >
            <ul href="#" class="text-success font-weight-bold font-size-h6 mt-2">{{this.successMsg}}</ul>
        </div>
        </div>
        <template v-if="!resetSuccess">
            <b-form-group
        id="example-input-group-1"
        :label="getTextLocale('email')"
        label-for="example-input-1"
        >
        <b-form-input
            class="form-control form-control-solid h-auto py-5 px-6"
            id="example-input-1"
            name="example-input-1"
            v-model="$v.email.$model"
        ></b-form-input>
        </b-form-group>
        <b-form-group
        id="example-input-group-2"
        :label="getTextLocale('new_pass')"
        label-for="example-input-2"
        >
        <b-form-input :type="status?'text':'password'"
            class="form-control form-control-solid h-auto py-5 px-6"
            id="example-input-2"
            name="example-input-2"
            v-model="$v.password.$model"
        ></b-form-input>
        </b-form-group>
        <b-form-group
        id="example-input-group-3"
        :label="getTextLocale('cf_new_pass')"
        label-for="example-input-3"
        >
        <b-form-input :type="status?'text':'password'"
            class="form-control form-control-solid h-auto py-5 px-6"
            id="example-input-3"
            name="example-input-3"
            v-model="$v.password_confirmation.$model"
        ></b-form-input>
        </b-form-group>
    <b-form-checkbox
    id="example-input-4"
    v-model="status"
    name="checkbox-1"
    value="1"
    unchecked-value=""
    >
    {{$t('show_pass')}}
    </b-form-checkbox>
        </template>
</div>
    <div class="card-footer d-flex justify-content-end">
        <a v-if="!resetSuccess" @click="onSubmit" class="ml-3 btn btn-primary font-weight-bold"  ref="kt_login_signin_submit">{{$t('reset_pass')}}</a>
        <router-link v-else :to="{name:'login'}" class="ml-3 btn btn-primary font-weight-bold">{{$t('SIGN_IN')}}</router-link>
</div>
</div>
</div>
</div>


</template>
<script>
import { validationMixin } from "vuelidate";
import { email,minLength, required,sameAs } from "vuelidate/lib/validators";
import { BFormInput,BFormGroup,BFormCheckbox} from 'bootstrap-vue'
import ApiService from 'aff/core/services/api.service';
export default {
    computed:{
        token(){
            return this.$route.params.token
        }
    },
    components:{
    BFormInput,BFormGroup,BFormCheckbox
  },
  mixins: [validationMixin],
  data() {
    return {
      successMsg:'',
      errorMsg :'',
        email: '',
        password:'',
        status:'',
        password_confirmation : '',
        resetSuccess:false
    };
  },
  validations: {
      email: {
        required,
        email
    },
    password:{
        required,
         minLength: minLength(8)
    },
          password_confirmation: {
        required,
        sameAsPassword: sameAs('password')
      }
  },
  methods: {
    onSubmit() {
      this.successMsg='';
        this.errorMsg='';
        let errorMessage=[];
      if (!this.$v.email.required) {
          errorMessage.push('Email is required!');
      }
        if (!this.$v.password.required) {
           errorMessage.push('Password is required!');
      }
                  if(!this.$v.password.minLength)
      {
          errorMessage.push('Password must have at least 8 letters!');
      }
            if(!this.$v.password_confirmation.sameAsPassword)
      {
          errorMessage.push('Passwords must be identical!');
      }
      if(!this.$v.email.email)
      {
          errorMessage.push('The email must be a valid email address!');
      }
      let errorLength=errorMessage.length;
      let htmlError='';
    if(errorLength>0)
    {
          htmlError=` <ul class="text-danger font-weight-bold font-size-h6 mt-2">`;
        for(let i=0;i<errorLength;i++)
        {

            htmlError+=`<li >${errorMessage[i]}</li>`;
        }
            htmlError+=`</ul>`;
    }
    if(htmlError)
    {
          this.errorMsg=htmlError;
          return;
    }
      // set spinner to submit button
      const submitButton = this.$refs["kt_login_signin_submit"];
      submitButton.classList.add("spinner", "spinner-light", "spinner-right");

      // dummy delay
      // send login request
      let resource=`/password/reset_password`;
      ApiService.post(resource,{
          email:this.email,
          token:this.token,
          password:this.password,
          password_confirmation:this.password_confirmation
          })
      .then(({data})=>{
        if(data.status)
        {
         this.errorMsg='';
         this.successMsg=data.msg;
         this.resetSuccess=true;
        }
        else{
              this.errorMsg='';
              this.errorMsg=` <ul class="text-danger font-weight-bold font-size-h6 mt-2">
               <li >${ data.msg}</li> </ul>`;
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
