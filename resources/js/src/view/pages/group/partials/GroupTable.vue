<template>
  <!--begin::Table-->
  <div class="table-responsive">
    <table class="table table-head-custom table-head-bg table-vertical-center">
      <thead>
        <tr class="text-left">
          <th class="pr-0" style="width: 25%">Name</th>
          <!-- <th style="min-width: 200px"></th> -->
          <th style="min-width: 25%">Type</th>
          <th style="min-width: 10%">Total affiliates</th>
          <th style="min-width: 15%">Active</th>
          <th class="pr-5 text-right min-w-160px">actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(group, i) in groups">
          <tr v-bind:key="i">
            <!-- <td class="pr-0">
                  <div class="symbol symbol-50 symbol-light mt-1">
                    <span class="symbol-label">
                      <img
                        :src="item.text0"
                        class="h-75 align-self-end"
                        alt=""
                      />
                    </span>
                  </div>
                </td> -->
            <td class="pl-0">
              <span
                class="
                  text-dark-75
                  font-weight-bolder
                  text-hover-primary
                  mb-1
                  font-size-lg
                  d-block
                "
                >{{ group.name }}</span
              >
              <span
                v-if="group.is_default"
                class="label label-bixgrow label-inline"
                >Default</span
              >
            </td>
            <td>
              <span class="text-dark-75 d-block font-size-lg">{{
                genTextCommissionType(group.commission_type)
              }}</span>
              <span
                class="
                  label label-lg label-inline
                  font-weight-bold
                  py-4
                  label-light-success
                "
                >{{
                  formatCommissionAmount(
                    group.commission_type,
                    group.commission_amount,
                    format
                  )
                }}</span
              >
            </td>
            <td>
              <span
                class="text-dark-75 font-weight-bolder d-block font-size-lg"
                >{{ group.affiliates_count }}</span
              >
            </td>
            <td>
              <b-form-checkbox
                class="mb-5"
                v-model="group.is_active"
                switch
                size="lg"
                value="1"
                unchecked-value="0"
                @change="toggleActive(group.id, i)"
              ></b-form-checkbox>
            </td>
            <td class="pr-0 text-right none-select">
              <button
                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                @click="openViewDetailModal(group, i)"
                v-b-tooltip.hover
                title="Quick view"
              >
                <span class="svg-icon svg-icon-lg svg-icon-primary">
                  <inline-svg src="media/svg/icons/Design/ZoomPlus.svg" />
                </span>
              </button>
              <router-link
                :to="{ name: 'groups.edit', params: { id: group.id } }"
                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                v-b-tooltip.hover
                title="Edit"
              >
                <span class="svg-icon svg-icon-md svg-icon-primary">
                  <inline-svg src="media/svg/icons/Communication/Write.svg" />
                </span>
              </router-link>
              <button
                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                @click="openDuplicateGroupModal(group, i)"
                v-b-tooltip.hover
                title="Duplicate this program"
              >
                 <span class="svg-icon svg-icon-lg svg-icon-primary"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Duplicate.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M15.9956071,6 L9,6 C7.34314575,6 6,7.34314575 6,9 L6,15.9956071 C4.70185442,15.9316381 4,15.1706419 4,13.8181818 L4,6.18181818 C4,4.76751186 4.76751186,4 6.18181818,4 L13.8181818,4 C15.1706419,4 15.9316381,4.70185442 15.9956071,6 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M10.1818182,8 L17.8181818,8 C19.2324881,8 20,8.76751186 20,10.1818182 L20,17.8181818 C20,19.2324881 19.2324881,20 17.8181818,20 L10.1818182,20 C8.76751186,20 8,19.2324881 8,17.8181818 L8,10.1818182 C8,8.76751186 8.76751186,8 10.1818182,8 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
              </button>
              <button
                href="#"
                class="btn btn-icon btn-light btn-hover-primary btn-sm"
                @click="
                  deleteGroup(
                    group.id,
                    group.is_default,
                    group.affiliates_count
                  )
                "
                v-b-tooltip.hover
                title="Delete"
              >
                <span class="svg-icon svg-icon-danger svg-icon-lg">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    version="1.1"
                  >
                    <g
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <rect x="0" y="0" width="24" height="24"></rect>
                      <path
                        d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                        fill="#000000"
                        fill-rule="nonzero"
                      ></path>
                      <path
                        d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                        fill="#000000"
                        opacity="0.3"
                      ></path>
                    </g>
                  </svg>
                </span>
              </button>
              <b-dropdown
                v-b-tooltip.hover
                title="More actions"
                class="more-action-dropdown mr-1"
                dropdown
                no-caret
              >
                <template #button-content class="btn-sm">
                  <i
                    class="fas fa-ellipsis-h"
                    style="color: #a7a2a2; position: relative; right: 5px"
                  ></i>
                </template>
                <b-dropdown-item @click="setDefault(group.id, i)"
                  >Set as default</b-dropdown-item
                >
                <b-dropdown-item
                  v-if="group.affiliates_count"
                  @click="openViewDetailAffiliateModal(group.id, i)"
                  >Move all affiliates to other programs</b-dropdown-item
                >
                <!-- <b-dropdown-item @click="openDuplicateGroupModal(group, i)"
                  >Duplicate this program</b-dropdown-item
                > -->
              </b-dropdown>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
    <ViewDetailModal
      :group="currentGroupView"
      ref="viewDetailModalComponent"
    ></ViewDetailModal>
    <ViewDetailAffiliateModal
      :groupOb="currentGroupView"
      ref="viewDetailAffiliateModalComponent"
    ></ViewDetailAffiliateModal>
    <DuplicateGroupModal
      :group="currentGroupView"
      ref="duplicateGroupModalComponent"
    ></DuplicateGroupModal>
  </div>
</template>

<script>
import swal from "sweetalert2";
import ViewDetailModal from "@/view/pages/group/partials/ViewDetailModal.vue";
import ViewDetailAffiliateModal from "@/view/pages/group/partials/ViewDetaiAffiliateModal.vue";
import DuplicateGroupModal from "@/view/pages/group/partials/DuplicateGroupModal.vue";
import ApiService from "./../../../../core/services/api.service";
window.Swal = swal;
import { BFormCheckbox, BDropdown, BDropdownItem } from "bootstrap-vue";
export default {
  components: {
    ViewDetailModal,
    ViewDetailAffiliateModal,
    BFormCheckbox,
    BDropdown,
    BDropdownItem,
    DuplicateGroupModal,
  },
  name: "GroupTable",
  data() {
    return {
      checked: false,
      currentGroupView: {},
    };
  },
  computed: {
    groups() {
      return this.$store.getters.groups;
    },
    format() {
      return this.$store.getters.moneyFormat;
    },
  },

  methods: {
    openViewDetailAffiliateModal(value, key) {
      this.currentGroupView = {
        groupId: value,
        index: key,
      };
      this.$refs["viewDetailAffiliateModalComponent"].$refs[
        "changeGroupModal"
      ].show();
    },
    openViewDetailModal(group) {
      this.currentGroupView = group;
      this.$refs["viewDetailModalComponent"].$refs["viewDetailModal"].show();
    },
    openDuplicateGroupModal(group, key) {
      this.currentGroupView = group;
      // console.log(this.currentGroupView, key);
      this.$refs["duplicateGroupModalComponent"].$refs[
        "duplicateGroupModal"
      ].show();
    },
    deleteGroup(id, is_default, affiliates_count) {
      if (is_default == 1) {
        Swal.fire({
          icon: "error",
          title: "Can not delete!",
          text: "This program can not be deleted because it is a default program. Before you can delete this one, you must make another program as default.",
        });
      } else if (affiliates_count > 0) {
        Swal.fire({
          icon: "error",
          title: "Can not delete!",
          text: "This program can not be deleted because it has affiliates attached to it. Before you can delete this one, you must move all affiliates to another program.",
        });
      } else {
        Swal.fire({
          title: "Are you sure?",
          text: "You will not be able to recover this program!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        }).then((result) => {
          if (result.isConfirmed) {
            ApiService.delete("groups/" + id)
              .then(({ data }) => {
                if (data.status == "Success") {
                  let index = this.groups.findIndex((x) => x.id == id);
                  if (index > -1) {
                    this.groups.splice(index, 1);
                  }
                }
                if (data.status == "Error") {
                  this.$toast.error(data.data.message, {
                    position: "bottom",
                  });
                }
              })
              .catch(({ response }) => {
                this.$toast.error(response.data.message, {
                  position: "bottom",
                });
              });
          }
        });
      }
    },

    toggleActive(groupId, index) {
      ApiService.put("groups/" + groupId + "/change-active").then(() => {
        // this.groups[index].is_active = !this.groups[index].is_active;
      });
    },

    setDefault(groupId, index) {
      ApiService.put("groups/" + groupId + "/set-default").then(() => {
        let group = this.groups.find((g) => g.is_default == 1);
        group.is_default = 0;
        this.groups[index].is_default = 1;
      });
    },
  },
};
</script>

<style>
.more-action-dropdown .btn-secondary {
  background-color: #f3f6f9;
  height: calc(1.35em + 1.1rem + 2px);
  width: calc(1.35em + 1.1rem + 2px);
  border-color: #f3f6f9;
}
</style>
