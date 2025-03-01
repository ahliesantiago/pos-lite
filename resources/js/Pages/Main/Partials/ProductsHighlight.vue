<script setup>
import { computed, inject, onUnmounted, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { ArrowUpTrayIcon, PlusIcon } from '@heroicons/vue/24/solid';
import ProductUpdates from '@/Pages/Store/Partials/ProductUpdates.vue';

const props = defineProps({
  searchProducts: Function
});

const { alertPopup } = inject('alert');
const products = inject('products');
const searchQuery = ref('');
const longPressTimer = ref(null);
const longPressDuration = 500; // ms
const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const editingProduct = ref(null);
const cart = inject('cart');

watch(searchQuery, (newQuery) => {
  props.searchProducts(newQuery);
});

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value;
  const query = searchQuery.value.toLowerCase();
  return products.value.filter(product => product.product_name.toLowerCase().includes(query));
});

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
};

const selectProduct = (product) => {
  addToCart(product);
};

const startLongPress = (product) => {
  longPressTimer.value = setTimeout(() => {
    openEditModal(product);
  }, longPressDuration);
};

const endLongPress = () => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value);
  }
};

const openEditModal = (product) => {
  editingProduct.value = { ...product };
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false
  setTimeout(() => {
    editingProduct.value = null;
  }, 300);
};

const addToCart = (product) => {
  const cartItem = cart.value.find(item => item.id === product.id);
  const sufficientStock = product.stocks > 0;
  if (cartItem && sufficientStock) {
    cartItem.quantity++;
    cartItem.stocks--;
    product.stocks--;
    alertPopup(`Added ${product.product_name} to cart`, 'success');
  } else if (!cartItem && sufficientStock) {
    product.stocks--;
    cart.value.push({ ...product, quantity: 1 });
    alertPopup(`Added ${product.product_name} to cart`, 'success');
  } else {
    alertPopup('Insufficient stock!', 'error', 5000);
  }
};

// Clean up long press timer
onUnmounted(() => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value);
  }
});
</script>

<template>
  <div class="mb-4 grid grid-cols-7 gap-x-2">
    <div class="col-span-5">
      <label for="search" class="sr-only">Search products</label>
      <div class="relative">
        <input
          id="search"
          v-model="searchQuery"
          type="text"
          placeholder="Search products..."
          class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
        </div>
      </div>
    </div>
    <button
      @click="openAddModal"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> <span class="hidden xl:inline-block">Add</span>
    </button>
    <Link
      :href="route('products.bulk-add')"
      as="button"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <ArrowUpTrayIcon class="h-5 w-5 inline-block" /> <span class="hidden xl:inline-block">Bulk</span>
    </Link>
  </div>

  <div v-if="products.length === 0" class="text-gray-500 text-center p-4">
    No products found. Add a new product to get started.
  </div>
  
  <ul v-if="products.length > 0" class="space-y-2">
    <li
      v-for="product in filteredProducts"
      :key="product.id"
      class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg cursor-pointer"
      @click="selectProduct(product)"
      @mousedown="startLongPress(product)"
      @mouseup="endLongPress"
      @mouseleave="endLongPress"
      @touchstart="startLongPress(product)"
      @touchend="endLongPress"
    >
      <span>
        {{ product.product_name }} <span class="text-gray-500 text-sm">(Available: {{ product.stocks }})</span>
      </span>
      <span class="text-gray-600">₱{{ product.price.toFixed(2) }}</span>
    </li>
  </ul>

  <ProductUpdates
    :isAddModalOpen="isAddModalOpen"
    :isEditModalOpen="isEditModalOpen"
    :closeAddModal="closeAddModal"
    :closeEditModal="closeEditModal"
    :editingProduct="editingProduct"
  />
</template>