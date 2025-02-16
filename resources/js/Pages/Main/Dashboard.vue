<script setup>
import { ref, onMounted, provide } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductList from './Partials/ProductList.vue';
import Cart from './Partials/Cart.vue';

const cart = ref([]);
const products = ref([]);

// Create a single instance of alert
const alert = useAlert();
provide('alert', alert);
provide('cart', cart);
provide('products', products);

const fetchProducts = async () => {
  try {
    const { data } = await axios.get('/inventory/products/featured/10');
    products.value = data;
  } catch (error) {
    console.error('Failed to fetch products', error);
    alert.error('Failed to fetch products');
  }
};

onMounted(async () => {
  fetchProducts();
});
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
      <Alert />
      <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
        <ProductList :fetch-products="fetchProducts" />
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <Cart />
      </div>
    </div>
  </AuthenticatedLayout>
</template>