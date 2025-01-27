<script setup>
import Navbar from '@/Layouts/Navbar.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <div class="mx-auto">
      <div class="sticky top-0 z-10">
        <Navbar />
      </div>
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>