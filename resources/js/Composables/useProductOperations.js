import axios from "axios";

export const fetchProductTypes = async () => {
  try {
    const { data } = await axios.get('/inventory/types/list');
    return data;
  } catch (error) {
    console.error('Failed to fetch product types', error);
    throw error;
  }
};