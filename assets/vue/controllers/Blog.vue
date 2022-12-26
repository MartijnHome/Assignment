<template>
  <template v-if="loaded">
    <div class="flex items-center justify-center justify-center">
      <h1 class="mx-auto text-5xl my-2">
        {{ blog.title }}
      </h1>
    </div>

    <p class="font-bold my-4">
      {{ blog.publish_date }}
    </p>

    <h2 class="text-xl my-4">
      {{ blog.subtitle }}
    </h2>

    <img class="h-80 w-full rounded-2xl rounded-2xl"
         :src="url + '/uploads/blog/image/' + blog.lead.filename"
    >

    <p class="my-16">
      {{ blog.text }}
    </p>

    <div v-if="images" class="flex flex-wrap gap-4 mb-10">
      <div v-for="(image, index) in images" class="relative">
        <button @click="setImage(index)">
          <img class="w-32 h-32 rounded-2xl hover:animate-pulse"
               :src="url + '/uploads/blog/image/' + image[1]"
          >
        </button>
        <form v-if="editMode"
              method="post"
              @submit.prevent="deleteImage(index)"
        >
          <input type="hidden" name="token" :value="token">
          <button class="absolute -top-2 -right-2 bg-red-800 text-white p-2 rounded-xl ">
            X
          </button>
        </form>
      </div>
    </div>

    <ImageViewer v-if="index !== null" :path="url" :index="index" :images="images" @requestClose="setImage(null)" />
  </template>
</template>


<script>
import ImageViewer from "./ImageViewer.vue";
import axios from "axios";

export default {
  components: {
    ImageViewer
  },

  props: {
    blogId: Number,
    token: String,
    editMode: Boolean,
    deleteUrl: String,
    lead: String,
  },

  data() {
    return {
      index: null,
      images: [],
      loaded: false,
      blog: null,
      url: location.origin,
    }
  },

  methods: {
    setImage(index) {
      this.index = index;
    },

    deleteImage(index) {
      if (confirm('Are you sure you want to delete this item?'))
        axios
            .post(this.deleteUrl.concat(this.images[index][0]), {'token': this.token}, {
            })
            .then((res) =>
            {
              this.images.splice(index, 1);
            })
            .catch((e) => {
              console.log("oops");
            });
    },

    initialize(data) {
      this.blog = data;
      for (let i = 0; i < data.images.length; i++)
        if (!data.images[i].isLead)
          this.images.push([data.images[i].id, data.images[i].filename])
    },
  },

  created() {
    axios
        .get(location.origin + '/blog/api/' + this.blogId)
        .then((res) =>
        {
          this.initialize(res.data);
          this.loaded = true;
        });
  }
}

</script>