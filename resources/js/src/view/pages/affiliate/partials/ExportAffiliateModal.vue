<template>
  <b-modal
    ref="exportAffiliateModal"
    size="md"
    title="Export options"
    ok-title="Export"
    cancel-title="Close"
  >
    <b-form-group label="Export by">
      <b-form-radio class="mb-2" v-model="selected" value="all">
        All affiliates</b-form-radio
      >
      <b-form-radio v-model="selected" value="filter">
        Filtered affiliates
      </b-form-radio>
    </b-form-group>
    <template #modal-footer>
      <div class="w-100">
        <div class="float-right">
          <a class="btn btn-primary" :href="linkExport()">
            <span class="svg-icon svg-icon-lg">
              <inline-svg src="/media/svg/icons/Files/Download.svg" />
            </span>
            Export
          </a>
        </div>
      </div>
    </template>
  </b-modal>
</template>

<script>
import { BModal, BFormRadio, BFormGroup } from "bootstrap-vue";
import JwtService from "@/core/services/jwt.service";
export default {
  props: ["startDate", "endDate", "groupId", "status",'affiliate'],
  components: {
    BModal,
    BFormRadio,
    BFormGroup,
  },
  data() {
    return {
      selected: "all",
    };
  },
  computed: {
    filter() {
      let params = [];
      if (this.startDate) {
        params.push("start_date=" + this.startDate);
      }
      if (this.endDate) {
        params.push("end_date=" + this.endDate);
      }
      if (this.status) {
        params.push("status=" + this.status);
      }
      if (this.groupId) {
        params.push("group_id=" + this.groupId);
      }
      if (this.affiliate)
      {
        params.push("affiliate_id=" + this.affiliate.id);
      }
      if (JwtService.getToken()) {
        params.push("token=" + JwtService.getToken());
      }
      let queryParams = params.join("&");
      return queryParams;
    },
  },
  methods: {
    linkExport() {
      let link = process.env.MIX_API_ENDPOINT + "/api/affiliates/export";
      if (this.filter && this.selected == "filter") {
        link += "?" + this.filter;
        return link;
      }
      return link + "?token=" + JwtService.getToken();
    },
  },
};
</script>
