<template>
    <div class="right_bar border-left">
        <div v-if="!isLogin">
            <div class="container">
                <LoginForm></LoginForm>
            </div>
        </div>
     
    </div>
</template>

<script>

import Content from './right/Content'
import LoginForm from './right/LoginForm'
export default {
    computed:{
        isLogin(){
            return this.$store.getters['auth/check']
        },
        user(){
            return this.$store.getters['auth/user']
        }
     },
    components:{
        Content,
        LoginForm
    }
}
</script>

<style scoped>
    .right_bar{
        width:25%;
        background:white;
        height:100vh;
        overflow-y: scroll;
        position:fixed;
    }
</style>