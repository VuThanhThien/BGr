<template>
  <div class="setting-main">
    <div class="flex-row-auto" id="kt_profile_aside">
      <div class="card card-custom card-stretch">
        <div class="card-body pt-4">
          <!--begin::Nav-->
          <div
            class="navi navi-bold navi-hover navi-active navi-link-rounded"
            role="tablist"
          >
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.general' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg src="media/svg/icons/Design/Layers.svg" />
                  </span>
                </span>
                <span class="navi-text font-size-lg">General</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.payment' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg
                      src="media/svg/icons/Layout/Layout-top-panel-6.svg"
                    />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Payment</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.coupons' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg
                      style="fill: red"
                      src="media/svg/icons/Shopping/Price1.svg"
                    />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Coupons</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.notification' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg
                      src="/media/svg/icons/General/Notifications1.svg"
                    />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Notifications</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.embedded-portal' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg src="/media/svg/icons/Code/Code.svg" />
                  </span>
                </span>
                <span class="navi-text font-size-lg"
                  >Embedded Affiliate Portal</span
                >
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.integration' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg src="/media/svg/icons/Code/Git3.svg" />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Integration</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.advanced' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg src="/media/svg/icons/General/Settings-2.svg" />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Advanced</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.term_condition' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <inline-svg src="/media/svg/icons/Files/File-done.svg" />
                  </span>
                </span>
                <span class="navi-text font-size-lg">Terms & Conditions</span>
              </router-link>
            </div>
            <div class="navi-item mb-2">
              <router-link
                :to="{ name: 'settings.account_language' }"
                class="navi-link py-4"
                style="cursor: pointer"
                active-class="active"
              >
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <i class="fas fa-language"></i>
                  </span>
                </span>
                <span class="navi-text font-size-lg"
                  >Affiliate Portal Language</span
                >
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
      <router-view></router-view>
    </div>
    <!--end::Content-->
  </div>
</template>

<script>
export default {};
</script>

<style>
.setting-main {
  display: flex;
}
@media only screen and (max-width: 992px) {
  .setting-main {
    flex-direction: column;
    gap: 25px;
  }
}
</style>
