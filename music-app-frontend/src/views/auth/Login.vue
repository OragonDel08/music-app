<script setup lang="ts">
  import { axiosApi } from '@/lib/axios';
  import router from '@/router';
  import { useAuthStore } from '@/stores/auth';
  import type { LoginForm } from '@/types';
  import { ref } from 'vue';
  import { RouterLink } from 'vue-router'

  const authStore = useAuthStore()
  const data = ref<LoginForm>({
    email: "",
    password: "",
  })

  const errorMessage = ref(null)
  const isLoading = ref<boolean>(false)

  const submit = async () => {
    isLoading.value = true
    try {
      await axiosApi.get('/sanctum/csrf-cookie')
      await axiosApi.post('/login', data.value)
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
        errorMessage.value = err.response.data.message
      }
    }
    isLoading.value = false
  }
  const closeAlert = () => {
    errorMessage.value = null
  }
</script>

<template>
  <div class="login-container">
    <div class="login-card">
      <h2>Sign in to your account</h2>
      <div v-if="errorMessage" class="alert-error">
        <span class="alert-icon">⚠️</span>
        <span class="alert-text">{{ errorMessage }}</span>
        <button class="alert-close" @click="closeAlert()">&times;</button>
      </div>
      <form @submit.prevent="submit">
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="data.email" type="email" id="email" class="input">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="data.password" type="password" id="password" class="input">
        </div>

        <button type="submit" class="login-btn btn" :class="{ loading: isLoading }" :disabled="isLoading">
          <span class="btn-text">Sign In</span>
        </button>
      </form>

      <p class="text-muted">
        Don't have an account? <RouterLink :to="{name: 'Register'}">Register</RouterLink>
      </p>
    </div>
  </div>
</template>