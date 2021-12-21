<template>
  <b-modal ref="ImageModal" size="xl" :title="getTextLocale('preview')" hide-footer>
    <div class="row">
      <div class="col-lg-4">
        <img
          :src="form.image"
          class="rounded mx-auto d-block preview-image"
          :alt="form.name"
        />
      </div>
      <div class="col-lg-8">
        <div class="card card-custom gutter-b card-stretch">
          <div
            class="card-header align-items-center border-0"
            style="border-bottom: 1px solid #e8e9f2 !important"
          >
            <h4 class="card-title align-items-start flex-column">
              <span class="font-weight-bolder text-dark">{{form.name}}</span>
            </h4>
          </div>
          <div class="card-body pt-0 mt-10">
            <h5 class="card-title align-items-start flex-column">
              <span class="font-weight-bolder text-dark"
                > {{$t('embed_code')}}:
              </span>
            </h5>
            <div class="d-flex">
              <textarea rows="3"
                type="text"
                disabled
                class="form-control form-control-solid me-3 flex-grow-1"
                ref="aff_link_input"
                name="search"
                v-model="affLink"
              />

            </div>
            <div class="d-flex mt-2">
             <span class="text-muted font-size-lg">{{$t('get_embed_code_guide')}}</span>

            </div>
            <div class="d-flex mt-2" >
                <button
                class="btn btn-primary fw-bolder flex-shrink-0 ml-1"
                @click="copy('aff_link_input', $event)"
              >
                {{$t('copy')}}
              </button>
            </div>
          </div>
        </div>
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
  computed:{
      affLink(){
        let link='';
          if(this.form.link)
          {
              link =`<a href='${this.generateLinkAffiliate(this.form.link)}${this.$store.getters.currentUser.group_id}:${this.$store.getters.currentUser.hash_code}'
              target='_blank'><img alt='${this.form.name}' src='${this.form.image}' /></a>`;
          }
          else{
             link =`<img alt='${this.form.name}' src='${this.form.image}' />`;
          }

          return link;
      }
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
  },
};
</script>
<style scoped>
.preview-image {
  width: 100%;
  height: auto;
}
</style>
