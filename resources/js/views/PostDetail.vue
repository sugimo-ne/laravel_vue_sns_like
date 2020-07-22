<template>
  <div class="main">
    <transition name="fade">
      <div v-if="deleteModal">
        <div>
          <div class="modal_cover" @click="deleteModal=false"></div>
          <div class="modal_content">
            <p>この投稿を削除しますか？</p>
            <div class="row">
              <div class="col-6">
                <button class="btn btn-light" @click="deleteModal = false">＜ 戻る</button>
              </div>
              <div class="col-6">
                <button class="btn btn-danger" @click="deletePost">削除する</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div v-if="loading">now loading...</div>
    <div v-else>
      <div class="container">
        <div class="user_link" @click="goUser()">
        <Post
          :commentCount="commentCount"
          :id="post.id"
          :content="post.content"
          :created_at="post.created_at"
          :name="post.user.name"
          :user_id="post.user.id"
        ></Post>
        </div>
        <hr />
        <div class="row">
          <div class="col-3 actions">
            <span class="info">いいね</span>
            {{post.likes_count}}
          </div>
          <div class="col-3 actions">
            <span class="info">コメント</span>
            {{commentCount}}
          </div>
        </div>

        <hr />
        <div class="row" v-if="isLogin">
          <div class="col-3 actions">
            <i class="fas fa-heart pr-1" @click="likeCheck" :class="{'liked':post.liked_by_user}"></i>
          </div>
          <div class="col-3 actions">
            <i
              class="far fa-comment-dots pr-1"
              :class="{'form_active':showForm == true}"
              @click="formCall(showForm)"
            ></i>
          </div>
          <div class="col-3 actions"></div>
          <div class="col-3 actions" @click="deleteModal = true">
            <div v-if="post.user_id === currentUser.id">
              <i class="fas fa-trash-alt"></i>
            </div>
          </div>
        </div>
        <div v-else>ログインするといいねやコメントなどが可能になります</div>
      </div>
      <hr />
      <div class="comment_form　fixed-bottom" v-if="showForm">
        <form @submit.prevent="postComment">
          <div class="row pl-3 pr-3">
            <div class="col-1 p-2">
              <i class="fas fa-user-circle icon"></i>
            </div>
            <div class="col-11 p-2">
              <textarea
                class="form-control"
                rows="2"
                placeholder="MESSAGE"
                v-model="comment.content"
              ></textarea>
              <br />
              <div class="actions text-right">
                <div v-if="comment.content !== ''">
                  <button class="btn btn-info">
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
        </form>
      </div>
      <div v-for="comment in commentLists" :key="comment.commenter.id + comment.created_at">
        <Comment
          @set="getPostDetail"
          :id="comment.id"
          :commenter="comment.commenter.name"
          :commenter_id="comment.commenter.id"
          :content="comment.content"
          :created_at="comment.created_at"
        ></Comment>
      </div>
    </div>
  </div>
</template>

<script>
import { OK } from "../util";
import Post from "../components/posts/Post";
import Comment from "../components/posts/Comment";
export default {
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    },
    commentLists() {
      return this.post.comments;
    },
    commentCount() {
      return this.commentLists.length;
    }
    // likeCount(){
    //     return this.post.likes.length
    // }
  },
  components: {
    Post,
    Comment
  },
  props: ["postId", "user"],
  data() {
    return {
      showForm: false,
      loading: false,
      post: null,
      deleteModal: false,
      comment: {
        content: ""
      }
    };
  },
  created() {
    this.getPostDetail();
  },
  methods: {
    getPostDetail() {
      this.loading = true;
      axios.get(`/api/posts/${this.postId}`).then(response => {
        this.post = response.data;
        console.log(this.post);
        this.loading = false;
      });
    },
    deletePost() {
      axios.post(`/api/posts/delete/${this.postId}`).then(response => {
        this.$router.push("/");
      });
    },
    postComment() {
      axios
        .post(`/api/posts/${this.postId}/comments`, {
          content: this.comment.content
        })
        .then(response => {
          console.log(response);
          this.getPostDetail();
          this.showForm = false;
        });

      this.comment.content = "";
    },
    formCall(judge) {
      this.showForm = !judge;
      console.log(this.showForm);
    },
    likeCheck() {
      if (this.post.liked_by_user) {
        this.unlike();
      } else {
        this.like();
      }
    },
    async like() {
      const response = await axios.put(`/api/posts/${this.postId}/like`);
      if (response.status !== OK) {
        this.$store.commit("error/setErrorCode", response.status);
        return false;
      } else {
        this.post.likes_count++;
        this.post.liked_by_user = true;
      }
    },

    async unlike() {
      const response = await axios.delete(`/api/posts/${this.postId}/like`);
      if (response.status !== OK) {
        this.$store.commit("error/setErrorCode", response.status);
        return false;
      } else {
        this.post.likes_count--;
        this.post.liked_by_user = false;
      }
    },
    goUser(){
      this.$router.push(`/${this.post.user_id}/user/profile/posts`)
    }
  }
};
</script>

<style scoped>
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.fade-enter-to,
.fade-leave {
  opacity: 1;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.main {
  /* height:100vh;
        overflow: scroll;
        position:relative; */
}
.modal_cover {
  background: rgba(0, 0, 0, 0.5);
  height: 100vh;
  position: absolute;
  width: 100%;
  z-index: 14;
}
.modal_content {
  z-index: 15;
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  left: 50%;
  background: white;
  padding: 40px;
  width: 60%;
}
.info {
  font-size: 0.7rem;
  font-weight: bold;
}
.actions {
  text-align: center;
}

.icon {
  font-size: 3rem;
}

textarea {
  border: none;
  resize: none;
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
/* .comment_form{
        position:absolute;
        bottom:0;
    } */
.form_active {
  color: brown;
}

.liked{
  transition: 0.7s;
  color:rgba(233, 40, 40, 0.74);
}
.user_link{
  transition:.4s
}
.user_link:hover{
 background: rgba(100, 148, 237, 0.15);
}
</style>