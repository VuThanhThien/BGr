<template>
  <div>
    <div class="skip">
        <a class="menu-link" @click="navigate">
          <span class="menu-text">Skip Setup</span>
        </a>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
export default {
  methods: {
    navigate() {
      ApiService.put("/settings/skip_started")
      .then(() =>{this.$router.push('/dashboard');})
      .catch(({ response }) => {
        console.log(response);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.skip {
  position: fixed;
  top: 30px;
  right: 30px;
  z-index: 5;
  cursor: pointer;
}
</style>
