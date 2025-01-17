<template>
  <div class="min-h-screen bg-gray-100 p-4">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-900 mb-6">POS-Lite</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Product List Section -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-4">
          <div class="mb-4">
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
              <span>{{ product.name }}</span>
              <span class="text-gray-600">${{ product.price.toFixed(2) }}</span>
            </li>
          </ul>
        </div>
        
        <!-- Cart Section -->
        <div class="bg-white rounded-lg shadow p-4">
          <h2 class="text-xl font-semibold mb-4">Current Cart</h2>
          <ul class="space-y-2 mb-4">
            <li v-for="item in cart" :key="item.id" class="flex justify-between">
              <span>{{ item.name }} x{{ item.quantity }}</span>
              <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
            </li>
          </ul>
          <div class="text-xl font-bold">
            Total: ${{ cartTotal.toFixed(2) }}
          </div>
          <button
            @click="checkout"
            class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Checkout
          </button>
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
    
    <!-- Edit/Archive Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Edit Product
                </DialogTitle>
                <div class="mt-2">
                  <div class="space-y-4">
                    <div>
                      <label for="productName" class="block text-sm font-medium text-gray-700">Name</label>
                      <input
                        id="productName"
                        v-model="editingProduct.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                    <div>
                      <label for="productPrice" class="block text-sm font-medium text-gray-700">Price</label>
                      <input
                        id="productPrice"
                        v-model="editingProduct.price"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                    <div>
                      <label for="productStock" class="block text-sm font-medium text-gray-700">Available Stock</label>
                      <input
                        id="productStock"
                        v-model="editingProduct.stock"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                  </div>
                </div>

                <div class="mt-4 flex justify-between">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="saveProduct"
                  >
                    Save
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    @click="archiveProduct"
                  >
                    Archive
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Add Product Modal -->
    <TransitionRoot appear :show="isAddModalOpen" as="template">
      <Dialog as="div" @close="closeAddModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Add New Product
                </DialogTitle>
                <div class="mt-2">
                  <div class="space-y-4">
                    <div>
                      <label for="newProductName" class="block text-sm font-medium text-gray-700">Name</label>
                      <input
                        id="newProductName"
                        v-model="newProduct.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                    <div>
                      <label for="newProductDescription" class="block text-sm font-medium text-gray-700">Description</label>
                      <textarea
                        id="newProductDescription"
                        v-model="newProduct.description"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      ></textarea>
                    </div>
                    <div>
                      <label for="newProductPrice" class="block text-sm font-medium text-gray-700">Price</label>
                      <input
                        id="newProductPrice"
                        v-model="newProduct.price"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                    <div>
                      <label for="newProductStock" class="block text-sm font-medium text-gray-700">Initial Stock</label>
                      <input
                        id="newProductStock"
                        v-model="newProduct.stock"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                    <div>
                      <label for="newProductExpiration" class="block text-sm font-medium text-gray-700">Expiration Date (if applicable)</label>
                      <input
                        id="newProductExpiration"
                        v-model="newProduct.expiration"
                        type="date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>
                  </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="addNewProduct"
                  >
                    Add Product
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                    @click="closeAddModal"
                  >
                    Cancel
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const products = ref([
  { id: 1, name: 'Apple', price: 0.50, stock: 100 },
  { id: 2, name: 'Banana', price: 0.75, stock: 80 },
  { id: 3, name: 'Orange', price: 0.60, stock: 90 },
  { id: 4, name: 'Milk', price: 2.50, stock: 50 },
  { id: 5, name: 'Bread', price: 1.50, stock: 30 },
])

const cart = ref([])
const searchQuery = ref('')
const isModalOpen = ref(false)
const isAddModalOpen = ref(false)
const editingProduct = ref(null)
const newProduct = ref({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  expiration: ''
})
const longPressTimer = ref(null)
const longPressDuration = 500 // ms

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const query = searchQuery.value.toLowerCase()
  return products.value.filter(product => product.name.toLowerCase().includes(query))
})

const cartTotal = computed(() => {
  return cart.value.reduce((total, item) => total + item.price * item.quantity, 0)
})

const addToCart = (product) => {
  const existingItem = cart.value.find(item => item.id === product.id)
  if (existingItem) {
    existingItem.quantity++
  } else {
    cart.value.push({ ...product, quantity: 1 })
  }
}

const selectProduct = (product) => {
  addToCart(product)
}

const startLongPress = (product) => {
  longPressTimer.value = setTimeout(() => {
    openEditModal(product)
  }, longPressDuration)
}

const endLongPress = () => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value)
  }
}

const openEditModal = (product) => {
  editingProduct.value = { ...product }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingProduct.value = null
}

const saveProduct = () => {
  const index = products.value.findIndex(p => p.id === editingProduct.value.id)
  if (index !== -1) {
    products.value[index] = { ...editingProduct.value }
  }
  closeModal()
}

const archiveProduct = () => {
  products.value = products.value.filter(p => p.id !== editingProduct.value.id)
  closeModal()
}

const checkout = () => {
  // Implement checkout logic here
  console.log('Checkout:', cart.value)
  cart.value = []
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

// Clean up long press timer
onUnmounted(() => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value)
  }
})
</script>