<template>
  <div>
    <div v-if="loading">loading</div>
    <div v-else>
      <ul>
        <li v-for="like in likesReverse" :key="like.id" @click="postDtail(like.id)" class="select border-bottom">
          <Post
            :id="like.id"
            :content="like.content"
            :created_at="like.created_at"
            :name="like.user"
            :user_id="like.user_id"
          ></Post>

        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Post from "../../components/posts/Post";
export default {
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    likesReverse(){
        return this.likesArr.slice().reverse()
    }
  },
  components: {
    Post
  },
  props: ["currentUserId"],
  data() {
    return {
      loading: false,
      likes: null,
      likesArr:[]
    };
  },
  methods: {
    postDtail(id) {
      this.$router.push(`/from_${this.currentUserId}/${id}`);
    }
  },
  created() {
    this.loading = true;
    axios
      .get(`/api/user/likes/${this.currentUserId}`)
      .then(response => {
         this.likes = response.data
        let likes = []
        let length = Object.values(this.likes).length
         for(let i = 0; i < length; i++){
             let like = this.likes[i]
             likes.push(like)
            // this.likesArr.push(like)
            console.log(likes)
         }
         let likesArr = []
         likes.forEach((item , index) => {
          axios.get(`/api/${item.user_id}`).then(response => {
             likes[index].user = response.data.name;
             likesArr.push(likes[index])
          })
        })
        console.log(likesArr)
        this.likesArr = likesArr
        this.loading = false;
      })
  }
};
</script>

<style scoped>
ul {
  padding: 0;
  list-style-type: none;
}
.select {
  transition: 0.4s;
}
.select:hover {
  background: rgba(215, 218, 235, 0.4);
}
</style>