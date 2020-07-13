<template>
    <div class="nav row border-bottom no-gutters">
        <div class="col-8">
            <h1>{{title}}</h1>
        </div>
        <div class="col-4 text-right">
            <div class="p-3" v-if='!isLogin'>
                <i class="fas fa-user-circle"></i>
            guest
            </div>
            <div class="p-3" v-else>
                <i class="fas fa-user-circle"></i>
                {{user.name}}
            </div>
            
        </div>
    </div>
</template>

<script>
export default {
    props:['isLogin' , 'user' ],
    computed:{
        title(){
            return this.$route.name
        }
    },
    mounted(){
        console.log(this.isLogin)
    },
}
</script>

<style scoped>
    i{
        font-size: 2rem;
    }
    .nav{
        z-index:10;
        background: white;
        height:55px;
        position: fixed;
    }


</style>