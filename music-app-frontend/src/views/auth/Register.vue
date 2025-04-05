<script setup lang="ts">
  import { axiosApi } from '@/lib/axios';
  import router from '@/router';
import { useAuthStore } from '@/stores/auth';
  import type { RegisterForm } from '@/types';
  import { ref } from 'vue';

  const authStore = useAuthStore()
  const data = ref<RegisterForm>({
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
  })

  const errors = ref({
    name: [],
    email: [],
    password: [],
  })

  const isLoading = ref<boolean>(false)

  const submit = async () => {
    isLoading.value = true
    try {
      await axiosApi.get('/sanctum/csrf-cookie')
      await axiosApi.post('/register', data.value)
      const res = await axiosApi.get('/api/user')
      if(res) {
        authStore.user = res.data
        if (res.data.role === 'admin') {
          router.push('/admin')
        } else {
          router.push('/user')
        }
      }
    } catch (err: any) {
      console.error('Error login:', err)
      if(err.response && err.response.data.message){
        errors.value = err.response.data.errors
      }
    }
    isLoading.value = false
  }
</script>

<template>
  <div class="login-container">
    <div class="login-card">
      <h2>Create new account</h2>

      <form @submit.prevent="submit">
        <div class="form-group">
          <label for="name">Name</label>
          <input v-model="data.name" type="text" id="name"
            :class="`input ${errors.email && errors.email.length ? 'error' : ''}`">
          <span v-if="errors.name && errors.name.length" class="error-message">{{ errors.name[0] }}</span>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="data.email" type="email" id="email" class="input">
          <span v-if="errors.email && errors.email.length" class="error-message">{{ errors.email[0] }}</span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="data.password" type="password" id="password" class="input">
          <span v-if="errors.password && errors.password.length" class="error-message">{{ errors.password[0] }}</span>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmed Password</label>
          <input v-model="data.password_confirmation" type="password" id="password_confirmation" class="input">
        </div>

        <button type="submit" class="login-btn btn" :class="{ loading: isLoading }" :disabled="isLoading">
          <span class="btn-text">Sign Up</span>
        </button>
      </form>

      <p class="text-muted">
        Already have an account? <RouterLink :to="{name: 'Login'}">Login</RouterLink>
      </p>
    </div>
  </div>
</template>