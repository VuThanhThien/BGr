import ApiService from "@/core/services/api.service";
import { lang } from "moment";
import getSymbolFromCurrency from 'currency-symbol-map'

const state = {
  group:{
    is_enable_mlm:0
  },
  paymentMethodAvailable:[],
  settings:{
    aff_conflict_resolution: 2,
    brand_name: "",
    contact_email: "",
    contact_name: "",
    favicon: null,
    from_contact_email: null,
    from_contact_name: null,
    id: 0,
    info: {shop_id: null, currency: "USD", money_format: "${{amount}}", name: "", domain:"", myshopify_domain:""},
    logo: null,
    shop_id: 0,
    sub_domain: "tuanluis",
    default_affiliate_link: "",
    allow_affiliates_custom_link:0,
    allow_self_generating_coupon: 0,
    autopay_discount_code:{min_amount: 0,max_amount: '',conversion_factor: 1,prefix:''}   
  },
};

const getters = {
  subDomain:state=>{
    return state.settings.sub_domain;
  },
  group:state=>{
    return state.group;
  },
  shopName: state => {
    return state.settings.info.name;
  },
  shopDomain: state => {
    return state.settings.info.domain;
  },
  shopShopifyDomain: state => {
    return state.settings.info.myshopify_domain;
  },
  moneyFormat: state => {
    return state.settings.info.money_format;
  },
  symbolCurrency() {
    return getSymbolFromCurrency(state.settings.info.currency);
  },
  paymentMethodAvailable: state => {
    return state.paymentMethodAvailable;
  },
  defaultAffiliateLink(){
    return state.settings.default_affiliate_link;
  },
  allowAffiliatesCustomLink(){
    return state.settings.allow_affiliates_custom_link;
  },
  lang() {
    return state.settings.default_affiliate_language;
  },
  allowSelfGeneratingCoupon()
  {
    return state.settings.allow_self_generating_coupon;
  },
  autopayDiscountCode()
  {
    return state.settings.autopay_discount_code;
  }
  
};


const actions = {
    loadLayoutData(context) {
        return new Promise(resolve => {
            ApiService.query("layout-data")
              .then(({data} ) => {
                context.commit('SET_LAYOUT_DATA', data.data);
                resolve(data);
              })
              .catch(({ response }) => {
                context.commit('SET_ERROR', response.data.errors);
              });
        });
    },
 
};

const mutations = {
    SET_LAYOUT_DATA(state, payload){
      let default_autopay_discount_code = state.settings.autopay_discount_code;
      state.settings = payload.shopSettings;
      if(!state.settings.autopay_discount_code)
      {
        state.settings.autopay_discount_code = default_autopay_discount_code;
      }
      state.paymentMethodAvailable = payload.paymentMethodAvailable;
      state.group=payload.group;
    },

    UPDATE_AFFILIATE_SETTING(state, payload){
      state.affiliateSetting = payload;
    }

};

export default {
  state,
  actions,
  mutations,
  getters
};
