import ApiService from "aff/core/services/api.service";
import JwtService from "aff/core/services/jwt.service";

// action types
export const VERIFY_AUTH = "verifyAuth";
export const LOGIN = "login";
export const LOGOUT = "logout";
export const REGISTER = "register";
export const UPDATE_PASSWORD = "updateUser";

// mutation types
export const PURGE_AUTH = "logOut";
export const SET_AUTH = "setUser";
export const UPDATE_USER = "UPDATE_USER";
export const SET_PASSWORD = "setPassword";
export const SET_ERROR = "setError";

const state = {
  errors: null,
  user: {},
  isAuthenticated: !!JwtService.getToken()
};

const getters = {
  currentUser(state) {
    return state.user;
  },
  isAuthenticated(state) {
    return state.isAuthenticated;
  },
  isActive(state) {
    return state.user.status == 1;
  },
};

const actions = {
  [LOGIN](context, credentials) {
    return new Promise((resolve, reject) => {
      ApiService.post("login", credentials)
        .then(({ data }) => {
          context.commit(SET_AUTH, data);
          resolve(data);
        })
        .catch(({ response }) => {
          reject(response);
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  },
  [LOGOUT](context) {
    context.commit(PURGE_AUTH);
  },
  [REGISTER](context, credentials) {
    return new Promise((resolve, reject) => {
      ApiService.post("register", credentials)
        .then(({ data }) => {
          context.commit(SET_AUTH, data);
          resolve(data);
        })
        .catch(({ response }) => {
          reject(response);
          context.commit(SET_ERROR, response.data.errors);
        });
    });
  },

  [VERIFY_AUTH](context) {
    if (JwtService.getToken()) {

      return new Promise((resolve, reject) => {
        ApiService.setHeader();
        ApiService.query("verify")
          .then(({ data }) => {
            context.commit(SET_AUTH, data);
            resolve(data.affiliate.status);
          })
          .catch(({ response }) => {
            reject(response);
            context.commit(SET_ERROR, response.data.errors);
          });
      });
    } else {
      context.commit(PURGE_AUTH);
    }
  },
  [UPDATE_PASSWORD](context, payload) {
    const password = payload;

    return ApiService.put("password", password).then(({ data }) => {
      context.commit(SET_PASSWORD, data);
      return data;
    });
  }
};

const mutations = {
  [SET_ERROR](state, error) {
    state.errors = error;
  },
  [SET_AUTH](state, data) {
    state.isAuthenticated = true;
    state.user = data.affiliate;
    state.errors = {};
    JwtService.saveToken(data.token);
  },
  [SET_PASSWORD](state, password) {
    state.user.password = password;
  },
  [PURGE_AUTH](state) {
    state.isAuthenticated = false;
    state.user = {};
    state.errors = {};
    JwtService.destroyToken();
  },
  [UPDATE_USER](state, data) {
    state.user = data;
  },
  UPDATE_PAYMENT(state, data) {
    state.user.payment_method = data.payment_method;
    state.user.payment_info = data.payment_info;
  },
  updateAffiliatesCustomLink(state,data){
    state.user.affiliates_custom_link=data.affiliates_custom_link;
  }
};

export default {
  state,
  actions,
  mutations,
  getters
};
