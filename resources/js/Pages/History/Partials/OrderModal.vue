<script setup>
import Modal from '@/Components/ui/Modal.vue';

const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
  selectedOrder: Object,
  cartItems: Array
})
</script>

<template>
  <Modal
    :isModalOpen="isModalOpen"
    :closeModal="closeModal"
    title="Order Details"
  >
    <p v-if="selectedOrder.customer_name" class="text-center">
      <span class="font-bold">Customer</span>: {{ selectedOrder.customer_name }}
    </p>

    <table>
      <thead class="text-xs uppercase bg-gray-50">
        <tr>
          <th scope="col" class="px-4 py-2">Product Name</th>
          <th scope="col" class="px-4 py-2 text-center">Price</th>
          <th scope="col" class="px-4 py-2 text-center">Qty</th>
          <th scope="col" class="px-4 py-2">Total</th>
        </tr>
      </thead>
      <tr v-for="item in cartItems" :key="item.id" class="border-b border-gray-800">
        <td class="px-4 py-2">{{ item.product.product_name }}</td>
        <td class="px-4 py-2">₱{{ item.price_at_checkout }}</td>
        <td class="px-4 py-2 text-right">x{{ item.quantity }}</td>
        <td class="px-4 py-2 font-bold bg-gray-100">₱{{ (item.total_price).toFixed(2) }}</td>
      </tr>
    </table>

    <button
      type="button"
      class="border border-gray-300 text-gray-900 py-2 px-4 rounded-md font-medium hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-100 focus-visible:ring-offset-2"
      @click="closeModal"
    >
      Close
    </button>
  </Modal>
</template>