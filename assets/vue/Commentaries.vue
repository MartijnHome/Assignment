<template>
  <div v-if="!commentaries">
    <button v-if="!showComments" class="bg-amber-600 text-white p-2 rounded-xl" @click="atShowComments">
      Show Comments
    </button>
    <p v-else>Loading</p>
  </div>
  <div v-else>
    <div v-if="newComment">
      <textarea class="w-full h-32 mb-2"
                v-model.trim="text"
                placeholder="Your comment..."
      />
      <p v-if="editCommentaryNotification !== null" :class="!valid ? 'text-red-600' : ''"> {{ editCommentaryNotification }}</p>
      <button class="bg-emerald-800 text-white p-2 text-xs rounded-xl mt-2"
              :class="!valid ? 'opacity-50' : ''"
              :disabled="!valid"
              @click="atSubmitCommentary">
        Submit
      </button>
      <button class="bg-amber-600 text-white p-2 text-xs rounded-xl mx-4"
              @click="newComment = false">
        Cancel
      </button>
    </div>
    <div v-else>
      <button v-if="userId > 0" class="bg-amber-600 text-white p-2 text-xs rounded-xl" @click="newComment = true">
        Add a comment
      </button>
    </div>

    <div v-if="commentaries.length === 0">
      Be the first to comment this blog!
    </div>
    <div v-else class="">
      <div v-for="(comment, index) in commentaries" class="flex w-full py-2 gap-4 mt-8">
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
        text: "",
        valid: false,
        editCommentaryNotification: null,
        newComment: false,
      }
    },

    methods: {
      atShowComments() {
        this.showComments = true;

        axios
            .get(this.url + "/commentary/blog/" + this.blogId)
            .then((res) => {
              this.avatarDirectory = this.url + "/" + res.data.avatarDirectory + "/";
              this.commentaries = res.data.commentaries.sort(function(a,b){
                return new Date(b.date) - new Date(a.date);
              });
            });
      },

      deleteComment(index) {
        this.commentaries.splice(index, 1);
      },

      setCommentaryEditNotification() {
        this.valid = false;

        if (this.text.length < 3)
        {
          this.editCommentaryNotification = "Comment should contain at least 3 characters!"
          return;
        }

        this.editCommentaryNotification = (255 - this.text.length) + " characters remaining.";

        if (255 - this.text.length >= 0)
          this.valid = true;
      },

      atSubmitCommentary() {
        axios
            .post(this.deleteUrl.concat(this.blogId.toString()).concat("/new"), {
              'token': this.deleteCommentaryToken,
              'commentary-text' : this.text,
            })
            .then((res) => {
              this.atShowComments();
              this.text = "";
              this.newComment = false;
            })
            .catch((e) => {
              console.log("oops");
            });
      },
    },

    watch: {
      text: function(val) {
          this.setCommentaryEditNotification();
      },
    }
  }
</script>