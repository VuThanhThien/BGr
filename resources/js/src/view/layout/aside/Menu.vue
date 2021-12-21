<template>
  <ul class="menu-nav nav-bixgrow">
    <!-- quick-start -->
    <router-link
      v-if="this.is_done_started != 1"
      to="/quick-start"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Home/Bulb1.svg" />
          </span>
          <span class="menu-text">Quick Start</span>
        </a>
      </li>
    </router-link>
    <!-- dashboard -->
    <router-link
      to="/dashboard"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Home/Home.svg" />
          </span>
          <span class="menu-text">Dashboard</span>
        </a>
      </li>
    </router-link>

    <!-- <router-link
    v-if="this.is_done_started != 1"
    to="/todo-list"
    v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[
          isActive && 'btn-grad',
          isExactActive && 'btn-grad'
        ]"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Communication/Clipboard-check.svg" />
          </span>
          <span class="menu-text">To-do list</span>
        </a>
      </li>
    </router-link> -->

    <li class="menu-section">
      <h4 class="menu-text">Setup</h4>
    </li>
    <!-- Programs -->
    <router-link
      to="/programs"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Box2.svg" />
          </span>
          <span class="menu-text">Programs</span>
        </a>
      </li>
    </router-link>
    <!-- coupons -->
    <router-link
      to="/coupons"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Price1.svg" />
          </span>
          <span class="menu-text">Coupons</span>
        </a>
      </li>
    </router-link>

    <!-- Customize -->
    <li
      aria-haspopup="true"
      data-menu-toggle="hover"
      class="menu-item menu-item-submenu menu-link"
      v-bind:class="{
        'menu-item-open': hasActiveChildren('/customize'),
      }"
    >
      <a href="#" class="menu-link menu-toggle">
        <i class="menu-icon fas fa-file-signature"></i>
        <span class="menu-text">Affiliate Portal</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="menu-submenu menu-submenu-classic menu-submenu-right">
        <ul class="menu-subnav">
          <router-link
            to="/customize/affiliate-registration"
            v-slot="{ href, navigate, isActive, isExactActive }"
          >
            <li
              aria-haspopup="true"
              data-menu-toggle="hover"
              class="menu-item"
              :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active',
              ]"
            >
              <a :href="href" class="menu-link" @click="navigate">
                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                <span class="menu-text">Affiliate Registration</span>
              </a>
            </li>
          </router-link>
          <router-link
            to="/customize/affiliate-login"
            v-slot="{ href, navigate, isActive, isExactActive }"
          >
            <li
              aria-haspopup="true"
              data-menu-toggle="hover"
              class="menu-item"
              :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active',
              ]"
            >
              <a :href="href" class="menu-link" @click="navigate">
                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                <span class="menu-text">Affiliate Login</span>
              </a>
            </li>
          </router-link>
        </ul>
      </div>
    </li>

    <!-- Notification -->
    <router-link
      to="/email-templates"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/General/Notifications1.svg" />
          </span>
          <span class="menu-text">Notification</span>
        </a>
      </li>
    </router-link>

    <li class="menu-section">
      <h4 class="menu-text">Manage</h4>
    </li>
    <!-- affiliates -->
    <router-link
      to="/affiliates"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/General/User.svg" />
          </span>
          <span class="menu-text">Affiliates</span>
        </a>
      </li>
    </router-link>

    <!-- Conversion -->
    <router-link
      to="/sales"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Cart2.svg" />
          </span>
          <span class="menu-text">Conversions</span>
        </a>
      </li>
    </router-link>
    <!-- payouts -->
    <router-link
      to="/payouts"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Credit-card.svg" />
          </span>
          <span class="menu-text">Payouts</span>
        </a>
      </li>
    </router-link>
    <!-- creatives -->
    <router-link
      to="/creatives"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Files/Pictures1.svg" />
          </span>
          <span class="menu-text">Creatives</span>
        </a>
      </li>
    </router-link>
    <li class="menu-section">
      <h4 class="menu-text">Tools</h4>
    </li>
    <!-- Email -->
    <li
      aria-haspopup="true"
      data-menu-toggle="hover"
      class="menu-item menu-item-submenu menu-link"
      v-bind:class="{
        'menu-item-open': hasActiveChildren('/emails'),
      }"
    >
      <a href="#" class="menu-link menu-toggle">
        <span class="menu-icon svg-icon">
          <inline-svg src="media/svg/icons/Communication/Mail.svg" />
        </span>
        <span class="menu-text"> Email </span>
        <i class="menu-arrow"></i>
      </a>
      <div class="menu-submenu menu-submenu-classic menu-submenu-right">
        <ul class="menu-subnav">
          <!-- bulk email -->
          <router-link
            to="/emails/bulk-email"
            v-slot="{ href, navigate, isActive, isExactActive }"
          >
            <li
              aria-haspopup="true"
              data-menu-toggle="hover"
              class="menu-item"
              :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active',
              ]"
            >
              <a :href="href" class="menu-link" @click="navigate">
                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                <span class="menu-text"> Bulk Email </span>
              </a>
            </li>
          </router-link>
          <!-- mail outbox -->
          <router-link
            to="/emails/mail-outbox"
            v-slot="{ href, navigate, isActive, isExactActive }"
          >
            <li
              aria-haspopup="true"
              data-menu-toggle="hover"
              class="menu-item"
              :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active',
              ]"
            >
              <a :href="href" class="menu-link" @click="navigate">
                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                <span class="menu-text"> Mail Outbox </span>
              </a>
            </li>
          </router-link>
        </ul>
      </div>
    </li>
    <!-- multi-level marketing -->
    <router-link
      to="/multi-level"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <i class="menu-icon fas fa-network-wired"></i>
          <span class="menu-text">Multi-level Marketing</span>
        </a>
      </li>
    </router-link>
    <!-- checkout-popup -->
    <router-link
      to="/checkout-popup"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Communication/Chat2.svg" />
          </span>
          <span class="menu-text">Post Purchase Popup</span>
        </a>
      </li>
    </router-link>
    <!-- affiliate-tier -->
    <router-link
      to="/affiliate-tier"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Cooking/Dishes.svg" />
          </span>
          <span class="menu-text">Affiliate Tiers</span>
        </a>
      </li>
    </router-link>

    <li class="menu-section">
      <h4 class="menu-text">Settings</h4>
    </li>
    <!-- setting -->
    <router-link
      to="/settings"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[isActive && 'btn-grad', isExactActive && 'btn-grad']"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon svg-icon-bixgrow">
            <inline-svg src="media/svg/icons/General/Settings-2.svg" />
          </span>
          <span class="menu-text">Settings</span>
        </a>
      </li>
    </router-link>

    <!-- help -->
    <li aria-haspopup="true" data-menu-toggle="hover" class="menu-item mt-10">
      <a
        class="menu-link d-flex flex-column"
        style="background-color: transparent !important"
      >
        <i
          class="menu-icon pl-1 far fa-bookmark help-item"
          style="z-index: 2"
        ></i>
        <div class="menu-text doc-wrapper">
          <div
            class="
              card card-background
              shadow-none
              card-background-mask-secondary
            "
          >
            <div
              class="full-background"
              style="background-image: url('/media/bg/bg-9.jpg')"
            ></div>
            <div class="card-body text-start p-3 w-100 mt-5" style="z-index: 2">
              <div
                class="
                  icon icon-shape icon-sm
                  bg-white
                  shadow
                  text-center
                  mb-3
                  d-flex
                  align-items-center
                  justify-content-center
                  border-radius-md
                "
              >
                <i
                  class="ni ni-diamond text-dark text-gradient text-lg top-0"
                  aria-hidden="true"
                ></i>
              </div>
              <div class="docs-info mt-5">
                <h6 class="text-dark font-size-h3 mb-0">Need help?</h6>
                <p class="text-dark font-weight-bold">
                  Visit our document or directly chat with us
                </p>
                <a
                  href="https://docs.bixgrow.com/"
                  target="_blank"
                  class="
                    btn btn-white btn-sm
                    w-100
                    mb-3
                    text-dark
                    font-weight-bolder
                  "
                  >Documentation</a
                >
                <button
                  @click="openChat"
                  class="
                    btn btn-white btn-sm
                    w-100
                    mb-3
                    text-dark
                    font-weight-bold
                  "
                >
                  Chat
                </button>
              </div>
            </div>
          </div>
        </div>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  name: "KTMenu",
  methods: {
    hasActiveChildren(match) {
      return this.$route["path"].indexOf(match) !== -1;
    },
    openChat() {
      $crisp.push(["do", "chat:open"]);
    },
  },
  computed: {
    is_skip_started() {
      return this.$store.state.layout.settings.is_skip_started;
    },
    is_done_started() {
      return this.$store.state.layout.settings.is_done_started;
    },
  },
};
</script>
<style lang="scss">
.doc-wrapper {
  position: absolute;
  width: 200px;
  margin-left: -10px;
}
.card.card-background .full-background {
  background-position: 50%;
  background-size: cover;
  margin-bottom: 30px;
  width: 100%;
  height: 100%;
  position: absolute;
  border-radius: inherit;
}
.docs-info {
  transition: all 0.2s ease-in-out;
}
.card-background-mask-secondary:after {
  background-image: linear-gradient(310deg, #627594, #a8b8d8);
}
$bixgrow: #ff9900;

.nav-bixgrow > .menu-item > .menu-heading .menu-text,
.nav-bixgrow > .menu-item > .menu-link .menu-text {
  font-weight: 600 !important;

}
.nav-bixgrow > .menu-item > .menu-heading .menu-icon.svg-icon svg g [fill],
.nav-bixgrow > .menu-item > .menu-link .menu-icon.svg-icon svg g [fill] {
  fill: #fff !important;
}

.nav-bixgrow
  > .menu-item.btn-grad
  > .menu-heading
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item.btn-grad
  > .menu-link
  .menu-icon.svg-icon
  svg
  g
  [fill] {
  fill: $bixgrow !important;
}
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.btn-grad):hover
  > .menu-heading
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.btn-grad):hover
  > .menu-link
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.btn-grad):hover
  > .menu-link
  .menu-icon {
  color: $bixgrow !important;
  fill: $bixgrow !important;
}
.nav-bixgrow > .menu-item.btn-grad > .menu-heading .menu-text,
.nav-bixgrow > .menu-item.btn-grad > .menu-link .menu-text,
.nav-bixgrow > .menu-item.btn-grad > .menu-link .menu-icon,
.nav-bixgrow > .menu-item > .menu-link .menu-text:hover,
.nav-bixgrow > .menu-item > .menu-link .menu-icon:hover,
.nav-bixgrow > .menu-item > .menu-heading .menu-text:hover,
.nav-bixgrow > .menu-item:hover > .menu-link > .menu-icon {
  color: $bixgrow !important;
}
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-heading,
.aside-menu
  .menu-nav
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link {
  background-color: rgba($color: #ff9900, $alpha: 0.15) !important;
}
.btn-grad {
  background-color: rgba($color: #ff9900, $alpha: 0.15) !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-heading
  .menu-bullet.menu-bullet-dot
  > span,
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link
  .menu-bullet.menu-bullet-dot
  > span {
  background-color: #ff9900 !important;
}
.aside-menu
  .menu-nav
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-heading
  .menu-text,
.aside-menu
  .menu-nav
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link
  .menu-text {
  color: #ff9900 !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-heading
  .menu-bullet.menu-bullet-dot
  > span,
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-link
  .menu-bullet.menu-bullet-dot
  > span {
  background-color: #ff9900 !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-link
  .menu-text {
  color: #ff9900 !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-heading,
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-link {
  background-color: rgba($color: #ff9900, $alpha: 0.15) !important;
  border-top-right-radius: 40px !important;
  border-bottom-right-radius: 40px !important;
  width: 227px !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-heading,
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link {
  background-color: rgba($color: #ff9900, $alpha: 0.15) !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link
  .menu-text {
  color: #ff9900 !important;
}
.btn-grad:hover {
  background-position: right center;
  text-decoration: none;
}
.help-item:hover,
.menu-item:hover > .menu-link .help-item {
  color: #ff9900 !important;
}
</style>
<style scoped>
.menu-section{
    margin: 0 !important;
    height: 30px !important;
}
.menu-item,
.menu-item > .menu-link:hover {
  border-top-right-radius: 40px !important;
  border-bottom-right-radius: 40px !important;
  width: 227px !important;
}
a {
  transition: none !important;
}
.aside-menu .menu-nav > .menu-item > .menu-link .menu-icon,
.aside-minimize:not(.aside-minimize-hover) .aside-menu .menu-nav > .menu-item > .menu-link > .menu-icon,
.aside-menu .menu-nav > .menu-item.menu-item-open > .menu-link .menu-icon {
  color: #fff;
}
</style>
