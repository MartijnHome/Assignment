<template>
  <template v-if="blog.style === STYLE_DEFAULT">
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
  </template>
  <template v-else-if="blog.style === STYLE_TITLEONTOP">
    <div class="flex items-center justify-center justify-center">
      <div class="relative w-full">
        <img class="h-80 w-full rounded-2xl rounded-2xl"
             :src="url + '/uploads/blog/image/' + blog.lead.filename"
        >
        <h1 class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 mx-auto text-5xl my-2">
          {{ blog.title }}
        </h1>
      </div>
    </div>

    <h2 class="text-xl my-4">
      {{ blog.subtitle }}
    </h2>
  </template>
  <template v-else> <!-- STYLE_LEADONTOP -->
    <img class="h-80 w-full rounded-2xl rounded-2xl"
         :src="url + '/uploads/blog/image/' + blog.lead.filename"
    >
    <div class="flex items-center justify-center justify-center">
      <h1 class="mx-auto text-5xl my-2">
        {{ blog.title }}
      </h1>
    </div>

    <h2 class="text-xl my-4">
      {{ blog.subtitle }}
    </h2>
  </template>

  <div v-for="(p, index) in paragraphs">
    <p class="my-16 w-full">
      {{ p }}
    </p>
    <div v-if="index < inlineImages.length" class="flex">
      <div  class="relative justify-center text-center w-full">
        <button @click="setImage(inlineImages[index])">
          <img class="w-80 rounded-2xl hover:animate-pulse"
               :src="url + '/uploads/blog/image/' + blog.images[inlineImages[index]].filename"
          >
        </button>
        <div v-if="blog.images[inlineImages[index]].description" class="justify-center mt-2 mx-32">
          <p class="text-xs text-amber-800">{{ blog.images[inlineImages[index]].description }}</p>
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
        <input type="hidden" name="token" :value="deleteImageToken">
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
                 :edit-mode="true"
                 @requestClose="setImage(null)"
    />

  <BlogTagHandler :blog-id="blog.id" :blog-tags="blog.blogtags"/>
</template>


<script>
import ImageViewer from "./ImageViewer.vue";
import axios from "axios";
import BlogTagHandler from "./BlogTagHandler.vue";

const STYLE_DEFAULT = 0;
const STYLE_TITLEONTOP = 1;
const STYLE_LEADONTOP = 2;

export default {
  name: "Editor",

  components: {
    BlogTagHandler,
    ImageViewer,
  },

  props: {
    deleteImageToken: String,
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

      STYLE_DEFAULT,
      STYLE_TITLEONTOP,
      STYLE_LEADONTOP,
    }
  },

  methods: {
    setImage(index) {
      this.index = index;
    },

    deleteImage(index) {
      if (confirm('Are you sure you want to delete this image?'))
        axios
            .post(this.deleteUrl.concat(this.blog.images[index].id), {
              'token': this.deleteImageToken
            })
            .then((res) => {
              for (let i = 0; i < this.inlineImages.length; i++)
                if (this.inlineImages[i] === index)
                  this.inlineImages.splice(i, 1);
              this.blog.images.splice(index, 1);
            })
            .catch((e) => {
              console.log("oops");
            });
    },

    parseText() {
      let split = this.blog.text.split('~');
      if (split.length % 2 === 0) {
        this.paragraphs.push("Something went wrong during loading, missing an ~ tag");
        return;
      }
      for (let i = 0; i < split.length; i++)
        if (i % 2 === 0)
          this.paragraphs.push(split[i]);
        else
          for (let j = 0, id = parseInt(split[i]); j < this.blog.images.length; j++)
            if (this.blog.images[j].id === id)
              this.inlineImages.push(j-1);
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