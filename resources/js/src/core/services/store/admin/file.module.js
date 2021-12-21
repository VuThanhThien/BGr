const state={
    files:0
}
const getters={
    getListFile(state){
        return state.files
    }
}
const mutations={
    setListFile(state,payload)
    {
        state.files=payload
    },
    addFile(state)
    {
        state.files=state.files+1;
    }
}
 export default {
     state,mutations,getters
 }