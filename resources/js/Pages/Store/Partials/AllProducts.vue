<script setup>
import { onMounted, provide, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { PlusIcon  } from '@heroicons/vue/24/solid';
import { ArchiveBoxIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';

const alert = useAlert();
provide('alert', alert);
const alertPopup = alert.alertPopup;
const products = ref([]);
const errors = ref({});

const fetchProducts = async () => {
  try {
    const { data } = await axios.get('/inventory/products/list', {
      params: { itemCount: 20, page: 1 }
    });
    products.value = data.data;
  } catch (error) {
    console.error('Failed to fetch products', error);
    alertPopup('Failed to fetch products', 'error');
  }
};

onMounted(async () => {
  fetchProducts();
});
</script>

<template>
  <Alert />

  <h2 class="text-2xl font-semibold">List of Products</h2>

  <div class="py-5 flex justify-around items-center">
    <button
      @click="openAddModal"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Product
    </button>
    <Link
      :href="route('products.bulk-add')"
      as="button"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Multiple Products
    </Link>
  </div>

  <div v-if="products.length === 0" class="text-gray-500 text-center p-4">
    No products found. Add a new product to get started.
  </div>
  
  <ul v-if="products.length > 0" class="space-y-2 border-b border-gray-300">
    <div
      v-for="product in products"
      :key="product.id"
      class="grid grid-cols-7 p-2 hover:bg-gray-50 rounded-lg cursor-pointer"
    >
      <p class="col-span-4">{{ product.product_name }}</p>
      <div class="col-span-1 flex justify-center items-center">
        <EyeIcon class="h-5 w-5" />
      </div>
      <div class="col-span-1 flex justify-center items-center">
        <ArchiveBoxIcon class="h-5 w-5" />
      </div>
      <div class="col-span-1 flex justify-center items-center">
        <PencilSquareIcon class="h-5 w-5" />
      </div>
    </div>
  </ul>
</template>
