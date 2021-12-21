<template>
  <b-modal ref="ImageModal" size="lg" :title="shareSocial" hide-footer>
    <div class="card card-custom gutter-b card-stretch">
      <div
        class="card-header align-items-center border-0"
        style="border-bottom: 1px solid #e8e9f2 !important"
      >
        <h4 class="card-title align-items-start flex-column">
          <span class="font-weight-bolder text-dark">{{ form.name }}</span>
        </h4>
      </div>
      <div class="card-body pt-0 mt-5">
        <h5 class="card-title align-items-start flex-column">
          <span class="font-weight-bolder text-dark">
            {{ $t("share_link") }}:
          </span>
        </h5>
        <img
          :src="form.image"
          v-if="form.type == 1"
          class="rounded mx-auto d-block preview-image"
          :alt="form.name"
        />
        <div class="d-flex mt-2">
          <input
            type="text"
            disabled
            class="form-control form-control-solid me-3 flex-grow-1"
            ref="aff_link_input"
            name="search"
            v-model="affLink"
          />
          <button
            class="btn btn-primary fw-bolder flex-shrink-0 ml-1"
            @click="copyGenerateUrl"
          >
            {{ $t("copy") }}
          </button>
        </div>
      </div>
      <div class="card-body pt-0 mt-10">
        <h6 class="card-title align-items-start flex-column">
          <span class="font-weight-bolder text-dark"
            >{{ $t("share_on_social_media") }}
          </span>
        </h6>
        <div class="d-flex mt-2 flex-wrap" style="gap: 5px">
          <div class="symbol-label">
            <a
              :href="'https://www.facebook.com/sharer.php?u=' + affLink"
              data-toggle-t="tooltip"
              data-placement="top"
              data-original-title="Share on Facebook"
              class="btn btn-icon btn-facebook btn-sm"
              target="_blank"
            >
              <i class="fab fa-facebook-f text-white"></i>
            </a>
          </div>
          <div class="symbol-label">
            <a
              :href="'https://twitter.com/intent/tweet?url=' + affLink"
              data-toggle-t="tooltip"
              data-placement="top"
              data-original-title="Share on Facebook"
              class="btn btn-icon btn-twitter btn-sm"
              target="_blank"
            >
              <i class="fab fa-twitter text-white"></i>
            </a>
          </div>
          <div class="symbol-label">
            <a
              :href="
                'https://www.linkedin.com/shareArticle?mini=true&url=' + affLink
              "
              data-toggle-t="tooltip"
              data-placement="top"
              data-original-title="Share on Facebook"
              class="btn btn-icon btn-linkedin btn-sm"
              target="_blank"
            >
              <i class="fab fa-linkedin-in text-white"></i>
            </a>
          </div>
          <div class="symbol-label">
            <a
              :href="'http://pinterest.com/pin/create/link/?url=' + affLink"
              data-toggle-t="tooltip"
              data-placement="top"
              data-original-title="Share on Facebook"
              class="btn btn-icon btn-pinterest btn-sm"
              target="_blank"
            >
              <i class="fab fa-pinterest-p text-white"></i>
            </a>
          </div>
        </div>
        <div></div>
      </div>
    </div>
  </b-modal>
</template>
<script>
import { BModal } from "bootstrap-vue";
export default {
  props: ["form"],
  components: {
    BModal,
  },
  computed: {
    affLink() {
      let link = ``;
      if (this.form.type == 1) {
        link =
          "https://app." +
          process.env.MIX_BASE_DOMAIN +
          "/creatives/" +
          this.form.id +
          "/" +
          this.$store.getters.currentUser.hash_code +
          "/" +
          this.$store.getters.currentUser.group_id;
      } else {
        link = `${this.generateLinkAffiliate(this.form.link)}${
          this.$store.getters.currentUser.group_id
        }:${this.$store.getters.currentUser.hash_code}`;
      }
      return link;
    },
    shareSocial() {
      return this.$t("share_social");
    },
  },
  methods: {
    validateLink(link) {
      let index = link.indexOf("https://www.youtube.com");
      if (index > -1) {
        let linkLast = link.lastIndexOf("watch?v=");
        if (linkLast > -1) {
          let codeLink = link.slice(linkLast + 8, linkLast + 19);
          link = "https://www.youtube.com/embed/" + codeLink;
        }
      }
      return link;
    },
    copyGenerateUrl(e) {
      var a = e.target.textContent;
      e.target.textContent = this.$t('copied');
      setTimeout(() => {
        e.target.textContent = a;
      }, 1500);
      var input = document.createElement("textarea");
      document.body.appendChild(input);
      input.value = this.affLink;
      input.select();
      input.focus();
      document.execCommand("Copy");
      input.remove();
    },
  },
};
</script>
<style scoped>
.preview-image {
  width: 150px;
  height: auto;
}
</style>
