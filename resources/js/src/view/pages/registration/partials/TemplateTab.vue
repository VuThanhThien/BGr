<template>
  <div class="form-group d-flex flex-column" style="gap: 1rem">
    <label class="font-weight-bolder mt-5"
      >Choose a template for your registration page</label
    >
    <div class="form-group d-lg-flex">
      <div class="carousel-main col-lg-4 col-sm-12 mx-1" v-for="item in carouselObj" :key="item.id">
        <div
          class="card"
          @click="change_Obj(item.id)"
          :class="{ active: item.name == active_el }"
        >
          <div class="checkmark" v-if="item.name == active_el">
            <span class="label label-success label-inline font-weight-bolder mr-2">Selected</span>
          </div>
          <div class="regisHeight d-flex flex-column p-5 align-items-center content">
            <img :src="item.src" class="img-template" alt="" />
            <div class="cardSelectText w-100">
              <p class="cardSelectHeadline font-size-h2 text-center">
                {{ item.headLine }}
              </p>
              <span class="cardSelectCaption" v-html="item.caption"></span>
            </div>
        <a class="text-center">Select and customize</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import {  mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      active_el: "",
      /**Object lưu loại template để chọn */
      carouselObj: [
        {
          id: 0,
          name: "basicObj",
          src: "media/image-template-form/Template1.png",
          headLine: "Minimal",
          caption:
            '<ul>\n<li><span">Single centered column design</span></li>\n<li><span">Slim and minimal layout</span></li>\n<li><span">One column for registration form</span></li>\n<li><span">Smaller screen display</span></li>\n</ul>',
        },
        {
          id: 1,
          name: "template2Obj",
          src: "media/image-template-form/Template2.png",
          headLine: "Typical",
          caption:
            '<ul>\n<li><span">Two-column design</span></li>\n<li><span">Left column for program details, brand description, etc</span></li>\n<li><span">Right column for registration form</span></li>\n<li><span">Smaller screen display</span></li>\n</ul>',
        },
        {
          id: 2,
          name: "template3Obj",
          src: "media/image-template-form/Template3.png",
          headLine: "Trendy",
          caption:
            '<ul>\n<li><span">Two-column design</span></li>\n<li><span">Left column for registration form</span></li>\n<li><span">Right column for background image with program details, brand description, etc</span></li>\n<li><span">Full screen display</span></li>\n</ul>',
        },
      ],
    };
  },
  methods: {
    ...mapActions(["changeBaseObj", "updateRegistration"]),
    /**
     Hàm này đổi trường name của object => đổi template
     @param id từ router
     @param id của template trong mảng template(data()) để lấy tên
     */
    change_Obj(carouselid) {
      this.active_el = this.carouselObj[carouselid].name;
      this.baseObjToGen.name = this.carouselObj[carouselid].name;
      var obj = this.baseObjToGen;
      var id = this.id;
      this.changeBaseObj(obj);
      this.updateRegistration({ id, obj }).then(()=>{
        this.$router.push({
            name: "registration.customize",
            params: { id: this.id },
        });
      });
    },
  },
  computed: {
    ...mapGetters(["baseObjToGen"]),
    id() {
      return this.$route.params.id;
    },
  },
  created() {
    ApiService.get("groups", this.id)
      .then(
        ({data}) => {
            this.changeBaseObj(data.data.registration_form);
            this.active_el = this.baseObjToGen.name;
        })
      .catch(({ response }) => {
          this.$toast.error(response.data.message, { position: "bottom" });
        });

  },
  watch: {
    baseObjToGen() {
      this.active_el = this.baseObjToGen.name;
    },
  },
};
</script>

<style scoped>
.active {
  border: 1px solid #1BC5BD !important;
}
.carousel-main {
  min-height: 200px;
}
.img-template {
  height: auto !important;
  width: 100% !important;
  max-width: 400px;
  border: 1px solid #d9d9d9;
  border-radius: 5px;
}
.content {
  border-radius: inherit;
  box-shadow: 0 0 9px 0 rgb(0 0 0 / 20%);
  border-radius: inherit;
}
.content:hover {
  background-color: #e2e2e2;
}
.card {
  cursor: pointer;
  position: relative;
  box-shadow: none !important;
}
.checkmark {
  position: absolute;
  right: 5px;
  top: 5px;
}
.cardSelectText {
  margin-left: 5px;
  height: 250px;
  overflow: hidden;
}
.cardSelectHeadline {
  font-size: 1.2rem;
  color: #55667d;
  margin: 6px;
  margin-left: 0;
}
.cardSelectCaption {
  color: #55667d;
}
.regisHeight{
    height: 400px;
}
@media screen and (max-width: 992px) {
.cardSelectText {
  display: none;
}
.regisHeight{
    height: auto;
}
}
</style>
