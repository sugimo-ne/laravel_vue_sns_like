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
    }
  },
  components: {
    Post
  },
  props: ["aboutUser"],
  data() {
    return {
      loading: false,
      likes: null
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
      this.likes = response.data.posts;
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