<template>
  <div>
    <div class="row no-gutters p-2">
      <div class="col-1 text-center p-2 " @click="goUser()">
        <img v-if="profile" :src="`${imageUrl}${profile}`" class="user_link" alt />
        <i v-else class="fas fa-user-circle user_link"></i>
        <br />
      </div>
      <div class="col-11 pl-2">
        <p class="owner user_link" @click="goUser()">
          <span class="mr-5">{{name}}</span>
          {{created_at}}
        </p>
        <br />
        <div v-if="filename" class="text-center">
          <img :src="`${imageUrl}${filename}`" class="post_show_image" alt />
        </div>
        <p class="pl-3 pt-2">{{content}}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    imageUrl() {
      return this.$store.getters["auth/imageUrl"];
    }
  },
  methods: {
    goUser(){
      this.$router.push(`/${this.user_id}/user/profile/posts`)
    }
  },
  props: {
    id: Number,
    content: String,
    created_at: String,
    name: String,
    number: Number,
    user_id: Number,
    profile: String,
    filename: String
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
img {
  display: inline;
  height: 50px;
  width: 50px;
  border-radius: 50%;
}
.post_show_image {
  display: block;
  /* height: 300px; */
  width: 85%;
  height:auto;
  border-radius: 8%;
}

.user_link{
  transition:.4s;
    cursor: pointer;
}
.user_link:hover{
 background: rgba(42, 85, 150, 0.35);
}
</style>