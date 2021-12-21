<template>
  <div>
    <b-modal
      size="xl"
      hide-footer
      ref="importAffiliateModal"
      body-bg-variant="light"
      title="Import affiliates"
      @hide="closeModalAddAffiliate"
    >
      <alert-note :type="'alert-white alert-shadow'">
        Reduce manual tasks by bulk importing hundreds of affiliates here.
      </alert-note>
       <AlertError :errorMsg="errorMsg"></AlertError>
      <div class="card card-custom card-stretch gutter-b">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <label for="input-coupon-code-4">Program</label>
              <select
                class="form-control form-control-custom"
                v-model="$v.form.groupSelected.$model"
              >
                <option
                  v-for="(o, index) in groupSelectedOptions"
                  :value="o.value"
                  :o="o"
                  :key="index"
                >
                  {{ o.text }}
                </option>
              </select>
              <label for="input-coupon-code-pass" class="mt-4">Password</label>
              <b-form-select
                class="mb-4"
                v-model="form.passwordType"
                :options="password_type_options"
                id="discount_type"
              ></b-form-select>
              <b-form-input
                v-if="form.passwordType"
                id="input-coupon-code-pass"
                aria-describedby="input-live-feedback-5"
                v-model="$v.form.password.$model"
                :state="validateState('password')"
                trim
                placeholder="Enter password"
              ></b-form-input>
              <b-form-invalid-feedback
                v-if="form.passwordType"
                id="input-live-feedback-5"
              >
                Password must have at least 6 letters!
              </b-form-invalid-feedback>
              <span
                class="text-muted font-weight-bold font-size-sm mt-1"
                >This default password will be used if password field is not included in your CSV file.</span
              > <br>
              <label for="input-coupon-code-3" class="mt-4"
                >Choose CSV file </label
              >
              <b-form-file
                ref="file-input"
                id="input-coupon-code-3"
                accept=".xlsx, .xls, .csv"
                @change="previewImage"
              ></b-form-file>

              <div class="checkbox-inline mt-4">
                <label class="checkbox checkbox-outline checkbox-success">
                  <input
                    type="checkbox"
                    v-model="form.isSendNotification"
                    value="true"
                  />
                  <span></span>
                  Send invitation email.
                </label>
              </div>
              <span class="text-muted font-weight-bold font-size-sm"
                >When selected, an invitation email with a log in link and
                password will be sent to an affiliate.</span
              >
              <div class="w-100 mt-5">
                <ImageButton
                  :loading="submitLoading1"
                  @click="saveAffiliate"
                  class="float-right"
                >
                  Import
                </ImageButton>
              </div>
            </div>
            <div class="col-md-6">

              <p>
               You can upload up to 250 affiliates at once. Upload a csv file that is properly formatted. <br>
                To make your own bulk import file, download the sample csv file. <br>
                   <a href="media/logos/import_bixGrow_demo.csv"  class="btn btn-primary btn-sm mt-2" download="">
                    Download sample csv file
                  </a>
              </p>           
              <img
                style="width: 100%"
                src="media/logos/bixGrow_affiliate_import.png"
                alt=""
              />
                            
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import {
  BFormInput,
  BFormInvalidFeedback,
  BFormFile,
  BFormSelect,
  BIconEmojiSmile,
} from "bootstrap-vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import ApiService from "@/core/services/api.service";
import { mapGetters } from "vuex";
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import AlertError from "@/view/content/AlertError.vue";
export default {
  data() {
    return {
      errorMsg:'',
      image: null,
      submitLoading1: false,
      form: {
        isSendNotification: true,
        groupSelected: "",
        password: "",
        path: "",
        passwordType: 1,
      },
      password_type_options: [
        { value: 0, text: "Auto generated password" },
        { value: 1, text: "Set default password" },
      ],
    };
  },
  components: {
    BFormInput,
    BFormInvalidFeedback,
    ImageButton,
    BFormFile,
    BFormSelect,
    AlertError
  },
  mixins: [validationMixin],
  computed: {
    ...mapGetters(["groups"]),
    groupSelectedOptions() {
      let options = [];
      for (let i = 0; i < this.groups.length; i++) {
        if (this.groups[i].is_active==1) {
          if (this.groups[i].is_default) {
            this.form.groupSelected = this.groups[i].id;
            options.push({
              text: this.groups[i].name + " (Default Program)",
              value: this.groups[i].id,
            });
          } else {
            options.push({
              text: this.groups[i].name,
              value: this.groups[i].id,
            });
          }
        }
      }
      return options;
    },
  },
  methods: {
    closeModalAddAffiliate() {
      this.$emit("addAffiliateSuccess");
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        this.image = input.files[0];
      }
    },
    saveAffiliate() {
      this.submitLoading1 = true;
      this.errorMsg='';
      this.form.isSendNotification
        ? (this.form.isSendNotification = 1)
        : (this.form.isSendNotification = 0);
      if (this.form.passwordType == 0) {
        this.form.password = "123456";
      }
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.submitLoading1 = false;
        return;
      }
      if (this.image != null) {
        let formData = new FormData();
        formData.append("image", this.image);
        ApiService.post("affiliates/upload-file-excel", formData).then(
          ({ data }) => {
            this.form.pathExcel = data.data.pathExcel;
            this.form.path = data.data.path;
            ApiService.post("affiliates/import", this.form)
              .then(({ data }) => {
                  let lengthArrAffiliateError=data.data.length;
                if (!lengthArrAffiliateError) {
                  this.form.password = "";
                  this.form.isSendNotification = true;
                   this.$v.$reset();
                  this.image = null;
                  this.$refs["file-input"].reset();
                  this.$toast.success("Imported", { position: "bottom" });
                }
                else{
                  if(lengthArrAffiliateError==1 && data.data[0].indexOf("must be included in your file.")>0)
                  {
                    this.errorMsg=data.data[0];
                  }
                  else
                  {
                    this.errorMsg=`There was an error while importing email:<b> ${data.data.join(",")} </b>.This might be caused by an improper email format 
                    (lacking first_name/last_name or email fields) or an existing email being uploaded.`;     
                  }   
                  this.form.password = "";
                  this.form.isSendNotification = true;
                   this.$v.$reset();
                  this.image = null;
                  this.$refs["file-input"].reset();   
                }
              })
              .catch(({ response }) => {
                if (response.status == 422) {
                  let arrError = Object.keys(response.data.errors);
                  if (arrError.length) {
                    this.$toast.error(response.data.errors[arrError[0]][0], {
                      position: "bottom",
                    });
                  } else {
                    this.$toast.error(response.data.message, {
                      position: "bottom",
                    });
                  }
                }
              })
              .finally(() => {
                this.submitLoading1 = false;
              });
          }
        );
      } else {
        this.submitLoading1 = false;
        this.$toast.error("File is required", {
          position: "bottom",
        });
      }
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
  },
  validations: {
    form: {
      password: {
        required,
        minLength: minLength(6),
      },
      groupSelected: {
        required,
      },
    },
  },
};
</script>
