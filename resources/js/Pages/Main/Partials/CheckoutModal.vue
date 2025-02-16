<script setup>
import { inject, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/ui/Modal.vue';

const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
  cartTotal: Number,
})
const emit = defineEmits();
const cart = inject('cart');
const { alertPopup } = inject('alert');
const form = useForm({
  customer_name: '',
  is_paid: false,
  is_discounted: false,
  total_price: props.cartTotal,
  due_date: null,
  remarks: '',
  products: [],
});

watch(
  () => cart.value, (newCart) => {
    if (newCart?.length) {
      form.products = newCart.map(item => ({
        product_id: item.id,
        stocks: item.stocks,
        quantity: item.quantity,
        price_at_checkout: item.price,
        total_price: (item.price * item.quantity).toFixed(2),
    }));
  }
}, { immediate: true, deep: true });

const handleCheckout = () => {
  form.post('/orders', {
    preserveScroll: true,
    onSuccess: () => {
      alertPopup('Checkout successful', 'success');
      props.closeModal();
      form.reset();
      cart.value = [];
    },
    onError: (error) => {
      alertPopup('Failed to checkout', 'error');
      console.error('Encountered error during checkout:', error);
    }
  });
}
</script>

<template>
  <Modal
    :isModalOpen="isModalOpen"
    :closeModal="closeModal"
    title="Checkout"
  >
    <div class="mt-2">
      <div class="space-y-4 text-gray-800">
        <div>
          <label for="customer_name" class="block text-md">Customer Name (optional)</label>
          <input
            id="customer_name"
            v-model="form.customer_name"
            type="text"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <span v-if="form.errors.customer_name" class="text-red-500 text-sm italic">
            {{ form.errors.customer_name }}
          </span>
        </div>
        <div class="overflow-x-auto">
          <table>
            <thead class="text-xs uppercase bg-gray-50">
              <tr>
                <th scope="col" class="px-4 py-2">Product Name</th>
                <th scope="col" class="px-4 py-2 text-center">Qty</th>
                <th scope="col" class="px-4 py-2">Amt</th>
                <th scope="col" class="px-4 py-2">Total</th>
              </tr>
            </thead>
            <tr v-for="item in cart" :key="item.id" class="border-b border-gray-800">
              <td class="px-4 py-2">{{ item.product_name }}</td>
              <td class="px-4 py-2">₱{{ item.price }}</td>
              <td class="px-4 py-2 text-right">x{{ item.quantity }}</td>
              <td class="px-4 py-2 font-bold bg-gray-100">₱{{ (item.price * item.quantity).toFixed(2) }}</td>
            </tr>
          </table>
          <div class="mt-4 grid grid-cols-2">
            <div class="col-span-2 sm:col-span-1 order-2 sm:order-1">
              <input
                id="is_discounted"
                type="checkbox"
                class="w-4 h-4"
              >
              <label for="is_discounted" class="ml-2 text-lg">Discounted?</label>
            </div>
            <div class="text-xl text-right font-bold col-span-2 sm:col-span-1 order-1 sm:order-2">
              Total: ₱{{ cartTotal.toFixed(2) }}
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <div class="col-span-3 sm:col-span-1">
              <input
                id="is_paid"
                v-model="form.is_paid"
                type="checkbox"
                class="w-4 h-4"
              >
              <label for="is_paid" class="ml-2 text-lg">Paid<span v-if="!form.is_paid" class="">? If not, enter due date:</span></label>
            </div>
            <div v-if="!form.is_paid" class="col-span-3 sm:col-span-2">
              <input
                id="due_date"
                v-model="form.due_date"
                type="date"
                class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm text-gray-500 focus:border-blue-500 focus:ring-blue-500"
              />
            </div>
          </div>
        </div>
        <div>
          <label for="remarks" class="block text-md">Remarks (optional)</label>
          <textarea
            name="remarks"
            id="remarks"
            v-model="form.remarks"
            class="border rounded-md w-full p-2"
          />
        </div>
      </div>
    </div>
    <div class="mt-4 flex gap-2">
      <button
        @click="handleCheckout"
        class="w-full bg-blue-100 text-blue-900 font-medium py-2 px-4 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
      >
        Checkout
      </button>
      <button
        type="button"
        class="border border-gray-300 text-gray-900 py-2 px-4 rounded-md font-medium hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-100 focus-visible:ring-offset-2"
        @click="closeModal"
      >
        Cancel
      </button>
    </div>
  </Modal>
</template>