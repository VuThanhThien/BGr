import ApiService from "@/core/services/api.service";
import getSymbolFromCurrency from 'currency-symbol-map'

const state = {
  settings:{
    activate_popup_checkout: 0,
    checkout_popup_data:{},
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
    sub_domain: "",
    is_verified_from_email:1,
    price_rule:null,
    notify_self_new_order:0,
    notify_self_new_registrations:0,
    is_skip_started: 1,
    is_done_started: 1,
    default_affiliate_link:'',
    allow_affiliates_custom_link:0,
    allow_self_generating_coupon: 0,
    autopay_discount_code:{min_amount: 0,max_amount: '',conversion_factor: 1,prefix:''},
    klaviyo_sync_enabled: 0,
    sync_klaviyo_approved_affiliate: 0,
    klaviyo:{klaviyo_api_key: null,klaviyo_list:null},
    coutUnreadNotifications: 0,
  },
  paymentMethodAvailable:[],
  moneyFormat:'${{amount}}',
  groups:[],
  is_feedback: false,
};

const getters = {
    coutUnreadNotifications(state){
        return state.settings.coutUnreadNotifications;
    },
  shopSettings(state){
    return state.settings;
  },
  shopName: state => {
    return state.settings.info.name;
  },
  subDomain: state => {
    return state.settings.sub_domain;
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
  groups: state => {
    return state.groups;
  },
  activeGroups: state => {
    return state.groups.filter((group) => {
      return group.is_active == 1;
    });
  },
  defaultGroup: state => {
    return state.groups.find((group) => {
      return group.is_default == 1;
    });
  },
  defaultAffiliateLink(){
    return state.settings.default_affiliate_link;
  },
  fromContactEmail(){
    return state.settings.from_contact_email;
  },
  isVerifiedFromEmail(){
    return state.settings.is_verified_from_email;
  },
  checkout_popup_data(state) {
    return state.settings.checkout_popup_data;
    },
  activate_popup_checkout(state){
    return state.settings.activate_popup_checkout;
    },
    is_feedback(state){
        return state.is_feedback;
    },
    sync_klaviyo_approved_affiliate(state){
      return state.settings.sync_klaviyo_approved_affiliate;
    },
    klaviyo_sync_enabled(state)
    {
      return state.settings.klaviyo_sync_enabled;
    },
    klaviyo_api_key(state)
    {
      return state.settings.klaviyo.klaviyo_api_key;
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
    setShopSetting(context,data){
      context.commit('setShopSetting',data);
    },
    feed_back(context, feed_back){
        context.commit('FEED_BACK',feed_back);
    },
    updatePaymentMethodAvailable(context, data){
        context.commit('UPDATE_PAYMENT_METHOD_AVAILABLE', data)
    },
    updatePopupSetting({commit}, obj) {
        return new Promise((resolve,reject) => {
            var payload = { checkout_popup_data: obj };
            ApiService.put("/settings/update-post-checkout", payload)
          .then(
            ({data}) => {
              commit('UPDATE_POPUP', obj)
              resolve();
            })
          .catch(({ response }) => {
              reject(response);
            });
        });
    },
    updateActivePopupSetting({commit}, obj) {
        commit('UPDATE_ACTIVE_POPUP', obj)
    },
    chooseTemplatePopup({ commit }, obj) {
        commit("CHANGE_CHECKOUT_POPUP", obj);
    },
    updateNotification({commit}, payload){
        commit("UPDATE_NOTIFICATION", payload)
    },
    async loadGroups(context)
    {
      try{
        let resource = `groups`;
        let data = await ApiService.query(resource);
        context.commit('LOAD_GROUPS',data.data.data);
        return data.data.data;
      }
      catch(err){
          return err.response;
      }
    },

   async toggleSyncKlaviyoApprovedAffiliate(context)
    {
      try{
        let resource = '/klaviyo/toggle_sync_klaviyo_approved_affiliate';
        let data = await ApiService.put(resource);
        context.commit('TOGGLE_SYNC_KLAVIYO_APPROVED_AFFILATE',data.data.data);
        return true;
      }
      catch(err)
      {
        return err;
      }
    },

    async toggleKlaviyoSyncEnabled(context)
    {
      try{
        let resource = `/klaviyo/toggle_klaviyo_sync_enabled`;
        let data = await ApiService.put(resource);
        context.commit('TOGGLE_KLAVIYO_SYNC_ENABLED',data.data.data);
        return true;
      }
      catch(err){
        return err;
      }
    }

}

const mutations = {
    SET_LAYOUT_DATA(state, payload){
        let default_autopay_discount_code = state.settings.autopay_discount_code;
        let klaviyo = state.settings.klaviyo;
        state.settings = payload.shopSettings;
        if(!state.settings.autopay_discount_code)
        {
          state.settings.autopay_discount_code = default_autopay_discount_code;
        }
        if(!state.settings.klaviyo)
        {
          state.settings.klaviyo = klaviyo;
        }
        state.paymentMethodAvailable = payload.paymentMethodAvailable;
        state.groups = payload.groups;
        state.is_feedback = payload.is_feedback;
        state.settings.coutUnreadNotifications = payload.coutUnreadNotifications;
    },

    UPDATE_PAYMENT_METHOD_AVAILABLE(state, payload){
        state.paymentMethodAvailable = payload;
      },

    UPDATE_AFFILIATE_SETTING(state, payload){
      state.affiliateSetting = payload;
    },

    FEED_BACK(state, payload){
        state.is_feedback = payload;
      },

    PREPEND_ITEM_TO_GROUPS(state, newGroup) {
      state.groups.unshift(newGroup);
    },

    DELETE_ITEM_GROUP(state, id){
      state.groups = state.groups.filter((group)=>{
        return group.id != id;
      })
    },

    UPDATE_GROUP_OK(state, updatedGroup) {
      var foundIndex = state.groups.findIndex(x => x.id == updatedGroup.id);
      if(foundIndex > -1) {
        state.groups[foundIndex] = updatedGroup;
      }
    },
    SET_SHOP_SETTING(state,data){
      state.settings = data;
    },
    SET_SHOP_SETTING_UPDATE(state,data){
      state.settings.is_verified_from_email=data.shopSetting.is_verified_from_email;
      state.settings.from_contact_name=data.shopSetting.from_contact_name;
      state.settings.from_contact_email=data.emailDefault;
    },
    SET_SHOP_SETTING_WAIT_VERIFI(state,data){
      state.settings.is_verified_from_email=data.shopSetting.is_verified_from_email;
      state.settings.from_contact_name=data.shopSetting.from_contact_name;
      if(data.shopSetting.is_verified_from_email)
      {
        state.settings.from_contact_email=data.shopSetting.from_contact_email;
      }
    },
    UPDATE_GROUP_BY_AFFILIATE_COUNT(state,payload){
      let index=state.groups.findIndex(g=>g.id==payload.idGroupCurrent);
      state.groups[index].affiliates_count-= payload.countAffiliate;
      let indexGroupChange=state.groups.findIndex(g=>g.id==payload.idGroupChange);
      state.groups[indexGroupChange].affiliates_count+= payload.countAffiliate;
    },
    UPDATE_SHOPSETTING_BY_PRICE_RULE(state,payload){
      if(payload)
      {
        state.settings.price_rule=payload;
      }
    },
    SET_SHOP_SETTING_BY_CONTACT_EMAIL(state,data)
    {
      state.settings.contact_email=data.contact_email;
      state.settings.contact_name=data.contact_name;
    },
    SET_SHOP_SETTING_BY_YOURS_NOTIFICATIONS(state,data)
    {
      if(data.type=='new_registrations')
      {
        state.settings.notify_self_new_registrations=data.status;
      }
      if(data.type=='new_order')
      {
        state.settings.notify_self_new_order=data.status;
      }
    },
    UPDATE_POPUP(state, payload){
        state.settings.checkout_popup_data = payload;
    },
    UPDATE_ACTIVE_POPUP(state, payload){
        state.settings.activate_popup_checkout = payload;
    },
    CHANGE_CHECKOUT_POPUP(state, payload) {
        state.settings.checkout_popup_data = payload;
    },
    UPDATE_AFFILIATE_LANGUAGE(state,payload){
      state.settings.default_affiliate_language = payload.default_affiliate_language;
      state.settings.enable_affiliate_language = payload.enable_affiliate_language;
      state.settings.auto_detect_language = payload.auto_detect_language;
    },
    LOAD_GROUPS(state, payload)
    {
      state.groups = payload;
    },
    UPDATE_LOGO_SET_SHOP_SETTING(state, payload)
    {
      state.settings.logo = payload.logo;
      state.settings.path_name = payload.path_name;
    },
    UPDATE_GENERAL_SHOP_SETTING(state, payload)
    {
      state.settings.brand_name = payload.brand_name;
      state.settings.logo = payload.logo;
      state.settings.sub_domain = payload.sub_domain;
      state.settings.favicon = payload.favicon;
      state.settings.path_name = payload.path_name;
    },
    UPDATE_DEFAULT_AFFILIATE_LINK(state, payload)
    {
      state.settings.default_affiliate_link = payload.default_affiliate_link;
      state.settings.allow_affiliates_custom_link = payload.allow_affiliates_custom_link;
    },
    UPDATE_NO_LOGO_SHOP_SETTING(state, payload)
    {
      state.settings.brand_name = payload.brand_name;
      state.settings.sub_domain = payload.sub_domain;
      state.settings.favicon = payload.favicon;
    },
    UPDATE_SELF_GENERATING_COUPON(state, payload)
    {
      state.settings.autopay_discount_code = payload.autopay_discount_code;
      state.settings.allow_self_generating_coupon = payload.allow_self_generating_coupon;
    },
    TOGGLE_SYNC_KLAVIYO_APPROVED_AFFILATE(state, payload)
    {
      state.settings.sync_klaviyo_approved_affiliate = payload.sync_klaviyo_approved_affiliate;
    },
    DO_INITIAL_SYNC(state,payload)
    {
      state.settings.klaviyo_sync_enabled = payload.klaviyo_sync_enabled;
      state.settings.klaviyo = payload.klaviyo;
    },
    TOGGLE_KLAVIYO_SYNC_ENABLED(state,payload)
    {
      state.settings.klaviyo_sync_enabled = payload.klaviyo_sync_enabled;
    },
    UPDATE_NOTIFICATION(state, payload){
        state.settings.coutUnreadNotifications = payload;
    }
};

export default {
  state,
  actions,
  mutations,
  getters
};
