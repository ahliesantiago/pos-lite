<script setup>
import { onMounted, provide, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { PlusIcon  } from '@heroicons/vue/24/solid';
import { ArchiveBoxIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
// TODO: List (Historical) changes to product stocks whenever an order for new stocks and when a new customer order is made
const alert = useAlert();
provide('alert', alert);
const alertPopup = alert.alertPopup;
const products = ref([]);

const fetchProducts = async () => {
  try {
    const { data } = await axios.get('/inventory/products/list');
    products.value = data;
  } catch (error) {
    console.error('Failed to fetch products', error);
    alertPopup('Failed to fetch products', 'error');
  }
};

onMounted(async () => {
  fetchProducts();
});
</script>

<template>
  <Alert />

  <h2 class="text-2xl font-semibold">List of Products</h2>

  <div class="py-5 flex justify-around items-center">
    <button
      @click="openAddModal"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Product
    </button>
    <Link
      :href="route('products.bulk-add')"
      as="button"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Multiple Products
    </Link>
  </div>

  <div v-if="products.length === 0" class="text-gray-500 text-center p-4">
    No products found. Add a new product to get started.
  </div>

  <DataTable
    v-if="products.length > 0"
    :value="products"
    sortMode="multiple"
    scrollable
    removableSort
  >
    <Column
      field="product_name"
      header="Name"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style="width: 30%;"
    />
    <Column
      field="brand"
      header="Brand"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style=""
    />
    <Column
      field="product_type.type_name"
      header="Category"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style=""
    />
    <Column
      field="price"
      header="Price"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style=""
    >
      <template #body="{ data }">
        ₱{{ (data.price).toFixed(2) }}
      </template>
    </Column>
    <Column
      field="stocks"
      header="Stocks"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style=""
    />
    <Column
      field="created_at"
      header="Date Added"
      headerStyle="text-transform: uppercase; color: black;"
      sortable
      style=""
    >
      <template #body="{ data }">
        {{ new Date(data.created_at).toLocaleDateString('en-US') }}
      </template>
    </Column>
    <Column
      header="Actions"
      headerStyle="text-transform: uppercase; color: black;"
    >
      <template #body="slotProps">
        <div class="flex justify-between">
          <button
            title="View"
          >
            <EyeIcon class="h-6 w-6" />
          </button>
          <button title="Edit">
            <PencilSquareIcon class="h-5 w-5" />
          </button>
          <button class="text-orange-400 hover:text-green-400" title="Mark as Paid">
            <ArchiveBoxIcon class="h-5 w-5" />
          </button>
        </div>
      </template>
    </Column>
  </DataTable>
</template>
