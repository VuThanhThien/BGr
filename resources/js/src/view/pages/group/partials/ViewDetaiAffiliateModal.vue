<template>
  <div>
    <b-modal ref="changeGroupModal" id="changeGroupModal" title="Move all affiliate to another program">
      <select
        v-model="selectedGroupId"
        class="form-control form-control-custom form-control-solid"
      >
        <option value="" disabled >Choose Program</option>
          <option v-for="item in groupChange" :key="item.id" :value="item.id">
            {{ item.name }}
          </option>
      </select>
      <template #modal-footer>
        <div class="w-100">
          <LoadingSubmitButton
            :loading="submitLoading"
            class="float-right"
            @click="onSaveGroup"
          >
            Save
          </LoadingSubmitButton>
        </div>
      </template>
    </b-modal>
  </div>
</template>
<script>
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import ApiService from "@/core/services/api.service";
import { mapMutations } from 'vuex';
export default {
  props: ["groupOb"],
  data() {
    return {
      submitLoading: false,
      selectedGroupId: "",
    };
  },
  computed: {
    groups() {
      return this.$store.getters.groups;
    },
    groupChange: function () {
    return this.groups.filter(i => i.id != this.groupOb.groupId);
  },
  },
  components: { LoadingSubmitButton },
  methods: {
    ...mapMutations(['UPDATE_GROUP_BY_AFFILIATE_COUNT']),
    onSaveGroup() {
      this.submitLoading = true;
      let resource = `affiliates/${this.groupOb.groupId}/remove-group`;
      let groupId = this.selectedGroupId != 0 ? this.selectedGroupId : 0;
      if (this.selectedGroupId) {
        ApiService.post(resource, { group_id: groupId }).then(({ data }) => {
          this.submitLoading = false;
          this.UPDATE_GROUP_BY_AFFILIATE_COUNT({
            idGroupCurrent:this.groupOb.groupId,
            idGroupChange:groupId,
            countAffiliate:data.data
          });
          this.$refs["changeGroupModal"].hide();
          this.$toast.success("Move affiliate success", {
            position: "bottom",
          });
        });
      } else {
        this.$toast.error("Please select group", { position: "bottom" });
        this.submitLoading = false;
      }
    },
  },
};
</script>