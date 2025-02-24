<script setup>
import { inject, onMounted, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { fetchProductTypes } from '@/Composables/useProductOperations';
import ProductModal from '@/Components/common/ProductModal.vue';

const props = defineProps({
  isAddModalOpen: Boolean,
  isEditModalOpen: Boolean,
  closeAddModal: Function,
  closeEditModal: Function,
  editingProduct: Object,
});

const { alertPopup } = inject('alert');
const categories = ref([]);

const form = useForm({
  product_name: '',
  product_type_id: 1,
  brand: '',
  description: '',
  price: null,
  discounted_price_1: null,
  stocks: null,
  closest_expiration_date: ''
});

const addNewProduct = () => {
  form.post('/inventory/products', {
    preserveScroll: true,
    onSuccess: (response) => {
      router.visit(window.location.href, { 
        only: ['products'],
        preserveScroll: true,
      });

      alertPopup(response.props.message || 'Product added successfully', 'success');
      props.closeAddModal();
      form.reset();
    },
    onError: (error) => {
      alertPopup('Failed to add product', 'error');
    }
  });
};

const saveProduct = () => {
  if (editingProduct.value) {
    // implement product edit
  }
  closeEditModal();
};

const archiveProduct = () => {
  if (editingProduct.value) {
    // implement product archiving
  }
  closeEditModal();
};

onMounted(async () => {
  try {
    categories.value = await fetchProductTypes();
  } catch (error) {
    alertPopup('Failed to fetch categories', 'error');
  }
});
</script>

<template>
  <ProductModal
    :isModalOpen="isAddModalOpen"
    :closeModal="closeAddModal"
    :action="'adding'"
    :positiveAction="addNewProduct"
    :negativeAction="form.reset"
    :product="form"
    :categories="categories"
  />

  <ProductModal
    :isModalOpen="isEditModalOpen"
    :closeModal="closeEditModal"
    :action="'editing'"
    :positiveAction="saveProduct"
    :negativeAction="archiveProduct"
    :product="editingProduct"
    :categories="categories"
  />
</template>