<script setup>
import Modal from '@/Components/ui/Modal.vue';

const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
  action: String,
  positiveAction: Function,
  negativeAction: Function,
  type: Object,
})
</script>

<template>
  <Modal
    :isModalOpen="isModalOpen"
    :closeModal="closeModal"
    :title="(action === 'editing' ? 'Edit' : 'Add New') + ' Product Category'"
  >
    <div class="mt-2">
      <div class="space-y-4">
        <div>
          <label for="type_name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            id="type_name"
            v-model="type.type_name"
            type="text"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
        </div>
        <!-- <div>
          <label for="parent_type_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
          <select
            id="parent_type_id"
            v-model="type.parent_type_id"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
            <option value="prodIDhere">prodNameHere</option>
          </select>
        </div> -->
      </div>
    </div>

    <div class="mt-4 flex justify-end space-x-2">
      <button
        type="button"
        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
        @click="positiveAction"
      >
      {{ action === 'editing' ? 'Save' : 'Add Product Category' }}
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
  </Modal>
</template>