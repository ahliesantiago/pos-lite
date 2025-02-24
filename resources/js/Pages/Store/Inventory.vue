<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AllProducts from './Partials/AllProducts.vue';
import Categories from './Partials/Categories.vue';

const activeKey = ref(['2']);
</script>

<template>
  <Head title="Inventory" />
  <AuthenticatedLayout>
    <a-collapse :bordered="false" v-model:activeKey="activeKey" class="pb-5">
      <a-collapse-panel
        key="1"
        header="List of Product Categories"
        headerClass="text-2xl font-semibold"
        class="bg-white !rounded-lg shadow p-2 my-5 mx-2"
        :show-arrow="false"
      >
        <Categories />
      </a-collapse-panel>

      <a-collapse-panel
        key="2"
        header="List of Products"
        headerClass="text-2xl font-semibold"
        class="bg-white !rounded-lg shadow p-2 my-5 mx-2"
        :show-arrow="false"
      >
        <AllProducts />
      </a-collapse-panel>
    </a-collapse>
  </AuthenticatedLayout>
</template>
