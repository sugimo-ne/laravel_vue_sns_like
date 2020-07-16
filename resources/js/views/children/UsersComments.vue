<template>
 
        <div @click="toDetail" class="select">
            <Comment
                :id="id"
                :content="content"
                :image="image"
                :created_at="created_at"
                :post_id="post_id"
                :commenter="commenter"></Comment>
        </div>
 
</template>

<script>
import Comment from '../../components/posts/Comment'
export default {
    props:['commenter' , 'image' , 'content' , 'created_at', 'id' , 'commenter_id' , 'post_id'],
    components:{
        Comment
    },
    methods:{
        toDetail(){
            this.$router.push(`/from_${this.id}/${this.post_id}`)
        }
    }
}
</script>

<style scoped>
 .select {
  transition: 0.4s;
}
.select:hover {
  background: rgba(215, 218, 235, 0.4);
}
</style>