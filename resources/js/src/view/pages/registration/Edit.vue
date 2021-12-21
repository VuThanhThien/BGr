<template>
  <Loader v-if="loaderEnabled" ></Loader>
  <div v-else>
    <div class="card card-btn">
      <div class="d-flex justify-content-between">
        <button class="btn btn-secondary btn-md px-10 my-5 ml-5" @click="back">
        <i class="fas fa-angle-left"></i>
          Back
        </button>
        <div class="d-flex">
        <button class="btn btn-secondary btn-md px-10 my-5 mr-5" @click="openPreview">
          <i class="fa fa-eye " aria-hidden="true"></i>
        <a>Preview</a>
        </button>
        <div class="my-5 mr-5">
          <LoadingSubmitButton :loading="submitLoading" @click="onApplyChange">
            Save
          </LoadingSubmitButton>
        </div>
        </div>
      </div>
    </div>
    <div class="d-lg-flex mt-25 mb-5">
      <!-- Các tab cùng nội dung tab -->
      <div class="col-lg-3">
        <b-tabs card nav-class="nav-tabs-line group-nav">
          <b-tab lazy title="Form">
            <FormTab />
          </b-tab>
          <b-tab lazy title="Text">
            <TextContentTab />
          </b-tab>
          <b-tab title="Design" lazy>
            <DesignTab ref="designTab" />
          </b-tab>
          <b-tab title="Advanced" lazy>
            <Advanced />
          </b-tab>
        </b-tabs>
      </div>
      <div class="col-lg-9">
        <!-- Preview với template mặc định-->
        <div id="sticky">
          <DefaultPreview v-if="this.baseObjToGen.name == 'basicObj'" />
          <TemplateTwo v-if="this.baseObjToGen.name == 'template2Obj'" />
          <TemplateThree v-if="this.baseObjToGen.name == 'template3Obj'" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loader from "aff/view/content/Loader.vue";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import { mapActions, mapGetters } from "vuex";
import { BTabs, BTab } from "bootstrap-vue";
import DesignTab from "@/view/pages/registration/partials/Design/DesignTab.vue";
import FormTab from "@/view/pages/registration/partials/Form/FormTab.vue";
import TextContentTab from "@/view/pages/registration/partials/TextContent/TextContentTab.vue";
import Advanced from "@/view/pages/registration/partials/Advanced.vue";
import DefaultPreview from "@/view/pages/registration/partials/preview/DefaultPreview.vue";
import TemplateTwo from "@/view/pages/registration/partials/preview/TemplateTwo.vue";
import TemplateThree from "@/view/pages/registration/partials/preview/TemplateThree.vue";
import ApiService from "@/core/services/api.service";
export default {
  components: {
    BTabs,
    BTab,
    DesignTab,
    FormTab,
    Advanced,
    DefaultPreview,
    TemplateTwo,
    TextContentTab,
    TemplateThree,
    LoadingSubmitButton,
    Loader
  },
  data() {
    return {
      group:{},
      submitLoading: false,
      baseObjToGen: {},
      loaderEnabled: true,
    };
  },
  methods: {
    ...mapActions(["loadGroup", "changeBaseObj", "updateRegistration"]),
    onApplyChange() {
      this.submitLoading = true;
      let obj = this.baseObjToGen;
      let id = this.id;
      this.changeBaseObj(obj);
      this.updateRegistration({ id, obj })
        .then(() => {
          this.submitLoading = false;
          this.$toast.success("Updated", {
            position: "bottom",
          });
        })
        .catch((respon) => {
          this.$toast.error(respon.data.message, { position: "bottom" });
          this.submitLoading = false;
        });
    },
    customCountry() {
      let array = this.baseObjToGen.schema.fields;
      for (let i = 0; i < array.length; i++) {
        if (array[i].model === "country") {
          this.baseObjToGen.schema.fields[i].type = "select";
          this.baseObjToGen.schema.fields[i].values = [];
          this.baseObjToGen.schema.fields[i].selectOptions = {
            noneSelectedText: "Select country",
          };
        }
      }
    },
    back() {
      window.history.back();
    },
    openPreview(){
        let url;
        if(this.group.is_default == 1){
            url = this.GroupSignupPageDefault;
        }else{
            url = this.GroupSignupPage;
        }
        window.open(url,'_blank');
    }
  },
  computed: {
    ...mapGetters(["subDomain"]),
    id() {
      return this.$route.params.id;
    },
    GroupSignupPageDefault() {
      return (
        "https://" +
        this.subDomain +
        "." +
        process.env.MIX_BASE_DOMAIN +
        "/#/register"
      );
    },
    GroupSignupPage() {
      return this.GroupSignupPageDefault + "/" + this.group.slug;
    },
  },
  created() {
    this.loaderEnabled = true;
    /** Lấy layout data để lấy logo, brandname, format money,... */
    this.$store.dispatch("loadLayoutData").then(({ data }) => {
        this.loaderEnabled = false;
      });
    /** load group hiện tại lên store
     * @param: id trên router
     */
    ApiService.get("groups", this.id)
      .then(({ data }) => {
        this.group = data.data;
        /**Cập nhật group vào store */
        this.$store.commit("LOAD_GROUP_OK", data.data);
        // lấy setting template lưu trên db gán vào store
        this.baseObjToGen = data.data.registration_form;
        this.changeBaseObj(this.baseObjToGen);
        //TODO: Nếu có sửa trường nào trước khi vào registration form thì sửa trước ở đây (vtthien)
        this.customCountry();
      })
      .catch(({ response }) => {
        this.$toast.error(response.data.message, { position: "bottom" });
      });
  },
};
</script>
<style scoped>
.card {
  box-shadow: none !important;
  border-radius: unset !important;
}
.card-btn {
  position: fixed;
  top: 0px;
  right: 0;
  left: 0;
  z-index: 97;
}
.tab-content > .active {
  padding: 0 !important;
}
</style>
<style lang="scss" >
@media screen and (max-width: 992px){
    .col-lg-3 .tabs .tab-content{
        height: 500px ;
        overflow: auto ;
    }
}
.font-picker ul.expanded {
  z-index: 3;
}
.one-colorpicker {
  .color-block {
    border: 2px solid #d9d9d9;
  }
}
.vue-form-gen {
  min-height: 400px;
  width: 100%;
}
.panel-body {
  border-radius: 5px;
  .wrapper {
    padding: 0 !important;
  }
  width: 50%;
}
.label-detail {
  font-weight: 600;
  width: 150px;
}
.content-detail {
  display: inline;
}


#sticky {
  position: sticky;
  position: -webkit-sticky;
  top: 80px;
}
.fullW > button {
  width: 100% !important;
}
@media screen and (max-width: 600px) {
  .panel-body {
    width: 100%;
    padding: 1rem;
  }
}
.group-nav > .nav-item > .nav-link.active, .nav-link:hover{
    color: #7E8299;
}
.group-nav .nav-link:hover:not(.disabled), .nav.nav-tabs.nav-tabs-line .nav-link.active, .nav.nav-tabs.nav-tabs-line .show > .nav-link{
    border-bottom: 1px solid #7E8299 !important;
    color : #7E8299 !important;
}
</style>
