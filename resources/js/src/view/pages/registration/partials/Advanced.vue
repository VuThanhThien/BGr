<template>
  <div>
    <!-- #region custom css -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">Custom css</h3>
      </div>
      <b-form-textarea
        id="required"
        v-model="baseObjToGen.customcss"
        placeholder="Enter code css here..."
        rows="10"
      ></b-form-textarea>
    </div>
    <!-- #endregion custom css -->
    <!-- #region captcha -->
    <div class="card p-5 pb-0 mb-1">
      <div class="col-xl-12 mx-auto">
        <h3 class="text-center mb-5">CAPTCHA</h3>
      </div>

      <div class="d-flex justify-content-between mb-5 pr-5 align-items-center">
        <label class="font-weight-bolder">Enable: </label>
        <b-form-checkbox
        style="margin-top: -20px;"
          id="enable"
          v-model="baseObjToGen.captcha.isShow"
          switch
          size="lg"
        ></b-form-checkbox>
      </div>

      <label class="font-weight-bolder">Theme: </label>
      <b-form-select
        id="theme"
        v-model="selectedCaptchaTheme"
        :options="captchaTheme"
        @change="changeCaptchaTheme(selectedCaptchaTheme)"
      ></b-form-select>
    </div>
    <!-- #endregion captcha -->

  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import {
  BFormSelect,
  BFormInput,
  BButton,
  BFormCheckbox,
  BFormTextarea,
  BFormGroup,
  BFormSelectOption,
  BInputGroup,
  BInputGroupAppend,
} from "bootstrap-vue";
export default {
  data() {
    return {
      captchaTheme: [
        { value: "light", text: "Light" },
        { value: "dark", text: "Dark" },
      ],
    };
  },
  components: {
    BFormSelect,
    BFormInput,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    BFormGroup,
    BFormSelectOption,
    BInputGroup,
    BInputGroupAppend,
  },
  methods: {
    ...mapActions(["changeBaseObj", "updateRegistration"]),
    onApplyChange() {
      let obj = this.baseObjToGen;
      let id = this.id;
      this.changeBaseObj(obj);
      this.updateRegistration({ id, obj }).then(() => {
        this.$toast.success("Updated", {
          position: "bottom",
        });
      });
    },
    changeCaptchaTheme(theme) {
      this.baseObjToGen.captcha.theme = theme;
    },
  },
  computed: {
    ...mapGetters(["baseObjToGen"]),
    id() {
      return this.$route.params.id;
    },

    selectedCaptchaTheme: {
      get: function () {
        return this.baseObjToGen.captcha.theme || "light";
      },
      set: function (newValue) {
        this.baseObjToGen.captcha.theme = newValue;
      },
    },
  },
};
</script>

<style lang="scss" scoped>
.card {
  box-shadow: none !important;
  border-radius: unset !important;
}
</style>
