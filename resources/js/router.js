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
            component:Home
        },
        {
            path:'/500',
            component:SystemError
        },
        {
            path:'/:user/:postId',
            component:PostDetail,
            props: true
        }
    ]
})

export default router