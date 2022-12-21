<template>
  <h1 class="text-2xl my-2">
    <input v-model="title" />
  </h1>

  <h2 class="text-lg my-2">
    <input v-model="subTitle" />
  </h2>

  <p class="my-4">
    <input v-model="text" />
  </p>

  <p>
    {{ blogId }}
  </p>

  <p>
    {{ token }}
  </p>

  <button @click="go">
    Hallo
  </button>

  <p v-if="visible">
    VISIBLE
  </p>
</template>

<script>
export default {
  props: {
    title: String,
    subTitle: String,
    text: String,
    blogId: Number,
    token: String,
  },

  data() {
    return {
      title: this.title,
      subTitle: this.subTitle,
      text: this.text,
      message: this.text,
      blogId: this.blogId,
      token: this.token,
      visible: false,
    }
  },

  methods: {
    go() {
      this.visible = !this.visible;
    }
  }


}

</script>