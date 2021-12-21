<template>
  <b-modal ref="ImageModal" size="lg" title="New Creative">
    <label for="input-coupon-code">Name:</label>
    <b-form-input
      id="input-coupon-code"
      placeholder="Enter name"
      v-model="$v.form.name.$model"
      :state="validateState('name')"
      trim
    ></b-form-input>

    <label for="input-coupon-code-2" class="mt-4">Video link </label>
    <b-form-input
      v-model="form.link"
      placeholder="Enter video link"
      id="input-coupon-code-2"
      trim
    ></b-form-input>
    <label for="input-coupon-code-4" class="mt-4">Program (optional)</label>
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
    <label for="input-coupon-code-4" class="mt-4">Category (optional)</label>
    <SearchCategory @input="setCategorySelected" :key="searchKey" />

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
  mixins: [validationMixin],
  data() {
    return {
      imageLoading: false,
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
  },
  methods: {
    isValidHttpUrl(string) {
      let url;

      try {
        url = new URL(string);
      } catch (_) {
        return false;
      }

      return true;
    },
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
    updateImage() {
      this.imageLoading = true;
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.imageLoading = false;
        return;
      }
      if (!this.isValidHttpUrl(this.form.link)) {
        this.imageLoading = false;
        this.$toast.error("The link video format is invalid.", {
          position: "bottom",
        });
        return;
      }
      if(this.form.categorySelected==null)
      {
          this.form.categorySelected = 0;
      }
      if(this.form.categorySelected != 0)
      {
        this.form.categorySelected =  JSON.stringify(this.form.categorySelected);
      }
      this.form.image = null;
      this.form.file_size = null;
      this.form.path_name = null;
      this.form.type = 3;
      ApiService.post("/categorys/create-creative", this.form).then(
        ({ data }) => {
          this.$emit("VideoModal");
          this.$refs["ImageModal"].hide();
        }
      ).finally(()=>{
          this.imageLoading = false;
          this.form.categorySelected = 0;
          this.form.groupSelected = 0;
          this.searchKey++;
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