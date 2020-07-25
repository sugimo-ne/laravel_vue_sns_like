<template>
  <div class="nav row border-bottom no-gutters">
    <div class="col-8">
      <i v-if="this.$route.name !== 'Home'" class="fas fa-caret-left back" @click="$router.go(-1)"></i>
      <h1>
        <span v-if="this.$route.name == 'follows'">{{$route.params.name}}/</span>
        {{title}}
      </h1>
    </div>
    <div class="col-4 text-right" @click="toProfile">
      <div class="p-3" v-if="!isLogin">
        <i class="fas fa-user-circle"></i>
        guest
      </div>
      <div class="p-2" v-else>
        <img v-if="currentUser.imageName" class="img-fluid" :src="`${user.image}`" alt />
        <i v-else class="fas fa-user-circle">
        </i>
        {{user.name}}
      </div>
    </div>
  </div>
</template>

<script>
import router from "../router";
export default {
  props: ["isLogin", "user"],
  computed: {
    currentUser() {
      return this.$store.getters["auth/user"];
    },
    title() {
      return this.$route.name;
    }
  },
  methods: {
    toProfile() {
      router.push(`/${this.user.id}/user/profile/posts`);
    }
  }
};
</script>

<style scoped>
i {
  font-size: 2rem;
}
.nav {
  z-index: 10;
  background: white;
  height: 55px;
  position: fixed;
  overflow: hidden;
}
.back {
  border-radius: 50px;
  padding: 12px 20px;
  font-size: 2rem;
  float: left;
  transition: 0.4s;
}
.back:hover {
  background: rgba(128, 128, 128, 0.493);
}

img {
  display: inline;
  height: 40px;
  width: 40px;
  border-radius: 50%;
}
h1 {
  padding: 15px;
  font-size: 1rem;
}
</style>