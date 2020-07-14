<template>
  <div>
    <div class="row no-gutters p-2">
      <div class="col-1 text-center p-2">
        <i class="fas fa-user-circle"></i>
        <br />
      </div>
      <div class="col-11 pl-2">
        <span class="owner">
          <span class="mr-5">{{name}}</span>
          {{created_at}}
        </span>
        <br />
        <p class="pl-3 pt-2">{{content}}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
  },
  props: {
    id: Number,
    content: String,
    created_at: String,
    name: String,
    number: Number, 
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
</style>