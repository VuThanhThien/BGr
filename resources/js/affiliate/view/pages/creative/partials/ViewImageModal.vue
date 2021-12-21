<template>
  <b-modal
    ref="ImageModal"
    :size="form.type == 2 ? 'lg' : 'xl'"
    :title= titlePreview
    hide-footer
  >
    <div class="row">
      <div class="col-lg-7">
        <img
          :src="form.image"
          v-if="form.type == 1"
          class="rounded mx-auto d-block preview-image"
          :alt="form.name"
        />
        <iframe
          width="560"
          height="315"
          v-if="form.type == 3"
          :src="validateLink(form.link)"
          :title="form.name"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
        >
        {{$t('not_sup_iframe')}}</iframe>
        <div class="d-flex" v-if="form.type == 4">
          <span class="text-muted font-size-lg mr-2">{{$t('link')}}:</span>
          <a
            :href="form.link"
            class="font-weight-bolder d-block font-size-lg"
            target="_blank"
            >{{ form.link }}</a
          >
        </div>
        <div class="d-flex" v-if="form.type == 2">
          <span class="text-muted font-size-lg mr-2">{{$t('file_name')}}:</span>
          <span class="font-weight-bolder font-size-lg text-center">{{
            form.file_name
          }}</span>
        </div>
      </div>
      <div class="col-lg-5">
        <table class="table-content">
          <tr>
            <td>
              <span class="text-muted font-size-lg">{{$t('name')}}:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                form.name
              }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">{{$t('description')}}:</span>
            </td>
            <td>
              <span class="font-weight-bolder font-size-lg">{{
                form.description
              }}</span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="text-muted font-size-lg">{{$t('link')}}:</span>
            </td>
            <td>
              <a
                :href="form.link"
                class="font-weight-bolder d-block font-size-lg"
                target="_blank"
                >{{ form.link }}</a
              >
            </td>
          </tr>
        </table>
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
  methods: {
    getId(url) {
      const regExp =
        /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
      const match = url.match(regExp);

      return match && match[2].length === 11 ? match[2] : null;
    },
    validateLink(link) {
      let checkLinkYoutube = this.getId(link);
      if (checkLinkYoutube) {
        link = "https://www.youtube.com/embed/" + checkLinkYoutube;
      }
      return link;
    },
  },
  computed:{
      titlePreview(){
          return this.$t('preview')
      }
  }
};
</script>
<style scoped>
.preview-image {
  width: 100%;
  height: auto;
}
</style>
