<template>
    <div class="left_bar border-right">
        <h1>
            left
        </h1>
        <div v-if="isLogin">
            <ul>
                <li @click="logout">
                    ログアウト
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
     computed:{
        isLogin(){
            return this.$store.getters['auth/check']
        },
        user(){
            return this.$store.getters['auth/user']
        },
     },
     methods:{
         async logout(){
             await this.$store.dispatch('auth/logout')
         }
     }
}
</script>

<style scoped>
 .left_bar{
        width:25%;
        background:white;
        height:100vh;
        overflow-y: scroll;
        position:fixed;
    }

</style>