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

const columns = [
  {
    title: 'Category',
    dataIndex: 'type_name',
    key: 'type_name',
    sorter: (a, b) => a.type_name.localeCompare(b.type_name),
  },
  {
    title: '# of Products',
    dataIndex: 'products_count',
    key: 'products_count',
    sorter: (a, b) => a.products_count - b.products_count,
  },
  {
    title: 'Parent Category',
    key: 'parent',
    sorter: (a, b) => a.parent.localeCompare(b.parent),
  },
  {
    title: 'Actions',
    key: 'actions',
  },
];

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

  <a-table
    :dataSource="categories"
    :columns="columns"
    @change="onChange"
    :locale="{ emptyText: 'No categories found. Add a new category to get started.' }"
  >
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'parent'">
        <span v-if="record.parent_type === null" class="text-gray-400">N/A</span>
        <span v-else>
          {{ record.parent_type.type_name }}
        </span>
      </template>
      <template v-if="column.key === 'actions'">
        <div class="flex justify-around">
          <button @click="handleView(category)" class="p-2 rounded hover:bg-gray-100 focus:outline-none">
            <EyeIcon class="h-5 w-5" />
          </button>
          <button>
            <PencilSquareIcon class="h-5 w-5" />
          </button>
          <button
            class="text-orange-400 hover:text-green-400" title="Archive"
          >
            <ArchiveBoxIcon class="h-5 w-5" />
          </button>
        </div>
      </template>
    </template>
  </a-table>

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
