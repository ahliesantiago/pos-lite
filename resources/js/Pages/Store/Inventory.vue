<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AllProducts from './Partials/AllProducts.vue';
import Categories from './Partials/Categories.vue';
</script>

<template>
  <Head title="Inventory" />
  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
      <div class="bg-white rounded-lg shadow p-4">
        <AllProducts />
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <Categories />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
