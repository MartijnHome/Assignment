<template>
  <div>
    Add new tag:
    <input v-model="name" />
    <button @click="addTag">
      Add tag
    </button>
    <button v-for="tag in tags" @click="setTag(tag[0])">
      {{ tag[1] }}
    </button>
  </div>

</template>

<script>
import axios from "axios";

export default {
  name: "BlogTagHandler",

  props: {
    blogId: Number,
  },

  data() {
    return {
      url: location.origin,
      tags: [],
      name: "",
    }
  },

  methods: {
    addTag() {
      axios
          .post(this.url + "/blogtag/new/" + this.blogId, {
            'blogtag-name': this.name,
          })
          .then((res) => {
            console.log(res.data.tags);
          });
    },

    setTag(blogtagId) {
      axios
          .post(this.url + "/blogtag/set/" + blogtagId + "/" + this.blogId)
          .then((res) => {
            console.log(res.data.tags);
          });
    }
  },

  created() {
    axios
        .get(this.url + "/blogtag/")
        .then((res) => {
          this.tags = res.data.tags;
        });
  }
}
</script>