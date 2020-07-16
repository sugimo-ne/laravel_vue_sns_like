<template>
  <ul>
    <li class="border-bottom" v-for="post in posts" :key="post.id">
      <Posts v-bind="post" :login="isLogin"></Posts>
    </li>
  </ul>
</template>

<script>
import Posts from '../../components/posts/Posts'
export default {
  props: ["posts" , 'isLogin'],
  components:{
      Posts
  }
};
</script>