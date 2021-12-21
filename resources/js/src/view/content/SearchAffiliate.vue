<template>
  <v-select
    ref="v_select"
    @search="onSearchAffiliate"
    :options="affiliateOptions"
    label="first_name"
    :filterable="false"
    :value="affiliateFilterSelected"
    @input="setAffiliateSelected"
    placeholder="Type to search Affiliate"
  >
    <template v-slot:no-options="{ search, searching }">
      <template v-if="searching">
        <span class="text-muted"
          >No affiliate found for <em>{{ search }}</em
          >.</span
        >
      </template>
      <span class="text-muted" v-else>Type to search Affiliate.</span>
    </template>
    <template slot="option" slot-scope="option">
      <div class="d-center">
        {{ option.full_name + "(" + option.email + ")" }}
      </div>
    </template>
    <template slot="selected-option" slot-scope="option">
      <div class="selected d-center">
        {{ option.full_name }}
      </div>
    </template>
  </v-select>
</template>

<script>
import ApiService from "@/core/services/api.service";
import vSelect from "vue-select";
export default {
  props: ["filterAffiliate"],
  components: { vSelect },
  data() {
    return {
      affiliateOptions: [],
      affiliateFilterSelected: null,
    };
  },
  methods: {
    onSearchAffiliate(search, loading) {
      if (search.length) {
        loading(true);
        this.searchAffiliate(loading, search, this);
      }
    },
    setAffiliateSelected(value) {
      this.affiliateFilterSelected = value;
      this.$emit("input", value);
    },
    searchAffiliate: _.debounce((loading, search, vm) => {
      let resource = "affiliates/search?query=" + escape(search);
      ApiService.query(resource)
        .then(({ data }) => {
          if (vm.filterAffiliate) {
            data = data.filter((aff) => {
              return aff.id != vm.filterAffiliate;
            });
          }
          vm.affiliateOptions = data;
          loading(false);
        })
        .catch((response) => {
          // console.log(response);
          setTimeout(() => {
            loading(false);
          }, 2000);
        });
    }),
  },
};
</script>
<style lang="scss" >
.vs__dropdown-toggle {
  padding: 0 !important;
  height: calc(1.5em + 1.65rem + 2px);
}
</style>
