import ApiService from "@/core/services/api.service";

const state = {
    currentDetailAffiliate:{}
    
};

const getters = {
  currentDetailAffiliate(state) {
    return state.currentDetailAffiliate;
  }
};

const actions = {
  
  loadAffiliate({commit}, id) {
    return new Promise((resolve,reject) => {
      ApiService.get("affiliates", id)
      .then(
        ({data}) => {
          commit('SET_CURRENT_DETAIL_AFFILIATE', data.data)
          resolve(data)
        })
      .catch(({ response }) => {
        reject(response);
        });
    });
  },

  
};

const mutations = {
  SET_CURRENT_DETAIL_AFFILIATE(state, payload){
    state.currentDetailAffiliate = payload;
  },
  updateAffiliatesCustomLink(state,data){
    state.currentDetailAffiliate.affiliates_custom_link=data.affiliates_custom_link;
  }
};

export default {
  state,
  actions,
  mutations,
  getters
};
