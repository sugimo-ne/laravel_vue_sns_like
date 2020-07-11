<template>
    <div>
        <div class="row no-gutters">
            <div class="col-3">
                <div class="side">
                    <LeftBar></LeftBar>
                </div>
            </div>
            <div class="col-6">
                <main>
                    <NavBar class="header" :isLogin="isLogin" :user="user"></NavBar>
                    <div class="view">
                        <router-view></router-view>
                    </div>

                </main>
            </div>
            <div class="col-3">
                <div class="side">
                    <RightBar></RightBar>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import NavBar from './components/NavBar'
import RightBar from './components/RightBar'
import LeftBar from './components/LeftBar'
import { INTERNAL_SERVER_ERROR } from './util'
export default {
    computed:{
         isLogin(){
            return this.$store.getters['auth/check']
        },
        user(){
            return this.$store.getters['auth/user']
        },
        error(){
            return this.$store.getters['error/error']
        }
    },
    watch:{
        error:{
            handler(val){
                if(val === INTERNAL_SERVER_ERROR){
                    this.$route.ush('/500')
                }
            },
            immediate: true
        },
         $route () {
            this.$store.commit('error/setErrorCode', null)
        }
    },
    components:{
        NavBar,
        RightBar,
        LeftBar
    },

}
</script>

<style scoped>
    .header{
        width:50%;
    }
    .view{
        padding-top:55px;
    }
</style>