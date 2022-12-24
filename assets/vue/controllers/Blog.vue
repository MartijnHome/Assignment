<template>
  <div class="flex items-center justify-center justify-center">
    <h1 class="mx-auto text-5xl my-2">
      {{ title }}
    </h1>
  </div>

  <p class="font-bold my-4">
    {{ date }}
  </p>

  <h2 class="text-xl my-4">
    {{ subTitle }}
  </h2>

  <img class="h-80 w-full rounded-2xl rounded-2xl"
       :src="path + lead"
  >

  <p class="my-16">
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

  <ImageViewer v-if="index !== null" :path="path" :index="index" :images="refImages" @requestClose="setImage(null)" />
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
    lead: String,
    date: String,
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