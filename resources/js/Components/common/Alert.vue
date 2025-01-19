<template>
  <div 
    v-if="isAlertOpen"
    :class="[
      'fixed top-4 right-4 p-4 rounded shadow-lg z-50',
      alertDetails.type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 
      alertDetails.type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 
      'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700'
    ]"
  >
    <div class="flex items-center">
      <div class="py-1">
        <p>{{ alertDetails.message }}</p>
      </div>
      <button @click="closeAlert" class="ml-4">
        <span class="text-gray-600">&times;</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { inject } from 'vue'

const alert = inject('alert')
const { isAlertOpen, alertDetails, closeAlert } = alert
</script>