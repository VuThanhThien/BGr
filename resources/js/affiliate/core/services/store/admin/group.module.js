import ApiService from "@/core/services/api.service";

const state = {
  groups: {
    current_page:1,
    data:[]
  },
  group: {},
  formatMoney1:'${{amount}}'
};

const getters = {
  groups(state) {
    return state.groups.data;
  },
  group(state) {
    return state.group;
  },
  moneyFormat1(state) {
    return state.formatMoney1;
  }
};

const actions = {
  getListGroup(context) {
    ApiService.get('groups')
    .then(({data}) => {
        context.commit('LOAD_GROUPS_OK', data.data);
    })
  },
  postGroup(context, group) {
    return new Promise(resolve => {
      ApiService.post("groups", group)
        .then((data ) => {
          resolve(data);
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  },
  loadGroup({commit}, id) {
    return new Promise(resolve => {
      ApiService.get("groups", id)
      .then(
        ({data}) => {
          commit('LOAD_GROUP_OK', data.data)
          resolve()
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  },

  updateGroup({commit}, {id, group}) {
    return new Promise(resolve => {
      ApiService.update("groups", id, group)
      .then(
        ({data}) => {
          commit('UPDATE_GROUP_OK', data.data)
          resolve()
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  },

  updateMLM({commit}, {id, form}) {
    return new Promise(resolve => {
      ApiService.update("groups", id+'/mlm', form)
      .then(
        ({data}) => {
          commit('UPDATE_GROUP_OK', data.data)
          resolve()
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  }
  
};

const mutations = {
  LOAD_GROUPS_OK(state, payload){
    state.groups.data = payload;
  },
  LOAD_GROUP_OK(state, payload){
    state.group = payload;
  },
  UPDATE_GROUP_OK(state, payload) {
    state.group = payload;
  }
};

export default {
  state,
  actions,
  mutations,
  getters
};
