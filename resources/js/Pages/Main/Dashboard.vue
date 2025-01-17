<template>
  <div class="min-h-screen bg-gray-100 p-4">
    <div class="mx-auto">
      <h1 class="text-3xl font-bold text-gray-900">POS-Lite</h1>
      <h2 class="text-xl font-semibold mb-6">{{ formattedDateTime }}</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
          <ProductList />
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
          <Cart />
        </div>
      </div>
      
      <!-- Quick Add Button -->
      <div class="mt-6">
        <button
          @click="openAddModal"
          class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          Add Product
        </button>
      </div>
    </div>

    <ProductModal
      :isModalOpen="isAddModalOpen"
      :closeModal="closeAddModal"
      :action="'adding'"
      :positiveAction="addNewProduct"
      :negativeAction="resetFields"
      :product="newProduct"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, provide } from 'vue'
import Cart from './Cart.vue'
import ProductList from './ProductList.vue'
import ProductModal from '@/Components/common/ProductModal.vue'

const cart = ref([])
provide('cart', cart)
let time = ref(null)
const currentDateTime = ref(new Date())
const formattedDateTime = computed(() => {
  const date = currentDateTime.value.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
  const time = currentDateTime.value.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })
  return `${date.split(', ')[1]}, ${date.split(', ')[2]} (${date.split(', ')[0]}): ${time}`
})
const isAddModalOpen = ref(false)
const newProduct = ref({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  expiration: ''
})

const openAddModal = () => {
  isAddModalOpen.value = true
}

const closeAddModal = () => {
  isAddModalOpen.value = false
  newProduct.value = {
    name: '',
    description: '',
    price: 0,
    stock: 0,
    expiration: ''
  }
}

const addNewProduct = () => {
  const id = Math.max(...products.value.map(p => p.id)) + 1
  products.value.push({
    id,
    ...newProduct.value,
    price: parseFloat(newProduct.value.price),
    stock: parseInt(newProduct.value.stock)
  })
  closeAddModal()
}

const resetFields = () => {
  newProduct.value = {
    name: '',
    description: '',
    price: 0,
    stock: 0,
    expiration: ''
  }
}

// Real-time clock
onMounted(() => {
  time = setInterval(() => {
    currentDateTime.value = new Date()
  }, 1000)
})

onUnmounted(() => {
  clearInterval(time)
})
</script>