import ApiService from "@/core/services/api.service";
const state = {
    loginLayoutData:{
        logo : {
            isShow : true,
            width : 30
        },
        size : {
            width: 450,
            mt: 20,
            px: 45,
            py: 24,
            gapImg: 10,
        },
        form:{
            bg : "#ffffff",
            borderRadius : 10,
            fontFamily: "Poppins",
            fontSizeTitle: 20,
            titleColor: "#000",
            bgMain: "#EEF0F8"
        },
        button : {
            buttonAlign : "fullW",
            color : "#fff",
            backgroundColor : "#000000",
            borderColor : "#000000",
            borderRadius : 10,
        },
        customcss : "",
    }
};

const getters = {
    loginLayoutData(state) {
        return state.loginLayoutData;
    }
};

const mutations = {
    CHANGE_LOGIN_LAYOUT(state, payload) {
        state.loginLayoutData = payload;
    },
    UPDATE_LAYOUT_LOGIN(state, payload){
        state.loginLayoutData = payload;
    }
};

const actions = {
    changeLoginLayout({ commit }, loginLayoutData) {
        commit("CHANGE_LOGIN_LAYOUT", loginLayoutData);
    },
    updateLayoutLogin({commit}, obj) {
        return new Promise((resolve,reject) => {
            var payload = { data: obj };
            console.log(payload);
            ApiService.update("setting","login-form-config", payload)
          .then(
            ({data}) => {
              commit('UPDATE_LAYOUT_LOGIN', obj);
              resolve();
            })
          .catch(({ response }) => {
              reject(response);
            });
        });
      },
};

export default {
    state,
    actions,
    mutations,
    getters
};
