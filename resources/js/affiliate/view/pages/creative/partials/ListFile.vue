<template>
  <div>
    <div class="d-flex flex-column flex-grow-1">
      <div class="card card-custom gutter-b">
        <!--begin::Body-->
        <div class="card-body">
          <div class="row align-items-center filter-container">
            <div class="col-lg-12 col-xl-12">
              <div class="row align-items-center">
                <div class="col-md-6 my-2 my-md-0">
                  <b-form-input
                    :placeholder="getTextLocale('type_to_search_media_name')"
                    class="form-control-custom form-control"
                    v-model="name"
                    @input="searchName"
                  >
                  </b-form-input>
                </div>
                <div class="col-md-3 my-2 my-md-0">
                  <select
                    class="form-control form-control-custom"
                    v-model="type"
                    @change="typeChange"
                  >
                    <option value="">{{$t('all_assets')}}</option>
                    <option value="1">{{$t('images')}}</option>
                    <option value="3">{{$t('videos')}}</option>
                    <option value="2">{{$t('files')}}</option>
                    <option value="4">{{$t('link')}}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--end::Body-->
    </div>
    <b-overlay
      :show="loadingFilesTable"
      style="min-height: 281px"
      id="overlay-background"
      rounded="sm"
    >
      <div class="row">
        <template v-if="files.data.length > 0">
          <div
            class="col-xl-4 col-lg-6 col-md-6 col-sm-6"
            v-for="(file, index) in files.data"
            :key="index"
          >
            <div class="card card-custom gutter-b card-stretch overlay">
              <div class="card-body">
                <div
                  class="d-flex flex-column align-items-center overlay-wrapper"
                >
                  <!--begin: Icon-->
                  <img
                    alt=""
                    class="max-h-150px"
                    style="width: auto; height: auto"
                    v-if="file.type == 1"
                    :src="file.image"
                  />
                  <iframe
                    height="150px"
                    width="100%"
                    v-if="file.type == 3"
                    :src="validateLink(file.link)"
                    :title="file.name"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  >
                    {{$t('not_sup_iframe')}}</iframe
                  >
                  <img
                    alt=""
                    class="max-h-150px"
                    v-if="extensionSupport.includes(file.extension)"
                    src="/media/svg/files/zip.svg"
                  />
                  <img
                    alt=""
                    class="max-h-150px"
                    v-else-if="extensionSupportWord.includes(file.extension)"
                    src="/media/svg/files/doc.svg"
                  />
                  <img
                    alt=""
                    class="max-h-150px"
                    v-else-if="extensionSupportExcel.includes(file.extension)"
                    src="/media/svg/files/csv.svg"
                  />
                  <img
                    alt=""
                    class="max-h-150px"
                    v-else-if="extensionSupportPdf.includes(file.extension)"
                    src="/media/svg/files/pdf.svg"
                  />
                  <!-- <img alt="" class="max-h-150px" v-else-if="file.type==2"  src="/media/svg/icons/files/Uploaded-file.svg" /> -->
                  <span
                    class="svg-icon svg-icon svg-icon-10x svg-icon-primary"
                    v-else-if="file.type == 2"
                  >
                    <inline-svg src="/media/svg/icons/Files/File.svg" />
                  </span>
                  <img
                    alt=""
                    class="max-h-150px"
                    v-if="file.type == 4"
                    src="/media/svg/files/html.svg"
                  />
                  <!--end: Icon-->
                  <!--begin: Tite-->
                  <a class="text-dark-75 font-weight-bold mt-15 font-size-lg">{{
                    file.name
                  }}</a>
                  <!--end: Tite-->
                </div>
                <div
                  class="overlay-layer align-items-end justify-content-center"
                  style="background-color: rgb(0 0 0 / 50%)"
                >
                  <div class="d-flex flex-column flex-center">
                    <div class="d-flex flex-column flex-center">
                      <span
                        class="label label-xl label-dark label-inline mb-2"
                        v-if="file.file_name"
                        >{{ file.file_name }}</span
                      >
                      <div></div>
                    </div>
                    <div class="d-flex flex-center bg-white-o-5 py-5">
                      <button
                        @click="ViewImageModal(file)"
                        class="
                          btn btn-icon btn-light btn-hover-primary btn-sm
                          mr-2
                        "
                        v-b-tooltip.hover
                        :title="getTextLocale('preview')"
                      >
                        <span class="svg-icon svg-icon-xl svg-icon-dark">
                          <inline-svg
                            src="media/svg/icons/General/Visible.svg"
                          />
                        </span>
                      </button>
                      <button
                        v-if="file.type == 1"
                        @click="ViewEmbededModal(file)"
                        class="
                          btn btn-icon btn-light btn-hover-primary btn-sm
                          mr-2
                        "
                        v-b-tooltip.hover
                        :title="getTextLocale('get_embeded_code')"
                      >
                        <span class="svg-icon svg-icon-xl svg-icon-dark">
                          <inline-svg src="media/svg/icons/Code/Code.svg" />
                        </span>
                      </button>

                      <button
                        @click="ViewSocialModal(file)"
                        v-if="file.type != 2"
                        class="
                          btn btn-icon btn-light btn-hover-primary btn-sm
                          mr-2
                        "
                        v-b-tooltip.hover
                        :title="getTextLocale('share_social')"
                      >
                        <span class="svg-icon svg-icon-xl svg-icon-dark">
                          <inline-svg
                            src="media/svg/icons/Communication/Share.svg"
                          />
                        </span>
                      </button>
                      <a
                        :href="downLoad(file.id)"
                        class="
                          btn btn-icon btn-light btn-hover-primary btn-sm
                          mr-2
                        "
                        v-b-tooltip.hover
                        :title="getTextLocale('download')"
                        v-if="file.type == 1 || file.type == 2"
                      >
                        <span class="svg-icon svg-icon-xl svg-icon-dark">
                          <inline-svg
                            src="/media/svg/icons/Files/Download.svg"
                          />
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="col-xl-12">
            <div class="card card-custom gutter-b card-stretch">
              <div
                class="col-xl-12 mt-5 mb-10"
                style="text-align: center; color: #b5b5c3 !important"
              >
                <i class="far fa-folder-open icon-3x"></i>
                <h6>{{$t('no_data')}}</h6>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div
        v-if="files.data.length > 0"
        class="d-flex justify-content-between align-items-center flex-wrap"
      >
        <div class="d-flex flex-wrap py-2 mr-3">
          <pagination
            :data="files"
            @pagination-change-page="getFileByCategory"
            :limit="3"
            show-disabled
            size="default"
          >
          </pagination>
        </div>
      </div>
      <template #overlay>
        <LoaderVue></LoaderVue>
      </template>
    </b-overlay>
    <ViewImageModal ref="viewImageModal" :form="fileEdit"></ViewImageModal>
    <ViewEmbededModal
      ref="viewEmbededModal"
      :form="fileEdit"
    ></ViewEmbededModal>
    <ViewSocialModal ref="viewSocialModal" :form="fileEdit"></ViewSocialModal>
  </div>
</template>
<script>
import ViewSocialModal from "aff/view/pages/creative/partials/ViewSocialModal.vue";
import ViewImageModal from "aff/view/pages/creative/partials/ViewImageModal.vue";
import ViewEmbededModal from "aff/view/pages/creative/partials/ViewEmbededModal.vue";
import { BFormInput, BDropdown, BDropdownItem, BOverlay } from "bootstrap-vue";
import ApiService from "aff/core/services/api.service";
import LoaderVue from "aff/view/content/LoaderVue.vue";
import pagination from "laravel-vue-pagination";
export default {
  data() {
    return {
      loadingFilesTable: false,
      files: {
        data: [],
      },
      type: "",
      name: "",
      fileEdit: {},
      extensionSupport: ["zip", "rar"],
      extensionSupportWord: ["doc", "docx"],
      extensionSupportPdf: ["pdf"],
      extensionSupportExcel: ["xls", "xlsx"],
    };
  },
  components: {
    BDropdown,
    BDropdownItem,
    BOverlay,
    LoaderVue,
    pagination,
    ViewImageModal,
    BFormInput,
    ViewEmbededModal,
    ViewSocialModal,
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
  },
  methods: {
    downLoad(id) {
      let domain = "https://app." + process.env.MIX_BASE_DOMAIN + "/";
      let temp = domain + "files-download/" + id;
      return temp;
    },
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
    searchName() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.getFileByCategory();
      }, 600);
    },
    typeChange() {
      this.getFileByCategory();
    },
    ViewImageModal(file) {
      this.fileEdit = file;
      this.$refs["viewImageModal"].$refs["ImageModal"].show();
    },
    ViewEmbededModal(file) {
      this.fileEdit = file;
      this.$refs["viewEmbededModal"].$refs["ImageModal"].show();
    },
    ViewSocialModal(file) {
      this.fileEdit = file;
      this.$refs["viewSocialModal"].$refs["ImageModal"].show();
    },
    getFileByCategory(page = 1) {
      this.loadingFilesTable = true;
      let params = {
        page: page,
      };
      if (this.type) {
        params["type"] = this.type;
      }
      if (this.name) {
        params["name"] = this.name;
      }
      let resource = `/categorys/get-file-category/${this.id}`;
      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.files = data.data;
        this.loadingFilesTable = false;
      });
    },
  },
  mounted() {
    this.getFileByCategory();
  },
};
</script>
