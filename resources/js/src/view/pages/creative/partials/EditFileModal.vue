<template>
  <b-modal ref="FileModal" size="lg" title="Edit Creative">
    <label for="input-coupon-code">Name:</label>
    <b-form-input
      id="input-coupon-code"
      placeholder="Enter name"
      v-model="$v.form.name.$model"
      :state="validateState('name')"
      trim
    ></b-form-input>

    <label for="input-coupon-code-1" class="mt-4">Description (optional)</label>
    <b-form-input
      v-model="form.description"
      placeholder="Enter description"
      id="input-coupon-code-1"
      trim
    ></b-form-input>

    <!-- <label for="input-coupon-code-3" class="mt-4"
      >File (upload maximum size 5MB)</label
    >
    <b-form-file ref="file-input"
      id="input-coupon-code-3"
      @change="previewImage"
    ></b-form-file> -->
      <label for="input-coupon-code-4" class="mt-4">Program (optional)</label>
                 <select
                v-model="form.group_id"
                class="form-control form-control-custom"
              >
                <option value="0" selected class="text-muted">
                  Select program
                </option>

                <option v-for="(o, index) in groupSelectedOptions" :value="o.value" :o="o" :key="index">
                  {{ o.text }}
                </option>
              </select>
    <label for="input-coupon-code-4" class="mt-4">Category (optional)</label>
    <SearchCategory @input="setCategorySelected" />

    <template #modal-footer>
      <div class="w-100">
        <ImageButton
          :loading="imageLoading"
          @click="updateImage"
          class="float-right"
        >
          Save
        </ImageButton>
      </div>
    </template>
  </b-modal>
</template>
<script>
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import { BModal, BFormInput, BFormGroup, BFormFile } from "bootstrap-vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import SearchCategory from "@/view/content/SearchCategory.vue";
import ApiService from "@/core/services/api.service";
import { mapGetters, mapMutations } from "vuex";
export default {
  props: ["form"],
  mixins: [validationMixin],
  data() {
    return {
      imageLoading: false,
    };
  },
  computed: {
    ...mapGetters(["groups"]),
    groupSelectedOptions() {
      let options = [];
      for (let i = 0; i < this.groups.length; i++) {
        if (this.groups[i].is_default) {
          options.push({
            text: this.groups[i].name + " (Default Program)",
            value: this.groups[i].id,
          });
        } else {
          options.push({ text: this.groups[i].name, value: this.groups[i].id });
        }
      }
      return options;
    },
  },
  components: {
    ImageButton,
    BModal,
    BFormInput,
    BFormGroup,
    BFormFile,
    SearchCategory
  },
  methods: {
    ...mapMutations(["addFile"]),
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    setCategorySelected(value) {
      this.form.categorySelected = value;
    },
    updateImage() {
      this.imageLoading = true;
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.imageLoading = false;
        return;
      }
      // if (this.image != null) {
      //     let file=this.validateSize(this.image);
      //   if (file.status) {
      //     let formData = new FormData();
      //     formData.append("image", this.image);
      //     ApiService.post("/categorys/upload-file", formData).then(
      //       ({ data }) => {
      //         this.form.image = data.data.path;
      //           this.form.extension=data.data.extension;
      //           this.form.file_name=data.data.file_name;
      //                 this.form.path_name=data.data.path_name;
      //         this.form.file_size=file.size;
      //         ApiService.put("/categorys/update-creative/"+this.form.id, this.form).then(
      //           ({ data }) => {
      //             this.imageLoading = false;
      //              this.image=null;
      //              this.$refs['FileModal'].hide();
      //               this.addFile();
      //             this.$refs['file-input'].reset();
      //           }
      //         );
      //       }
      //     );
      //   } else {
      //     this.imageLoading = false;
      //     this.$toast.error("File size exceeds 5 MiB", {
      //       position: "bottom",
      //     });
      //   }
      // } else {
      if(this.form.group_id==0)
      {
        this.form.group_id = 0;
      }
      if(this.form.categorySelected==null)
      {
        this.form.categorySelected = 0;
      }
      ApiService.put(
        "/categorys/update-creative/" + this.form.id,
        this.form
      ).then(({ data }) => {
        this.imageLoading = false;
        if(this.form.categorySelected != 0)
        {
          this.addFile();
        }
        this.$refs["FileModal"].hide();
        this.$emit('editSuccess');
        // this.addFile();
        // this.$refs["file-input"].reset();
      });
    },
  },
  validations: {
    form: {
      name: {
        required,
      },
    },
  },
};
</script>