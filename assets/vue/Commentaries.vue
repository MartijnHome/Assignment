<template>
  <div v-if="!commentaries">
    <button v-if="!showComments" class="bg-amber-800 text-white p-2 rounded-xl" @click="atShowComments">
      Show Comments
    </button>
    <button v-else class="bg-amber-800 text-white p-2 rounded-xl">
      Loading Comments - {{ url }}
    </button>
  </div>
  <div v-else>
    <div v-if="commentaries.length > 0" class="divide-y divide-amber-600">
      <div v-for="(comment, index) in commentaries" class="flex w-full py-2 gap-4">
        <Comment :comment="comment"
                 :avatar-directory="avatarDirectory"
                 :user-id="userId"
                 :index="index"
                 :delete-commentary-token="deleteCommentaryToken"
                 :delete-url="deleteUrl"
                 @delete-comment="deleteComment"
        />
      </div>
    </div>
    <div v-else>
      Be the first to comment this blog!
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import Comment from "./Comment.vue";

  export default {
    name: "Commentaries",
    components: {Comment},

    props: {
      blogId: Number,
      userId: Number,
      deleteCommentaryToken: String,
      deleteUrl: String,
    },

    data() {
      return {
        showComments: false,
        commentaries: null,
        url: location.origin,
        avatarDirectory: null,
      }
    },

    methods: {
      atShowComments() {
        this.showComments = true;
        axios
            .get(this.url + "/commentary/blog/" + this.blogId)
            .then((res) => {
              this.avatarDirectory = this.url + "/" + res.data.avatarDirectory + "/";
              this.commentaries = res.data.commentaries;
            });
      },

      deleteComment(index) {
        this.commentaries.splice(index, 1);
      }
    }
  }
</script>