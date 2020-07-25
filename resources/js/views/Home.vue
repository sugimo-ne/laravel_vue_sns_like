<template>
  <div class="back">
    <div v-if="!isLogin"></div>
    <div v-else>
      <div class="post_form mb-3">
        <div class="row border-bottom pl-3 pr-3">
          <div class="col-2 p-2">
            <img v-if="isLogin" :src="`${currentUser.image}`" alt />
            <i v-else class="fas fa-user-circle icon"></i>
          </div>
          <div class="col-10 p-2">
            <output v-if="preview">
              <img :src="preview" alt class="post_img" />
            </output>
            <textarea
              class="form-control"
              rows="2"
              placeholder="MESSAGE"
              v-model="postData.content"
            ></textarea>
            <br />

            <div class="actions text-right row">
              <div class="col-3">
                <input type="file" @change="onFileSelected" />
              </div>
              <div class="col-3 offset-6">
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

    <p class="bg-light text-center p-4 add_show" @click="addShow">もっとみる</p>
  </div>
</template>


<script>
import Posts from "../components/posts/Posts";
export default {
  computed: {
    imageUrl(){
      return this.$store.getters["auth/imageUrl"]
    },
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    currentUser() {
      return this.$store.getters["auth/user"];
    },
  },
  components: {
    Posts
  },
  data() {
    return {
      preview: null,
      fileName: null,
      loading: false,
      posts: [],
      page:1,
      commentCount: 0,
      postData: {
        content: "",
        filename: ""
      }
    };
  },
  created() {
    this.loading = true;
    this.getPosts();
    this.loading = false;
  },
  methods: {
    post() {
      const formData = new FormData()
    formData.append('photo', this.fileName)
       axios.post("/api/photos", formData).then(response => {
        console.log(response);
        this.postData.filename = response.data.photo_name
      console.log(this.postData);
      (this.postData.user_id = this.$store.getters["auth/user"].id),
        axios.post("/api/posts", this.postData).then(response => {
          console.log(response)
          let post = response.data
          post.user = {name:null , profile:null}
          post.user.name = this.currentUser.name
          post.user.image = this.currentUser.imageName
          post.comments = []
          this.posts.unshift(post)
          this.postData.content = "";
        });
         });
    },
    getPosts() {
      axios
        .get(`/api/posts/?page=${this.page}`)
        .then(response => {
          // this.posts = response.data.data;
          response.data.data.forEach(item => {
            this.posts.push(item)
          })
            
          console.log(this.posts);
          
        })
        .catch(e => {
          console.log("error");
        });
    },
    addShow(){
      console.log('ooooi')
      this.page++
      this.getPosts()
    },
   onFileSelected(event) {
      //console.log(event.target.files[0].type)
      if (event.target.files.length === 0) {
        this.reset()
        return false;
      }
      if (!event.target.files[0].type.match("image.*")) {
        console.log("false");
        this.reset()
        return false;
      }
      const reader = new FileReader();
      reader.onload = e => {
        this.preview = e.target.result;
      };
      this.fileName = event.target.files[0]
      reader.readAsDataURL(event.target.files[0])
    },
    reset () {
    this.preview = ''
    this.fileName = ''
    this.$el.querySelector('input[type="file"]').value = null
  },
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
.add_show {
  transition: 0.4s;
  cursor: pointer;
}
.add_show:hover {
  background: rgba(100, 237, 214, 0.719);
}
img {
  height: 50px;
  width: 50px;
  border-radius: 50%;
}
.post_img {
  display: block;
  height: 200px;
  width: 200px;
  border-radius: 0%;
}
</style>