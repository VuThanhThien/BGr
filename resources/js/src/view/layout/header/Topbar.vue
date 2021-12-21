<template>
  <!-- begin:: Header Topbar -->
  <div class="topbar">
    <!--#region: Message -->
    <!-- <b-dropdown
      size="sm"
      variant="link"
      toggle-class="topbar-item text-decoration-none"
      no-caret
      right
      no-flip
    >
      <template v-slot:button-content>
        <div
          class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary"
        >
          <span class="svg-icon svg-icon-xl svg-icon-primary">
            <inline-svg src="media/svg/icons/Communication/Group-chat.svg" />
          </span>
          <span class="pulse-ring"></span>
        </div>
      </template>
      <b-dropdown-text tag="div" class="min-w-md-350px">
        <form>
          <DropdownListMessage></DropdownListMessage>
        </form>
      </b-dropdown-text>
    </b-dropdown> -->
    <!--#endregion: Message -->
    <!--#region: Notifications -->
    <KTQuickPanel></KTQuickPanel>
    <!--#endregion: Notifications -->
    <!--begin: User Bar -->
    <KTQuickUser></KTQuickUser>
    <!--end: User Bar -->
  </div>
  <!-- end:: Header Topbar -->
</template>

<style lang="scss">
.topbar {
  .dropdown-toggle {
    padding: 0;
    &:hover {
      text-decoration: none;
    }

    &.dropdown-toggle-no-caret {
      &:after {
        content: none;
      }
    }
  }

  .dropdown-menu {
    margin: 0;
    padding: 0;
    outline: none;
    .b-dropdown-text {
      padding: 0;
    }
  }
}
</style>
<script>
import KTQuickUser from "@/view/layout/extras/offcanvas/QuickUser.vue";
import DropdownListMessage from "@/view/layout/extras/dropdown/DropdownListMessage.vue";
import KTQuickPanel from "@/view/layout/extras/offcanvas/QuickPanel.vue";
import { BDropdown, BDropdownText } from "bootstrap-vue";
export default {
  name: "KTTopbar",
  data() {
    return {};
  },
  components: {
    KTQuickUser,
    DropdownListMessage,
    BDropdown,
    BDropdownText,
    KTQuickPanel,
  },
};
</script>
