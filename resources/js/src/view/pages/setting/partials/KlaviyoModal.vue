<template>
  <b-modal hide-footer ref="klaviyoSetup" size="md" title="Klaviyo Setup" @hidden="hiddenKlaviyoModal">
    <alert-note :type="'alert-default'">
      To read instructions of how to get the API key? Click
      <a class="font-weight-bold" href="https://docs.bixgrow.com/settings/integrations/klaviyo-integration" target="_blank">here.</a>
    </alert-note>
    <form @submit.stop.prevent="submit">
      <div class="form-group">
        <label>Only sync if the affiliate is approved</label>
        <div v-if="!loadingButton">
          <b-form-checkbox
            id="active_klaviyo_12"
            class="mb-10"
            v-model="syncKlaviyoApprovedAffiliate"
            switch
            size="lg"
            value="1"
            unchecked-value="0"
          ></b-form-checkbox>
        </div>
        <div v-else>
          <div class="mb-10 custom-control">
            <Loading></Loading>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>API Key</label
        ><input
          required
          type="text"
          placeholder="Klaviyo API key"
          class="form-control"
          v-model="apiKey"
        />
      </div>

      <div v-if="lists.length > 0">
        <div class="form-group">
          <label>Sync to list </label
          ><select class="form-control" v-model="klaviyo_list_selected">
            <option v-for="(item, i) in lists" :value="item.list_id" :key="i">
              {{ item.list_name }}
            </option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <b-spinner v-if="loading" small></b-spinner>
          Save and Sync
        </button>
      </div>
      <div v-else>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <b-spinner v-if="loading" small></b-spinner>
          Next
        </button>
      </div>
    </form>
  </b-modal>
</template>

<script>
import ApiService from "aff/core/services/api.service";
import { BSpinner, BFormCheckbox } from "bootstrap-vue";
import { mapActions, mapGetters, mapMutations } from "vuex";
import Loading from "@/view/content/LoaderVue.vue";
export default {
  components: {
    BSpinner,
    BFormCheckbox,
    Loading,
  },
  data() {
    return {
      loading: false,
      apiKey: "",
      klaviyo_list_selected: "",
      lists: [],
      klaviyo_sync_enabled: 1,
      loadingButton: false,
    };
  },
  methods: {
    ...mapActions(["toggleSyncKlaviyoApprovedAffiliate"]),
    ...mapMutations(["DO_INITIAL_SYNC"]),
    submit() {
      this.loading = true;
      let params = {};
      if (this.lists.length != 0) {
        let klaviyo_list = this.lists.find((item) => {
          return item.list_id == this.klaviyo_list_selected;
        });
        params["klaviyo"] = {
          klaviyo_list: klaviyo_list,
          klaviyo_api_key: this.apiKey
        }
        params["klaviyo_sync_enabled"] = 1;
        ApiService.post("klaviyo/save_sync_klaviyo", params).then(({ data }) => {
          this.DO_INITIAL_SYNC(params);
          this.$refs["klaviyoSetup"].hide();
          if(data.data.status == 'ok')
          {
              this.$toast.success(data.data.message, { position: "bottom" });
          }
        }).finally(() => {
            this.loading = false;
          });
      } else {
        params['klaviyo_api_key'] = this.apiKey;
        ApiService.query("klaviyo/list_klaviyo", {
          params: params,
        })
          .then(({ data }) => {
            if (typeof data.data.lists !== "undefined") {
              this.klaviyo_list_selected = data.data.lists[0].list_id;
              this.lists = data.data.lists;
            } else {
              this.$toast.error(data.data.message, { position: "bottom" });
            }
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    hiddenKlaviyoModal()
    {
      this.$emit('hiddenKlaviyoModal');
    }
  },
  computed: {
    ...mapGetters(["sync_klaviyo_approved_affiliate"]),
    syncKlaviyoApprovedAffiliate: {
      get() {
        return this.sync_klaviyo_approved_affiliate;
      },
      set() {
        this.loadingButton = true;
        this.toggleSyncKlaviyoApprovedAffiliate().then(() => {
          this.loadingButton = false;
          this.$toast.success("Updated", { position: "bottom" });
        });
      },
    },
  },
};
</script>
