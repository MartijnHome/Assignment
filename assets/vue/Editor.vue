<template>
    <div class="flex items-center justify-center justify-center">
      <h1 class="mx-auto text-5xl my-2">
        {{ blog.title }}
      </h1>
    </div>

    <h2 class="text-xl my-4">
      {{ blog.subtitle }}
    </h2>

    <img class="h-80 w-full rounded-2xl rounded-2xl"
         :src="url + '/uploads/blog/image/' + blog.lead.filename"
    >

  <div v-for="(p, index) in paragraphs">
    <p class="my-16 w-full">
      {{ p }}
    </p>
    <div v-if="index < inlineImages.length" class="flex">
      <div  class="relative justify-center text-center w-full">
        <button @click="setImage(index)">
          <img class="w-64 h-64 rounded-2xl hover:animate-pulse"
               :src="url + '/uploads/blog/image/' + blog.images[index].filename"
          >
        </button>
        <div v-if="blog.images[index].description" class="justify-center mt-2">
          <p class="text-sm text-amber-600">{{ blog.images[index].description }}</p>
        </div>
      </div>
    </div>
  </div>

    <div v-if="blog.images" class="flex flex-wrap gap-4 mb-10">
      <div v-for="(image, index) in blog.images" class="relative">
        <button @click="setImage(index)">
          <img class="w-32 h-32 rounded-2xl hover:animate-pulse"
               :src="url + '/uploads/blog/image/' + image.filename"
          >
        </button>
        <form method="post"
              @submit.prevent="deleteImage(index)"
        >
          <input type="hidden" name="token" :value="token">
          <button class="absolute -top-2 -right-2 bg-red-800 text-white p-2 rounded-xl ">
            X
          </button>
        </form>
        <div class="absolute -top-2 -left-2 bg-white p-2 rounded-xl ">
          {{ image.id }}
        </div>
      </div>
    </div>

    <ImageViewer v-if="index !== null"
                 :path="url"
                 :index="index"
                 :images="blog.images"
                 @requestClose="setImage(null)"
    />
</template>


<script>
import ImageViewer from "./ImageViewer.vue";
import axios from "axios";

export default {
  components: {
    ImageViewer,
  },

  props: {
    token: String,
    deleteUrl: String,
    json: String,
  },

  data() {
    return {
      index: null,
      blog: null,
      url: location.origin,
      paragraphs: [],
      inlineImages: [],
    }
  },

  methods: {
    setImage(index) {
      this.index = index;
    },

    deleteImage(index) {
      if (confirm('Are you sure you want to delete this item?'))
        axios
            .post(this.deleteUrl.concat(this.blog.images[index].id), {
              'token': this.token
            })
            .then((res) => this.blog.images.splice(index, 1))
            .catch((e) => {
              console.log("oops");
            });
    },

    parseText() {
      let split = this.blog.text.split('~');
      if (split.length % 2 === 0) {
        this.paragraphs.push("Something went wrong during loading");
        return;
      }
      for (let i = 0; i < split.length; i++)
        if (i % 2 === 0)
          this.paragraphs.push(split[i]);
        else
          for (let j = 0, id = parseInt(split[i]); j < this.blog.images.length; j++)
            if (this.blog.images[j].id === id)
              this.inlineImages.push(id);
    }
  },

  created() {
    this.blog = JSON.parse(this.json);
    this.parseText();
    for (let i = 0; i < this.blog.images.length; i++)
      if (this.blog.images[i].isLead)
        this.blog.images.splice(i, 1);
  }
}
</script>