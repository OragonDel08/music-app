<script setup lang="ts">
  import { axiosApi } from '@/lib/axios';
  import router from '@/router';
  import { useAuthStore } from '@/stores/auth';
  import { ref } from 'vue';
  import { RouterView } from 'vue-router';
  import ChevronDownIcon from '@/icons/ChevronDownIcon.vue'

  const authStore = useAuthStore()
  const isSidebarOpen = ref(false)
  const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
  }
  const isLoading = ref<boolean>(false)

  const logout = async () => {
    isLoading.value = true
    try {
      const res = await axiosApi.post('/logout')
      console.log(res)
      authStore.clearState()
      router.push({name: 'Login'})
    } catch (err) {
      console.error('Error fetching albums:', err)
    }
    isLoading.value = false
  }
</script>
<template>
  <div class="admin-layout" :class="{ 'sidebar-open': isSidebarOpen }">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">Music App</div>
      <nav v-if="authStore.user?.role === 'admin'">
        <RouterLink to="/admin" active-class="active">Album</RouterLink>
      </nav>
      <nav v-if="authStore.user?.role === 'user'">
        <RouterLink to="/user" active-class="active">Album</RouterLink>
      </nav>
    </aside>

    <!-- Content Area -->
    <div class="content-wrapper">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-content">
          <button class="menu-btn" @click="toggleSidebar">
            ☰
          </button>
          <div class="greeting">Welcome, <span style="text-transform: capitalize;">{{ authStore.user?.role }}</span></div>
          <div class="user-dropdown">
            <button class="user-btn">
              <span class="user-name">{{ authStore.user?.name }}</span>
              <ChevronDownIcon class="icon" />
            </button>
            <div class="dropdown-menu">
              <button class="logout-btn btn" :class="{loading: isLoading}" :disabled="isLoading" @click="logout">
                <span class="btn-text">Logout</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="main-content">
        <RouterView />
      </main>
    </div>

    <!-- Overlay for mobile when sidebar is open -->
    <div class="overlay" v-if="isSidebarOpen" @click="toggleSidebar"></div>
  </div>
</template>