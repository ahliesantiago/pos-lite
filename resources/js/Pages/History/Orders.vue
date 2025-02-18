<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CreditCardIcon, EyeIcon, PencilIcon } from '@heroicons/vue/24/outline';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
// import ColumnGroup from 'primevue/columngroup';
// import Row from 'primevue/row';
import OrderModal from './Partials/OrderModal.vue';

const props = defineProps({
    orders: Object
});

const isOrderModalOpen = ref(false);
const selectedOrder = ref(null);
const cartItems = ref(null);

const fetchCartDetails = async (cartId) => {
  try {
    const response = await axios.get(`/orders/${cartId}`);
    cartItems.value = response.data;
    isOrderModalOpen.value = true;
    console.log('selectedOrder', selectedOrder.value, 'cartItems', cartItems.value);
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

      <div v-if="orders.length === 0" class="text-gray-500 text-center p-4">
        No orders found. Add a new product to get started.
      </div>

      <DataTable :value="orders" sortMode="multiple" tableStyle="">
        <Column
          field="is_paid"
          header="Paid?"
          headerStyle="text-transform: uppercase; color: black;"
          sortable
          style="width: 20%"
        >
          <template #body="{ data }">
            <span v-if="data.is_paid" class="text-green-500">Paid</span>
            <span v-else class="text-red-500">Unpaid</span>
          </template>
        </Column>
        <Column
          field="created_at"
          header="Purchase Date"
          headerStyle="text-transform: uppercase; color: black;"
          sortable
          style="width: 20%"
        >
          <template #body="{ data }">
            {{ new Date(data.created_at).toLocaleDateString('en-US') }}
          </template>
        </Column>
        <Column
          field="total_price"
          header="Price"
          headerStyle="text-transform: uppercase; color: black;"
          sortable
          style="width: 20%"
        >
          <template #body="{ data }">
            ₱{{ (data.total_price).toFixed(2) }}
          </template>
        </Column>
        <Column
          field="customer_name"
          header="Customer"
          headerStyle="text-transform: uppercase; color: black;"
          sortable
          style="width: 20%"
        >
        </Column>
        <Column
          header="Actions"
          headerStyle="text-transform: uppercase; color: black;"
          style="width: 20%"
        >
          <template #body="slotProps">
            <div class="flex justify-between">
              <button
                title="View Order"
                @click="handleViewOrder(slotProps.data)"
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
        </Column>
      </DataTable>
    </div>
    <OrderModal
      :isModalOpen="isOrderModalOpen"
      :closeModal="closeOrderModal"
      :selectedOrder="selectedOrder"
      :cartItems="cartItems"
    />
  </AuthenticatedLayout>
</template>
