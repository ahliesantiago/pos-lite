<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CreditCardIcon, EyeIcon, PencilIcon } from '@heroicons/vue/24/outline';
import OrderModal from './Partials/OrderModal.vue';

const props = defineProps({
    orders: Object
});

const isOrderModalOpen = ref(false);
const selectedOrder = ref(null);
const cartItems = ref(null);

// TODO: Column filtering
const columns = [
  {
    title: 'Paid?',
    dataIndex: 'is_paid',
    key: 'is_paid',
    sorter: {
      compare: (a, b) => a.is_paid.localeCompare(b.is_paid),
      multiple: 2,
    },
  },
  {
    title: 'Purchase Date',
    dataIndex: 'created_at',
    key: 'created_at',
  },
  {
    title: 'Price',
    dataIndex: 'total_price',
    key: 'total_price',
    sorter: {
      compare: (a, b) => a.price - b.price,
      multiple: 1,
    },
  },
  {
    title: 'Customer',
    dataIndex: 'customer_name',
    key: 'customer_name',
    sorter: {
      compare: (a, b) => a?.customer_name?.localeCompare(b?.customer_name),
      multiple: 2,
    },
  },
  {
    title: 'Actions',
    key: 'actions',
  },
]

const onChange = (pagination, filters, sorter, extra) => {
  console.log('changing')
}

const fetchCartDetails = async (cartId) => {
  try {
    const response = await axios.get(`/orders/${cartId}`);
    cartItems.value = response.data;
    isOrderModalOpen.value = true;
  } catch (error) {
    console.error("Failed to fetch cart items", error);
  }
};

const handleViewOrder = (order) => {
  selectedOrder.value = order;
  fetchCartDetails(order.id);
};

const closeOrderModal = () => {
  isOrderModalOpen.value = false;
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="bg-white rounded-lg shadow p-4">
      <h2 class="text-2xl font-semibold">List of Orders</h2>

      <a-table
        :dataSource="orders"
        :columns="columns"
        @change="onChange"
        :locale="{ emptyText: 'No orders found.' }"
      >
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'is_paid'">
            <span v-if="record.is_paid" class="text-green-500">Paid</span>
            <span v-else class="text-red-500">Unpaid</span>
          </template>
          <template v-if="column.key === 'created_at'">
            {{ new Date(record.created_at).toLocaleDateString('en-US') }}
          </template>
          <template v-if="column.key === 'total_price'">
            ₱{{ record.total_price.toFixed(2) }}
          </template>
          <template v-if="column.key === 'actions'">
            <div class="flex justify-between">
              <button
                title="View Order"
                @click="handleViewOrder(record)"
              >
                <EyeIcon class="h-6 w-6" />
              </button>
              <button title="Edit Order">
                <PencilIcon class="h-6 w-6" />
              </button>
              <button class="text-orange-400 hover:text-green-400" title="Mark as Paid">
                <CreditCardIcon class="h-6 w-6" />
              </button>
            </div>
          </template>
        </template>
      </a-table>
    </div>

    <OrderModal
      :isModalOpen="isOrderModalOpen"
      :closeModal="closeOrderModal"
      :selectedOrder="selectedOrder"
      :cartItems="cartItems"
    />
  </AuthenticatedLayout>
</template>
