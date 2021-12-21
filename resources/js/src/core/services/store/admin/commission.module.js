import ApiService from "@/core/services/api.service";

const state = {
    conversions: {
      data:[]
    },
    paginate: 10,
    sortDirection: "desc",
    sortField: "created_at",
    startDate: null,
    endDate: null,
    affiliateSelected:null,
    status: "",
    loadingCommissionTable:false,
    
};

const getters = {
  conversions(state) {
    return state.conversions;
  },
  paginate(state) {
    return state.paginate;
  }
};

const actions = {

  loadCommissions(context, page=1) {
    state.loadingCommissionTable = true;
    let resource = 'commissions'+
    '?page='+page+
    '&paginate='+
    state.paginate+
    "&sort_direction=" +
    state.sortDirection +
    "&sort_field="+
    state.sortField;

    if(state.affiliateSelected) {
      resource+='&affiliate='+state.affiliateSelected.id;
    }
    if(state.startDate) {
      resource+='&start_date='+state.startDate;
    }
    if(state.endDate) {
      resource+='&end_date='+state.endDate;
    }
    if(state.status) {
      resource+='&status='+state.status;
    }
    
    ApiService.query(resource)
    .then(({data}) => {
        context.commit('LOAD_COMMISSIONS_OK', data.data);
        state.loadingCommissionTable = false;
    })
  },
  
  loadCommission({commit}, id) {
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

  changeSort({commit, dispatch }, field) {
    if (state.sortField == field) {
      state.sortDirection = state.sortDirection == "asc" ? "desc" : "asc";
    } else {
      state.sortDirection = "desc";
      state.sortField = field;
    }
    dispatch('loadCommissions');
  },

  approve({commit}, id) {
    ApiService.put("commissions/"+id+"/approve")
      .then(
        ({data}) => {
          commit('UPDATE_STATUS_OK', data.data);
          resolve();
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
  },
  reject({commit}, id) {
    ApiService.put("commissions/"+id+"/reject")
      .then(
        ({data}) => {
          commit('UPDATE_STATUS_OK', data.data);
          resolve();
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
  },
  undo({commit}, id) {
    ApiService.put("commissions/"+id+"/undo")
      .then(
        ({data}) => {
          commit('UPDATE_STATUS_OK', data.data);
          resolve();
        })
      .catch(({ response }) => {
          context.commit(SET_ERROR, response.data.errors);
        });
  }

  
};

const mutations = {
  LOAD_COMMISSIONS_OK(state, payload){
    state.conversions = payload;
  },
  LOAD_COMMISSION_OK(state, payload){
    state.group = payload;
  },
  UPDATE_COMMISSION_OK(state, payload) {
    state.group = payload;
  },
  SET_RANGE_TIME_PICKER(state, payload){
    state.startDate = payload.startDate;
    state.endDate = payload.endDate;
  },
  SET_STATUS(state, payload){
    state.status = payload.status;
  },
  SET_AFFILIATE_SELECTED(state, payload){
    state.affiliateSelected = payload;
  },
  UPDATE_STATUS_OK(state, payload) {
    var foundIndex = state.conversions.data.findIndex(x => x.id == payload.id);
    state.conversions.data[foundIndex].status = payload.status;

  }
  
};

export default {
  state,
  actions,
  mutations,
  getters
};
