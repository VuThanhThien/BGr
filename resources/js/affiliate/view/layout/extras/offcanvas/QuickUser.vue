<template>
  <div class="topbar-item">
    <div
      class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
      id="kt_quick_user_toggle"
    >
      <span
        class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1"
      >
        {{$t('hi')}},
      </span>
      <span
        class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"
      >
        {{ currentUser.first_name }}
      </span>
      <span class="symbol symbol-35 symbol-light-success">
        <img v-if="false" alt="Pic" :src="currentUser.photo" />
        <span v-if="currentUser.first_name" class="symbol-label font-size-h5 font-weight-bold">
          {{ currentUser.first_name.charAt(0).toUpperCase() }}
        </span>
      </span>
    </div>

    <div
      id="kt_quick_user"
      ref="kt_quick_user"
      class="offcanvas offcanvas-right p-10"
    >
      <!--begin::Header-->
      <div
        class="offcanvas-header d-flex align-items-center justify-content-between pb-5"
      >
        <h3 class="font-weight-bold m-0">
          {{$t('user_profile')}}
        </h3>
        <a
          href="#"
          class="btn btn-xs btn-icon btn-light btn-hover-primary"
          id="kt_quick_user_close"
        >
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Navigation/Close.svg" />
          </span>
        </a>
      </div>
      <!--end::Header-->

      <!--begin::Content-->
      <perfect-scrollbar
        class="offcanvas-content pr-5 mr-n5 scroll"
        style="max-height: 90vh; position: relative;"
      >
        <!--begin::Header-->
        <div class="d-flex align-items-center mt-5">
          <div class="symbol symbol-120 mr-5 symbol-light-success">
            <!-- <img v-if="false" alt="Pic" :src="currentUser.photo" /> -->
            <span v-if="currentUser.first_name"  class="symbol-label font-size-h1 font-weight-bold">
              {{ currentUser.first_name.charAt(0).toUpperCase() }}
            </span>
            <i class="symbol-badge bg-success"></i>
          </div>
          <div class="d-flex flex-column">
            <router-link
              to="/settings"
              class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary mb-9"
            >
              {{ getFullName }}
            </router-link>
            <div class="navi mt-2">
                <span class="navi-link p-0 pb-2">
                  <span class="navi-icon mr-1">
                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                      <!--begin::Svg Icon-->
                      <inline-svg
                        src="media/svg/icons/Communication/Mail-notification.svg"
                      />
                      <!--end::Svg Icon-->
                    </span>
                  </span>
                  <span class="navi-text text-muted">
                    {{ currentUser.email }}
                  </span>
                </span>
            </div>
            <button class="btn btn-light-primary btn-bold" @click="onLogout">
              {{$t('sign_out')}}
            </button>
          </div>
        </div>
        <!--end::Header-->
        <div class="separator separator-dashed mt-8 mb-5"></div>
        <!--begin::Nav-->
      </perfect-scrollbar>
      <!--end::Content-->
    </div>
  </div>
</template>

<style lang="scss" scoped>
#kt_quick_user {
  overflow: hidden;
}
@media screen and (max-width: 992px){
    #kt_quick_user{
        margin-top: 50px;
    }
}
</style>

<script>
import { mapGetters } from "vuex";
import { LOGOUT } from "@/core/services/store/auth.module";
import KTLayoutQuickUser from "@/assets/js/layout/extended/quick-user.js";
import KTOffcanvas from "@/assets/js/components/offcanvas.js";

export default {
  name: "KTQuickUser",
  data() {
    return {

    };
  },
  mounted() {
    // Init Quick User Panel
    KTLayoutQuickUser.init(this.$refs["kt_quick_user"]);
  },
  methods: {
    onLogout() {
      this.$store
        .dispatch(LOGOUT)
        .then(() => this.$router.push({ name: "login" }));
    },
    closeOffcanvas() {
      new KTOffcanvas(KTLayoutQuickUser.getElement()).hide();
    }
  },
  computed: {
    ...mapGetters(["currentUser"]),

    getFullName() {
      return (
        this.currentUser.first_name +
        " " +
        this.currentUser.last_name
      );
    }
  }
};
</script>
