<template>
  <div>
    <div
      class="alert alert-custom alert-white alert-shadow fade show gutter-b"
      role="alert"
    >
      <div class="alert-icon">
        <span class="svg-icon svg-icon-primary svg-icon-xl">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            width="24px"
            height="24px"
            viewBox="0 0 24 24"
            version="1.1"
          >
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <rect x="0" y="0" width="24" height="24"></rect>
              <path
                d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                fill="#000000"
                opacity="0.3"
              ></path>
              <path
                d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                fill="#000000"
                fill-rule="nonzero"
              ></path>
            </g>
          </svg>
        </span>
      </div>
      <div class="alert-text">
        You can post images, files, video links or other resources to assist your
        affiliates promoting your products.
        <br />

        Your affiliates can view, copy and download these marketing resources and distribute them to their audiences.
        <br/>
         Learn more about
        <a class="font-weight-bold" target="_blank" href="https://docs.bixgrow.com/manage/creatives">creatives.</a>
      </div>
    </div>
    <div class="row d-flex">
      <!--begin::Aside-->
      <div class="col-xl-3 mb-5" id="kt_todo_aside">
        <!--begin::Card-->
        <div class="card card-custom card-stretch">
          <!--begin::Body-->
          <div class="card-body px-5">
            <!--begin:Nav-->
            <div
              class="
                navi
                navi-hover
                navi-active
                navi-link-rounded
                navi-bold
                navi-icon-center
                navi-light-icon
              "
            >
              <!--begin:Item-->
              <div class="navi-item">
                <b-dropdown variant="primary" class="w-100 mb-5">
                <template slot="button-content">
                  <span class="svg-icon svg-icon-md svg-icon-white">
                    <inline-svg src="media/svg/icons/Code/Plus.svg" />
                  </span>
                  New creative
                </template>
                <b-dropdown-item @click="openImageModal">
                  <span class="svg-icon svg-icon-md svg-icon-primary">
                    <inline-svg src="media/svg/icons/Files/Pictures1.svg" />
                  </span>
                  <span class="navi-text font-size-lg ml-4">Add images</span>
                </b-dropdown-item>
                <b-dropdown-item @click="openFileModal">
                  <span class="svg-icon svg-icon-md svg-icon-primary">
                    <inline-svg src="media/svg/icons/Files/File.svg" />
                  </span>
                  <span class="navi-text font-size-lg ml-4"
                    >Add files</span
                  ></b-dropdown-item
                >
                <b-dropdown-item @click="openVideoModal">
                  <span class="svg-icon svg-icon-md svg-icon-primary">
                    <inline-svg src="media/svg/icons/Files/Media.svg" />
                  </span>
                  <span class="navi-text font-size-lg ml-4"
                    >Add videos</span
                  ></b-dropdown-item
                >
                <b-dropdown-item @click="openLinkModal">
                  <span class="svg-icon svg-icon-md svg-icon-primary">
                    <inline-svg src="media/svg/icons/General/Clip.svg" />
                  </span>
                  <span class="navi-text font-size-lg ml-4"
                    >Add links</span
                  ></b-dropdown-item
                >
              </b-dropdown>
              </div>
              <div class="navi-item my-2">
                <router-link
                  :to="{
                    name: 'creatives.view',
                    params: { name: 'all', id: 0 },
                  }"
                  class="navi-link"
                  active-class="active"
                >
                  <span class="navi-icon mr-4">
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <inline-svg
                        src="media/svg/icons/Layout/Layout-grid.svg"
                      />
                    </span>
                  </span>
                  <span class="navi-text font-weight-bolder font-size-lg"
                    >All</span
                  >
                  <!-- <span class="navi-label">
                  <span
                    class="
                      label label-rounded label-light-success
                      font-weight-bolder
                    "
                    >{{files_count}}</span
                  >
                </span> -->
                </router-link>
              </div>
              <div
                class="navi-item my-2 navi-item-hover-delete"
                v-for="(cat, index) in categorys.data"
                :key="index"
              >
                <router-link
                  :to="{
                    name: 'creatives.view',
                    params: { name: cat.name, id: cat.id },
                  }"
                  class="navi-link"
                  active-class="active"
                >
                  <span class="navi-icon mr-4">
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <inline-svg src="media/svg/icons/Files/Folder.svg" />
                    </span>
                  </span>
                  <span class="navi-text font-weight-bolder font-size-lg">{{
                    cat.name
                  }}</span>
                  <span class="navi-label"  v-b-tooltip.hover
                  title="Delete"  >
                  <span class="svg-icon svg-icon-danger svg-icon-md button-category-delete d-none"
                  @click="deleteCategory(cat.id)">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      width="24px"
                      height="24px"
                      viewBox="0 0 24 24"
                      version="1.1"
                    >
                      <g
                        stroke="none"
                        stroke-width="1"
                        fill="none"
                        fill-rule="evenodd"
                      >
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                          d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                          fill="#000000"
                          fill-rule="nonzero"
                        ></path>
                        <path
                          d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                          fill="#000000"
                          opacity="0.3"
                        ></path>
                      </g>
                    </svg>
                  </span>
                </span>
                </router-link>
              </div>

              <div
                v-if="categorys.data.length > 0"
                class="
                  d-flex
                  justify-content-between
                  align-items-center
                  flex-wrap
                "
              >
                <div class="d-flex flex-wrap py-2 mr-3">
                  <pagination
                    :data="categorys"
                    @pagination-change-page="loadCategory"
                    :limit="3"
                    show-disabled
                    size="default"
                  >
                    <span slot="prev-nav">
                      <i class="ki ki-bold-arrow-back icon-xs"></i>
                    </span>
                    <span slot="next-nav">
                      <i class="ki ki-bold-arrow-next icon-xs"></i>
                    </span>
                  </pagination>
                </div>
              </div>
            </div>
            <!--end:Nav-->
          </div>
          <!--end::Body-->
        </div>
        <!--end::Card-->
      </div>
      <!--end::Aside-->
      <!--begin::List-->
      <div class="flex-row-fluid d-flex flex-column col-xl-9">
        <!--begin::Row-->
        <router-view :key="page"/>
      </div>
      <ImageModal
        ref="mediaImageModal"
        @saveImageSuccess="saveSuccess"
      ></ImageModal>
      <FileModal
        ref="mediaFileModal"
        @saveFileSuccess="saveFileSuccess"
      ></FileModal>
      <VideoModal
        ref="mediaVideoModal"
        @VideoModal="saveVideoSuccess"
      ></VideoModal>
      <LinkModal
        ref="mediaLinkModal"
        @saveLinkSuccess="saveLinkSuccess"
      ></LinkModal>
    </div>
  </div>
  <!--end::List-->
</template>
<script>
import FileModal from "@/view/pages/creative/partials/FileModal.vue";
import ImageModal from "@/view/pages/creative/partials/ImageModal.vue";
import VideoModal from "@/view/pages/creative/partials/VideoModal.vue";
import LinkModal from "@/view/pages/creative/partials/LinkModal.vue";
import ApiService from "@/core/services/api.service";
import pagination from "laravel-vue-pagination";
import { BDropdown, BDropdownItem, BFormInput } from "bootstrap-vue";
import { mapMutations } from "vuex";
import {mapGetters} from "vuex";
import swal from "sweetalert2";
window.Swal = swal;
export default {
  computed: {
     ...mapGetters(['getListFile']),
          count_files()
          {
            return this.getListFile;
          },
    id() {
      return this.$route.params.id;
    },
  },
  data() {
    return {
      categorys: {
        data: [],
      },
      page: 0
    };
  },
  components: {
    BDropdown,
    BDropdownItem,
    BFormInput,
    ImageModal,
    pagination,
    FileModal,
    VideoModal,
    LinkModal,
  },
  methods: {
    openFileModal() {
      this.$refs["mediaFileModal"].$refs["FileModal"].show();
    },
    openImageModal() {
      this.$refs["mediaImageModal"].$refs["ImageModal"].show();
    },
    openVideoModal() {
      this.$refs["mediaVideoModal"].$refs["ImageModal"].show();
    },
    openLinkModal() {
      this.$refs["mediaLinkModal"].$refs["ImageModal"].show();
    },
    saveSuccess() {
      this.loadCategory();
     this.page++;
    },
    saveFileSuccess() {
      this.loadCategory();
     this.page++;
    },
    saveVideoSuccess() {
      this.loadCategory();
      this.page++;
    },
    saveLinkSuccess() {
      this.loadCategory();
    this.page++
    },
    loadCategory(page = 1) {
      let resource = `/categorys/get-category`;
      let params = {
        page: page,
      };
      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.categorys = data.data;
      });
    },
    deleteCategory(idCat) {
        Swal.fire({
        title: "Are you sure?",
        text: "",
        input: 'checkbox',
        inputValue: 0,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        inputPlaceholder:
    'Do not select the checkbox if you want to delete the category only. Select the checkbox if you want to delete all creatives inside the category.',
      }).then((result) => {
        if (result.isConfirmed) {
          ApiService.delete("categorys/" + result.value+'/'+idCat)
            .then(({ data }) => {
              if(data.data)
              {
                  this.categorys.data = this.categorys.data.filter((x) => x.id != idCat);
                  this.$router.push({name:'creatives'});
                   this.$toast.success('Deleted', { position: "bottom" });
              }
              // if (foundIndex > -1) {
              //   this.affiliates.data.splice(foundIndex, 1);
              // }
            })
            .catch(({ response }) => {
               this.$toast.error(response.data.message, { position: "bottom" });
            });
        }
      });
    }
  },
  mounted() {
    this.loadCategory();
  },
  watch:{
    count_files(){
      this.loadCategory();
    }
  }
};
</script>
<style scoped>
.navi-item-hover-delete:hover .button-category-delete{
  display: block !important;;
}
</style>
