<script setup>
import Modal from '@/Components/ui/Modal.vue';

const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
})

const handleSave = () => {
  console.log('Saving product...');
}
</script>

<template>
  <Modal
    :isModalOpen="isModalOpen"
    :closeModal="closeModal"
    title="Checkout"
  >
    <div>
      <div>
        <ul class="list-disc list-inside">
          <li>Product 1</li>
        </ul>
      </div>
      <input
        id="isDiscounted"
        type="checkbox"
      >
      <label for="isDiscounted" class="ml-2 text-sm text-gray-600">Is this order discounted?</label>
    </div>
  </Modal>
</template>