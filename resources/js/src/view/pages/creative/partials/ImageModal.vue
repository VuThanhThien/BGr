<template>
  <b-modal
    ref="ImageModal"
    size="lg"
    body-bg-variant="light"
    title="New Creative"
    hide-footer
  >
    <div class="card card-custom card-stretch gutter-b">
      <b-tabs card nav-class="nav-tabs-line">
        <b-tab title="Single upload">
          <label for="input-coupon-code">Name:</label>
          <b-form-input
            id="input-coupon-code"
            placeholder="Enter name"
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            trim
          ></b-form-input>

          <label for="input-coupon-code-1" class="mt-4"
            >Description (optional)</label
          >
          <b-form-input
            v-model="form.description"
            placeholder="Enter description"
            id="input-coupon-code-1"
            trim
          ></b-form-input>

          <label for="input-coupon-code-2" class="mt-4">Link (optional)</label>
          <b-form-input
            v-model="form.link"
            placeholder="Enter link"
            id="input-coupon-code-2"
            trim
          ></b-form-input>
          <label for="input-coupon-code-3" class="mt-4"
            >Image (upload maximum size 5MB)</label
          >
          <b-form-file
            ref="file-input"
            id="input-coupon-code-3"
            accept="image/*"
            @change="previewImage"
          ></b-form-file>
          <label for="input-coupon-code-4" class="mt-4"
            >Program (optional)</label
          >
          <select
            v-model="form.groupSelected"
            class="form-control form-control-custom"
          >
            <option value="0" selected class="text-muted">
              Select program
            </option>

            <option
              v-for="(o, index) in groupSelectedOptions"
              :value="o.value"
              :o="o"
              :key="index"
            >
              {{ o.text }}
            </option>
          </select>
          <label for="input-coupon-code-4" class="mt-4"
            >Category (optional)</label
          >
          <SearchCategory :key="searchKey" @input="setCategorySelected" />
          <ImageButton
            :loading="imageLoading"
            @click="updateImage"
            class="float-right mb-6 mt-6"
          >
            Save
          </ImageButton>

          <!-- <template #modal-footer>
      <div class="w-100">
        <ImageButton
          :loading="imageLoading"
          @click="updateImage"
          class="float-right"
        >
          Save
        </ImageButton>
      </div>
      
    </template> -->
        </b-tab>
        <b-tab title="Bulk upload" lazy>
          <BulkUploadImageModal
            @saveImageSuccess="bulkUploadSuccess"
          ></BulkUploadImageModal>
        </b-tab>
      </b-tabs>
    </div>
  </b-modal>
</template>
<script>
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import {
  BModal,
  BFormInput,
  BFormGroup,
  BFormFile,
  BTabs,
  BTab,
} from "bootstrap-vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import SearchCategory from "@/view/content/SearchCategory.vue";
import ApiService from "@/core/services/api.service";
import { mapGetters, mapMutations } from "vuex";
import BulkUploadImageModal from "./BulkUploadImageModal.vue";
export default {
  mixins: [validationMixin],
  data() {
    return {
      imageLoading: false,
      image: null,
      form: {
        name: "",
        categorySelected: 0,
        groupSelected: 0,
      },
      searchKey: 0
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
    SearchCategory,
    BTabs,
    BTab,
    BulkUploadImageModal,
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    setCategorySelected(value) {
      this.form.categorySelected = value;
    },
    setGroupSelected(value) {
      this.form.groupSelected = value;
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        this.image = input.files[0];
      }
    },
    validateSize(file) {
      const fileSize = file.size / 1024 / 1024; // in MiB
      if (fileSize <= 5) {
        return { status: true, size: fileSize };
      } else {
        return { status: false, size: fileSize };
      }
    },
    updateImage() {
      this.imageLoading = true;
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.imageLoading = false;
        return;
      }
      if (this.image != null) {
        let file = this.validateSize(this.image);
        if (file.status) {
          if (this.form.categorySelected == null) {
            this.form.categorySelected = 0;
          }
          this.form.file_size = file.size;
          this.form.type = 1;
          if (this.form.categorySelected != 0) {
            this.form.categorySelected = JSON.stringify(
              this.form.categorySelected
            );
          }
          let formData = new FormData();
          formData.append("image", this.image);
          // this.form.image = data.data.path;
          // this.form.extension=data.data.extension;
          // this.form.file_name=data.data.file_name;
          // this.form.path_name=data.data.path_name;
          for (let i in this.form) {
            formData.append(i, this.form[i]);
          }
          ApiService.post("/categorys/create-creative", formData)
            .then(({ data }) => {
              this.image = null;
              this.$emit("saveImageSuccess");
              this.$refs["ImageModal"].hide();
              this.$refs["file-input"].reset();
            })
            .catch(({ response }) => {
              this.$toast
                .error(response.data.message, {
                  position: "bottom",
                })
            }).finally(() => {
                  this.imageLoading = false;
                  this.form.categorySelected = 0;
                  this.form.groupSelected = 0;
                  this.searchKey++;

                });
        } else {
          this.imageLoading = false;
          this.$toast.error("File size exceeds 5 MiB", {
            position: "bottom",
          });
        }
      } else {
        this.imageLoading = false;
        this.$toast.error("Image is required", {
          position: "bottom",
        });
      }
    },
    bulkUploadSuccess() {
      this.$emit("saveImageSuccess");
      this.$refs["ImageModal"].hide();
      this.$refs["file-input"].reset();
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