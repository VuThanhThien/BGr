import ApiService from "@/core/services/api.service";

const state = {
    payment_method_availabel:null,
    affiliate_setting:null,
    
};
const actions = {
    updatePaymentMethod(context, payload) {
        console.log(payload);
        return new Promise(resolve => {
          ApiService.post("settings/update-payment-method", payload)
            .then((data ) => {
              resolve(data);
            })
            .catch(({ response }) => {
              context.commit(SET_ERROR, response.data.errors);
            });
        });
    },
    
  };

  const mutations = {
    SET_PAYMENT_METHOD_AVAILABEL(state, payload){
        state.payment_method_availabel = payload;
    },
    SET_AFFILIATE_SETTING(state, payload){
        state.affiliate_setting = payload;
    }
};

export default {
  state,
  actions,
  mutations,
};



  