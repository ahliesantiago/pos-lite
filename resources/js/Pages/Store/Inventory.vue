<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AllProducts from './Partials/AllProducts.vue';
import Categories from './Partials/Categories.vue';
</script>

<template>
  <Head title="Inventory" />
  <AuthenticatedLayout>
    <div class="bg-white rounded-lg shadow p-5 my-5 mx-2">
      <AllProducts />
    </div>
    
    <div class="bg-white rounded-lg shadow p-5 my-5 mx-2">
      <Categories />
    </div>
  </AuthenticatedLayout>
</template>
