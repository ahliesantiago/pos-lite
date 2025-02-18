<script setup>
import { onMounted, provide, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { PlusIcon  } from '@heroicons/vue/24/solid';
import { ArchiveBoxIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import Alert from '@/Components/common/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
import { fetchProductTypes } from '@/Composables/useProductOperations';
import TypeModal from '@/Components/common/TypeModal.vue';

const alert = useAlert();
provide('alert', alert);
const alertPopup = alert.alertPopup;
const categories = ref([]);
const errors = ref({});

const form = useForm({
  type_name: '',
  parent_type_id: null,
  description: '',
});

const isAddModalOpen = ref(false);

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
};

const addNewCategory = () => {
  errors.value = {}; // Reset previous errors, if any
  form.post('/inventory/types', {
    preserveScroll: true,
    onSuccess: () => {
      alertPopup('Category added successfully', 'success');
      closeAddModal();
      form.reset();
      fetchProductTypes();
    },
    onError: (error) => {
      alertPopup('Failed to add category, please try again later.', 'error');
      console.error('Failed to add category', error);
    }
  });
};

const handleView = (category) => {
  console.log('Viewing category:', category);
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
  <Alert />

  <h2 class="text-2xl font-semibold">List of Product Categories</h2>

  <div class="py-5 flex justify-around items-center">
    <button
      @click="openAddModal"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Category
    </button>
    <Link
      :href="route('types.bulk-add')"
      as="button"
      class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <PlusIcon class="h-5 w-5 inline-block" /> Add Multiple Categories
    </Link>
  </div>

  <div v-if="categories.length === 0" class="text-gray-500 text-center p-4">
    No categories found. Add a new category to get started.
  </div>

  <!-- TO DO: Make list into a sortable, filterable, and paginated table -->
  
  <ul v-if="categories.length > 0" class="space-y-2">
    <div
      v-for="category in categories"
      :key="category.id"
      class="grid grid-cols-7 p-2 hover:bg-gray-50 rounded-lg cursor-pointer"
    >
      <p class="col-span-4">{{ category.type_name }}</p>
      <div class="col-span-1 flex justify-center items-center">
        <button @click="handleView(category)" class="p-2 rounded hover:bg-gray-100 focus:outline-none">
          <EyeIcon class="h-5 w-5" />
        </button>
      </div>
      <div class="col-span-1 flex justify-center items-center">
        <ArchiveBoxIcon class="h-5 w-5" />
      </div>
      <div class="col-span-1 flex justify-center items-center">
        <PencilSquareIcon class="h-5 w-5" />
      </div>
    </div>
  </ul>

  <TypeModal
    :isModalOpen="isAddModalOpen"
    :closeModal="closeAddModal"
    :action="'adding'"
    :positiveAction="addNewCategory"
    :negativeAction="form.reset"
    :type="form"
    :categories="categories"
  />
</template>
