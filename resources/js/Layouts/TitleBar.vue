<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Bars3Icon } from '@heroicons/vue/24/outline';

let time = ref(null);
const currentDateTime = ref(new Date());
const formattedDateTime = computed(() => {
  const date = currentDateTime.value.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  const time = currentDateTime.value.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
  return `${date.split(', ')[1]}, ${date.split(', ')[2]} (${date.split(', ')[0]}): ${time}`;
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

<template>
  <div class="flex justify-between items-center bg-slate-200 px-4 py-1">
    <h1 class="text-2xl font-bold text-gray-900">POS-Lite</h1>
    <h2 class="text-sm sm:block hidden">{{ formattedDateTime }}</h2>
    <Bars3Icon
      class="h-6 w-6 sm:hidden"
    />
  </div>
</template>