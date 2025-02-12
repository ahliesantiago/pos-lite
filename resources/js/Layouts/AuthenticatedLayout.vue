<script setup>
import Navbar from '@/Layouts/Navbar.vue'
import TitleBar from '@/Layouts/TitleBar.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <div class="mx-auto">
      <div class="sticky top-0 z-10">
        <TitleBar />
        <Navbar />
      </div>
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>