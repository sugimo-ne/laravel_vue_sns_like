<template>
<div @click="pushPostView">
    <Post
     :id='id'
     :content='content'
     :created_at="created_at"
     :name="user.name"
     >
     </Post>
</div>
    <!-- <div class="select">
        <div class="row no-gutters p-2">
            <div class="col-1 text-center p-2">
                <i class="fas fa-user-circle"></i>
                <br>
            </div>
            <div class="col-11 pl-2">
                <span class="owner"><span class="mr-5">{{user.name}}</span>{{created_at}}</span>
                <br>
                <p class="pl-3 pt-2">
                    {{content}}
                </p>
            </div>
        </div>
    </div> -->
</template>

<script>
import Post from './Post'
export default {
    components:{
        Post,
    },
    props:{
        id:Number , 
        content:String,
        created_at:String,
        user:{
            name:String
        }
    },
    methods:{
        pushPostView(){
            let postId = this.id
            let user = this.user.name
            this.$router.push(`/${user}/${postId}`)
        }
    }
}
</script>

<style scoped>
  i{
        font-size: 3rem;
    }

    .owner{
        font-size:0.8rem;
        font-weight:bold;
    }
    .select{
        transition: 0.4s;
    }
    .select:hover{
        background:rgba(215, 218, 235, 0.4)
    }

</style>