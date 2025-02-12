import { ref } from 'vue'

export const useAlert = () => {
  const isAlertOpen = ref(false)
  const alertDetails = ref({
    message: '',
    type: '',
  })
  const alertTimeout = ref(null)

  const alertPopup = (message, type = 'success', duration = 3000) => {
    alertDetails.value = {
      message,
      type
    }
    isAlertOpen.value = true
    
    if (alertTimeout.value) {
      clearTimeout(alertTimeout.value)
    }
    
    alertTimeout.value = setTimeout(() => {
      closeAlert()
    }, duration)
  }

  const closeAlert = () => {
    isAlertOpen.value = false
    alertDetails.value = {
      message: '',
      type: '',
    }
  }

  return {
    isAlertOpen,
    alertDetails,
    alertPopup,
    closeAlert
  }
} 