<template>
  <div class="flex justify-between items-center bg-slate-200 px-4 py-1">
    <h1 class="text-2xl font-bold text-gray-900">POS-Lite</h1>
    <h2 class="text-sm sm:block hidden">{{ formattedDateTime }}</h2>
    <Bars3Icon
      class="h-6 w-6 sm:hidden"
    />
  </div>
  <div class="min-[645px]:flex w-full hidden">
    <div class="grid w-full" :style="{ gridTemplateColumns: gridColumns }">
      <div v-for="item in navItems" :key="item.name">
        <Link
          :href="route(item.route)"
          as="button"
          :class="`w-full p-5 shadow focus:outline-none focus:ring-2
            ${route().current(item.route) ? 'bg-gray-100 border border-gray-500' : item.colors}
          `"
        >
          <div :class="`flex flex-col items-center gap-y-2 ${route().current(item.route) ? 'text-black' : 'text-white'} font-medium`">
            <component :is="item.icon" class="h-5 w-5" />
            <span class="text-sm whitespace-normal text-center">{{ item.name }}</span>
          </div>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Bars3Icon, DocumentChartBarIcon, ClipboardDocumentListIcon, Cog6ToothIcon, CreditCardIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline';

let time = ref(null);
const currentDateTime = ref(new Date());
const formattedDateTime = computed(() => {
  const date = currentDateTime.value.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  const time = currentDateTime.value.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
  return `${date.split(', ')[1]}, ${date.split(', ')[2]} (${date.split(', ')[0]}): ${time}`;
});

const navItems = [
  { 
    name: 'Cart', 
    icon: ShoppingCartIcon, 
    colors: 'bg-orange-500 hover:bg-orange-600 focus:ring-orange-500',
    route: 'dashboard',
  },
  { 
    name: 'Inventory', 
    icon: ClipboardDocumentListIcon, 
    colors: 'bg-green-500 hover:bg-green-600 focus:ring-green-500',
    route: 'inventory',
  },
  { 
    name: 'Pending Orders', 
    icon: CreditCardIcon, 
    colors: 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-500',
    route: 'orders',
  },
  { 
    name: 'Records', 
    icon: DocumentChartBarIcon, 
    colors: 'bg-purple-500 hover:bg-purple-600 focus:ring-purple-500',
    route: 'records',
  },
  { 
    name: 'Settings', 
    icon: Cog6ToothIcon, 
    colors: 'bg-amber-900 hover:bg-amber-900 focus:ring-amber-900',
    route: 'records',
    weight: 0.3
  },
];

// Responsive grid columns for the nav buttons
const gridColumns = computed(() => {
  const totalWeight = navItems.reduce((sum, item) => sum + (item.weight || 1), 0);
  return navItems.map((item) => `${(item.weight || 1) * (100 / totalWeight)}fr`).join(' ');
});

// Real-time clock
onMounted(() => {
  time = setInterval(() => {
    currentDateTime.value = new Date();
  }, 1000);
});

onUnmounted(() => {
  clearInterval(time);
});
</script>
