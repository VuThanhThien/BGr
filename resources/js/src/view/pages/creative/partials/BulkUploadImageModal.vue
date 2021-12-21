<template>
  <div>
    <label for="input-coupon-code-10"
      >Images (Upload maximum 10 files, 5MB for each file)</label
    >
    <b-form-file
      ref="file-input-bulk"
      id="input-coupon-code-10"
      accept="image/*"
      multiple
      @change="previewImage"
    ></b-form-file>
    <label for="input-coupon-code-11" class="mt-4">Program (optional)</label>
    <select
      v-model="form.groupSelected"
      class="form-control form-control-custom"
    >
      <option value="0" selected class="text-muted">Select program</option>

      <option
        v-for="(o, index) in groupSelectedOptions"
        :value="o.value"
        :o="o"
        :key="index"
      >
        {{ o.text }}
      </option>
    </select>
    <label for="input-coupon-code-12" class="mt-4">Category (optional)</label>
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
  </div>
</template>
<script>
import ImageButton from "@/view/content/LoadingSubmitButton.vue";
import { BModal, BFormFile, BTabs, BTab } from "bootstrap-vue";
import { validationMixin } from "vuelidate";
import { required, minLength } from "vuelidate/lib/validators";
import SearchCategory from "@/view/content/SearchCategory.vue";
import ApiService from "@/core/services/api.service";
import { mapGetters, mapMutations } from "vuex";
export default {
  mixins: [validationMixin],
  data() {
    return {
      imageLoading: false,
      image: null,
      form: {
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
    BFormFile,
    SearchCategory,
    BTabs,
    BTab,
  },
  methods: {
    setCategorySelected(value) {
      this.form.categorySelected = value;
    },
    setGroupSelected(value) {
      this.form.groupSelected = value;
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        this.image = input.files;
      }
    },
    validateSize(file) {
      let fileLength = file.length;
      if (fileLength > 10) {
        return {
          status: false,
          message: "Please upload no more than 10 files at a time",
        };
      }
      for (let i = 0; i < fileLength; i++) {
        let fileSize = file[i].size / 1024 / 1024; // in MiB
        if (fileSize > 5) {
          return { status: false, message: "File size exceeds 5 MiB" };
        }
      }
      return { status: true, message: "Success" };
    },
    updateImage() {
      this.imageLoading = true;
      if (this.image != null) {
        let checkFile = this.validateSize(this.image);
        if (checkFile.status) {
          if (this.form.categorySelected == null) {
            this.form.categorySelected = 0;
          }
          this.form.type = 1;
          if (this.form.categorySelected != 0) {
            this.form.categorySelected = JSON.stringify(
              this.form.categorySelected
            );
          }
          let formData = new FormData();
          for (let i = 0; i < this.image.length; i++) {
            formData.append("files[" + i + "]", this.image[i]);
          }
          for (let i in this.form) {
            formData.append(i, this.form[i]);
          }
          ApiService.post("/categorys/bulk-upload", formData).then(
            ({ data }) => {
              this.image = null;
              this.$refs["file-input-bulk"].reset();
              this.$emit("saveImageSuccess");
            }
          ).catch(({ response }) => {
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
          this.$toast.error(checkFile.message, {
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