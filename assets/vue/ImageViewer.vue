<template>
  <TransitionRoot as="template"
                  :show="open">
    <Dialog as="div"
            class="relative z-50"
            @close="close">
      <TransitionChild as="template"
                       enter="ease-out duration-300"
                       enter-from="opacity-0"
                       enter-to="opacity-100"
                       leave="ease-in duration-200"
                       leave-from="opacity-100"
                       leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template"
                           enter="ease-out duration-300"
                           enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                           enter-to="opacity-100 translate-y-0 sm:scale-100"
                           leave="ease-in duration-200"
                           leave-from="opacity-100 translate-y-0 sm:scale-100"
                           leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel
                class="relative overflow-hidden rounded-lg bg-black w-full  shadow-xl transition-all m-32 sm:p-6">
                <div class="relative">
                  <button @click="close" class="">
                    <img class="max-h-[32rem]"
                         :src="path + '/uploads/blog/image/' + images[currentIndex].filename"
                    />
                  </button>
                  <div v-if="editMode">
                    <textarea v-model="text">
                    </textarea>
                    <button class="bg-amber-600 text-white p-2 rounded-xl" @click="atSetDescription">
                      Set description
                    </button>
                  </div>
                  <div v-else>
                    <p class="text-white">
                      {{ images[currentIndex].description }}
                    </p>
                  </div>

                  <button class="hover:animate-pulse absolute top-1/2 left-0 bg-black border-slate-400 border-2 text-white p-2 rounded-full w-20 h-20 text-3xl align-middle -translate-y-1/2 "
                          @click="decreaseIndex">
                    ←
                  </button>
                  <button class="hover:animate-pulse absolute top-1/2 right-0 bg-black border-slate-400 border-2 text-white p-2 rounded-full w-20 h-20 text-3xl	align-middle -translate-y-1/2"
                          @click="increaseIndex">
                    →
                  </button>
                </div>

            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {ref} from 'vue'
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from '@headlessui/vue'
const open = ref(true)
</script>

<script>
import axios from "axios";

export default {
  name: "ImageViewer",

  props: {
    index: Number,
    images: Array,
    path: String,
    editMode: Boolean,
  },

  data() {
    return {
      currentIndex: this.index,
      text: "",
      url: location.origin,
    }
  },

  created() {
    this.text = this.images[this.currentIndex].description;
  },

  methods: {
    atSetDescription()
    {
      axios
          .post(this.url + "/image/edit/" + this.images[this.currentIndex].id, {
            'description': (this.text.length > 0) ? this.text : null,
          })
          .then((res) => {
            this.images[this.currentIndex].description = this.text;
          })
    },

    close()
    {
      this.$emit('requestClose');
    },

    decreaseIndex()
    {
      this.currentIndex -= 1;
      if (this.currentIndex < 0)
        this.currentIndex = this.images.length - 1;
      this.text = this.images[this.currentIndex].description;
    },

    increaseIndex()
    {
      this.currentIndex += 1;
      if (this.currentIndex === this.images.length)
        this.currentIndex = 0;
      this.text = this.images[this.currentIndex].description;
    }
  },
}
</script>