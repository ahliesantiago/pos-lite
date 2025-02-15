<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AllProducts from './Partials/AllProducts.vue';
import Categories from './Partials/Categories.vue';

</script>

<template>
  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
      <AllProducts />
      <Categories />
    </div>
  </AuthenticatedLayout>
</template>
