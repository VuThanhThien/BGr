<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
      This feature allows each affiliate to invite others into the affiliate network and they all get paid when a conversion is made down the level. For each network level, you can set up different network commission.
      <br/>
         Learn more about
        <a class="font-weight-bold" target="_blank" href="https://docs.bixgrow.com/manage/program/multi-level-marketing">Multi-level Marketing.</a>
    </alert-note>

  <div class="card card-custom gutter-b">
      <!--begin::Header-->
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Multi-level Marketing
          </span>
          <span class="text-muted mt-3 font-weight-bold font-size-sm"> </span>
        </h3>
      </div>
    <div class="card-body py-0">
      <div class="row">
        <div class="col-md-6">
            <!-- chọn program -->
          <div class="form-group">
            <label class="font-weight-bolder">Choose program: </label>
            <select
            v-model="slugOfSelectedGroup"
            class="form-control"
            >
            <option
                v-for="(o, index) in groupSelectOption"
                :value="o.slug"
                :o="o"
                :key="index"
            >
                {{ o.text }}
            </option>
            </select>
            <span class="form-text text-muted">
              Select the program that you want to set up multi-level marketing.
            </span>
          </div>
            <!-- Enable -->
          <div class="form-group" style="margin-bottom: 4rem">
            <label class="font-weight-bolder">Activate MLM: </label>
            <b-form-checkbox
              v-model="form.is_enable"
              id="enable_mlm"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
            ></b-form-checkbox>
          </div>

        <!-- chọn level -->
          <div class="form-group">
            <label class="font-weight-bolder">Number of levels: </label>
            <b-form-select
              id="select-number-level"
              v-model="$v.form.number_level.$model"
              :options="number_level_options"
              required
              @change="selectNumberLevel"
              :state="validateState('number_level')"
            ></b-form-select>
            <span v-if="form.number_level" class="form-text text-muted">
              Set up number of levels that receive network commission. The maximum is 20.
            </span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="font-weight-bolder">Network commission on: </label>
            <b-form-select
              v-model="form.mlm_commission_on"
              :options="[
                { value: 1, text: 'Total sale' },
                { value: 2, text: 'Program commission' },
              ]"
              required
            ></b-form-select>
            <span v-if="form.mlm_commission_on==1" class="form-text text-muted">
              The commission is calculated based on the total orders value.
            </span>
            <span v-if="form.mlm_commission_on==2" class="form-text text-muted">
              The commission is calculated based on the program commission.
            </span>
          </div>
          <div class="form-group">
            <label class="font-weight-bolder">Recruitment bonus: </label>
            <b-input-group
              class="mb-2"
              :prepend="symbolCurrency"
            >
                <b-form-input
                type="number"
                v-model="form.mlm_bonus"
                required
                ></b-form-input>
            </b-input-group>
            <span class="form-text text-muted">
              A reward for an affiliate when they recruit other affiliates
            </span>
          </div>
        </div>
      </div>
      <div v-if="form.number_level" class="row" style="margin-top: 25px">
        <div class="col-md-6">
          <h5 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark">
              Commission per level:
            </span>
          </h5>
          <div class="commission-level-container">
            <div class="form-group" v-for="(item, index) in form.mlm_tree" :key="index">
              <label>Commission level {{ index + 1 }}: </label>
              <b-input-group
                class="mb-2"
                :append="item.commission_type == 1 ? '%' : symbolCurrency"
              >
                <b-form-select
                  v-model="item.commission_type"
                  aria-label="First name"
                  :options="[
                    { value: 1, text: 'Percent' },
                    { value: 2, text: 'Flat rate' },
                  ]"
                ></b-form-select>
                <b-form-input
                  v-model="item.commission_amount"
                  type="number"
                ></b-form-input>
              </b-input-group>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h5 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark">
              Live example:
            </span>
          </h5>
          <div
            v-if="form.mlm_tree.length > 0"
            class="form-inline"
            style="margin-bottom: 15px"
          >
            <label>Order amount: </label>
            <b-input-group
              class="mb-2 ml-20"
              :prepend="symbolCurrency"
            >
              <b-form-input
                v-model="orderAmountEx"
                type="number"
              ></b-form-input>
            </b-input-group>
          </div>
          <div class="level-commission-example">
            <div
              v-if="form.mlm_tree.length > 0"
              class="form-group mb-0"

            >
              <div class="d-flex align-items-center rounded p-2 border">
                {{ calGroupCommission() }} - Affiliate
                {{ form.mlm_tree.length + 1 }}: Brings conversion (program commission)
              </div>
            </div>
            <img
              v-if="form.mlm_tree.length > 0"
              src="/media/svg/icons/Navigation/Arrow-down.svg"
            />

            <template v-for="(item, index) in form.mlm_tree" :index="index" >
                <div :key="index">
              <div class="form-group mb-0">
                <div class="d-flex align-items-center rounded p-2 border">
                  {{ calCommissionLevel(item) }} - Affiliate
                  {{ form.mlm_tree.length - index }} (commission level
                  {{ index + 1 }})
                </div>
              </div>
              <img
                v-if="index != form.mlm_tree.length - 1"
                src="/media/svg/icons/Navigation/Arrow-down.svg"
              />
                </div>
            </template>
          </div>
        </div>
      </div>
        <div class="separator separator-dashed mt-10 "></div>
      <div class="float-right mt-5 mb-5">
        <button
          type="button"
          class="btn btn-primary font-weight-bolder"
          @click="submit()"
          :disabled="loading"
        >
        <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
            <inline-svg src="media/svg/icons/General/Save.svg" />
        </span>
          <b-spinner v-if="loading" small></b-spinner>
          Save
        </button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import { mapActions, mapGetters } from "vuex";
import { BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner,BFormCheckbox   } from 'bootstrap-vue'
export default {
  components:{
    BFormGroup,BFormInput,BFormSelect,BInputGroup,BSpinner,BFormCheckbox
  },
  mixins: [validationMixin],

  data() {
    return {
      loading:false,
      number_level_options: [
        { value: null, text: "Select number of levels" },
        { value: 1, text: "1 level" },
        { value: 2, text: "2 levels" },
        { value: 3, text: "3 levels" },
        { value: 4, text: "4 levels" },
        { value: 5, text: "5 levels" },
        { value: 6, text: "6 levels" },
        { value: 7, text: "7 levels" },
        { value: 8, text: "8 levels" },
        { value: 9, text: "9 levels" },
        { value: 10, text: "10 levels" },
        { value: 11, text: "11 levels" },
        { value: 12, text: "12 levels" },
        { value: 13, text: "13 levels" },
        { value: 14, text: "14 levels" },
        { value: 15, text: "15 levels" },
        { value: 16, text: "16 levels" },
        { value: 17, text: "17 levels" },
        { value: 18, text: "18 levels" },
        { value: 19, text: "19 levels" },
        { value: 20, text: "20 levels" },
      ],
      form: {
        is_enable: 0,
        number_level: null,
        mlm_commission_on: 1,
        mlm_bonus: 0.0,
        mlm_tree: [],
      },
      orderAmountEx: 100,
      slugOfSelectedGroup: "",
    };
  },
  validations: {
    form: {
      number_level: {
        required,
      },
    },
  },
  computed: {
    ...mapGetters([
    "groups",
    ]),
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
    // current group
    group() {
    let a = this.groups.filter(group=>(group.slug==this.slugOfSelectedGroup));
    return a[0];
    },
    // Mảng group trong store để select
    groupSelectOption() {
      let options = [];
      let groups = this.groups;
      for (var i = 0; i < groups.length; i++) {
        if(groups[i].is_default==1)
        {
          this.slugOfSelectedGroup=groups[i].slug;
           options.push({
          default:groups[i].is_default,
          value: groups[i].id,
          text: this.groups[i].name +' (Default Program)',
          slug: groups[i].slug,
        });
        }
        else{
           options.push({
           default:groups[i].is_default,
          value: groups[i].id,
          text: groups[i].name,
          slug: groups[i].slug,
        });
        }

      }
      return options;
    },
    // id của group đã chọn
    // Nếu k chọn lấy id default
    idOfSlug() {
      let groups = this.groups;
      let defaultId = groups[0].id;
      for (var i = 0; i < groups.length; i++) {
        if (groups[i].is_default == 1) {
          defaultId = groups[i].id;
        }
        if (groups[i].slug == this.slugOfSelectedGroup) {
          return groups[i].id;
        }
      }
      return defaultId;
    },
  },
  methods: {
    ...mapActions(["updateMLM"]),
    selectNumberLevel(value) {
      if (value < this.form.mlm_tree.length) {
        this.form.mlm_tree = this.form.mlm_tree.slice(0, value);
      } else {
        let difference = value - this.form.mlm_tree.length;
        for (var i = 0; i < difference; i++) {
          this.form.mlm_tree.push({ commission_type: 1, commission_amount: 0 });
        }
      }
    },

    calCommissionLevel(item) {
      let amount = 0;
      if (item.commission_type == 2) {
        amount = item.commission_amount;
      } else {
        if (this.form.mlm_commission_on == 1) {
          amount = (this.orderAmountEx * item.commission_amount) / 100;
        } else {
          let programCommission = 0;
          if (this.group.commission_type == 1) {
            programCommission = (100 * this.group.commission_amount) / 100;
          } else {
            programCommission = this.group.commission_amount;
          }

          amount = (programCommission * item.commission_amount) / 100;
        }
      }

      return this.formatMoney(amount, this.format);
    },

    calGroupCommission() {
      if (this.group.commission_type == 1) {
        return this.formatMoney(
          (this.orderAmountEx * this.group.commission_amount) / 100,
          this.format
        );
      } else {
        return this.formatMoney(this.group.commission_amount, this.format);
      }
    },

    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.loading = true;
      const id = this.idOfSlug;
      const form = this.form;
      this.updateMLM({ id, form }).then(()=>{
        this.loading=false;
        this.$toast.success('Updated',{position:'bottom'});
      });
    },
},
  watch: {
    group: function (newValue) {
      this.form.is_enable = newValue.is_enable_mlm;
      this.form.mlm_commission_on = newValue.mlm_commission_on;
      this.form.mlm_bonus = newValue.mlm_bonus;
      if (newValue.mlm_tree) {
        this.form.number_level = newValue.mlm_tree.length;
        this.form.mlm_tree = newValue.mlm_tree;
      }else{
        this.form.number_level = null;
        this.form.mlm_tree = [];
      }
    },
  },
};
</script>

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
