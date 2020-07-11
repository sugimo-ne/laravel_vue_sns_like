const state = {
    code:null
}

const getters = {
    error: state => state.code
}

const mutations = {
    setErrorCode(state , code){
        state.code = code
    }
}

export default{
    namespaced:true,
    state,
    getters,
    mutations
}