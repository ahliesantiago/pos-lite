<script setup>
import { Link } from '@inertiajs/vue3';
import { PlusIcon  } from '@heroicons/vue/24/solid';

</script>

<template>
  <div>
    <div>
      Will contain the complete list of products and options to add, edit, and archive products
      (with options to do all the operations individually or multiple at a time).
    </div>
    <Link
      :href="route('products.bulk-add')"
      as="button"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> <span class="hidden xl:inline-block">Add Products</span>
    </Link>
  </div>
</template>
