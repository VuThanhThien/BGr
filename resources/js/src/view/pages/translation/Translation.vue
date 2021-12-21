<template>
  <div>
    <div class="row row d-flex">
      <div class="col-lg-12">
        <div class="card card-custom gutter-b">
          <div class="card-header py-3">
            <h3 class="card-title align-items-start flex-column w-100">
              <span class="card-label font-weight-bolder text-bixgrow">
                Edit Translations
              </span>
              <span class="text-muted mt-3 font-weight-bold font-size-sm w-100">
              </span>
            </h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="exampleSelect1">Language </label>
              <select
                class="form-control"
                v-model="affiliateLanguage"
                @change="changeLanguage"
                id="exampleSelect1"
              >
                <option
                  v-for="(lang, index) in languages"
                  :key="index"
                  :value="lang.value"
                >
                  {{ lang.text }}
                </option>
              </select>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Type to search Translations"
                    @input="searchTranslation"
                    v-model="search"
                  />
                </div>
              </div>
            </div>
            <h6 class="font-weight-bold text-dark mb-10 border-top"></h6>
            <b-overlay opacity="0" :show="submitLoading" rounded="sm">
              <div v-if="translations.data.length > 0">
                <form
                  @submit.stop.prevent="saveChange"
                  class="form"
                  ref="form_language"
                >
                  <div
                    class="form-group"
                    v-for="(v, index) in form"
                    :key="index"
                  >
                    <label
                      >{{ v.label }} <span class="text-danger">*</span></label
                    >
                    <input
                      type="text"
                      class="form-control"
                      required
                      v-model="v.value"
                      :name="v.key"
                    />
                  </div>
                  <div
                    v-if="translations.data.length > 0"
                    class="
                      d-flex
                      justify-content-between
                      align-items-center
                      flex-wrap
                    "
                  >
                    <div class="d-flex flex-wrap py-2 mr-3">
                      <pagination
                        :data="translations"
                        @pagination-change-page="getTranslations"
                        :limit="3"
                        show-disabled
                        size="default"
                      >
                        <span slot="prev-nav">
                          <i class="ki ki-bold-arrow-back icon-xs"></i>
                        </span>
                        <span slot="next-nav">
                          <i class="ki ki-bold-arrow-next icon-xs"></i>
                        </span>
                      </pagination>
                    </div>
                    <div class="d-flex align-items-center py-3">
                      <select
                        v-model="paginate"
                        class="
                          form-control form-control-sm
                          font-weight-bold
                          mr-4
                          border-0
                          bg-light
                        "
                        style="width: 75px"
                      >
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                      <span class="text-muted"
                        >Showing {{ translations.from }} -
                        {{ translations.to }} of {{ translations.total }}</span
                      >
                    </div>
                  </div>
                  <div class="float-right">
                    <b-button
                      type="submit"
                      variant="primary"
                      class="font-weight-bolder"
                      :disabled="loading"
                    >
                      <span
                        v-if="!loading"
                        class="svg-icon svg-icon-md svg-icon-white"
                      >
                        <inline-svg src="media/svg/icons/General/Save.svg" />
                      </span>
                      <b-spinner v-if="loading" small></b-spinner>
                      Save Changes</b-button
                    >
                  </div>
                </form>
              </div>
              <template #overlay>
                <TableLoader></TableLoader>
              </template>
            </b-overlay>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ApiService from "@/core/services/api.service";
import { BButton, BOverlay, BSpinner } from "bootstrap-vue";
import TableLoader from "@/view/content/TableLoader.vue";
import pagination from "laravel-vue-pagination";
export default {
  data() {
    return {
      loading: false,
      submitLoading: false,
      affiliateLanguage: "en",
      search: "",
      paginate: 10,
      languages: [
        { text: "English", value: "en" },
        { text: "Spanish", value: "es" },
        { text: "Chinese", value: "zh" },
        { text: "German", value: "de" },
        { text: "French", value: "fr" },
        { text: "Portuguese", value: "pt" },
        { text: "Italian", value: "it" },
      ],
      translations: {
        data: [],
      },
      form: [],
    };
  },
  components: {
    BButton,
    BOverlay,
    TableLoader,
    pagination,
    BSpinner,
  },
  methods: {
    saveChange() {
      this.loading = true;
      // let formData = new FormData(this.$refs["form_language"]);
      // let data = {};
      // for (let [key, value] of formData.entries()) {
      //   Object.assign(data, { [key]: value });
      // }
      let data = {};
      let lengthForm = this.form.length;
      for (let i = 0; i < lengthForm; i++) {
        {
          for (const key in this.form[i]) {
            if (key == "key") {
              data[this.form[i][key]] = this.form[i].value;
            }
          }
        }
      }
      let resource = `/settings/translations/${this.affiliateLanguage}`;
      ApiService.put(resource, data)
        .then(() => {
          this.$toast.success("Updated", {
            position: "bottom",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getTranslations(page = 1) {
      this.submitLoading = true;
      let params = {
        paginate: this.paginate,
        page: page,
      };
      if (this.search) {
        params["search"] = this.search;
      }
      let resource = `/settings/translations/${this.affiliateLanguage}`;
      ApiService.query(resource, {
        params: params,
      }).then(({ data }) => {
        this.translations = data.data.translations;
        this.form = this.convertArrayIndexToKey(
          data.data.translations.data
        );
        this.submitLoading = false;
      });
    },
    convertArrayIndexToKey(translations) {
      let arrTranslations = [];
      let translationsLength = translations.length;
      for (let i = 0; i < translationsLength; i++) {
        let param = {};
            param["key"] = translations[i].key;
            param["label"] = translations[i].textEn.replace(/<[^>]*>?/gm, "");
            translations[i]["t_text"]
              ? (param["value"] = translations[i]["t_text"])
              : (param["value"] = translations[i]["text"]);
            arrTranslations.push(param);
      }
      return arrTranslations;
    },
    searchTranslation() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.getTranslations();
      }, 600);
    },
    changeLanguage() {
      this.getTranslations();
    },
  },
  created() {
    this.getTranslations();
  },
  watch: {
    paginate() {
      this.getTranslations();
    },
  },
};
</script>