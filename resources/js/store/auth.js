import axios from "axios"
import router from '../router'
import {OK , INTERNAL_SERVER_ERROR , UNPROCESSABLE_ENTITY , CREATED} from '../util'

const state = {
    user:null,
    apiStatus:null,
    loginErrorMessages:null,
    registerErrorMessages: null,
    baseImageUrl:'https://my-laravel-vue-work01.s3-ap-northeast-1.amazonaws.com/'
}

const getters = {
    check:state => !!state.user,
    user:state => state.user,
    imageUrl:state => state.baseImageUrl,
    loginErrorMessages:state => state.loginErrorMessages,
    registerErrorMessages:state => state.registerErrorMessages,
}

const mutations = {
    setUser (state, user) {
      if(user){
      user.imageName = user.image
      user.image = "https://my-laravel-vue-work01.s3-ap-northeast-1.amazonaws.com/" + user.image||null
      }
      state.user = user
    },
    setApiStatus(state , status){
      state.apiStatus = status
    },
    setLoginErrorMessages(state , messages){
      state.loginErrorMessages = messages
    },
    setRegisterErrorMessages (state, messages) {
      state.registerErrorMessages = messages
    }
  }

const actions = {
    async register (context, data) {
      context.commit('setApiStatus' , null)
      const response = await axios.post('/api/register', data)
      if(response.status === CREATED){
        context.commit('setUser', response.data)
        context.commit('setApiStatus' , true)
        router.push('/')
        return false
      }
      
      context.commit('setApiStatus' , false)
      if(response.status === UNPROCESSABLE_ENTITY){
        context.commit('setRegisterErrorMessages' , response.data.errors)
      }else{
        context.commit('error/setErrorCode' , response.status , {route:true})
      }
    },
    async login(context , data){
        context.commit('setApiStatus' , null)
        const response = await axios.post('/api/login' , data).catch(e => e.response||e)
        
        if(response.status === OK){
          context.commit('setApiStatus' , true)
          context.commit('setUser', response.data)
          router.push('/')
          return false
        }

        context.commit('setApiStatus' , false)
        if(response.status === UNPROCESSABLE_ENTITY){
          context.commit('setLoginErrorMessages', response.data.errors)
        }else{
          context.commit('error/setErrorCode' , response.status , {route:true})
        }    
    },
    async logout (context) {
      context.commit('setApiStatus', null)
      const response = await axios.post('/api/logout')
  
      if (response.status === OK) {
        context.commit('setApiStatus', true)
        context.commit('setUser', null)
        router.push('/')
        return false
      }
  
      context.commit('setApiStatus', false)
      context.commit('error/setCode', response.status, { root: true })
    },
    async currentUser (context) {
      context.commit('setApiStatus', null)
      const response = await axios.get('/api/user')
      const user = response.data || null
  
      if (response.status === OK) {
        context.commit('setApiStatus', true)
        context.commit('setUser', user)
        return false
      }
  
      context.commit('setApiStatus', false)
      context.commit('error/setCode', response.status, { root: true })
    },

    async editUser(context , userInfo){
      context.commit('setApiStatus' , null)
      let response = await axios.put(`/api/${userInfo.id}/profile/edit` , userInfo).catch(e => e.response||e)
      console.log(response)
      if(response.status === 201){
        context.commit('setApiStatus' , true)
        //context.commit('setUser', response.data.data)
        window.location.reload()
        return false
      }

      context.commit('setApiStatus' , false)
      if(response.status === UNPROCESSABLE_ENTITY){
        context.commit('setLoginErrorMessages', response.data.errors)
      }else{
        context.commit('error/setErrorCode' , response.status , {route:true})
      }  
    }

  }
 

export default{
    namespaced:true,
    state,
    mutations,
    getters,
    actions
}