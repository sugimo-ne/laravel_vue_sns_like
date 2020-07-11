<template>
    <div class="container pt-5">
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <span class="nav-link" 
                @click="currentComponent = 'Register'"
                :class="{'active':currentComponent == 'Register'}">ユーザー登録</span>
            </li>
            <li class="nav-item">
                <span class="nav-link" 
                @click="currentComponent = 'Login'"
                :class="{'active':currentComponent == 'Login'}">ログイン</span>
            </li>
        </ul>
    <Component :is="currentComponent"></Component>
    </div>
    
</template>

<script>
import Login from './Login'
import Register from './Register'
export default {
    components:{
         Login,
         Register,
    },
     data(){
         return{
              currentComponent:'Register',
         }
    }
}
</script>

<style scoped>

</style>