<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    orders: Object
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="bg-white rounded-lg shadow p-4">
      <h2 class="text-2xl font-semibold">List of Orders</h2>

      <div v-if="orders.length === 0" class="text-gray-500 text-center p-4">
        No orders found. Add a new product to get started.
      </div>

      <table class="w-full text-center">
        <thead class="uppercase bg-gray-50">
          <tr>
            <th scope="col" class="px-4 py-2">Date Ordered</th>
            <th scope="col" class="px-4 py-2 text-center">Price</th>
            <th scope="col" class="px-4 py-2">Customer</th>
            <th scope="col" class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order of orders">
            <td class="px-4 py-2">{{ new Date(order.created_at).toLocaleDateString('en-US') }}</td>
            <td class="px-4 py-2">₱{{ order.total_price }}</td>
            <td class="px-4 py-2">{{ order.customer_name }}</td>
            <td>
              <!-- View -->
              <!-- Edit -->
              <!-- Mark Paid -->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>
