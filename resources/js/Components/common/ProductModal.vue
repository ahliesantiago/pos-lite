<script setup>
import Modal from '@/Components/ui/Modal.vue';
// TO DO: EDIT PRODUCT
const props = defineProps({
  isModalOpen: Boolean,
  closeModal: Function,
  action: String,
  positiveAction: Function,
  negativeAction: Function,
  product: Object,
  categories: Array,
});

const validatePrice = (e) => {
  let value = parseFloat(e.target.value);
  if (isNaN(value) || value < 0.25) {
    props.product.price = 0.25;
  }
}

const handleDiscountInput = (event, field) => {
  const value = event.target.value.trim();
  if (value === '') {
    props.product[field] = null;
  } else {
    const numValue = parseFloat(value);
    if (!isNaN(numValue) && numValue >= 0) {
      props.product[field] = numValue;
    }
  }
}

const handleDiscountBlur = (field) => {
  if (props.product[field] !== null && !isNaN(props.product[field])) {
    props.product[field] = parseFloat(props.product[field]).toFixed(2);
  }
}
</script>

<template>
  <Modal
    :isModalOpen="isModalOpen"
    :closeModal="closeModal"
    :title="(action === 'editing' ? 'Edit' : 'Add New') + ' Product'"
  >
    <div class="mt-2">
      <div class="space-y-4">
        <div>
          <label for="product_name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            id="product_name"
            v-model="product.product_name"
            type="text"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <span v-if="product?.errors?.product_name" class="text-red-500 text-sm italic">
            {{ product.errors.product_name }}
          </span>
        </div>

        <div>
          <label for="product_type_id" class="block text-sm font-medium text-gray-700">Type or Category</label>
          <select
            id="product_type_id"
            v-model="product.product_type_id"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.type_name }}
            </option>
          </select>
          <span v-if="product?.errors?.product_type_id" class="text-red-500 text-sm italic">
            {{ product.errors.product_type_id }}
          </span>
        </div>

        <div>
          <label for="brand" class="block text-sm font-medium text-gray-700">Brand (optional)</label>
          <input
            id="brand"
            v-model="product.brand"
            type="text"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <span v-if="product?.errors?.brand" class="text-red-500 text-sm italic">
            {{ product.errors.brand }}
          </span>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
          <textarea
            id="description"
            v-model="product.description"
            rows="3"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          ></textarea>
          <span v-if="product?.errors?.description" class="text-red-500 text-sm italic">
            {{ product.errors.description }}
          </span>
        </div>

        <div class="grid grid-cols-4 gap-4">
          <div class="col-span-4 sm:col-span-1">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <div class="mt-1 flex items-center">
              <span class="bg-gray-200 text-gray-600 p-2 rounded-l-md border-t border-b border border-gray-300">
                ₱
              </span>
              <input
                id="price"
                v-model="product.price"
                type="number"
                step="1"
                class="block p-2 w-full rounded-r-md border-l-0 border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                @input="validatePrice"
                @blur="product.price = Math.max(0.25, parseFloat(product.price) || 0).toFixed(2)"
              />
            </div>
            <span v-if="product?.errors?.price" class="text-red-500 text-sm italic">
              {{ product.errors.price }}
            </span>
          </div>
          <div class="col-span-4 sm:col-span-3 flex flex-col">
            <label for="discounted_price_1" class="block text-sm font-medium text-gray-700">Discounted prices (optional):</label>
            <div class="flex gap-3">
              <div>
                <div class="mt-1 flex items-center">
                  <span class="bg-gray-200 text-gray-600 p-2 rounded-l-md border-t border-b border border-gray-300">
                    ₱
                  </span>
                  <input
                    id="discounted_price_1"
                    v-model="product.discounted_price_1"
                    type="text"
                    @input="e => handleDiscountInput(e, 'discounted_price_1')"
                    @blur="handleDiscountBlur('discounted_price_1')"
                    class="block p-2 w-full rounded-r-md border-l-0 border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                </div>
                <span v-if="product?.errors?.discounted_price_1" class="text-red-500 text-sm italic">
                  {{ product.errors.discounted_price_1 }}
                </span>
              </div>

              <div>
                <div class="mt-1 flex items-center">
                  <span class="bg-gray-200 text-gray-600 p-2 rounded-l-md border-t border-b border border-gray-300">
                    ₱
                  </span>
                  <input
                    id="discounted_price_2"
                    v-model="product.discounted_price_2"
                    type="text"
                    @input="e => handleDiscountInput(e, 'discounted_price_2')"
                    @blur="handleDiscountBlur('discounted_price_2')"
                    class="block p-2 w-full rounded-r-md border-l-0 border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                </div>
                <span v-if="product?.errors?.discounted_price_2" class="text-red-500 text-sm italic">
                  {{ product.errors.discounted_price_2 }}
                </span>
              </div>

              <div>
                <div class="mt-1 flex items-center">
                  <span class="bg-gray-200 text-gray-600 p-2 rounded-l-md border-t border-b border border-gray-300">
                    ₱
                  </span>
                  <input
                    id="discounted_price_3"
                    v-model="product.discounted_price_3"
                    type="text"
                    @input="e => handleDiscountInput(e, 'discounted_price_3')"
                    @blur="handleDiscountBlur('discounted_price_3')"
                    class="block p-2 w-full rounded-r-md border-l-0 border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                </div>
                <span v-if="product?.errors?.discounted_price_3" class="text-red-500 text-sm italic">
                  {{ product.errors.discounted_price_3 }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label for="stocks" class="block text-sm font-medium text-gray-700">Available Stock</label>
          <input
            id="stocks"
            v-model="product.stocks"
            type="number"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <span v-if="product?.errors?.stocks" class="text-red-500 text-sm italic">
            {{ product.errors.stocks }}
          </span>
        </div>

        <div>
          <label for="closest_expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date (if applicable)</label>
          <input
            id="closest_expiration_date"
            v-model="product.closest_expiration_date"
            type="date"
            class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <span v-if="product?.errors?.closest_expiration_date" class="text-red-500 text-sm italic">
            {{ product.errors.closest_expiration_date }}
          </span>
        </div>
      </div>
    </div>

    <div class="mt-4 flex justify-end space-x-2">
      <button
        type="button"
        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
        @click="positiveAction"
      >
      {{ action === 'editing' ? 'Save' : 'Add Product' }}
      </button>
      <button
        v-if="action === 'adding'"
        type="button"
        class="inline-flex justify-center rounded-md border border-blue-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
        @click="negativeAction"
      >
        Reset
      </button>
      <button
        v-if="action === 'editing'"
        type="button"
        class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
        @click="negativeAction"
      >
        Archive
      </button>
      <button
        type="button"
        class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
        @click="closeModal"
      >
        Cancel
      </button>
    </div>
  </Modal>
</template>