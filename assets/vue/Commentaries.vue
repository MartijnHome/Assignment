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
      <div v-for="comment in commentaries" class="flex w-full pl-16 py-2">
         <div class="flex-none w-2/12">
           <div class="flex-none w-16 h-16 rounded-full shadow-xl border-2 border-amber-600">
             <img class="h-full w-full object-cover object-center rounded-full "
                  :src="avatarDirectory.concat(comment.user.avatar.filename)"
             >
           </div>
         </div>
        <div class="flex-none w-2/12">
          {{ comment.user.name }}
        </div>
        <div class="flex-none">
          {{ comment.text }}
        </div>
        <div class="flex-auto">

        </div>
        <!--
        <div class="flex">

          {% if app.user == commentary.user %}

          <a href="{{ path('app_commentary_edit', {'id': commentary.id}) }}"
             class="hover:text-amber-600"
          >
            <button class="bg-sky-800 text-white p-2 rounded-xl mx-2">
              Edit
            </button>
          </a>
          <form method="post"
                action="{{ path('app_commentary_delete', {'id': commentary.id}) }}"
                onsubmit="return confirm('Are you sure you want to delete this comment?');">
            <input type="hidden" name="token" value="{{ csrf_token('delete-item') }}"/>
            <button class="btn bg-red-800 text-white p-2 rounded-xl mx-2">
              Delete
            </button>
          </form>
          {% endif %}
        </div>
        -->
      </div>
    </div>
    <div v-else>
      Be the first to comment this blog!
    </div>
  </div>


  <!--
  <div class="divide-y divide-amber-600">
    {% for commentary in commentaries %}
    <div class="flex w-full pl-16 py-2">
      <div class="flex-none w-2/12">
        {{ commentary.user.name }}
      </div>
      <div class="flex-none">
        {{ commentary.text }}
      </div>
      <div class="flex-auto">

      </div>
      <div class="flex">

        {% if app.user == commentary.user %}

        <a href="{{ path('app_commentary_edit', {'id': commentary.id}) }}"
           class="hover:text-amber-600"
        >
          <button class="bg-sky-800 text-white p-2 rounded-xl mx-2">
            Edit
          </button>
        </a>
        <form method="post"
              action="{{ path('app_commentary_delete', {'id': commentary.id}) }}"
              onsubmit="return confirm('Are you sure you want to delete this comment?');">
          <input type="hidden" name="token" value="{{ csrf_token('delete-item') }}"/>
          <button class="btn bg-red-800 text-white p-2 rounded-xl mx-2">
            Delete
          </button>
        </form>
        {% endif %}
      </div>
    </div>
    {% else %}
    Be the first to comment this blog!
    {% endfor %}

  </div>
  -->
</template>

<script>
  import axios from "axios";

  export default {
    name: "Commentaries",

    props: {
      blogId: Number,
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
      }
    }
  }
</script>