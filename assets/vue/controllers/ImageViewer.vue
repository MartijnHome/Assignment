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
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
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
                class="relative transform overflow-hidden rounded-lg bg-white  shadow-xl transition-all sm:my-8 mx-4 sm:p-6">

              <div class="relative">
                <button @click="close">
                  <img class=""
                       :src="path + images[currentIndex][0]"
                  />
                </button>
                <button class="absolute top-1/2 left-0 bg-red-800 text-white p-2 rounded-xl text-3xl"
                @click="decreaseIndex">
                  ←
                </button>
                <button class="absolute top-1/2 right-0 bg-red-800 text-white p-2 rounded-xl text-3xl"
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
export default {
  props: {
    index: Number,
    images: Array,
    path: String,
  },

  data() {
    return {
      currentIndex: this.index,
    }
  },

  methods: {
    close()
    {
      this.$emit('requestClose')
    },

    decreaseIndex()
    {
      this.currentIndex -= 1;
      if (this.currentIndex < 0)
        this.currentIndex = this.images.length - 1;
    },

    increaseIndex()
    {
      this.currentIndex += 1;
      if (this.currentIndex === this.images.length)
        this.currentIndex = 0;
    }
  },
}
</script>