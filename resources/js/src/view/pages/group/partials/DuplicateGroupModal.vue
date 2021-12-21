<template>
  <div>
    <b-modal
      size="lg"
      ref="duplicateGroupModal"
      :title="'Duplicate program ' + group.name"
      body-bg-variant="light"
    >
      <alert-note :type="'alert-white alert-shadow'">
        This new program will automatically duplicate the general settings. If you want to also duplicate other options, please tick the boxes below.
      </alert-note>
      <div class="card card-custom card-stretch gutter-b">
        <div class="card-body">
      <div class="row">
        <div class="col-xl-1"></div>
        <div class="col-xl-10">
          <div class="my-5">
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Program Name :"
              label-for="name"
            >
              <b-form-input v-model="groupName"></b-form-input>
            </b-form-group>

            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Options :"
              label-for="checkbox-group-1"
              v-slot="{ ariaDescribedby }"
            >
              <b-form-checkbox
                v-for="option in options"
                v-model="selected"
                :key="option.value"
                :value="option.value"
                :aria-describedby="ariaDescribedby"
                name="flavour-3a"
                size="lg"
              >
                {{ option.text }}
              </b-form-checkbox>
            </b-form-group>
          </div>
        </div>
        <div class="col-xl-1"></div>
      </div>
        </div>
      </div>
      <template #modal-footer>
        <LoadingSubmitButton
          :loading="submitLoading"
          @click="submitDuplicate"
          class="float-right"
        >
          Create New Program
        </LoadingSubmitButton>
      </template>
    </b-modal>
  </div>
</template>

<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BInputGroup,
  BSpinner,
  BButton,
  BFormCheckboxGroup,
  BFormCheckbox,
} from "bootstrap-vue";
export default {
  components: {
    LoadingSubmitButton,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    BSpinner,
    BButton,
    BFormCheckboxGroup,
    BFormCheckbox,
  },
  props: ["group"],
  data() {
    return {
      submitLoading: false,
      selected: [],
      groupName: "",
      options: [
        { text: "Product Commission", value: "product_commission" },
        { text: "Multi-level Marketing", value: "multilevel_marketing" },
        { text: "Affiliate Registration", value: "affiliate_registration" },
      ],
    };
  },
  methods: {
    submitDuplicate() {
      this.submitLoading = true;
      let resource = `/groups/duplicate`;
      let data = {
        options: this.selected,
        origin_group: this.group.id,
        name: this.groupName,
      };
      ApiService.post(resource, data).then(({ data }) => {
        this.$store.commit("PREPEND_ITEM_TO_GROUPS", data.data);
        this.submitLoading = false;
        this.$toast.success("Program created", { position: "bottom" });
        this.$refs["duplicateGroupModal"].hide();
      });
    },
  },
  watch: {
    group: function (newVal, oldVal) {
      // watch it
      this.groupName = newVal.name + " (copy)";
    },
  },
};
</script>
<style scoped>
.table-content tr td {
  padding: 10px;
}
</style>
