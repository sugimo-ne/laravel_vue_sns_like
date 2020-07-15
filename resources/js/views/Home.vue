<template>
  <div class="back">
    <div v-if="!isLogin"></div>
    <div v-else>
      <div class="post_form mb-3">
        <div class="row border-bottom pl-3 pr-3">
          <div class="col-1 p-2">
            <i class="fas fa-user-circle icon"></i>
          </div>
          <div class="col-11 p-2">
            <textarea
              class="form-control"
              rows="2"
              placeholder="MESSAGE"
              v-model="postData.content"
            ></textarea>
            <br />
            <div class="actions text-right">
              <div v-if="postData.content !== ''">
                <button class="btn btn-info" @click="post">
                  <i class="fas fa-dove"></i>
                </button>
              </div>
              <div v-else>
                <span class="dummy btn btn-info">
                  <i class="fas fa-dove"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="post_area">
      <div v-if="loading">now loading...</div>
      <div v-else>
        <ul>
          <li class="border-bottom" v-for="post in posts" :key="post.id">
            <Posts
              v-bind="post"
              :login="isLogin"
              :commentCount="commentCount"
              :comments="post.comments"
            ></Posts>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>


<script>
import Posts from "../components/posts/Posts";
export default {
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    }
  },
  components: {
    Posts
  },
  data() {
    return {
      loading: false,
      posts: [],
      commentCount: 0,
      postData: {
        content: "",
        filename: ""
      }
    };
  },
  created() {
    this.getPosts();
  },
  methods: {
    post() {
      this.loading = true;
      console.log(this.postData);
      (this.postData.user_id = this.$store.getters["auth/user"].id),
        axios.post("/api/posts", this.postData).then(response => {
          console.log(response);
          this.postData.content = "";
          this.getPosts();
        });
    },
    getPosts() {
      this.loading = true;
      axios
        .get("/api/posts")
        .then(response => {
          this.posts = response.data.data;
          console.log(this.posts);
          this.loading = false;
        })
        .catch(e => {
          console.log("error");
        });
    },
    // likeCheck() {
    //   if (this.liked_by_user) {
    //     this.unlike();
    //   } else {
    //     this.like();
    //   }
    // },
    // async like() {
    //   const response = await axios.put(`/api/posts/${this.id}/like`);
    //   if (response.status !== OK) {
    //     this.$store.commit("error/setErrorCode", response.status);
    //     return false;
    //   } else {
    //     this.likes_count++;
    //     this.liked_by_user = true;
    //   }
    // },

    // async unlike() {
    //   const response = await axios.delete(`/api/posts/${this.id}/like`);
    //   if (response.status !== OK) {
    //     this.$store.commit("error/setErrorCode", response.status);
    //     return false;
    //   } else {
    //     this.likes_count--;
    //     this.liked_by_user = false;
    //   }
    // }
  }
};
</script>

<style scoped>
ul {
  padding: 0;
  list-style-type: none;
}
.icon {
  font-size: 3rem;
}

textarea {
  border: none;
  resize: none;
}
.back {
  background: rgb(240, 240, 240);
}
.post_area,
.post_form {
  background: white;
}
.dummy {
  /* color:white;
        font-weight: bold; */
  opacity: 0.5;
}
</style>