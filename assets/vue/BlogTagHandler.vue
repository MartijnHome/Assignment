<template>
  <div class="w-full">
    <h1 class="text-2xl block">
      Tags
    </h1>
    <p>
      Tags on your blog posts can make it easier for readers to find what they're looking for. They can help search engine crawlers understand the content that's on your blog.
      You can add an existing tag by clicking on it, or create a new one by filling out the text field and pressing the add button.
    </p>
    <div class="my-2">
      Your blog currently has these tags:
    </div>
    <div class="my-2">
      <button v-for="(tag, index) in currentTags" @click="unsetTag(tag.id, index)" class="mx-2 text-white p-2 rounded-xl bg-red-800">
        {{ tag.name }}
      </button>
      </div>
    <div class="my-2">
      Add new tag:
      <input v-model="name" class="border border-black p-2"/>

      <button @click="atAdd" class="mx-2 text-white p-2 rounded-xl bg-emerald-800">
        + Add tag
      </button>
      <p v-if="error" class="text-red-600">{{ error }}</p>
    </div>

  </div>

</template>

<script>
import axios from "axios";

export default {
  name: "BlogTagHandler",

  props: {
    blogId: Number,
    blogTags: Array,
  },

  data() {
    return {
      url: location.origin,
      tags: [],
      name: "",
      error: null,
      currentTags: [],
    }
  },

  methods: {
    atAdd() {
      this.error = null;
      if (this.name.length < 3)
      {
        this.error = "Tag must be at least 3 characters";
        return;
      }

      if (this.name.length > 255)
      {
        this.error = "Tag cannot exceed 255 characters";
        return;
      }

      this.name = this.name.toUpperCase();
      this.addTag();
    },

    addTag() {
      axios
          .post(this.url + "/blogtag/new/" + this.blogId, {
            'blogtag-name': this.name,
          })
          .then((res) => {
            this.tags.push({id: res.data.tag[0], name: res.data.tag[1]});
            this.currentTags.push({id: res.data.tag[0], name: res.data.tag[1]});
          })
          .catch((err) => {
            this.error = "Tag " + this.name + " already exists!"
          });
    },

    setTag(blogtagId) {
      axios
          .post(this.url + "/blogtag/set/" + blogtagId + "/" + this.blogId)
          .then((res) => {
            this.currentTags.push({id: res.data.tag[0], name: res.data.tag[1]});
          })
          .catch((err) => {
            this.error = "Something went wrong, please try again!"
          });
    },

    unsetTag(blogtagId, index) {
      if (confirm('Are you sure you want to delete this tag?'))
        axios
            .post(this.url + "/blogtag/unset/" + blogtagId + "/" + this.blogId)
            .then((res) => {
              this.currentTags.splice(index, 1);
            })
            .catch((err) => {
              this.error = "Something went wrong, please try again!"
            });
    },
  },

  created() {
    this.currentTags = this.blogTags;
    axios
        .get(this.url + "/blogtag/")
        .then((res) => {
          this.tags = res.data.tags;
        });
  }
}
</script>