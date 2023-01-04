<template>
  <div class="flex-none w-16">
    <div class="flex-none w-16 h-16 rounded-full shadow-xl border-2 border-amber-600">
      <img v-if="comment.user.avatar" class="h-full w-full object-cover object-center rounded-full "
           :src="avatarDirectory.concat(comment.user.avatar.filename)"
      >
    </div>
  </div>

  <div class="flex-auto w-64">
    <div class="flex-none ">
      <div class="flex text-lg ">
        <div class="text-amber-600 font-semibold">
          {{ comment.user.name }}
        </div>
        <div class="italic tracking-widest font-light ml-6">
          {{ new Date(comment.date).toDateString() }}
        </div>
        <div class="mx-2">
          •
        </div>
        <div class="italic tracking-widest font-light">
          {{ new Date(comment.date).getUTCHours() }}:{{ (new Date(comment.date).getUTCMinutes() < 10) ? '0'.concat(new Date(comment.date).getUTCMinutes().toString()) : new Date(comment.date).getUTCMinutes() }}
        </div>
      </div>

    </div>
    <div class="flex-none my-2">

      <template v-if="editCommentary">
              <textarea class="w-full h-32"
                        v-model.trim="editCommentaryText"
                        :placeholder="editCommentaryText"
              />
        <p v-if="editCommentaryNotification !== null" :class="!editValid ? 'text-red-600' : ''"> {{ editCommentaryNotification }}</p>
      </template>
      <p v-else class="break-words">
        {{ comment.text }}
      </p>
    </div>
    <div v-if="comment.user.id === userId" class="flex-none">
      <template v-if="editCommentary">
        <button class="bg-emerald-800 text-white p-2 text-xs rounded-xl"
                :class="!editValid ? 'opacity-50' : ''"
                :disabled="!editValid"
                @click="atSaveCommentary">
          Save
        </button>
        <button class="bg-amber-600 text-white p-2 text-xs rounded-xl mx-4"
                @click="atCancel">
          Cancel
        </button>
      </template>
      <template v-else>
        <button class="bg-sky-800 text-white p-2 text-xs rounded-xl" @click="atEditCommentary">
          Edit
        </button>

        <button class="btn bg-red-800 text-white p-2 rounded-xl text-xs mx-4" @click="atDeleteCommentary">
          Delete
        </button>
      </template>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Comment",

  props: {
    comment: Object,
    avatarDirectory: String,
    userId: Number,
    index: Number,
    deleteCommentaryToken: String,
    deleteUrl: String,
  },

  emits: ['deleteComment'],

  data() {
    return {
      editCommentary: false,
      editCommentaryText: null,
      editCommentaryNotification: null,
      editValid: false,
    }
  },

  methods: {
    atDeleteCommentary() {
      if (confirm('Are you sure you want to delete this comment?'))
        axios
            .post(this.deleteUrl.concat(this.comment.id), {
              'token': this.deleteCommentaryToken
            })
            .then((res) => {
              this.$emit('deleteComment', this.index);
            })
            .catch((e) => {
              console.log("oops");
            });
    },

    atEditCommentary(index) {
      this.editCommentary = true;
      this.editCommentaryText = this.comment.text;
      this.setCommentaryEditNotification();
    },

    atSaveCommentary() {
      axios
          .post(this.deleteUrl.concat(this.comment.id).concat("/edit"), {
            'token': this.deleteCommentaryToken,
            'commentary-text' : this.editCommentaryText,
          })
          .then((res) => {
            this.comment.text = this.editCommentaryText;
            this.editCommentary = false;
            this.editCommentaryText = null;
          })
          .catch((e) => {
            console.log("oops");
          });
    },

    atCancel() {
      this.editCommentary = false;
      this.editCommentaryText = null;
    },

    setCommentaryEditNotification() {
      this.editValid = false;

      if (this.editCommentaryText.length < 3)
      {
        this.editCommentaryNotification = "Comment should contain at least 3 characters!"
        return;
      }

      this.editCommentaryNotification = (255 - this.editCommentaryText.length) + " characters remaining.";

      if (255 - this.editCommentaryText.length >= 0)
        this.editValid = true;
    },
  },

  watch: {
    editCommentaryText: function(val) {
      if(val)
        this.setCommentaryEditNotification();
    },
  }
}
</script>