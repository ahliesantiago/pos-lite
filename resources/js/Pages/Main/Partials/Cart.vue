<script setup>
import { computed, inject, ref } from 'vue';
import { MinusIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/solid';
import Checkout from './Checkout.vue';

const emit = defineEmits();
const cart = inject('cart');
const products = inject('products');
const { alertPopup } = inject('alert');
const isCheckoutModalOpen = ref(false);

const removeFromCart = (itemId) => {
  const item = cart.value.find(item => item.id === itemId);
  if (item) {
    const product = products.value.find(p => p.id === itemId);
    if (product) {
      product.stock += item.quantity;
    }
    cart.value = cart.value.filter((item) => item.id !== itemId);
    alertPopup('Item removed from cart', 'success');
  }
};

const updateQuantity = (payload) => {
  const { id, quantity } = payload;
  const cartItem = cart.value.find((item) => item.id === id);
  const product = products.value.find((p) => p.id === id);
  
  if (cartItem && product) {
    const quantityDiff = quantity - cartItem.quantity;
    // Check if product stocks is sufficient
    if (product.stocks >= quantityDiff && quantity >= 1) {
      const newQuantity = Math.max(quantity, 1);
      const oldQuantity = cartItem.quantity;
      cartItem.quantity = Math.min(newQuantity, oldQuantity + product.stocks);
      // Update product stocks
      product.stocks -= (cartItem.quantity - oldQuantity);
      alertPopup('Quantity updated successfully', 'success');
    } else if (quantity <= 0) {
      alertPopup('Unable to decrease further', 'error');
    } else {
      alertPopup('Insufficient stocks!', 'error');
    }
  }
};

const cartTotal = computed(() => {
  return cart.value.reduce((total, item) => total + item.price * item.quantity, 0);
});

const openCheckout = () => {
  if (cart.value.length === 0) {
    alertPopup('Cart is empty!', 'error');
    return;
  }
  isCheckoutModalOpen.value = true;
};

const closeCheckout = () => {
  isCheckoutModalOpen.value = false;
  // // If success:
  // alertPopup('Checkout successful!', 'success');
  // cart.value = [];
};
</script>

<template>
  <h2 class="text-xl font-semibold mb-4">Current Cart</h2>

  <table class="w-full text-sm text-left">
    <thead class="text-xs uppercase bg-gray-50">
      <tr>
        <th></th>
        <th scope="col" class="px-4 py-2">Product Name</th>
        <th scope="col" class="px-4 py-2 text-center">Qty</th>
        <th scope="col" class="px-4 py-2">Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in cart" :key="item.id" class="border-b">
        <td>
          <TrashIcon
            class="h-5 w-5 text-red-400"
            @click="removeFromCart(item.id)"
          />
        </td>
        <td class="px-4 py-2">{{ item.product_name }}</td>
        <td class="px-4 py-2">
          <div class="flex">
            <button
              @click="updateQuantity({ id: item.id, quantity: item.quantity - 1 })"
              class="bg-gray-200 text-gray-600 py-1 px-2 rounded-l-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              <MinusIcon class="h-5 w-5" />
            </button>
            <div class="bg-gray-200 text-gray-600 py-1 px-2">
              {{ item.quantity }}
            </div>
            <button
              @click="updateQuantity({ id: item.id, quantity: item.quantity + 1 })"
              class="bg-gray-200 text-gray-600 py-1 px-2 rounded-r-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              <PlusIcon class="h-5 w-5" />
            </button>
          </div>
        </td>
        <td class="px-4 py-2">₱{{ (item.price * item.quantity).toFixed(2) }}</td>
      </tr>
    </tbody>
  </table>
  <div class="text-xl font-bold mt-4">
    Total: ₱{{ cartTotal.toFixed(2) }}
  </div>
  <button
    @click="openCheckout"
    class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-300 disabled:text-gray-700 disabled:cursor-not-allowed"
    :disabled="cart?.length === 0"
  >
    Checkout
  </button>

  <Checkout
    :isModalOpen="isCheckoutModalOpen"
    :closeModal="closeCheckout"
    :cartTotal="cartTotal"
  />
</template>