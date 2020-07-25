<template>
  <div class="left_bar border-right">
    <h1>laravel vue bbs</h1>
    <ul>
      <li @click="toHome" :class="{'action':this.$route.name == 'Home'}">
        <i class="fas fa-home"></i> ホーム
      </li>
      <li>
          ログインで様々な機能が楽しめます
      </li>
    </ul>
    <div v-if="isLogin">
      <ul>
        <li
          @click="toFollows"
          :class="{'action':this.$route.name == 'follows' && this.$route.params.name == user.name}"
        >
          <i class="fas fa-user-friends"></i> フォロー
        </li>
        <li
          @click="toProfile"
          :class="{'action':this.$route.name == '投稿' ||this.$route.name == 'コメント'||this.$route.name == 'いいね'}"
        >
          <i class="fas fa-user-circle"></i> プロフィール
        </li>
        <li @click="logout">
          <i class="fas fa-chevron-circle-left"></i> ログアウト
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import router from "../router";
export default {
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    user() {
      return this.$store.getters["auth/user"];
    }
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");
    },
    toHome() {
      router.push("/");
    },
    toProfile() {
      router.push(`/${this.user.id}/user/profile/posts`);
    },
    toFollows() {
      router.push(`/follows/${this.user.id}/${this.user.name}`);
    }
  }
};
</script>

<style scoped>
.left_bar {
  z-index: 10;
  width: 25%;
  background: white;
  height: 100vh;
  overflow-y: scroll;
  position: fixed;
}
ul {
  padding: 20px;
  list-style-type: none;
}
li {
  padding: 8px 15px;
  border-radius: 20px;
  margin: 10px;
  font-size: 1.5rem;
  transition: 0.4s;
}
li:hover {
  background: rgba(168, 155, 155, 0.459);
}
.action {
  background: rgba(168, 155, 155, 0.459);
}
</style>