<template>
  <div :class="{'fixed':modal}">
    <div v-if="loading">
      <h1>プロフィール</h1>now loading ...
    </div>
    <div v-else>
      <div class="my_modal" v-if="modal">
        <div class="my_modal_back"></div>
        <transition name="fade" appear>
          <div class="my_modal_content">
            <h5 class="border-bottom p-1 bg-dark text-light">{{currentUser.name}} のプロフィール</h5>
            <form class="pl-5 pr-5 pt-3 pb-4">
              <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" class="form-control" id="name" v-model="editInfo.name" />
              </div>
              <div class="form-group">
                <label for="intro">自己紹介</label>
                <textarea
                  name
                  id="intro"
                  cols="30"
                  rows="3"
                  class="form-control"
                  v-model="editInfo.intro"
                ></textarea>
              </div>
              <div class="form-group">
                <input type="file" @change="onFileSelected" />
                <output v-if="preview">
                  <img :src="preview" alt class="image-fluid" />
                </output>
              </div>
              <span class="btn btn-danger" @click="modal = false">やめる</span>
              <span @click="editUser" class="btn btn-info">更新する</span>
            </form>
          </div>
        </transition>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="p-3">
            <img v-if="user.image" :src="`${imageUrl}${user.image}`" class="img-fluid" alt />
            <i v-else class="fas fa-user-circle profile"></i>
            <br />
            <h1>{{user.name}}</h1>
          </div>
        </div>
        <div class="col-6 text-right p-5">
          <div v-if="isLogin">
            <button
              v-if="id == currentUser.id"
              class="btn btn-info"
              @click="modal = true"
            >プロフィールを編集する</button>
            <div v-else>
              <button v-if="!isfollowing" class="btn btn-info" @click="follow">フォローする</button>
              <button v-if="isfollowing" class="btn btn-info" @click="unFollow">フォロー解除</button>
            </div>
          </div>
        </div>
      </div>
      <div class="profile_detail">
        <div v-if="user.intro == null">
          <!-- <p v-if="currentUser.id == user.id" class="p-2">プロフィール編集で自己紹介文を入力できます</p> -->
          <!-- <p v-else class="p-2">よろしくお願いします</p> -->
          <p>よろしくお願いします</p>
        </div>
        <p v-else class="p-2">{{user.intro}}</p>
        <small>開始日:{{user.created_at}}</small>
        <br />
        <small class="follows" @click="goFollow">フォロー{{following}} / フォロワー{{follower}}</small>
      </div>
      <div class="row border-bottom">
        <div class="col-4 text-center tab" @click="showPosts" :class="{'active_tab': tab == 1}">
          <span>投稿</span>
        </div>
        <div class="col-4 text-center tab" @click="showComments" :class="{'active_tab': tab == 2}">
          <span>コメント</span>
        </div>
        <div class="col-4 text-center tab" @click="showLikes" :class="{'active_tab': tab == 3}">
          <span>いいね</span>
        </div>
      </div>
      <div v-if="tab == 1">
        <router-view :posts="postsReverse" :isLogin="isLogin"></router-view>
      </div>
      <div v-if="tab == 2">
        <ul>
          <li v-for="comment in commentsReverse" :key="comment.id">
            <router-view
              :id="comment.id"
              :content="comment.content"
              :image="user.image"
              :created_at="comment.created_at"
              :post_id="comment.post_id"
              :commenter="user.name"
            ></router-view>

            <!-- <router-view
              v-else
              :id="comment.id"
              :content="comment.content"
              :created_at="comment.created_at"
              :post_id="comment.post_id"
              :commenter="user.name"
            ></router-view>-->
          </li>
        </ul>
      </div>

      <div v-if="tab == 3">
        <router-view :about-user="id"></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import Posts from "../components/posts/Posts";
import Comment from "../components/posts/Comment";
export default {
  props: ["id"],
  computed: {
    imageUrl() {
      return this.$store.getters["auth/imageUrl"];
    },
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    },
    following() {
      return this.user.followings.length;
    },
    postsReverse() {
       if(this.posts){
        return this.posts.slice().reverse();
      }
      return false
    },
    commentsReverse() {
      if(this.comments){
        return this.comments.slice().reverse();
      }
      return false
    }
    // follower(){
    //   return this.user.followers.length;
    // },
  },
  components: {
    Posts,
    Comment
  },
  data() {
    return {
      editInfo: {
        id: null,
        name: null,
        intro: null,
        image: null
      },
      preview: null,
      fileName: null,
      modal: false,
      tab: null,
      loading: false,
      user: null,
      posts: null,
      comments: null,
      isfollowing: null,
      follower: null
    };
  },
  mounted() {
    if (this.isLogin) {
      this.editInfo.id = this.currentUser.id;
      this.editInfo.name = this.currentUser.name;
      this.editInfo.image = this.currentUser.image;
      this.editInfo.intro = this.currentUser.intro;
    }
  },
  created() {
    this.fetchData();
    if(this.isLogin){
      this.editInfo.image = this.currentUser.image;
    }
  },
  methods: {
    fetchData() {
      this.loading = true;
      axios.get(`/api/user/${this.id}`).then(response => {
        let res = response.data;
        this.user = res.user;
        this.posts = res.posts;
        this.comments = res.Comments;
        console.log(this.posts);
        this.loading = false;

        let name = this.$route.name;
        if (name == "投稿") {
          this.tab = 1;
        } else if (name == "コメント") {
          this.tab = 2;
        } else {
          this.tab = 3;
        }

        this.follower = this.user.followers.length;
        if (this.isLogin) {
          let check = this.user.followers.filter(
            e => e.id == this.currentUser.id
          );

          if (check.length > 0) {
            this.isfollowing = true;
          } else {
            this.isfollowing = false;
          }
        }
      });
    },
    showPosts() {
      this.$router.push(`/${this.id}/user/profile/posts`);
      this.tab = 1;
    },
    showComments() {
      this.$router.push(`/${this.id}/user/profile/comments`);
      this.tab = 2;
    },
    showLikes() {
      this.$router.push(`/${this.id}/user/profile/likes`);
      this.tab = 3;
    },

    async follow() {
      const response = await axios
        .put(`/api/user/${this.id}/follow`)
        .then(response => {
          console.log(response);
          this.isfollowing = true;
          this.follower++;
        });
    },
    async unFollow() {
      const response = await axios
        .delete(`/api/user/${this.id}/unfollow`)
        .then(response => {
          console.log(response);
          this.isfollowing = false;
          this.follower--;
        });
    },
    goFollow() {
      this.$router.push(`/follows/${this.id}/${this.user.name}`);
    },

    onFileSelected(event) {
      //console.log(event.target.files[0].type)
      if (event.target.files.length === 0) {
        this.reset();
        return false;
      }
      if (!event.target.files[0].type.match("image.*")) {
        console.log("false");
        this.reset();
        return false;
      }
      const reader = new FileReader();
      reader.onload = e => {
        this.preview = e.target.result;
      };
      this.fileName = event.target.files[0];
      reader.readAsDataURL(event.target.files[0]);
    },
    reset() {
      this.preview = "";
      this.fileName = "";
      this.$el.querySelector('input[type="file"]').value = null;
    },
    async editUser() {
      const formData = new FormData();
      formData.append("photo", this.fileName);
      await axios.post("/api/photos", formData).then(response => {
        console.log(response);
        this.editInfo.image = response.data.photo_name;
        this.$store.dispatch("auth/editUser", this.editInfo);
      });
    }
  },
  watch: {
    id: function(to, from) {
      this.fetchData();
    }
  }
};
</script>

<style scoped>
.fixed {
  position: fixed;
  width: 50%;
}
.profile {
  font-size: 4.5rem;
}
h1 {
  font-size: 1.5rem;
}
ul {
  padding: 0;
  list-style-type: none;
}
.tab {
  padding: 10px 0;
}
.tab:hover {
  transition: 0.4s;
  background: rgba(100, 148, 237, 0.322);
}
.active_tab {
  background: rgba(100, 148, 237, 0.322);
}
.follows {
  transition: 0.4s;
  cursor: pointer;
}
.follows:hover {
  background: rgba(100, 148, 237, 0.322);
}

.my_modal_back {
  position: absolute;
  z-index: 100;
  background-color: rgba(0, 0, 0, 0.356);
  width: 100%;
  height: 100vh;
}
.my_modal_content {
  width: 85%;
  left: 7.5%;
  margin: 30px auto;
  z-index: 150;
  position: absolute;
  background-color: white;
  height: 400px;
  overflow: scroll;
  /* padding:30px 45px; */
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
img {
  height: 100px;
  width: 100px;
  border-radius: 50%;
}
</style>