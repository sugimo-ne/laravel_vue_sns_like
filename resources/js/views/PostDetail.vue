<template>
    <div class="main">
        <transition name="fade">
            <div v-if="deleteModal">
                <div>
                    <div class="modal_cover" @click="deleteModal=false" ></div>
                        <div class="modal_content">
                            <p>この投稿を削除しますか？</p>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-light" @click="deleteModal = false">＜　戻る</button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-danger" @click="deletePost">削除する</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </transition>
        <div v-if="loading">
            now loading...
        </div>
        <div v-else class="container">
            <Post 
            :id='post.id'
            :content='post.content'
            :created_at="post.created_at"
            :name="post.user.name">
            </Post>
            <hr>
            <div class="row">
                <div class="col-3 actions">
                    <span class="info">いいね</span>
                </div>
                <div class="col-3 actions">
                    <span class="info">コメント</span>
                </div>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-3 actions">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="col-3 actions">
                    <i class="far fa-comment-dots"></i>
                </div>
                <div class="col-3 actions">
                    	
                </div>
                <div class="col-3 actions" @click="deleteModal = true">
                    <div v-if="post.user_id === currentUser.id">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script>
import Post from '../components/posts/Post'
export default {
    computed:{
        currentUser(){
            return this.$store.getters['auth/user']
        }
    },
    components:{
        Post,
    },
    props:['postId' , 'user'],
    data(){
        return{
            loading:false,
            post:null,
            deleteModal:false,
        }
    },
    created(){
       this.getPostDetail()
    },
    methods:{
        getPostDetail(){
            this.loading = true
            axios.get(`/api/posts/${this.postId}`).then(response => {
                this.post = response.data
                 console.log(this.post)
                this.loading = false
            })
        },
        deletePost(){
            if(this.post.user_id === this.user.id){
                    axios.post(`/api/posts/delete/${this.postId}`).then(response => {
                    this.$router.push('/')
                })
            } 
        }
    },
}
</script>

<style scoped>
    .fade-enter,
    .fade-leave-to{
        opacity: 0;
    }
    .fade-enter-to,
    .fade-leave{
        opacity:1;
    }
    .fade-enter-active,
    .fade-leave-active{
        transition:opacity 0.3s;
    }
    .main{
        position:relative;
    }
    .modal_cover{
        background: rgba(0,0,0,0.5);
        height: 100vh;
        position: absolute;
        width: 100%;
    }
    .modal_content{
        z-index:15;
        position: absolute;
        top: 50%;
        transform: translate(-50%,-50%);
        left: 50%;
        background:white;
        padding: 40px;
        width: 60%;
    }
    .info{
        font-size: 0.7rem;
        font-weight:bold;
    }
    .actions{
        text-align:center;
        
    }
</style>