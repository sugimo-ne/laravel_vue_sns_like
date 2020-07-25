<template>
  <div>
    <div class="select">
      <div class="push" @click="pushPostView">
        <Post
          class="position"
          :id="id"
          :content="content"
          :created_at="created_at"
          :name="user.name"
          :profile="user.image"
          :filename="filename"
        >
        </Post>
      </div>
      <div class="row items p-1">
        <div class="offset-3"></div>
        <div class="col-3 actions">
          <i class="fas fa-heart pr-1" @click="likeCheck" :class="{'liked':isLike}"></i>
          {{likeCount}}
        </div>
        <div class="col-3 actions">
          <i class="far fa-comment-dots pr-1"></i>
          {{commentCount}}
        </div>
        <div class="offset-3"></div>
      </div>
    </div>
  </div>
</template>

<script>
import Post from "./Post";
import { OK } from "../../util";
export default {
  data() {
    return {
        likeCount:null,
        isLike:false,
    };
  },
  mounted(){
      this.likeCount = this.likes_count
      this.isLike = this.liked_by_user
  },
  computed: {
    commentCount() {
      return this.comments.length;
    },
    imageUrl(){
      return this.$store.getters["auth/imageUrl"]
    },
  },
  components: {
    Post
  },
  props: {
    id: Number,
    content: String,
    created_at: String,
    filename:String,
    user: {
      name: String,
      image:String
    },
    login: Boolean,
    comments: Array,
    liked_by_user: Boolean,
    likes_count: Number
  },
  methods: {
    pushPostView() {
      let postId = this.id;
      let user = this.user.name;
      this.$router.push(`/${user}/${postId}`);
    },
    likeCheck() {
      if(this.login){
          if (this.isLike) {
          this.unlike();
        } else {
          this.like();
        }
      }else{
        console.log('loginセイ')
      }
     
    },
    async like() {
      const response = await axios.put(`/api/posts/${this.id}/like`);
      if (response.status !== OK) {
        this.$store.commit("error/setErrorCode", response.status);
        return false;
      } else {
        this.likeCount++;
        this.isLike = true;
      }
    },
    async unlike() {
      const response = await axios.delete(`/api/posts/${this.id}/like`);
      if (response.status !== OK) {
        this.$store.commit("error/setErrorCode", response.status);
        return false;
      } else {
        this.likeCount--;
        this.isLike = false;
      }
    }
  }
};
</script>

<style scoped>
i {
  font-size: 3rem;
}
.owner {
  font-size: 0.8rem;
  font-weight: bold;
}
.select {
  transition: 0.4s;
}
.select:hover {
  background: rgba(215, 218, 235, 0.4);
}
.actions i {
  font-size: 1rem;
}
.liked{
  transition: 0.7s;
  color:rgba(233, 40, 40, 0.74);
}
</style>