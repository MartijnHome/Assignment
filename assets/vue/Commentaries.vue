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
      <div v-for="(comment, index) in commentaries" class="flex w-full pl-16 py-2">
         <div class="flex-none w-2/12">
           <div class="flex-none w-16 h-16 rounded-full shadow-xl border-2 border-amber-600">
             <img class="h-full w-full object-cover object-center rounded-full "
                  :src="avatarDirectory.concat(comment.user.avatar.filename)"
             >
           </div>
         </div>

        <div class="flex-auto">
          <div class="flex-none">
            {{ comment.user.name }}
          </div>
          <div class="flex-none">
              <textarea v-show="editCommentaryIndex === index"
                     v-model.trim="editCommentaryText"
                     :placeholder="editCommentaryText"
              />
            <p v-if="editCommentaryIndex === null">
              {{ comment.text }}
            </p>
          </div>
          <div v-if="comment.user.id === userId" class="flex-none">
              <button v-if="editCommentaryIndex === null" class="bg-sky-800 text-white p-2 rounded-xl" @click="atEditCommentary(index)">
                Edit
              </button>
            <button v-else class="bg-emerald-800 text-white p-2 rounded-xl" @click="atSaveCommentary">
              Save
            </button>
            <button class="btn bg-red-800 text-white p-2 rounded-xl mx-4" @click="atDeleteCommentary(index)">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      Be the first to comment this blog!
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    name: "Commentaries",

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
        editCommentaryIndex: null,
        editCommentaryText: null,
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

      atDeleteCommentary(index) {
        if (confirm('Are you sure you want to delete this comment?'))
          axios
              .post(this.deleteUrl.concat(this.commentaries[index].id), {
                'token': this.deleteCommentaryToken
              })
              .then((res) => this.commentaries.splice(index, 1))
              .catch((e) => {
                console.log("oops");
              });
      },

      atEditCommentary(index) {
        this.editCommentaryIndex = index;
        this.editCommentaryText = this.commentaries[index].text;
      },

      atSaveCommentary() {
        axios
            .post(this.deleteUrl.concat(this.commentaries[this.editCommentaryIndex].id).concat("/edit"), {
              'token': this.deleteCommentaryToken,
              'commentary-text' : this.editCommentaryText,
            })
            .then((res) => {
              this.commentaries[this.editCommentaryIndex].text = this.editCommentaryText;
              this.editCommentaryIndex = null;
              this.editCommentaryText = null;
            })
            .catch((e) => {
              console.log("oops");
            });
      },
    }
  }
</script>