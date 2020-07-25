<template>
  <div class="row no-gutters p-2 user border-bottom">
    <div class="col-1 text-center p-2" @click="goUser()">
      <img v-if="image" :src="`${imageUrl}${image}`" alt="">
      <i v-else class="fas fa-user-circle"></i>
      <br />
    </div>
    <div class="col-8 pl-2" @click="goUser()">
      <span class="owner">
        <span class="mr-5">{{name}}</span>
        {{created_at}}
      </span>
      <br />
      <p class="pl-3 pt-2" v-if="intro">{{intro}}</p>
      <p class="pl-3 pt-2" v-else>よろしくお願いします</p>
    </div>
    <div class="col-3" v-if="isLogin">
      <div v-if="currentUser.id == id"></div>
      <div v-else>
        <transition name="fade" mode="out-in">
          <div @mouseleave="mouseLeave" @mouseover="mouseOver">
            <button
              v-if="stateMessage"
              class="btn btn-primary unfollow"
              @click="unFollow"
            >{{showMessage}}</button>
          </div>
        </transition>
        <button class="btn btn-info" v-if="!stateMessage" @click="follow">{{showMessage}}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "id",
    "image",
    "intro",
    "created_at",
    "name",
    "isfollowing",
    "state",
    "isLogin"
  ],
  computed: {
    imageUrl() {
      return this.$store.getters["auth/imageUrl"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    }
  },
  data() {
    return {
      showMessage: null,
      stateMessage: null
    };
  },
  created() {
    if (this.state) {
      this.stateMessage = this.state;
      this.showMessage = "フォロー中";
    } else {
      this.stateMessage = this.state;
      this.showMessage = "フォローする";
    }
  },
  methods: {
    goUser() {
      this.$router.push(`/${this.id}/user/profile/posts`);
    },
    mouseOver() {
      this.showMessage = "フォロー解除";
    },
    mouseLeave() {
      this.showMessage = "フォロー中";
    },
    async follow() {
      const response = await axios
        .put(`/api/user/${this.id}/follow`)
        .then(response => {
          this.stateMessage = true;
          this.showMessage = "フォロー中";
        });
    },
    async unFollow() {
      const response = await axios
        .delete(`/api/user/${this.id}/unfollow`)
        .then(response => {
          this.stateMessage = false;
          this.showMessage = "フォローする";
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
.user {
  transition: 0.4s;
}
.user:hover {
  background: rgba(100, 148, 237, 0.322);
}
.unfollow {
  transition: 0.4s;
}
.unfollow:hover {
  background-color: rgba(223, 47, 47, 0.925);
}
img {
  display: inline;
  height: 50px;
  width: 50px;
  border-radius: 50%;
}
</style>