<script setup>
import { computed, onMounted, provide, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { PlusIcon  } from '@heroicons/vue/24/solid';
import { ArchiveBoxIcon, CalculatorIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
import { archiveProduct, restockProduct } from '@/Composables/useProductOperations';
import Modal from '@/Components/ui/Modal.vue';
import ProductUpdates from './ProductUpdates.vue';

// TODO: List (Historical) changes to product stocks whenever an order for new stocks and when a new customer order is made
const alert = useAlert();
provide('alert', alert);
const alertPopup = alert.alertPopup;
const products = ref([]);
const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isRestockModalOpen = ref(false);
const restockDetails = ref({ id: null, product_name: null, stocks: null, closest_expiration_date: null })
const isViewModalOpen = ref(false);
const editingProduct = ref(null);
const viewingProduct = ref(null);

const discountPrices = computed(() => {
    if (!viewingProduct.value) return [];
    return ['discounted_price_1', 'discounted_price_2', 'discounted_price_3']
        .map((key) => viewingProduct.value[key])
        .filter((price) => price !== undefined && price !== null);
});

const daysUntilExpiration = computed(() => {
  if (!viewingProduct.value?.closest_expiration_date) return null;
  
  const expirationDate = new Date(viewingProduct.value.closest_expiration_date);
  const today = new Date();
  
  // Reset time portion to compare dates only
  expirationDate.setHours(0, 0, 0, 0);
  today.setHours(0, 0, 0, 0);
  
  const diffTime = expirationDate - today;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  return diffDays;
});

const fetchProducts = async () => {
  try {
    const { data } = await axios.get('/inventory/products/list');
    products.value = data;
  } catch (error) {
    console.error('Failed to fetch products', error);
    alertPopup('Failed to fetch products', 'error');
  }
};

// TODO: Column filtering
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
    sorter: (a, b) => a.stocks - b.stocks,
  },
  {
    title: 'Date Added',
    dataIndex: 'created_at',
    key: 'created_at',
    sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
  },
  {
    title: 'Exp. Date',
    dataIndex: 'closest_expiration_date',
    key: 'closest_expiration_date',
    sorter: (a, b) => new Date(a.closest_expiration_date) - new Date(b.closest_expiration_date),
  },
  {
    title: 'Actions',
    key: 'actions',
  },
];

const onChange = (pagination, filters, sorter, extra) => {
  console.log('changing')
};

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
};

const openEditModal = (product) => {
  editingProduct.value = { ...product };
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false
  setTimeout(() => {
    editingProduct.value = null;
  }, 300);
};

const openRestockModal = (product) => {
  restockDetails.value = {
    id: product.id,
    product_name: product.product_name,
    stocks: product.stocks,
    closest_expiration_date: product.closest_expiration_date,
  }
  isRestockModalOpen.value = true;
};

const closeRestockModal = () => {
  isRestockModalOpen.value = false;
};

const openViewModal = (product) => {
  viewingProduct.value = { ... product };
  isViewModalOpen.value = true;
};

const closeViewModal = () => {
  isViewModalOpen.value = false;
};

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

  <a-table
    :dataSource="products"
    :columns="columns"
    @change="onChange"
    :locale="{ emptyText: 'No orders found. Add a new product to get started.' }"
  >
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'brand'">
        <span v-if="record.brand === null">-</span>
        <span v-else>
          {{ record.brand }}
        </span>
      </template>
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
            @click="openViewModal(record)"
            title="View"
          >
            <EyeIcon class="h-5 w-5" />
          </button>
          <button
            @click="openEditModal(record)"
            title="Edit"
          >
            <PencilSquareIcon class="h-5 w-5" />
          </button>
          <button
            class="text-yellow-500"
            @click="openRestockModal(record)"
            title="Restock"
          >
            <CalculatorIcon class="h-5 w-5" />
          </button>
          <button
            class="text-orange-400 hover:text-green-400"
            @click="archiveProduct(record.id, closeEditModal, alertPopup)"
            title="Archive"
          >
            <ArchiveBoxIcon class="h-5 w-5" />
          </button>
        </div>
      </template>
    </template>
  </a-table>

  <ProductUpdates
    :isAddModalOpen="isAddModalOpen"
    :isEditModalOpen="isEditModalOpen"
    :closeAddModal="closeAddModal"
    :closeEditModal="closeEditModal"
    :editingProduct="editingProduct"
  />

  <Modal
    :isModalOpen="isViewModalOpen"
    :closeModal="closeViewModal"
    :title="'Product Details for ' + viewingProduct?.product_name"
  >
    <div class="mt-5">
      <p v-if="viewingProduct.brand">
        <span class="font-bold">Brand</span>:
        {{ viewingProduct.brand }}
      </p>
      <p>
        <span class="font-bold">Product Type</span>:
        {{ viewingProduct.product_type.type_name }}
      </p>
      <p>
        <span class="font-bold">Retail Price</span>:
        ₱{{ viewingProduct.price.toFixed(2) }}
      </p>
      <p v-if="discountPrices.length">
        <span class="font-bold">Discounted Prices</span>:
        <span v-for="(price, index) in discountPrices" :key="index">
          ₱{{ price.toFixed(2) }} <span v-if="index < discountPrices.length - 1">/</span>
        </span>
      </p>
      <p v-if="viewingProduct.description">
        <span class="font-bold">Description</span>:
        {{ viewingProduct.description }}
      </p>
      <p>
        <span class="font-bold">Available Stocks</span>:
        {{ viewingProduct.stocks }}
      </p>
      <p v-if="viewingProduct.closest_expiration_date">
        <span class="font-bold">Closest Expiration Date</span>:
        {{ new Date(viewingProduct.closest_expiration_date).toLocaleDateString('en-US') }}
        (<template v-if="daysUntilExpiration > 0">{{ daysUntilExpiration }} {{ daysUntilExpiration === 1 ? 'day' : 'days' }} left</template>
        <template v-else><span class="text-red-500">Expired</span>; {{ Math.abs(daysUntilExpiration) }} {{ Math.abs(daysUntilExpiration) === 1 ? 'day has' : 'days have' }} past</template>)
      </p>
      <div class="flex justify-center">
        <button
          @click="closeViewModal"
          class="border px-5 py-3 text-white bg-blue-500 border-blue-400 rounded-md"
        >
          OK
        </button>
      </div>
    </div>
  </Modal>

  <Modal
    :isModalOpen="isRestockModalOpen"
    :closeModal="closeRestockModal"
    :title="'Restock ' + restockDetails?.product_name"
  >
    <div class="mt-5">
      <div>
        <label for="stocks" class="block text-sm font-medium text-gray-700">Available Stock</label>
        <input
          id="stocks"
          v-model="restockDetails.stocks"
          type="number"
          class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        />
      </div>

      <div>
        <label for="closest_expiration_date" class="block text-sm font-medium text-gray-700">Batch (Closest) Expiration Date (if applicable)</label>
        <input
          id="closest_expiration_date"
          v-model="restockDetails.closest_expiration_date"
          type="date"
          class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        />
      </div>

      <div class="mt-4 flex justify-end space-x-2">
        <button
          type="button"
          class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
          @click="restockProduct(restockDetails, closeRestockModal, alertPopup)"
        >
          Save
        </button>
        <button
          type="button"
          class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
          @click="closeRestockModal"
        >
          Cancel
        </button>
      </div>
    </div>
  </Modal>
</template>
