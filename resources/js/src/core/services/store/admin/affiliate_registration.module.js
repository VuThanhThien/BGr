import ApiService from "@/core/services/api.service";
const state = {
    baseObjToGen: {
        name: "basicObj",
        logo :{
            isShow: true,
            width: 10
        },
        /**Background và border và cả form */
        bgAndBorder: {
            bg: "#f3f6f9",
            borderSize: 1,
            borderColor: "#e8e9f2",
            borderRadius: 10
        },
        /**size và padding của cả form */
        size: {
            width: 100,
            padding: 10
        },
        /**title và description */
        titleAndDescription: {
            title: {
                text: "My Custom Form",
                align: "text-center",
                color: "",
                font_family: "",
                font_size: 28,
                font_weight: "bold",
                font_style: "normal"
            },
            subtitle: {
                text: "Hello world",
            }
        },
        /***button */
        button: {
            classSubmitBtn: "",
            submittext: "Submit",
            style: "",
            buttonAlign: "justify-content-left",
            color: "#fff",
            backgroundColor: "#3699ff",
            borderRadius: 3
        },
        captcha:{
            isShow: false,
            theme: "light",
        },
        /**thông tin chi tiết bên cạnh form chính */
        detailObj: {
            mainHeading: "PROGRAM DETAILS",
            background: "",
            color: "",
            fontfamily: "",
            detailText: [
                {
                    label: "Reward",
                    content: "Percent of sale"
                },
                {
                    label: "Reward value",
                    content: "1%"
                },
                {
                    label: "Cookie days",
                    content: "30"
                }
            ]
        },
        /**custom lại phần input */
        customInput:{
            labelFamily:"",
            labelColor: '#3F4254',
            fontWeight: 'bold',
            fontSize: 14,
            fontStyle: "normal"
        },
        model: {
            first_name: "",
            last_name:"",
            email: "",
            password: "",
            password_confirmation: "",
            company:"",
            address:"",
            city:"",
            zipcode:"",
            state:"",
            country:"",
            phone:"",
            facebook:"",
            youtube:"",
            instagram:"",
            twitter:"",
            website:"",
            personal_detail:""
        },
        tag:"div",
        schema: {
            fields: [
                {
                    type: "input",
                    inputType: "text",
                    label: "First Name",
                    model: "first_name",
                    placeholder: "John",
                    required: true,
                    visible: true,
                    disableEdit: true ,
                    styleClasses: ["apply-font-labelInput"],
                    validator: "required"
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Last Name",
                    model: "last_name",
                    placeholder: "Doe",
                    required: true,
                    visible: true,
                    disableEdit: true ,
                    styleClasses: ["apply-font-labelInput"],
                    validator: "required"
                },
                {
                    type: "input",
                    inputType: "email",
                    label: "Email Address",
                    model: "email",
                    placeholder: "Example@gmail.com...",
                    required: true,
                    visible: true,
                    disableEdit: true ,
                    styleClasses: ["apply-font-labelInput"],
                    validator: "required"
                },
                {
                    type: "input",
                    inputType: "password",
                    label: "Password",
                    model: "password",
                    placeholder: "",
                    required: true,
                    visible: true,
                    disableEdit: true ,
                    styleClasses: ["apply-font-labelInput"],
                    min: 6,
                    validator: ["required","string"]
                },
                {
                    type: "input",
                    inputType: "password",
                    label: "Password Confirmation",
                    model: "password_confirmation",
                    placeholder: "",
                    required: true,
                    visible: true,
                    disableEdit: true ,
                    min: 6,
                    styleClasses: ["apply-font-labelInput"],
                    validator: ["required","string"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Company",
                    model: "company",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"],
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Address",
                    model: "address",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "City",
                    model: "city",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Zip Code",
                    model: "zipcode",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "State",
                    model: "state",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Country",
                    model: "country",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "Number",
                    label: "Phone",
                    model: "phone",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Facebook",
                    model: "facebook",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Youtube",
                    model: "youtube",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Instagram",
                    model: "instagram",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Twitter",
                    model: "twitter",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Website",
                    model: "website",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
                {
                    type: "input",
                    inputType: "text",
                    label: "Personal detail",
                    model: "personal_detail",
                    placeholder: "",
                    required: false,
                    visible: false,
                    disableEdit: false ,
                    styleClasses: ["apply-font-labelInput"]
                },
            ]
        }
    }
};

const getters = {
    baseObjToGen(state) {
        return state.baseObjToGen;
    }
};

const mutations = {
    CHANGE_OBJ(state, payload) {
        state.baseObjToGen = payload;
    },
    UPDATE_REGISTRATION_OK(state, payload){
        state.baseObjToGen = payload;
    }
};

const actions = {
    changeBaseObj({ commit }, baseObjToGen) {
        commit("CHANGE_OBJ", baseObjToGen);
    },
    updateRegistration({commit}, {id, obj}) {
        return new Promise((resolve,reject) => {
            var payload = { registration_form: obj };
            ApiService.update("groups", id + "/registration_form", payload)
          .then(
            ({data}) => {
              commit('UPDATE_REGISTRATION_OK', obj);
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
