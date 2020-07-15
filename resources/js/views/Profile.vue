<template>
    <div>
        <div v-if="loading">
            <h1>
                プロフィール
            </h1>
            now loading ...
        </div>
        <div v-else>
            <div class="row">
                <div class="col-6">
                    <div class="p-3">
                        <i class="fas fa-user-circle profile"></i><br>
                        <h1>{{user.name}}</h1>
                    </div>
                </div>
            </div>
            <hr>
            <ul>
                <li class="border-bottom" v-for="post in posts" :key="post.id">
                   <Posts
                    v-bind="post"
                    :login="isLogin"
                    ></Posts>
                </li>
            </ul>
            <!-- {{posts}} -->
            <hr>
            {{comments}}
        </div>
    </div>
</template>

<script>
import Posts from "../components/posts/Posts";
export default {
    props:['id'],
    computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    }
  },
    components: {
    Posts
  },
    data(){
        return{
            loading:false,
            user:null,
            posts:null,
            comments:null
        }
    },
    created(){
        this.loading = true
        axios.get(`/api/user/${this.id}`).then(response => {
            let res = response.data
            this.user = res.user
            this.posts = res.posts
            this.comments = res.Comments
            console.log(this.posts)
            this.loading = false
        })
    }
}
</script>

<style scoped>
    .profile{
        font-size: 4.5rem;
    }
    h1{
        font-size: 1.5rem;
    }
    ul {
  padding: 0;
  list-style-type: none;
}
</style>