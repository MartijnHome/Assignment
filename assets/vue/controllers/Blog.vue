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

  <div v-if="refImages" class="flex flex-wrap gap-4 mb-10">
    <div v-for="(image, index) in refImages" class="relative">
      <button @click="setImage(index)">
        <img class="w-32 h-32 rounded-2xl hover:animate-pulse"
             :src="path + image[0]"
        >
      </button>
      <form v-if="editMode"
            method="post"
            :action="deleteUrl.concat(image[1])"
            onsubmit="return confirm('Are you sure you want to delete this item?');"
            @submit.prevent="deleteImage(index)"
      >
        <input type="hidden" name="_token" :value="token">
        <button class="absolute -top-2 -right-2 bg-red-800 text-white p-2 rounded-xl ">
          X
        </button>
      </form>
    </div>
  </div>

  <ImageViewer v-if="index" :path="path" :index="index" :images="refImages" @requestClose="setImage(null)" />
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
    editMode: Boolean,
    deleteUrl: String,
  },

  data() {
    return {
      index: null,
      refImages: this.images,
    }
  },

  methods: {
    setImage(index) {
      this.index = index;
    },

    deleteImage(index) {
      this.refImages.splice(index, 1);
    }
  }
}

</script>