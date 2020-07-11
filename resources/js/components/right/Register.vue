<template>
    <div>
        <form class="p-5 border" @submit.prevent ='register'>
            <h2 class="pb-3">新規登録</h2>
            <div class="error" v-if="registerError">
               <p class="text-center">
                   既に存在しているメールアドレス、<br>もしくわ
                   パスワードが8文字以上である必要があります
               </p>
           </div>
           <div class="form-group">
                    <label for="name">お名前</label>
                    <input type="text" class="form-control" id="name" placeholder="お名前" v-model="registerForm.name">
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" class="form-control" id="email" placeholder="メールアドレス" v-model="registerForm.email">
                </div>

                <div class="form-group">
                    <label for="password">パスワード<br><span class="error_message">{{checkPassword}}</span></label>
                    <input type="password" class="form-control" id="password" v-model="registerForm.password" >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">確認用パスワード<br><span class="error_message">{{checkSamePassword}}</span></label>
                    <input type="password" class="form-control" id="password_confirmation" v-model="registerForm.password_confirmation">
                </div>

                <div v-if="validate">
                    <button class="btn btn-info form-control">
                        登録する
                    </button>
                </div>
                <div v-else class="text-center p-3 dummy">
                    <span class="btn btn-info form-control">
                        登録する
                    </span>
                </div>
                
        </form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            registerForm:{
                name: 'name',
                email: 'email@email',
                password: '',
                password_confirmation: ''
            }
        }
    },
    computed:{
        apiStatus(){
            return this.$store.getters['error/error'];
        },
        registerError(){
            return this.$store.getters['auth/registerErrorMessages']
        },
        checkPassword(){
            if(this.registerForm.password.length > 0){
                return this.registerForm.password.length >= 8 ? 'ok':'パスワードが短すぎです'
            }
        },
        checkSamePassword(){
            if(this.registerForm.password !== this.registerForm.password_confirmation){
                return 'パスワードが一致しません'
            }
        },
        validate(){
            if(this.registerForm.password.length >= 8 && this.registerForm.password == this.registerForm.password_confirmation){
                return true
            }
        }
    },
    methods:{
       async register () {
           await this.$store.dispatch('auth/register', this.registerForm)
            console.log(this.registerError)
        }
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
    .error_message{
        color:red;
        font-size: 0.5rem;
    }
    /* .dummy{
        border:1px solid black;
        background:white;
        color:black;
        font-weight: 500;
        padding:20px 40px;
        cursor: pointer;
        transition: .4s;
    }
    .dummy:hover{
        background: rgba(209, 87, 87, 0.87);
        color:white;
    } */

    .dummy{
        opacity: 0.5;
    }
</style>