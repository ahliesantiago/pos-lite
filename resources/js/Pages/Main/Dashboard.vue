<script setup>
import { ref, provide } from 'vue';
import { Head } from '@inertiajs/vue3';
import ProductList from './Partials/ProductList.vue';
import Cart from './Partials/Cart.vue';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const cart = ref([]);
const products = ref([
  { id: 1, name: 'Coke (1.5L)', price: 70.00, stock: 15, description: 'Test Description', expiration: '2025-03-30' },
  { id: 2, name: 'Coke (mismo)', price: 20.00, stock: 8, description: 'Test Description', expiration: '2025-03-25' },
  { id: 3, name: 'Doowee Donut', price: 12.00, stock: 19, description: 'Test Description', expiration: '2025-03-28' },
  { id: 4, name: 'Fudgee Bar', price: 10.00, stock: 5, description: 'Test Description', expiration: '2025-03-15' },
  { id: 5, name: 'Top Coffee - Brown Palm Sugar (3s)', price: 14.00, stock: 30, description: 'Test Description', expiration: '2025-06-05' },
  { id: 6, name: 'Four Seasons (1L)', price: 98.00, stock: 0, description: 'Test Description', expiration: '2025-04-10' },
]);

// Create a single instance of alert
const alert = useAlert();
provide('alert', alert);
provide('cart', cart);
provide('products', products);
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
      <Alert />
      <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
        <ProductList />
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <Cart />
      </div>
    </div>
  </AuthenticatedLayout>
</template>