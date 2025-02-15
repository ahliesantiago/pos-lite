<script setup>
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { ArrowUpTrayIcon, PlusIcon } from '@heroicons/vue/24/solid';
import { fetchProductTypes } from '@/Composables/useProductOperations';
import ProductModal from '@/Components/common/ProductModal.vue';

defineProps({
  fetchProducts: Function
});

const { alertPopup } = inject('alert');
const products = inject('products');
const categories = ref([]);
const form = useForm({
  product_name: '',
  product_type_id: 1,
  brand: '',
  description: '',
  price: null,
  discounted_price_1: null,
  stocks: null,
  closest_expiration_date: ''
});
const longPressTimer = ref(null);
const longPressDuration = 500; // ms
const searchQuery = ref('');
const isEditModalOpen = ref(false);
const isAddModalOpen = ref(false);
const editingProduct = ref(null);
const cart = inject('cart');

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
};

const addNewProduct = () => {
  form.post('/products', {
    preserveScroll: true,
    onSuccess: () => {
      alertPopup('Product added successfully', 'success');
      closeAddModal();
      form.reset();
      // fetchProducts();
    },
    onError: (error) => {
      alertPopup('Failed to add product', 'error');
    }
  });
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

onMounted(async () => {
  try {
    categories.value = await fetchProductTypes();
  } catch (error) {
    alertPopup('Failed to fetch categories', 'error');
  }
});

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
    :categories="categories"
  />

  <ProductModal
    :isModalOpen="isAddModalOpen"
    :closeModal="closeAddModal"
    :action="'adding'"
    :positiveAction="addNewProduct"
    :negativeAction="form.reset"
    :product="form"
    :categories="categories"
  />
</template>