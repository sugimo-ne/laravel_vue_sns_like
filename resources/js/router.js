import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './views/Home'
import PostDetail from './views/PostDetail'
import SystemError from './views/errors/System'


Vue.use(VueRouter)

const router = new VueRouter({
    mode:'history',
    routes:[
        {
            path:'/',
            component:Home,
            name:'Home',
            props:true
        },
        {
            path:'/500',
            component:SystemError,
            name:'error',
            props:true
        },
        {
            path:'/:user/:postId',
            component:PostDetail,
            name:'詳細',
            props:true
        }
    ]
})

export default router