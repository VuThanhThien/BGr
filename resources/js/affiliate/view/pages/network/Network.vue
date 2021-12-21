<template>
  <div>
    <div class="row">
        <div class="col-xl-6 col-lg-12" v-if="isEnableMlm">
        <div class="card card-custom gutter-b card-stretch">
          <div
            class="card-header align-items-center border-0"
            style="border-bottom: 1px solid #e8e9f2 !important"
          >
            <h4 class="card-title align-items-start flex-column">
              <span class="font-weight-bolder text-dark">{{$t('network_link')}}</span>
            </h4>
          </div>
          <div class="card-body pt-0 mt-10">
            <div class="d-flex">
              <input
                type="text"
                disabled
                class="form-control form-control-solid me-3 flex-grow-1"
                ref="network_link_input"
                name="search"
                v-model="networkLink"
              />
              <button
                class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                @click="copy('network_link_input', $event)"
              >
                {{$t('copy')}}
              </button>
            </div>
          </div>
          <div class="card-body pt-0">
            <h6 class="card-title align-items-start flex-column">
              <span class="font-weight-bolder text-dark"
                >{{$t('share_on_social_media')}}
              </span>
            </h6>
            <div class="d-flex mt-2 flex-wrap" style="gap: 5px">
              <div class="symbol-label">
                <a
                  :href="'https://www.facebook.com/sharer.php?u=' + affLink"
                  data-toggle-t="tooltip"
                  data-placement="top"
                  :data-original-title="getTextLocale('share_on_fb')"
                  class="btn btn-icon btn-facebook btn-sm "
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
                  :data-original-title="getTextLocale('share_on_tw')"
                  class="btn btn-icon btn-twitter btn-sm "
                  target="_blank"
                >
                  <i class="fab fa-twitter text-white"></i>
                </a>
              </div>
              <div class="symbol-label">
                <a
                  :href="
                    'https://www.linkedin.com/shareArticle?mini=true&url=' +
                    affLink
                  "
                  data-toggle-t="tooltip"
                  data-placement="top"
                  :data-original-title="getTextLocale('share_on_ln')"
                  class="btn btn-icon btn-linkedin btn-sm "
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
                  :data-original-title="getTextLocale('share_on_ptr')"
                  class="btn btn-icon btn-pinterest btn-sm "
                  target="_blank"
                >
                  <i class="fab fa-pinterest-p text-white"></i>
                </a>
              </div>
              <!-- <div class="symbol-label">
                <a
                  href="#"
                  url_qrcode="https://af.uppromote.com/kanawut/dashboard/create_qrcode"
                  class="btn btn-icon btn-twitter btn-sm "
                  :data_link="affLink"
                  data-toggle="modal"
                  data-target="#modal_qrcode"
                  ><i class="fas fa-qrcode text-white"></i
                ></a>
              </div> -->
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <!--begin::List Widget 1-->
        <div class="card card-custom gutter-b card-stretch">
          <div class="card-header mx-auto pt-5">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label font-weight-bolder text-dark"
                >{{$t('total_commission')}}</span
              >
              <!-- <span class="text-muted mt-3 font-weight-bold font-size-sm"
              >Pending 10 tasks</span
            > -->
            </h3>
          </div>
          <div class="card-body mx-auto d-flex align-items-center">
            <span
              class="
                card-title
                font-weight-bolder
                text-primary
                font-size-h1
                mb-0
                d-block
              "
              >{{formatMoney(totalCommission, format)}}</span
            >
          </div>
        </div>
        <!--end::List Widget 1-->
      </div>

      <div class="col-xl-3">
        <!--begin::List Widget 1-->
        <div class="card card-custom gutter-b card-stretch">
          <div class="card-header mx-auto pt-5">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label font-weight-bolder text-dark"
                >{{$t('total_signups')}}</span
              >
              <!-- <span class="text-muted mt-3 font-weight-bold font-size-sm"
              >Pending 10 tasks</span
            > -->
            </h3>
          </div>
          <div class="card-body mx-auto d-flex align-items-center">
            <span
              class="
                card-title
                font-weight-bolder
                text-primary
                font-size-h1
                mb-0
                d-block
              "
              >{{totalDownline}}</span
            >
          </div>
        </div>
        <!--end::List Widget 1-->
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card card-custom-gutter-b card-stretch">
          <div class="card-body">
            <Datatable :loading="loadingCouponTable">
              <thead>
                <th class="pr-0" style="min-width: 100px">
                  <span>#{{$t('level')}} </span>
                </th>
                <th class="pr-0" style="min-width: 100px">
                  <span>{{$t('network_below')}}</span>
                </th>
                <th class="pr-0" style="min-width: 100px">
                  <span>{{$t('total_sales')}}</span>
                </th>
                <th class="pr-0 text-right" style="min-width: 100px">
                  <span>{{$t('revenue')}}</span>
                </th>
                <th class="pr-5 text-right" style="min-width: 100px">
                  <span>{{$t('commission')}}</span>
                </th>
              </thead>
              <tbody>
                <template v-if="levelInfos.length > 0">
                  <tr v-for="(level, i) in levelInfos" :key="level.level" :i= i>
                    <td>
                      <div class="font-weight-bolder font-size-lg d-block">
                        {{(i + 1)}}
                      </div>
                      <!-- <span class="text-muted"></span> -->
                    </td>
                    <td>
                      <span
                        class="
                          text-dark-75
                          font-weight-bolder
                          d-block
                          font-size-lg
                        "
                        >{{level.total_signup}}</span
                      >
                    </td>
                    <td>
                      <span v-if="level.statistics" href="#" class="text-dark-75 d-block font-size-lg">
                        {{level.statistics.total_conversion}}
                      </span>
                      <span v-else href="#" class="text-dark-75 d-block font-size-lg">
                        0
                      </span>
                    </td>
                    <td>
                      <span
                        href="#"
                        class="text-dark-75 d-block font-size-lg text-right"
                      >
                      <span v-if="level.statistics" href="#" class="text-dark-75 d-block font-size-lg">
                        {{formatMoney(level.statistics.total_revenue, format)}}
                      </span>
                      <span v-else href="#" class="text-dark-75 d-block font-size-lg">
                        {{formatMoney(0, format)}}
                      </span>
                      </span>
                    </td>
                    <td>
                      <span
                        href="#"
                        class="text-dark-75 d-block font-size-lg text-right"
                      >
                        <span v-if="level.statistics" href="#" class="text-dark-75 d-block font-size-lg">
                        {{formatMoney(level.statistics.total_commission, format)}}
                      </span>
                      <span v-else href="#" class="text-dark-75 d-block font-size-lg">
                        {{formatMoney(0, format)}}
                      </span>
                      </span>
                    </td>
                  </tr>
                </template>
               <template v-else>
                <tr>
                  <td colspan="8" style="text-align: center;color: #B5B5C3 !important;">
                    <i class="far fa-folder-open icon-3x"></i>
                    <h6>{{$t('no_data')}}</h6>
                  </td>
                </tr>
              </template>
              </tbody>
            </Datatable>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Datatable from "aff/view/content/Datatable.vue";
import ApiService from 'aff/core/services/api.service';
import { mapGetters } from 'vuex';
export default {
     data() {
    return {
      loadingCouponTable: false,
      totalCommission:0,
      totalDownline:0,
      levelInfos:[]
    };
  },
  computed:{
    ...mapGetters(['subDomain','group','defaultAffiliateLink']),
   affLink() {
      let linkAff =  (this.$store.getters.currentUser.primary_aff_link
          ? this.$store.getters.currentUser.primary_aff_link
          : this.defaultAffiliateLink) ;
      return (
        this.generateLinkAffiliate(linkAff)+this.$store.getters.currentUser.group_id+':' +
        this.$store.getters.currentUser.hash_code
      );
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
    GroupSignupPage(){
        return this.GroupSignupPageDefault + '/'+ this.group.slug+'?parent=';
    },
    networkLink() {
              if(this.group.is_default){
      return (
       this.GroupSignupPageDefault+'?parent='+
        this.$store.getters.currentUser.hash_code
      );    }
      else{
        return (this.GroupSignupPage+this.$store.getters.currentUser.hash_code);
      }

    },
    isEnableMlm() {
      return this.$store.getters.group.is_enable_mlm;
    },
  },
      components: {
    Datatable
  },
  methods:{
      loadDataStatistics(){
      let resource =`/affiliates/network-statistics`;
      ApiService.post(resource)
      .then(({data})=>{
          this.totalCommission = data.data.totalCommission;
          this.totalDownline = data.data.totalDownline;
          this.levelInfos = data.data.levelInfos;
      }).catch(({response})=>{
         this.$toast.error(response.data.message, { position: "bottom" });
      });
    }
  },
     mounted() {
        this.loadDataStatistics();
  }
}
</script>
