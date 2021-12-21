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
          {{$t('marketing_resources_text_note')}}
      </div>
    </div>
    <div class="row d-flex">
      <!--begin::Aside-->
      <div class="col-lg-3 mb-5" id="kt_todo_aside">
        <!--begin::Card-->
        <div class="card card-custom card-stretch">
          <!--begin::Body-->
          <div class="card-body px-5">
            <!-- <div class="d-flex align-items-center mb-6 ml-7">
              <b-dropdown variant="primary">
                <template slot="button-content">
                  <span class="svg-icon svg-icon-md svg-icon-white">
                    <inline-svg src="media/svg/icons/Code/Plus.svg" />
                  </span>
                  Add media
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
            </div> -->
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
                    >{{$t('all')}}</span
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
              <!-- <div
                class="navi-item"
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
                  <span class="navi-label">
                  <span
                    class="
                      label label-rounded label-light-success
                      font-weight-bolder
                    "
                    >{{cat.files_count}}</span
                  >
                </span>
                </router-link>
              </div> -->

              <!-- <div
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
              </div> -->
            </div>
            <!--end:Nav-->
          </div>
          <!--end::Body-->
        </div>
        <!--end::Card-->
      </div>
      <!--end::Aside-->
      <!--begin::List-->
      <div class="flex-row-fluid d-flex flex-column col-lg-9">
        <!--begin::Row-->
        <router-view />
      </div>
      <!-- <ImageModal
        ref="mediaImageModal"
        @saveImageSuccess="saveSuccess"
      ></ImageModal>
      <FileModal
        ref="mediaFileModal"
        @saveFileSuccess="saveFileSuccess"
      ></FileModal>
      <VideoModal
        ref="mediaVideoModal"
        @saveVideoSuccess="saveVideoSuccess"
      ></VideoModal>
      <LinkModal
        ref="mediaLinkModal"
        @saveLinkSuccess="saveLinkSuccess"
      ></LinkModal> -->
    </div>
  </div>
  <!--end::List-->
</template>
<script>
export default {
       mounted() {
  }
}
</script>
