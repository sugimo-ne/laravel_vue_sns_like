<template>
  <div>
    <div v-if="loading">loading</div>
    <div v-else>
      <ul>
        <li
          v-for="like in likes"
          :key="like.id"
          @click="postDtail(like.id)"
          class="select border-bottom"
        >
          <div v-if="like.user.id">
            <Post
              :id="like.id"
              :content="like.content"
              :created_at="like.created_at"
              :name="like.user.name"
              :user_id="like.user_id"
              :profile="like.user.image"
              :filename="like.filename"
            ></Post>
          </div>

          <Post
            v-else
            :id="like.id"
            :content="like.content"
            :created_at="like.created_at"
            :name="like.user.name"
            :user_id="like.image"
            :filename="like.filename"
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
    // likesReverse() {
    //   return this.likes.slice().reverse();
    // }
  },
  components: {
    Post
  },
  props: ["aboutUser"],
  data() {
    return {
      loading: false,
      likes: null,
      //likesArr: []
    };
  },
  methods: {
    postDtail(id) {
      this.$router.push(`/from_${this.aboutUser}/${id}`);
    }
  },
  created() {
    this.loading = true;
    axios.get(`/api/user/likes/${this.aboutUser}`).then(response => {
      console.log(response.data.posts)
      this.likes = response.data.posts;
      // let likes = [];
      // let length = Object.values(this.likes).length;
      // for (let i = 0; i < length; i++) {
      //   let like = this.likes[i];
      //   likes.push(like);
      //   // this.likesArr.push(like)
      //   console.log(likes);
      // }
      // let likesArr = [];
      // likes.forEach((item, index) => {
      //   axios.get(`/api/${item.user_id}`).then(response => {
      //     likes[index].user = response.data.name;
      //     likesArr.push(likes[index]);
      //   });
      // });
      // console.log(likesArr);
      // this.likesArr = likesArr;
      this.loading = false;
    });
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