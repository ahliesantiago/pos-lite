<template>
  <TransitionRoot appear :show="isModalOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                {{ action === 'editing' ? 'Edit' : 'Add New' }} Product
              </DialogTitle>
              <div class="mt-2">
                <div class="space-y-4">
                  <div>
                    <label for="productName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                      id="productName"
                      v-model="product.name"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                  <div>
                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      id="productDescription"
                      v-model="product.description"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    ></textarea>
                  </div>
                  <div>
                    <label for="productPrice" class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 flex items-center">
                      <span class="bg-gray-200 text-gray-600 py-2 px-2 rounded-l-md border-t border-b border-gray-300">
                        ₱
                      </span>
                      <input
                        id="productPrice"
                        v-model="formattedPrice"
                        type="number"
                        step="1"
                        class="block w-full rounded-r-md border-l-0 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                  </div>
                  <div>
                    <label for="productStock" class="block text-sm font-medium text-gray-700">Available Stock</label>
                    <input
                      id="productStock"
                      v-model="product.stock"
                      type="number"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                  <div>
                    <label for="productExpiration" class="block text-sm font-medium text-gray-700">Expiration Date (if applicable)</label>
                    <input
                      id="productExpiration"
                      v-model="product.expiration"
                      type="date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>
                </div>
              </div>

              <div class="mt-4 flex justify-end space-x-2">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="positiveAction"
                >
                {{ action === 'editing' ? 'Save' : 'Add Product' }}
                </button>
                <button
                  v-if="action === 'adding'"
                  type="button"
                  class="inline-flex justify-center rounded-md border border-blue-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                  @click="negativeAction"
                >
                  Reset
                </button>
                <button
                  v-if="action === 'editing'"
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                  @click="negativeAction"
                >
                  Archive
                </button>
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                  @click="closeModal"
                >
                  Cancel
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
import { computed, defineProps } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
  action: String,
  positiveAction: Function,
  negativeAction: Function,
  product: Object,
})

const formattedPrice = computed({
  get: () => props.product.price.toFixed(2),
  set: (value) => {
    const parsed = parseFloat(value)
    props.product.price = isNaN(parsed) ? 0 : parsed
  },
})

</script>