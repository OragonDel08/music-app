import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { RegisterForm, LoginForm } from '@/types'
import { axiosApi } from '@/lib/axios'
import type { FormKitNode } from '@formkit/core'
import { AxiosError } from 'axios'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<any>(null)
    const clearState = () => {
        user.value = null
    }
    return {
        user,
        clearState
    }
}, {
    persist: {
        storage: sessionStorage,
        pick: ['user']
    }
})