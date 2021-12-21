<template>
  <div>
    <alert-note :type="'alert-white alert-shadow'">
      This feature allows creating tiers with a condition that affiliates must
      achieve in order to level up from one program to another. Remember to
      create a program for each Affiliate Tier you are going to have.
      <br />
      Learn more about
      <a
        class="font-weight-bold"
        target="_blank"
        href="https://docs.bixgrow.com/manage/affiliate-tiers"
        >affiliate tiers.</a
      >
    </alert-note>
    <div class="card card-custom gutter-b">
      <!--begin::Header-->
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Affiliate Tiers
          </span>
          <span class="text-muted font-weight-bold font-size-sm mt-3"> </span>
        </h3>
      </div>

      <!--end::Header-->
      <!--begin::Body-->

      <div class="card-body">
        <b-overlay opacity="0" :show="loadingAffiliateTier" rounded="sm">
          <form @submit.stop.prevent="submit">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="font-weight-bolder">Enable: </label>
                  <b-form-checkbox
                    v-model="form.status"
                    id="enable_mlm"
                    switch
                    size="lg"
                    value="1"
                    unchecked-value="0"
                  ></b-form-checkbox>
                  <span
                    class="form-text text-muted mt-5"
                    style="font-size: 0.9rem"
                    >When applied, the affiliate will get automatically leveled
                    up from one program to another once their performance meets
                    the condition level.
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bolder">Number of levels: </label>
                  <b-form-select
                    id="select-number-level"
                    v-model="$v.form.level_number.$model"
                    :options="number_level_options"
                    required
                    @change="selectNumberLevel($v.form.level_number.$model)"
                    :state="validateState('level_number')"
                  ></b-form-select>
                  <span
                    class="form-text text-muted "
                    style="font-size: 0.9rem"
                    >The maximum number of levels is the number of your programs.
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bolder">Level condition: </label>
                  <b-form-select
                    v-model="form.level_condition"
                    :options="[
                      { value: 0, text: 'Total revenue' },
                      { value: 1, text: 'Total earned commission value' },
                      { value: 2,text: 'Total number of conversion'},
                      { value: 3, text: 'Monthly revenue (affiliate are downgraded to level 1 each month)' },
                      { value: 4, text: 'Monthly conversions (affiliate are downgraded to level 1 each month)' },
                    ]"
                    required
                  ></b-form-select>
                </div>
              </div>
            </div>
            <div v-if="form.level_number" class="row mt-2">
              <div class="col-md-6">
                <span class="font-weight-bolder mb-4">
                  Specify affiliate program per level:
                </span>

                <div class="commission-level-container mt-2">
                  <div
                    class="form-group mb-2"
                    v-for="(item, index) in form.data_levels"
                    :key="index"
                  >
                    <label>Program level {{ index + 1 }}: </label>
                    <b-form-select
                      required
                      v-model="item.program_id"
                      aria-label="First name"
                      :options="groupSelectOption"
                    ></b-form-select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <span class="font-weight-bolder mb-4">
                  Specify a minimum value for an affiliate to automatically
                  level-up:
                </span>

                <div class="level-commission-example mt-2">
                  <template
                    v-for="(item, index) in form.data_levels"
                    :index="index"
                  >
                    <div :key="index">
                      <div class="form-group mb-2">
                        <label>Level {{ index + 1 }} condition value: </label>
                        <b-form-input
                          v-model="item.condition_amount"
                          v-if="!index"
                          type="number"
                          disabled
                          value="0"
                          placeholder="0"
                        ></b-form-input>
                        <b-form-input
                          v-else
                          type="number"
                          min="1"
                          required
                          v-model="item.condition_amount"
                        ></b-form-input>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <div class="separator separator-dashed mt-5"></div>
            <div class="float-right mt-5">
              <button
                type="submit"
                class="btn btn-primary font-weight-bolder"
                :disabled="loading"
              >
                <span
                  v-if="!loading"
                  class="svg-icon svg-icon-md svg-icon-white"
                >
                  <inline-svg src="media/svg/icons/General/Save.svg" />
                </span>
                <b-spinner v-if="loading" small></b-spinner>
                Save
              </button>
            </div>
          </form>
          <template #overlay>
            <TableLoader></TableLoader>
          </template>
        </b-overlay>
      </div>
    </div>
  </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import { mapActions, mapGetters } from "vuex";
import ApiService from "@/core/services/api.service";
import TableLoader from "@/view/content/TableLoader.vue";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BInputGroup,
  BSpinner,
  BFormCheckbox,
  BOverlay,
} from "bootstrap-vue";
export default {
  components: {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    BSpinner,
    BFormCheckbox,
    BOverlay,
    TableLoader,
  },
  mixins: [validationMixin],
  data() {
    return {
      loadingAffiliateTier: false,
      loading: false,
      number_level_options: [],
      form: {
        status: 0,
        level_number: null,
        level_condition: 1,
        data_levels: [],
      },
    };
  },
  validations: {
    form: {
      level_number: {
        required,
      },
    },
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    selectNumberLevel(value) {
      if (value < this.form.data_levels.length) {
        this.form.data_levels = this.form.data_levels.slice(0, value);
      } else {
        let difference = value - this.form.data_levels.length;
        for (let i = 0; i < difference; i++) {
          this.form.data_levels.push({
            program_id: null,
            condition_amount: null,
          });
        }
      }
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.loading = true;
      const form = this.form;
      form.data_levels[0].condition_amount = 0;
      let lengthDataLevels = form.data_levels.length;
      for (let i = 0; i < lengthDataLevels; i++) {
        if (!form.data_levels[i].program_id) {
          this.$toast.error("Program must be set for each active level.", {
            position: "bottom",
          });
          this.loading = false;
          return;
        }
      }
      let arrTempDataLevels = form.data_levels.map((data) => {
        return data.program_id;
      });
      let arrConditionValueTempDataLevels = form.data_levels.map((data) => {
        return parseInt(data.condition_amount);
      });
      for (let i = 0; i < arrConditionValueTempDataLevels.length - 1; i++) {
        if (
          arrConditionValueTempDataLevels[i] >
          arrConditionValueTempDataLevels[i + 1]
        ) {
          let tempArrSort = arrConditionValueTempDataLevels.sort((a, b) => {
            if (parseInt(a) > parseInt(b)) return 1;
            if (parseInt(a) < parseInt(b)) return -1;
            return 0;
          });
          this.$toast.error(
            "Level condition values must be ascending (like: " +
              tempArrSort.join(" , ") +
              " )",
            {
              position: "bottom",
            }
          );
          this.loading = false;
          return;
        }
      }
      let lengthArrTempDataLevels = arrTempDataLevels.length;
      let countsProgramInArray = {};
      for (let i = 0; i < lengthArrTempDataLevels; i++) {
        let program = arrTempDataLevels[i];
        countsProgramInArray[program] = countsProgramInArray[program]
          ? countsProgramInArray[program] + 1
          : 1;
      }
      for (const key in countsProgramInArray) {
        if (countsProgramInArray[key] > 1) {
          this.$toast.error("Program must be unique for each level.", {
            position: "bottom",
          });
          this.loading = false;
          return;
        }
      }
      for (let i = 0; i < lengthDataLevels; i++) {
        this.form.data_levels[i].condition_amount = parseInt(
          this.form.data_levels[i].condition_amount
        );
      }
      let resource = "/affiliate-tier";
      ApiService.put(resource, this.form).then(() => {
        this.loading = false;
        this.$toast.success("Updated", { position: "bottom" });
      });
    },
    getAffiliateTier() {
      this.loadingAffiliateTier = true;
      let resource = `/affiliate-tier`;
      ApiService.query(resource)
        .then(({ data }) => {
          if (data.data) {
            this.form.status = data.data.status;
            this.form.level_condition = data.data.level_condition;
            if (data.data.data_levels) {
              this.form.level_number = data.data.data_levels.length;
              this.form.data_levels = data.data.data_levels;
            }
          }
        })
        .finally(() => {
          this.loadingAffiliateTier = false;
        });
    },
  },
  computed: {
    ...mapGetters(["groups"]),
    countLevel() {
      this.number_level_options = [];
      let lengthGroup = this.groups.length;
      this.number_level_options.push({
        text: "Select number of levels",
        value: null,
      });
      if (lengthGroup <= 1) {
        this.number_level_options.push({
          value: 2,
          text: 2,
        });
      } else {
        for (let i = 2; i <= lengthGroup; i++) {
          this.number_level_options.push({
            value: i,
            text: i,
          });
        }
      }
      return this.groups.length;
    },
    groupSelectOption() {
      let options = [];
      let groups = this.groups;
      options.push({
        value: null,
        text: "Choose Program",
      });
      for (var i = 0; i < groups.length; i++) {
        if (groups[i].is_default == 1) {
          this.groupSelected = groups[i].id;
          options.push({
            value: groups[i].id,
            text: this.groups[i].name + " (Default Program)",
          });
        } else {
          options.push({
            value: groups[i].id,
            text: groups[i].name,
          });
        }
      }
      return options;
    },
  },
  watch: {
    countLevel() {},
  },
  mounted() {
    this.getAffiliateTier();
  },
};
</script>

<style>
</style>
<style scoped>
.form-group {
  margin-bottom: 0.75rem;
  display: block !important;
}
.level-commission-example .p-2 {
  padding: 0.8rem !important;
}
.enable-feature {
  padding: 20px 40px;
  border-color: #e8e9f2;
  border-style: none;
  text-align: left;
  align-items: center;
  gap: 2rem;
  margin-bottom: 2rem;
  box-shadow: -2px 4px 19px rgb(0 0 0 / 15%);
  font-family: Poppins, Helvetica, "sans-serif";
}
</style>
