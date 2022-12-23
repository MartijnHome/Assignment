<template>
  <h1 class="text-2xl my-2">
    {{ title }}
  </h1>

  <h2 class="text-lg my-2">
    {{ subTitle }}
  </h2>

  <p class="my-4">
    {{ text }}
  </p>

  <div v-if="images" class="flex flex-wrap gap-4 mb-10">
    <div v-for="image in images">
      <button @click="setImage(image)">
        <img class="w-32 h-32 rounded-2xl hover:animate-pulse"
             :src="path + image"
        >
      </button>
    </div>
  </div>

  <ImageViewer v-if="showImage" :fileName="showImage" @requestClose="setImage(null)" />
</template>

<script>
import ImageViewer from "./ImageViewer.vue";

export default {
  components: {
    ImageViewer
  },

  props: {
    title: String,
    subTitle: String,
    text: String,
    blogId: Number,
    token: String,
    path: String,
    images: Array,
  },

  data() {
    return {
      showImage: null,
    }
  },

  methods: {
    setImage(image) {
      this.showImage = image;
    }
  }
}

</script>