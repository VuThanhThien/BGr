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
                      placeholder="Type to search media name"
                      class="form-control-custom form-control"
                      v-model="name"
                      @input="searchName"
                    >
                    </b-form-input>
                  </div>
                  <div class="col-md-3 my-2 my-md-0">
                    <select class="form-control form-control-custom" v-model="type" @change="typeChange">
                      <option value="">All Assets</option>
                      <option value="1">Images</option>
                      <option value="3">Videos</option>
                      <option value="2">Files</option>
                      <option value="4">Link</option>
                    </select>
                  </div>
    <div class="col-md-3 my-2 my-md-0">
                 <select
                class="form-control form-control-custom" v-model="groupId" @change="groupChange"
              >
                <option value="" selected class="text-muted">
                  All Programs
                </option>

                <option v-for="(o, index) in groupSelectedOptions" :value="o.value" :o="o" :key="index">
                  {{ o.text }}
                </option>
              </select>
    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end::Body-->
      </div>
       <b-overlay  :show="loadingFilesTable"  style="min-height: 281px;"         id="overlay-background"
           rounded="sm">
      <div class="row">
        <template v-if="files.data.length>0">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6" v-for="(file,index) in files.data" :key="index">
          <div class="card card-custom gutter-b card-stretch overlay">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center overlay-wrapper">
                <!--begin: Icon-->
                <img alt="" class="max-h-150px" style="width:100%;height:auto;" v-if="file.type==1" :src="file.image"/>
                <iframe height="150px" width="100%" v-if="file.type==3" :src="validateLink(file.link)" :title="file.name" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                  Your browser does not support iframes</iframe>    
                <img alt="" class="max-h-150px" v-if="extensionSupport.includes(file.extension)"  src="/media/svg/files/zip.svg" />
                  <img alt="" class="max-h-150px" v-else-if="extensionSupportWord.includes(file.extension)"  src="/media/svg/files/doc.svg" />
                           <img alt="" class="max-h-150px" v-else-if="extensionSupportExcel.includes(file.extension)"  src="/media/svg/files/csv.svg" />
                  <img alt="" class="max-h-150px" v-else-if="extensionSupportPdf.includes(file.extension)"  src="/media/svg/files/pdf.svg" />
                  <!-- <img alt="" class="max-h-150px" v-else-if="file.type==2"  src="/media/svg/icons/files/Uploaded-file.svg" /> -->
                        <span class="svg-icon svg-icon svg-icon-10x svg-icon-primary" v-else-if="file.type==2">
                    <inline-svg src="/media/svg/icons/Files/File.svg" />
                  </span>
                 <img alt="" class="max-h-150px" v-if="file.type==4" src="/media/svg/files/html.svg" />
                <!--end: Icon-->
                <!--begin: Tite-->
                <span
                  class="mt-15 font-size-lg"
                  >{{file.name}}</span
                >
                <!--end: Tite-->
              </div>
        <div class="overlay-layer align-items-end justify-content-center" style="background-color: rgb(0 0 0 / 50%);">
        <div class="d-flex flex-column flex-center">
                      <div class="d-flex flex-column flex-center">
                        <span class="label label-xl label-dark label-inline mb-1" v-if="file.file_name" >{{file.file_name}}</span>
                <!-- <button type="button" @click="ViewImageModal(file)" class="btn font-weight-bold btn-info btn-shadow">View</button> -->
                <div ></div>
               
            </div>
                        <div class="d-flex  flex-center bg-white-o-5 py-5">
                           <button  @click="ViewImageModal(file)"  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2"
                v-b-tooltip.hover   title="View">
     <span class="svg-icon svg-icon-xl svg-icon-dark" 
                >
   <inline-svg src="media/svg/icons/General/Visible.svg" />
                    </span>
                    </button>
                     <button  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2" v-if="file.type==1" @click="openImageModal(file)"
                v-b-tooltip.hover   title="Edit">
     <span class="svg-icon svg-icon-xl svg-icon-dark" 
                >
   <inline-svg src="media/svg/icons/Communication/Write.svg" />
                    </span>
                    </button>
                                       <button  v-if="file.type==2" @click="openFileModal(file)"  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2"
                v-b-tooltip.hover   title="Edit">
     <span class="svg-icon svg-icon-xl svg-icon-dark" 
                >
   <inline-svg src="media/svg/icons/Communication/Write.svg" />
                    </span>
                    </button>
                                       <button  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2" v-if="file.type==3" @click="openVideoModal(file)"
                v-b-tooltip.hover   title="Edit">
     <span class="svg-icon svg-icon-xl svg-icon-dark" >
   <inline-svg src="media/svg/icons/Communication/Write.svg" />
                    </span>
                    </button>
                    <button  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2" v-if="file.type==4" @click="openLinkModal(file)"
                v-b-tooltip.hover   title="Edit">
     <span class="svg-icon svg-icon-xl svg-icon-dark" >
   <inline-svg src="media/svg/icons/Communication/Write.svg" />
                    </span>
                    </button>
                                                        
             <button
                href="#"
                class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2"
               @click="deleteCreative(file.id)"
                v-b-tooltip.hover
                title="Delete"
              >
                <span class="svg-icon svg-icon-dark svg-icon-lg">
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
              </button>                                 
                <!-- <button type="button" v-if="file.type==1" @click="openImageModal(file)" class="btn font-weight-bold btn-primary btn-shadow">Edit</button>
                 <button type="button" v-if="file.type==2" @click="openFileModal(file)" class="btn font-weight-bold btn-primary btn-shadow">Edit</button>
                 <button type="button" v-if="file.type==3"  @click="openVideoModal(file)" class="btn font-weight-bold btn-primary btn-shadow">Edit</button> 
                   <button type="button" v-if="file.type==4" @click="openLinkModal(file)" class="btn font-weight-bold btn-primary btn-shadow">Edit</button>  -->
                <!-- <button type="button" @click="deleteCreative(file.id)" class="btn font-weight-bold btn-danger btn-shadow ml-2">Delete</button> -->
                <a :href="downLoad(file.id)"  class="btn btn-icon btn-light btn-hover-primary btn-sm mr-2" v-b-tooltip.hover title="Download" v-if="file.type==1 || file.type==2">
                   <span class="svg-icon svg-icon-xl svg-icon-dark" 
               >
   <inline-svg src="/media/svg/icons/Files/Download.svg" />
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
            <div class="col-xl-12 ">
               <div class="card card-custom gutter-b card-stretch ">
                <div  class="col-xl-12 mt-5 mb-10" style="text-align: center;color: #B5B5C3 !important;">
                <i class="far fa-folder-open icon-3x"></i>
                <h6>No Data</h6>
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
          <span slot="prev-nav">
              <i class="ki ki-bold-arrow-back icon-xs"></i>
          </span>
            <span slot="next-nav">
                <i class="ki ki-bold-arrow-next icon-xs"></i>
            </span>
          </pagination>
        </div>
      </div>
              <template #overlay>
        <LoaderVue></LoaderVue>
      </template>
       </b-overlay>
       <EditImageModal
      ref="mediaImageModal" :form="fileEdit" @editSuccess="editSuccess"
    ></EditImageModal>
           <EditFileModal
      ref="mediaFileModal" :form="fileEdit" @editSuccess="editSuccess"
    ></EditFileModal>
               <EditLinkModal
      ref="mediaLinkModal" :form="fileEdit" @editSuccess="editSuccess"
    ></EditLinkModal>
               <EditVideoModal
      ref="mediaVideoModal" :form="fileEdit" @editSuccess="editSuccess"
    ></EditVideoModal>
                   <ViewImageModal
      ref="viewImageModal" :form="fileEdit" @editSuccess="editSuccess"
    ></ViewImageModal>
        </div>
</template>
<script>
import swal from "sweetalert2";
import ViewImageModal from "@/view/pages/creative/partials/ViewImageModal.vue";
import EditLinkModal from "@/view/pages/creative/partials/EditLinkModal.vue";
import EditVideoModal from "@/view/pages/creative/partials/EditVideoModal.vue";
import EditImageModal from "@/view/pages/creative/partials/EditImageModal.vue";
import EditFileModal from "@/view/pages/creative/partials/EditFileModal.vue";
import { BDropdown, BDropdownItem, BFormInput,BOverlay } from "bootstrap-vue";
import { mapGetters, mapMutations } from 'vuex';
import ApiService from '@/core/services/api.service';
import LoaderVue from "@/view/content/LoaderVue.vue";
window.Swal = swal;
import pagination from "laravel-vue-pagination";
export default {
    data(){
        return{
            loadingFilesTable:false,
            files:{
              data:[]
            },
            type:'',
            groupId:'',
            name:'',
            fileEdit:{},
            extensionSupport:['zip','rar'],
            extensionSupportWord:['doc','docx'],
            extensionSupportPdf:['pdf'],
            extensionSupportExcel:['xls','xlsx']
        }
    },
    components:{
        BDropdown, BDropdownItem, BFormInput,BOverlay,LoaderVue,EditImageModal,EditFileModal,pagination,
        EditLinkModal, EditVideoModal,ViewImageModal
    },
      computed:{  
          id(){
          return this.$route.params.id;    
          },
    ...mapGetters(['groups']),
    groupSelectedOptions(){
      let options=[];
      for(let i=0;i<this.groups.length;i++)
      {
        if(this.groups[i].is_default)
        {
   options.push({text:this.groups[i].name +' (Default Program)',value:this.groups[i].id});
        }
        else{
        options.push({text:this.groups[i].name,value:this.groups[i].id});
        }
      }
      return options;
    }
  },
  methods:{
    downLoad(id)
    {
        let domain ='https://app.'+process.env.MIX_BASE_DOMAIN+'/';
      let temp=domain+'files-download/'+id;
      return temp;
    },
    searchName()
    {
      clearTimeout(this.debounce);
      this.debounce=setTimeout(()=>{
        this.getFileByCategory();
      },600);
    },
    typeChange(){
      this.getFileByCategory();
    },
    groupChange(){
      this.getFileByCategory();
    },
    ViewImageModal(file){
         this.fileEdit=file;
      this.$refs["viewImageModal"].$refs["ImageModal"].show();
    },
        openImageModal(file) {
          this.fileEdit=Object.assign({},file) ;
      this.$refs["mediaImageModal"].$refs["ImageModal"].show();
    }, openFileModal(file) {
          this.fileEdit=Object.assign({},file) ;
      this.$refs["mediaFileModal"].$refs["FileModal"].show();
    },openVideoModal(file) {
          this.fileEdit=Object.assign({},file) ;
      this.$refs["mediaVideoModal"].$refs["ImageModal"].show();
    },
    openLinkModal(file) {
          this.fileEdit=Object.assign({},file) ;
      this.$refs["mediaLinkModal"].$refs["ImageModal"].show();
    },
      getFileByCategory(page=1){
          this.loadingFilesTable=true;
                let params={
              page:page
      }
      if(this.type)
      {
        params['type']=this.type;
      }
      if(this.name)
      {
        params['name']=this.name;
      }
      if(this.groupId)
      {
        params['group_id']=this.groupId;
      }
          let resource=`/categorys/get-file-category/${this.id}`;
          ApiService.query(resource,{params:params}).then(({data})=>{
              this.files=data.data;
              this.loadingFilesTable=false;
          })
      },
      deleteCreative(id)
      {
                   Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this creative!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
          }).then((result) => {
            if (result.isConfirmed) {
               this.loadingFilesTable = true;
               let resource=`/categorys/files/${id}`;
              ApiService.delete(resource)
                .then(({data}) => {
                  this.files.data = this.files.data.filter(file =>{
                    return file.id != id;
                  });
                }).finally(()=>{
                  this.loadingFilesTable = false;
                })
            }
          })
       
      },
       getId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
},
            validateLink(link)
      {
        let checkLinkYoutube=this.getId(link);
      if(checkLinkYoutube)
        {

               link='https://www.youtube.com/embed/'+ checkLinkYoutube;

        }
        return link;
      },
      editSuccess()
      {
         this.getFileByCategory();
      }
      
  },
  mounted(){
      this.getFileByCategory();
  },
  watch:{
    $route (to, from){
      this.getFileByCategory();
    }
} 
}
</script>