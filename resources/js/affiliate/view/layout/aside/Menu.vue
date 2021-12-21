<template>
  <ul class="menu-nav nav-bixgrow">
    <router-link
      to="/dashboard"
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
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Home/Home.svg" />
          </span>
          <span class="menu-text">{{ $t("dashboard") }}</span>
        </a>
      </li>
    </router-link>

    <li class="menu-section">
      <h4 class="menu-text">{{ $t("manage") }}</h4>
    </li>

    <router-link
      to="/commissions"
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
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Cart2.svg" />
          </span>
          <span class="menu-text">{{ $t("conversions") }}</span>
        </a>
      </li>
    </router-link>
    <!-- <router-link
      to="/payouts"
      v-slot="{ href, navigate, isActive, isExactActive }"
    > -->
    <!-- <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[
          isActive && 'menu-item-active',
          isExactActive && 'menu-item-active'
        ]"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Shopping/Credit-card.svg" />
          </span>
          <span class="menu-text">{{ $t('payouts')}}</span>
        </a>
      </li> -->
    <li
      aria-haspopup="true"
      data-menu-toggle="hover"
      class="menu-item menu-item-submenu menu-link"
      v-bind:class="{
        'menu-item-open': hasActiveChildren('/payouts'),
      }"
    >
      <a href="#" class="menu-link menu-toggle">
        <span class="menu-icon svg-icon">
          <inline-svg src="media/svg/icons/Shopping/Credit-card.svg" />
        </span>
        <span class="menu-text"> {{ $t("payouts") }} </span>
        <i class="menu-arrow"></i>
      </a>
      <div class="menu-submenu menu-submenu-classic menu-submenu-right">
        <ul class="menu-subnav">
          <router-link
            to="/payouts/payouts"
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
                <span class="menu-text"> {{ $t("payouts") }} </span>
              </a>
            </li>
          </router-link>
          <router-link
            to="/payouts/store-credit"
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
                <span class="menu-text"> {{ $t("store_credit") }} </span>
              </a>
            </li>
          </router-link>
        </ul>
      </div>
    </li>
    <!-- </router-link> -->
    <router-link
      v-if="isEnableMlm"
      to="/network"
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
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Code/Git4.svg" />
          </span>
          <span class="menu-text">{{ $t("network") }}</span>
        </a>
      </li>
    </router-link>
    <router-link
      to="/creatives"
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
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/Files/Pictures1.svg" />
          </span>
          <span class="menu-text">{{ $t("marketing_resources") }}</span>
        </a>
      </li>
    </router-link>
    <li class="menu-section">
      <h4 class="menu-text">{{ $t("settings") }}</h4>
    </li>
    <router-link
      to="/settings"
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
          <span class="menu-icon svg-icon">
            <inline-svg src="media/svg/icons/General/Settings-2.svg" />
          </span>
          <span class="menu-text">{{ $t("settings") }}</span>
        </a>
      </li>
    </router-link>
  </ul>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "KTMenu",
  methods: {
    hasActiveChildren(match) {
      return this.$route["path"].indexOf(match) !== -1;
    },
  },
  computed: {
    ...mapGetters(["group"]),
    isEnableMlm() {
      return this.group.is_enable_mlm;
    },
  },
};
</script>
<style lang="scss" >
$bixgrow: #ff9900;
.nav-bixgrow > .menu-item > .menu-heading .menu-icon.svg-icon svg g [fill],
.nav-bixgrow > .menu-item > .menu-link .menu-icon.svg-icon svg g [fill] {
  fill: #fff !important;
}
.nav-bixgrow
  > .menu-item.menu-item-active
  > .menu-heading
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item.menu-item-active
  > .menu-link
  .menu-icon.svg-icon
  svg
  g
  [fill] {
  fill: $bixgrow !important;
}
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):hover
  > .menu-heading
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):hover
  > .menu-link
  .menu-icon.svg-icon
  svg
  g
  [fill],
.nav-bixgrow
  > .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):hover
  > .menu-link
  .menu-icon {
  color: $bixgrow !important;
  fill: $bixgrow !important;
}
.nav-bixgrow > .menu-item.menu-item-active > .menu-heading .menu-text,
.nav-bixgrow > .menu-item.menu-item-active > .menu-link .menu-text,
.nav-bixgrow > .menu-item.menu-item-active > .menu-link .menu-icon,
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
  background-color: rgba($color: $bixgrow, $alpha: 0.15) !important;
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
  background-color: $bixgrow !important;
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
  color: $bixgrow !important;
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
  background-color: $bixgrow !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item.menu-item-active
  > .menu-link
  .menu-text {
  color: $bixgrow !important;
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
  background-color: rgba($color: $bixgrow, $alpha: 0.15) !important;
  border-top-right-radius: 40px !important;
  border-bottom-right-radius: 40px !important;
//   width: 227px !important;
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
  background-color: rgba($color: $bixgrow, $alpha: 0.15) !important;
}
.aside-menu
  .menu-nav
  > .menu-item
  .menu-submenu
  .menu-item:not(.menu-item-parent):not(.menu-item-open):not(.menu-item-here):not(.menu-item-active):hover
  > .menu-link
  .menu-text {
  color: $bixgrow !important;
}
.menu-item-active:hover {
  background-position: right center;
  text-decoration: none;
}
.menu-item,
.menu-item > .menu-link:hover {
  border-top-right-radius: 40px !important;
  border-bottom-right-radius: 40px !important;
  width: 245px !important;
}
.nav-bixgrow > .menu-item > .menu-heading .menu-text,
.nav-bixgrow > .menu-item > .menu-link .menu-text {
  font-weight: 600 !important;

}
</style>
