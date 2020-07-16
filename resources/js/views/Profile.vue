<template>
  <div>
    <div v-if="loading">
      <h1>プロフィール</h1>now loading ...
    </div>
    <div v-else>
      <div class="row">
        <div class="col-6">
          <div class="p-3">
            <i class="fas fa-user-circle profile"></i>
            <br />
            <h1>{{user.name}}</h1>
          </div>
        </div>
      </div>
      <div class="profile_detail">
        <div v-if="user.intro == null">
          <p v-if="currentUser.id == user.id" class="p-2">プロフィール編集で自己紹介文を入力できます</p>
          <p v-else class="p-2">よろしくお願いします</p>
        </div>
        <p v-else class="p-2">{{user.intro}}</p>
        <small>開始日:{{user.created_at}}</small>
      </div>
      <div class="row border-bottom">
        <div class="col-4 text-center tab" @click="showPosts" :class="{'active_tab': tab == 1}">
          <span>投稿</span>
        </div>
        <div class="col-4 text-center tab" @click="showComments" :class="{'active_tab': tab == 2}">
          <span>コメント</span>
        </div>
        <div class="col-4 text-center tab" @click="showLikes" :class="{'active_tab': tab == 3}">
          <span>いいね</span>
        </div>
      </div>
        <div v-if="tab == 1">
            <router-view 
        :posts="posts" 
        :isLogin="isLogin"
        ></router-view>
        </div> 
        <div v-if="tab == 2">
            <ul>
                <li v-for="comment in comments" :key="comment.id">
                    <router-view
                    :id="comment.id"
                    :content="comment.content"
                    :image="currentUser.image"
                    :created_at="comment.created_at"
                    :post_id="comment.post_id"
                    :commenter="user.name"></router-view>
                 </li>
            </ul>
            
        </div>

        <div v-if="tab == 3">
            <router-view
            :current-user-id="currentUser.id"
            >

            </router-view>
        </div>
      
    </div>
  </div>
</template>

<script>
import Posts from "../components/posts/Posts";
import Comment from "../components/posts/Comment";
export default {
  props: ["id"],
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    },
    // tab(){
    //     let name = this.$route.name
    //     if(name =='投稿'){
    //         return 1
    //     }
    //     else if(name == 'コメント'){
    //         return 2
    //     }
    //     else{
    //         return 3
    //     }
    // }
  },
  components: {
    Posts,
    Comment
  },
  data() {
    return {
        tab:null,
      loading: false,
      user: null,
      posts: null,
      comments: null
    };
  },
  created() {
    this.loading = true;
    axios.get(`/api/user/${this.id}`).then(response => {
      let res = response.data;
      this.user = res.user;
      this.posts = res.posts;
      this.comments = res.Comments;
      console.log(this.posts);
      this.loading = false;

      let name = this.$route.name
        if(name =='投稿'){
            this.tab=1
        }
        else if(name == 'コメント'){
             this.tab=2
        }
        else{
             this.tab=3
        }
    });
  },
  methods: {
    showPosts() {
      this.$router.push(`/${this.id}/user/profile/posts`);
      this.tab = 1;
    },
    showComments() {
      this.$router.push(`/${this.id}/user/profile/comments`);
      this.tab = 2;
    },
    showLikes() {
      this.$router.push(`/${this.id}/user/profile/likes`);
      this.tab = 3;
    }
  }
};
</script>

<style scoped>
.profile {
  font-size: 4.5rem;
}
h1 {
  font-size: 1.5rem;
}
ul {
  padding: 0;
  list-style-type: none;
}
.tab {
  padding: 10px 0;
}
.tab:hover {
  transition: 0.4s;
  background: rgba(100, 148, 237, 0.322);
}
.active_tab {
  background: rgba(100, 148, 237, 0.322);
}
</style>