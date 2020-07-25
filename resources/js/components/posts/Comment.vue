<template>
  <div>
    <div class="select border-top">
      <div class="row no-gutters p-2">
        <div class="col-1 text-center p-2">
          <div v-if="image">
            <img :src="`${imageUrl}${image}`" alt />
          </div>
          <i v-else class="fas fa-user-circle"></i>
          <br />
        </div>
        <div class="col-11 pl-2">
          <span class="owner">
            <span class="mr-5">{{commenter}}</span>
            {{created_at}}
          </span>
          <span v-if="isLogin">
            <span class="text-right" v-if="currentUser.id === commenter_id">
              <i class="fas fa-trash-alt delete" @click="deleteComment"></i>
            </span>
          </span>
          <br />
          <p class="pl-3 pt-2">{{content}}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "commenter",
    "image",
    "content",
    "created_at",
    "id",
    "commenter_id",
    "post_id"
  ],
  computed: {
    imageUrl() {
      return this.$store.getters["auth/imageUrl"];
    },
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    }
  },
  methods: {
    deleteComment() {
      axios.post(`/api/posts/${this.id}/comments/delete`).then(response => {
        this.$emit("set");
      });
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
.delete {
  font-size: 1rem;
}
img {
  display: inline;
  height: 50px;
  width: 50px;
  border-radius: 50%;
}
</style>