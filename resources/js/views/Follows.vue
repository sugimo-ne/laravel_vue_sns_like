<template>
  <div>
    <div class="fixed">
      <div class="row text-center no-gutters border-bottom">
        <div class="col-6 p-3 border-right" @click="changeTab(1)" :class="{'active':tab == 1}">
          <span>フォロー中</span>
        </div>
        <div class="col-6 p-3" @click="changeTab(2)" :class="{'active':tab == 2}">
          <span>フォロワー</span>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <div v-if="tab == 1">
          <div v-for="following in followings" :key="following.id + 'following'">
              <UserCard v-bind="following" 
        :state="followChecker(following.id)"></UserCard>
          </div>
      </div>

      <div v-if="tab == 2">
          <div v-for="follower in followers" :key="follower.id + 'follower'">
          <UserCard v-bind="follower" 
         :state="followChecker(follower.id)"
        ></UserCard>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import UserCard from "../components/UserCard";
export default {
  components: {
    UserCard
  },
  props: ["name", "id"],
  data() {
    return {
      tab: 1,
      loading: false,
      followings: null,
      followers: null,
      following:'フォロー中',
      unfollowing:'フォローする', 
      user:null,
    };
  },
  created() {
    this.getFollows();
  },
  computed: {
    currentUser() {
      return this.$store.getters["auth/user"];
    },
  },
  methods: {
    getFollows() {
      this.loading = true;
      axios.get(`/api/user/${this.id}/follows`).then(response => {
        console.log(response.data);
        this.followings = response.data.about_user.followings;
        this.followers = response.data.about_user.followers;

        this.user = response.data.about_user
        this.loading = false;
      });
    },
    followChecker(id){
        let check = this.user.followings.filter(e => e.id == id)
        if(check.length > 0){
            return true
        }else{
            return false
        }
    },
    changeTab(num){
        this.tab = num
        this.getFollows()
    }
  }
};
</script>

<style scoped>
.active {
  background: rgba(100, 148, 237, 0.322);
}
</style>