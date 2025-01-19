<template>
  <div class="flex justify-between items-center bg-slate-200 px-4 py-1">
    <h1 class="text-2xl font-bold text-gray-900">POS-Lite</h1>
    <h2 class="text-sm">{{ formattedDateTime }}</h2>
  </div>
  <div class="flex w-full">
    <div class="grid grid-cols-4 w-full">
      <div v-for="item in navItems" :key="item.name">
        <button :class="`w-full p-5 shadow focus:outline-none focus:ring-2 ${item.colors}`">
          <div class="flex flex-col items-center gap-x-1 text-white font-medium">
            <component :is="item.icon" class="h-5 w-5" />
            {{ item.name }}
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { DocumentChartBarIcon, ClipboardDocumentListIcon, CreditCardIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline'

let time = ref(null)
const currentDateTime = ref(new Date())
const formattedDateTime = computed(() => {
  const date = currentDateTime.value.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
  const time = currentDateTime.value.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })
  return `${date.split(', ')[1]}, ${date.split(', ')[2]} (${date.split(', ')[0]}): ${time}`
})

const navItems = [
  { name: 'Cart', icon: ShoppingCartIcon, colors: 'bg-orange-500 hover:bg-orange-600 focus:ring-orange-500' },
  { name: 'Inventory', icon: ClipboardDocumentListIcon, colors: 'bg-green-500 hover:bg-green-600 focus:ring-green-500' },
  { name: 'Pending Orders', icon: CreditCardIcon, colors: 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-500' },
  { name: 'Records', icon: DocumentChartBarIcon, colors: 'bg-purple-500 hover:bg-purple-600 focus:ring-purple-500' },
]

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