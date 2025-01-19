<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
    <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
      <ProductList />
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
      <Cart />
    </div>
  </div>
</template>

<script setup>
import { ref, provide } from 'vue'
import ProductList from './Partials/ProductList.vue'
import Cart from './Partials/Cart.vue'

const cart = ref([])
provide('cart', cart)
</script>