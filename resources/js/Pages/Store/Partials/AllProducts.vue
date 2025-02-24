<script setup>
import { onMounted, provide, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { PlusIcon  } from '@heroicons/vue/24/solid';
import { ArchiveBoxIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
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

const columns = [
  {
    title: 'Name',
    dataIndex: 'product_name',
    key: 'product_name',
    sorter: {
      compare: (a, b) => a.product_name.localeCompare(b.product_name),
      multiple: 3,
    },
  },
  {
    title: 'Brand',
    dataIndex: 'brand',
    key: 'brand',
    sorter: (a, b) => a.brand.localeCompare(b.brand),
  },
  {
    title: 'Category',
    key: 'category',
    sorter: {
      compare: (a, b) => a.category.localeCompare(b.category),
      multiple: 2,
    },
  },
  {
    title: 'Price',
    dataIndex: 'price',
    key: 'price',
    sorter: {
      compare: (a, b) => a.price - b.price,
      multiple: 1,
    },
  },
  {
    title: 'Stocks',
    dataIndex: 'stocks',
    key: 'stocks',
    sorter: (a, b) => a.price - b.price,
  },
  {
    title: 'Date Added',
    dataIndex: 'created_at',
    key: 'created_at',
  },
  {
    title: 'Exp. Date',
    dataIndex: 'closest_expiration_date',
    key: 'closest_expiration_date',
  },
  {
    title: 'Actions',
    key: 'actions',
  },
];

const onChange = (pagination, filters, sorter, extra) => {
  console.log('changing')
}

onMounted(async () => {
  fetchProducts();
});
</script>

<template>
  <Alert />

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

  <a-table :dataSource="products" :columns="columns" @change="onChange">
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'category'">
        {{ record.product_type.type_name }}
      </template>
      <template v-if="column.key === 'price'">
        ₱{{ record.price.toFixed(2) }}
      </template>
      <template v-if="column.key === 'created_at'">
        {{ new Date(record.created_at).toLocaleDateString('en-US') }}
      </template>
      <template v-if="column.key === 'closest_expiration_date'">
        <span v-if="record.closest_expiration_date === null">-</span>
        <span v-else>
          {{ new Date(record.closest_expiration_date).toLocaleDateString('en-US') }}
        </span>
      </template>
      <template v-if="column.key === 'actions'">
        <div class="flex justify-around">
          <button
            title="View"
          >
            <EyeIcon class="h-6 w-6" />
          </button>
          <button
            title="Edit"
          >
            <PencilSquareIcon class="h-5 w-5" />
          </button>
          <button
            class="text-orange-400 hover:text-green-400" title="Mark as Paid"
          >
            <ArchiveBoxIcon class="h-5 w-5" />
          </button>
        </div>
      </template>
    </template>
  </a-table>
</template>
