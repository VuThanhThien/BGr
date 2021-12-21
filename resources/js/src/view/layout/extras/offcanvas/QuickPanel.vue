<template>
  <div class="topbar-item">
    <div
      class="
        btn btn-icon btn-clean btn-dropdown btn-lg
        mr-1
        pulse pulse-danger
        relative
      "
      id="kt_quick_panel_toggle"
      @click="onClickShowNoti"
    >
      <span class="svg-icon svg-icon-xl svg-icon-primary">
        <inline-svg src="media/svg/icons/General/Notifications1.svg" />
      </span>
      <span
        v-if="coutUnreadNotifications > 0 && clicked === false"
        class="badge"
        >{{ coutUnreadNotifications }}</span
      >
      <span
        class="pulse-ring"
        v-if="coutUnreadNotifications > 0 && clicked === false"
      ></span>
    </div>

    <!-- begin::Quick Panel -->
    <div
      id="kt_quick_panel"
      ref="kt_quick_panel"
      class="offcanvas offcanvas-right "
      style="overflow: hidden"
    >
      <!--begin::Header-->
      <div
        class="
          offcanvas-header offcanvas-header-navs
          d-flex
          align-items-center
          justify-content-between
          mb-5
        "
      >
        <div
          class="d-flex flex-column w-100 pt-12 bgi-size-cover bgi-no-repeat"
          :style="{ backgroundImage: `url(${backgroundImage})` }"
        >
          <h4 class="d-flex flex-center rounded-top mb-10">
            <span class="text-white">Notifications</span>
            <span
              class="
                btn btn-text btn-success btn-sm
                font-weight-bold
                btn-font-md
                ml-2
              "
            >
              {{ this.coutUnreadNotifications }} new
            </span>
          </h4>
        </div>
        <div class="offcanvas-close pt-5 pr-5">
          <a
            href="#"
            class="btn btn-xs btn-icon btn-light btn-hover-primary"
            id="kt_quick_panel_close"
          >
            <i class="ki ki-close icon-xs text-muted"></i>
          </a>
        </div>
      </div>
      <!--end::Header-->
      <div class="offcanvas-content">
        <div class="scroll pr-7 mr-n7" id="kt_quick_panel_notifications">
          <!--begin::Nav-->
          <div class="navi navi-icon-circle navi-spacer-x-0 " style="background-color: #e8e8e8">
            <template v-for="(item, i) in listNoti">
              <div class="noti-element my-3" v-bind:key="i" style="color: inherit">
                <div class="navi-link-custom">
                  <div class="w-100">
                    <span v-html="formatDate(item.time_stamp, true, true)" class="text-muted">
                    </span>
                    <h4 class="font-weight-bold mt-2">
                      {{ item.data.header }}
                    </h4>
                    <span v-html="item.data.content"></span>
                    <div class="d-flex" v-if="item.data.label">
                      <a
                        :href="item.data.link"
                        class="follow-link mr-1"
                        target="_blank"
                        rel="nofolow"
                        >{{ item.data.label }}</a
                      >
                      <svg
                        class="follow-link-icon"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"
                        ></path>
                        <path d="M0 0h24v24H0z" fill="none"></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <div class="w-100 d-flex justify-content-center">
              <a
                v-if="this.currenPage < this.lastPage"
                @click="loadMore"
                class="my-3"
                style="cursor: pointer"
                >Load more...</a
              >
              <a v-if="this.currenPage == this.lastPage" class="my-3"
                >All notifications have been displayed.</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end::Quick Panel -->
  </div>
</template>

<script>
import KTLayoutQuickPanel from "@/assets/js/layout/extended/quick-panel.js";
import { mapGetters, mapActions } from "vuex";
import ApiService from "@/core/services/api.service";
import moment from "moment";
export default {
  name: "KTQuickPanel",
  data() {
    return {
      listNoti: [],
      currenPage: 1,
      lastPage: 2,
      clicked: false,
    };
  },
  mounted() {
    KTLayoutQuickPanel.init(this.$refs["kt_quick_panel"]);
  },
  computed: {
    ...mapGetters(["coutUnreadNotifications"]),
    backgroundImage() {
      return "media/misc/bg-1.jpg";
    },
  },
  methods: {
    ...mapActions(["updateNotification"]),
    loadMore() {
      let nexpage = this.currenPage + 1;
      ApiService.query(
        "/settings/system-notifications/load-more?page=" + nexpage
      )
        .then(({ data }) => {
          let nextPageData = data.data.data;
          this.currenPage = data.data.current_page;
          this.lastPage = data.data.last_page;
          this.listNoti = this.listNoti.concat(nextPageData);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    onClickShowNoti() {
      ApiService.query("/settings/system-notifications")
      .then(({ data }) => {
        this.listNoti = data.data.data;
        this.currenPage = data.data.current_page;
        this.lastPage = data.data.last_page;
      })
      .catch(({ response }) => {
        console.log(response);
      });
      let payload = 0;
      this.clicked = true;
      this.updateNotification(payload);
    },
  },
};
</script>
<style lang="scss" scoped>
.follow-link-icon {
  width: 16px !important;
  height: 16px !important;
  position: relative;
  top: 2px;
  fill: #3699ff;
  left: 0;
}
.relative {
  position: relative;
}
.badge {
  position: absolute;
  top: 2px;
  right: 2px;
  border-radius: 50%;
  background: red;
  color: white;
  height: 18px;
  width: 18px;
  font-size: 11px;
  align-items: center;
  display: flex;
  justify-content: center;
}
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
.pulse .pulse-ring {
  -webkit-animation: animation-pulse 1.5s ease-out;
  animation: animation-pulse 1.5s ease-out;
  -webkit-animation-iteration-count: infinite;
}
.navi-link-custom {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
}
.navi-item:hover {
  background-color: rgb(216, 216, 216);
}
.follow-link-icon {
  width: 16px !important;
  height: 16px !important;
  position: relative;
  top: 2px;
  fill: #3699ff;
  left: 0;
}
.noti-element{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #ffffff;
    background-clip: border-box;
}
</style>
