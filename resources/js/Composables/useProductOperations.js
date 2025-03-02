import axios from "axios";
import { router } from '@inertiajs/vue3';

export const fetchProductTypes = async () => {
  try {
    const { data } = await axios.get('/inventory/types/list');
    return data;
  } catch (error) {
    console.error('Failed to fetch product types', error);
    throw error;
  }
};

export const archiveProduct = (id, closeEditModal, alertPopup, form = null) => {
  if (confirm('Are you sure you want to archive this product?')) {
    router.put(`/inventory/products/${id}/archive`, {}, {
      preserveScroll: true,
      onSuccess: (response) => {
        router.visit(window.location.href, { 
          only: ['products'],
          preserveScroll: true,
        });

        alertPopup(response.props.message || 'Product archived successfully', 'success');
        closeEditModal();
        if (form) form.reset();
      },
      onError: (error) => {
        alertPopup('Failed to archive product', 'error');
      }
    });
  }
};

export const restockProduct = (details, closeRestockModal, alertPopup,) => {
  router.put(`/inventory/products/${details.id}/restock`, details, {
    preserveScroll: true,
    onSuccess: (response) => {
      router.visit(window.location.href, { 
        only: ['products'],
        preserveScroll: true,
      });

      alertPopup(response.props.message || 'Stocks updated successfully', 'success');
      closeRestockModal();
    },
    onError: (error) => {
      alertPopup('Failed to restock product', 'error');
    }
  });
};
