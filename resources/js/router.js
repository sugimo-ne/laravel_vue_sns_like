import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './views/Home'
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
    ]
})

export default router