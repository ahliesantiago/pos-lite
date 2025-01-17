<template>
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
  />

  <!-- <TransitionRoot appear :show="isEditModalOpen" as="template">
    <Dialog as="div" @close="closeEditModal" class="relative z-10">
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
              <div v-if="editingProduct" class="mt-2">
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
                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      id="productDescription"
                      v-model="editingProduct.description"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    ></textarea>
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
                  <div>
                    <label for="productExpiration" class="block text-sm font-medium text-gray-700">Expiration Date (if applicable)</label>
                    <input
                      id="productExpiration"
                      v-model="editingProduct.expiration"
                      type="date"
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
  </TransitionRoot> -->
</template>

<script setup>
import { inject, ref, computed, onUnmounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import ProductModal from '@/Components/common/ProductModal.vue'

const products = ref([
  { id: 1, name: 'Coke (1.5L)', price: 70.00, stock: 15, description: 'Test Description', expiration: '2025-03-30' },
  { id: 2, name: 'Coke (mismo)', price: 20.00, stock: 8, description: 'Test Description', expiration: '2025-03-25' },
  { id: 3, name: 'Doowee Donut', price: 12.00, stock: 19, description: 'Test Description', expiration: '2025-03-28' },
  { id: 4, name: 'Fudgee Bar', price: 10.00, stock: 5, description: 'Test Description', expiration: '2025-03-15' },
  { id: 5, name: 'Top Coffee - Brown Palm Sugar (3s)', price: 14.00, stock: 30, description: 'Test Description', expiration: '2025-06-05' },
  { id: 6, name: 'Four Seasons (1L)', price: 98.00, stock: 0, description: 'Test Description', expiration: '2025-04-10' },
])
const cart = inject('cart')
const searchQuery = ref('')
const isEditModalOpen = ref(false)
const editingProduct = ref(null)
const longPressTimer = ref(null)
const longPressDuration = 500 // ms

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const query = searchQuery.value.toLowerCase()
  return products.value.filter(product => product.name.toLowerCase().includes(query))
})

const addToCart = (product) => {
  const existingItem = cart.value.find(item => (item.id === product.id) && (item.stock > item.quantity))
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
  isEditModalOpen.value = true
}

const closeEditModal = () => {
  isEditModalOpen.value = false
  setTimeout(() => {
    editingProduct.value = null
  }, 300)
}

const saveProduct = () => {
  if (editingProduct.value) {
    const index = products.value.findIndex(p => p.id === editingProduct.value.id)
    if (index !== -1) {
      products.value[index] = { ...editingProduct.value }
    }
  }
  closeEditModal()
}

const archiveProduct = () => {
  if (editingProduct.value) {
    products.value = products.value.filter(p => p.id !== editingProduct.value.id)
  }
  closeEditModal()
}

// Clean up long press timer
onUnmounted(() => {
  if (longPressTimer.value) {
    clearTimeout(longPressTimer.value)
  }
})
</script>