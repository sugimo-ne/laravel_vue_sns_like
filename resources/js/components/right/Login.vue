<template>
    <div>
        <form class="p-5 border" @submit.prevent='login'>
            <h2 class="pb-3">新規登録</h2>
           <div class="error" v-if="loginErrors">
               <p class="text-center">
                   メールアドレス<br>
                   パスワードのいずれかが
                   違います
               </p>
           </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="text" class="form-control" id="email" placeholder="メールアドレス" v-model="loginForm.email">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" class="form-control" id="password" v-model="loginForm.password">
            </div>
        

            <button class="btn btn-info form-control">登録</button>
        </form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            loginForm:{
                  email:null,
                  password:null
              }
        }
    },
    computed:{
        apiStatus(){
            return this.$store.getters['error/error'];
        },
        loginErrors(){
            return this.$store.getters['auth/loginErrorMessages']
        }
    },
    methods:{
        async login(){
           await this.$store.dispatch('auth/login' , this.loginForm)
           console.log(this.loginErrors)

           this.loginForm.email = ''
           this.loginForm.password = ''
        },
         clearError () {
    this.$store.commit('auth/setLoginErrorMessages', null)
  }
    },
    created () {
  this.clearError()
}
}
</script>

<style scoped>
    form{
        border-radius:20px;
    }
    .error{
        border-radius: 20px;
        box-shadow: 0px 0px 4px rgba(0 , 0 , 0 , .8);
        background: rgba(238, 103, 103, 0.699);
    }
    .error p{
        color: red;
        font-weight: 600;
    }
</style>