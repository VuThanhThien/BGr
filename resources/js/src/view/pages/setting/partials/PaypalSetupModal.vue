<template>
  <b-modal ref="paypalSetupModal" size="xl" title="Setup Paypal">
    <alert-note :type="'alert-default'">
      To read instructions of how to get the API key? Click
      <a
        class="font-weight-bold"
        href="https://docs.bixgrow.com/settings/integrations/klaviyo-integration"
        target="_blank"
        >here.</a
      >
    </alert-note>
    <form @submit.stop.prevent="submit">
      <div class="form-group">
        <label>Enalbe</label>
        
          <b-form-checkbox
            id="active_klaviyo_12"
            class="mb-10"
            v-model="form.status"
            switch
            size="lg"
            value="1"
            unchecked-value="0"
          ></b-form-checkbox>
       
      </div>
      <div class="form-group">
        <label>Paypal Client ID</label
        ><input
          required
          type="text"
          class="form-control"
          v-model="form.client_id"
        />
      </div>
      <div class="form-group">
        <label>Paypal Client ID</label
        ><input
          required
          type="text"
          class="form-control"
          v-model="form.client_id"
        />
      </div>

      <div class="form-group">
        <label>Paypal Client Secret</label
        ><input
          required
          type="text"
          class="form-control"
          v-model="form.client_secret"
        />
      </div>
      <div class="form-group">
        <label>Payment Password</label
        ><input
          required
          type="password"
          class="form-control"
          v-model="form.password"
        />
      </div>
      <div class="form-group">
        <label>Payment Password Confirmation</label
        ><input
          required
          type="password"
          class="form-control"
          v-model="form.password_confirmation"
        />
      </div>
    </form>
    <template #modal-footer>
      <div class="w-100">
       
        <button type="button" class="btn btn-primary" @click="submit">
          <b-spinner v-if="loading" small></b-spinner>
          Save
        </button>
      </div>
    </template>
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
        loading:false,
        form: {
            status: 0,
            client_id: "",
            client_secret: "",
            password: "",
            password_confirmation: "",
        },
    };
  },
  methods: {
      submit(){
          console.log('aa');
      }
  },
  computed: {},
};
</script>
