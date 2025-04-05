import router from '@/router';
import axios from 'axios';
const axiosApi = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  withCredentials: true,
  withXSRFToken: true
});

axiosApi.interceptors.response.use(
  response => response,
  async error => {
  const { useAuthStore } = await import('@/stores/auth')
    if (error.response && (error.response.status === 401 || error.response.status === 500)) {
      const authStore = useAuthStore()
      await authStore.clearState()
      if (router.currentRoute.value.name !== 'Login') {
        router.push({ name: 'Login' })
      }
    }
    return Promise.reject(error)
  }
)

export { axiosApi } 