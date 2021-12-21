<template>
    <div class="d-flex flex-column flex-root" v-if="isAuthenticated">
        <!-- TODO: Không sửa dòng này !!!!! -->
        <div v-if="loading">
            <Loader />
        </div>
        <div v-else>
            <!-- begin:: Header Mobile -->
            <KTHeaderMobile></KTHeaderMobile>
            <!-- end:: Header Mobile -->

            <!-- begin::Body -->
            <div class="d-flex flex-row flex-column-fluid page">
            <!-- begin:: Aside Left -->
            <KTAside v-if="asideEnabled"></KTAside>
            <!-- end:: Aside Left -->

            <div id="kt_wrapper" class="d-flex flex-column flex-row-fluid wrapper">
                <!-- begin:: Header -->
                <KTHeader></KTHeader>
                <!-- end:: Header -->

                <!-- begin:: Content -->
                <div
                id="kt_content"
                class="content d-flex flex-column flex-column-fluid"
                >
                <!-- begin:: Content Head -->

                <!-- begin:: Content Body -->
                <div class="d-flex flex-column-fluid">
                    <div
                    :class="{
                        'container-fluid': contentFluid,
                        container: !contentFluid
                    }"
                    >
                    <template v-if="isActive">
                    <router-view />
                    </template>
                    <template v-else>
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-body">
                            <div class="alert alert-custom alert-light-warning" role="alert">
                                <div class="alert-icon">
                                <span class="svg-icon svg-icon-3x svg-icon-primary px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                            <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                        </g>
                                    </svg>
                                </span>
                                </div>
                                <div class="alert-text">
                                    <p v-html="$t('affiliate_pending_message')"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </template>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <KTScrollTop></KTScrollTop>
        </div>
    </div>
</template>

<script>
import Loader from "aff/view/content/Loader.vue";
import { mapGetters, mapState } from "vuex";
import KTAside from "aff/view/layout/aside/Aside.vue";
import KTHeader from "aff/view/layout/header/Header.vue";
import KTHeaderMobile from "aff/view/layout/header/HeaderMobile.vue";
import KTFooter from "aff/view/layout/footer/Footer.vue";
import HtmlClass from "@/core/services/htmlclass.service";
import KTScrollTop from "aff/view/layout/extras/ScrollTop";
import {VERIFY_AUTH} from "@/core/services/store/auth.module.js";
import ApiService from "aff/core/services/api.service";

export default {
  name: "Layout",
  components: {
    KTAside,
    KTHeader,
    KTHeaderMobile,
    KTFooter,
    KTScrollTop,
    Loader
  },
   data(){
      return{
          loading: true,
          enable_affiliate_language: 0,
          auto_detect_language: 0,
      }
  },
   created() {
    this.$store.dispatch(VERIFY_AUTH);
    // lấy ngôn ngữ từ local storage
    let lang = localStorage.getItem("language");
    // check if current user is authenticated
    if (!this.isAuthenticated) {
        this.$router.push({ name: "login" });
    }else{
    this.$store.dispatch('loadLayoutData').then((res)=>{
        this.enable_affiliate_language = res.data.shopSettings.enable_affiliate_language;
        this.auto_detect_language = res.data.shopSettings.auto_detect_language;
        //nếu như bật auto detect
        if(this.auto_detect_language == 1){

            //xét xem ngôn ngữ browser có nằm trong 7 ngôn ngữ locale của mình k
            // nếu có nằm trong 7 ngôn ngữ (khác default) thì gọi ngôn ngữ theo browser
            if(this.detectLanguage() !== "default"){
                this.callLocale(this.detectLanguage());
            }
            //Nếu không nằm trong 7 ngôn ngữ thì gọi mặc định
            else{

                this.callLocaleDefault();
            }
        }
        //Nếu không bật auto-detect
        else{
            //nếu bật enable_affiliate_language
            if(this.enable_affiliate_language == 1){
                // gọi ngôn ngữ trong localSTorage
                if(lang && lang != '' ){
                    this.callLocale(lang);
                }
                else{
                    this.callLocaleDefault();
                }
            }
            //Không bật thì gọi default
            else{
                this.callLocaleDefault();
            }
        };
        localStorage.setItem('enable_affiliate_language', this.enable_affiliate_language);
        localStorage.setItem('auto_detect_language', this.auto_detect_language);
        var linkfav = document.createElement('link');
        if(this.favicon != null){
            linkfav.href = this.favicon;
            linkfav.rel = "icon";
            linkfav.type="image/png";
            document.head.appendChild(linkfav);
        }else{
            linkfav.href = "media/logos/favicon.png";
            linkfav.rel = "icon";
            linkfav.type="image/png";
            document.head.appendChild(linkfav);
        }
      });
    }

  },
  beforeMount() {
    HtmlClass.init(this.layoutConfig());
  },
  mounted() {
  },
  methods: {
    callLocaleDefault(){
        ApiService.query("/translations").then(({ data }) => {
            localStorage.setItem("language",data.data.locale);
            this.$i18n.setLocaleMessage(data.data.locale, data.data.translations) ;
            this.$i18n.locale = data.data.locale;
            this.loading = false;
        });
    },
    callLocale(lang){
        ApiService.query("/translations/" + lang).then(({ data }) => {
            localStorage.setItem("language",data.data.locale);
            this.$i18n.setLocaleMessage(data.data.locale, data.data.translations) ;
            this.$i18n.locale = data.data.locale;
            this.loading = false;
        });
    },
    detectLanguage(){
        let thisLang = navigator.language || navigator.userLanguage;
        let enArray = ["en","en-US", "en-EG", "en-AU", "en-GB", "en-CA", "en-NZ", "en-IE", "en-ZA", "en-JM","en-BZ", "en-TT"];
        let zhArray = ["zh-TW", "zh-CN", "zh-HK", "zh-SG"];
        let esArry = ["es", "es-AR", "es-GT", "es-CR", "es-PA", "es-DO", "es-MX", "es-VE", "es-CO",
            "es-PE", "es-EC", "es-CL", "es-UY", "es-PY", "es-BO", "es-SV", "es-HN", "es-NI","es-PR"];
        let itArray = ["it", "it-CH"];
        let frArray = ["fr", "fr-BE", "fr-CA", "fr-CH", "fr-LU"];
        let deArray = ["de", "de-CH", "de-AT", "de-LU", "de-LI"];
        let ptArray = ["pt-BR", "pt"];
        if(enArray.includes(thisLang)){
            thisLang = "en";
        };
        if(zhArray.includes(thisLang)){
            thisLang = "zh";
        };
        if(esArry.includes(thisLang)){
            thisLang = "es";
        };
        if(itArray.includes(thisLang)){
            thisLang = "it";
        };
        if(frArray.includes(thisLang)){
            thisLang = "fr";
        };
        if(deArray.includes(thisLang)){
            thisLang = "de";
        };
        if(ptArray.includes(thisLang)){
            thisLang = "pt";
        };
        let bixgrowLang = ["en", "zh", "es","it","fr","de","pt"];
        if(bixgrowLang.includes(thisLang)){
            return thisLang;
        }else{
            return "default";
        }
    }
  },
  computed: {
    ...mapGetters([
      "isAuthenticated",
      "layoutConfig",
      "isActive"
    ]),
    ...mapState({
        favicon: state => state.layout.settings.favicon,
    }),

    /**
     * Check if container width is fluid
     * @returns {boolean}
     */
    contentFluid() {
      return this.layoutConfig("content.width") === "fluid";
    },

    /**
     * Page loader logo image using require() function
     * @returns {string}
     */
    loaderLogo() {
      return process.env.BASE_URL + this.layoutConfig("loader.logo");
    },

    /**
     * Check if the left aside menu is enabled
     * @returns {boolean}
     */
    asideEnabled() {
      return !!this.layoutConfig("aside.self.display");
    },

    /**
     * Set the right toolbar display
     * @returns {boolean}
     */
    toolbarDisplay() {
      // return !!this.layoutConfig("toolbar.display");
      return true;
    },

    /**
     * Set the subheader display
     * @returns {boolean}
     */
    subheaderDisplay() {
      return !!this.layoutConfig("subheader.display");
    }

  },

};
</script>
