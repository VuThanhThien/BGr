<template>
  <div>
    <div
      class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
      :style="{ backgroundImage: `url(${backgroundImage})` }"
    >
      <h4 class="d-flex flex-center rounded-top">
        <span class="text-white">Chat with your team</span>
      </h4>
    </div>
      <div>
        <perfect-scrollbar
          class="navi navi-hover scroll my-4"
          style="max-height: 40vh; position: relative;"
        >
          <template v-for="(item, i) in list2">
            <a href="#" class="navi-item" v-bind:key="i">
              <div class="navi-link">
                <div class="navi-icon mr-2">
                  <i v-bind:class="item.icon"></i>
                </div>
                <div class="navi-text">
                  <div class="font-weight-bold">
                    {{ item.title }}
                  </div>
                  <div class="text-muted">
                    {{ item.desc }}
                  </div>
                </div>
              </div>
            </a>
          </template>
        </perfect-scrollbar>
      </div>

  </div>
</template>

<style lang="scss">
/* hide default vue-bootstrap tab links */
.hide-tabs > div:not(.tab-content) {
  display: none;
}
</style>

<script>
export default {
  name: "KTDropdownNotification",
  data() {
    return {
      list2: [
        {
          title: "New report has been received",
          desc: "23 hrs ago",
          icon: "far fa-bell text-success"
        },
        {
          title: "Finance report has been generated",
          desc: "25 hrs ago",
          icon: "far fa-bell text-danger"
        },
        {
          title: "New order has been received",
          desc: "2 hrs ago",
          icon: "far fa-bell text-success"
        },
        {
          title: "New customer is registered",
          desc: "3 hrs ago",
          icon: "far fa-bell text-primary"
        },
      ]
    };
  },
  methods: {
    setActiveTab(event) {
      const tab = event.target.closest('[role="tablist"]');
      const links = tab.querySelectorAll(".nav-link");
      // remove active tab links
      for (let i = 0; i < links.length; i++) {
        links[i].classList.remove("active");
      }

      // set clicked tab index to bootstrap tab
      this.tabIndex = parseInt(event.target.getAttribute("data-tab"));

      // set current active tab
      event.target.classList.add("active");
    }
  },
  computed: {
    backgroundImage() {
      return  "media/misc/bg-1.jpg";
    }
  }
};
</script>
