<script setup>
import { inject, ref, computed, onUnmounted } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { PlusIcon } from '@heroicons/vue/24/solid';
import ProductModal from '@/Components/common/ProductModal.vue';

const products = inject('products');
const cart = inject('cart');
const searchQuery = ref('');
const isEditModalOpen = ref(false);
const isAddModalOpen = ref(false);
const editingProduct = ref(null);
const newProduct = ref({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  expiration: ''
});
const longPressTimer = ref(null);
const longPressDuration = 500; // ms

const { alertPopup } = inject('alert');

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value;
  const query = searchQuery.value.toLowerCase();
  return products.value.filter(product => product.name.toLowerCase().includes(query));
});

const addToCart = (product) => {
  const existingItem = cart.value.find(item => item.id === product.id);
  const sufficientStock = product.stock > 0;
  if (existingItem && sufficientStock) {
    existingItem.quantity++;
    product.stock--;
    alertPopup(`Added ${product.name} to cart`, 'success');
  } else if (!existingItem && sufficientStock) {
    product.stock--;
    cart.value.push({ ...product, quantity: 1 });
    alertPopup(`Added ${product.name} to cart`, 'success');
  } else {
    alertPopup('Insufficient stock!', 'error', 5000);
  }
};

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
  newProduct.value = {
    name: '',
    description: '',
    price: 0,
    stock: 0,
    expiration: ''
  };
};

const addNewProduct = () => {
  const id = Math.max(...products.value.map(p => p.id)) + 1;
  products.value.push({
    id,
    ...newProduct.value,
    price: parseFloat(newProduct.value.price),
    stock: parseInt(newProduct.value.stock)
  });
  alertPopup('Product added successfully', 'success');
  closeAddModal();
};

const resetFields = () => {
  newProduct.value = {
    name: '',
    description: '',
    price: 0,
    stock: 0,
    expiration: ''
  };
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

const saveProduct = () => {
  if (editingProduct.value) {
    const index = products.value.findIndex(p => p.id === editingProduct.value.id)
    if (index !== -1) {
      products.value[index] = { ...editingProduct.value };
      alertPopup('Product updated successfully', 'success');
    }
  }
  closeEditModal();
};

const archiveProduct = () => {
  if (editingProduct.value) {
    products.value = products.value.filter(p => p.id !== editingProduct.value.id);
    alertPopup('Product archived successfully', 'success');
  }
  closeEditModal();
};

// Clean up long press timer
onUnmounted(() => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value);
  }
});
</script>

<template>
  <div class="mb-4 grid grid-cols-6 gap-x-2">
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
      <PlusIcon class="h-5 w-5 inline-block" /> Add
    </button>
  </div>
  
  <ul class="space-y-2">
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
        {{ product.name }} <span class="text-gray-500 text-sm">(Available: {{ product.stock }})</span>
      </span>
      <span class="text-gray-600">₱{{ product.price.toFixed(2) }}</span>
    </li>
  </ul>

  <ProductModal
    :isModalOpen="isEditModalOpen"
    :closeModal="closeEditModal"
    :action="'editing'"
    :positiveAction="saveProduct"
    :negativeAction="archiveProduct"
    :product="editingProduct"
  />

  <ProductModal
    :isModalOpen="isAddModalOpen"
    :closeModal="closeAddModal"
    :action="'adding'"
    :positiveAction="addNewProduct"
    :negativeAction="resetFields"
    :product="newProduct"
  />
</template>