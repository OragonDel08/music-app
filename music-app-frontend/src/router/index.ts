import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import AdminAlbum from '@/views/admin/AdminAlbum.vue'
import UserAlbum from '@/views/user/UserAlbum.vue'
import { useAuthStore } from '@/stores/auth'
import AuthLayout from '@/components/layouts/AuthLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/admin',
      component: AuthLayout,
      meta: { requiresAuth: true, role: 'admin' },
      children: [
        { path: '', name: 'AdminAlbum', component: AdminAlbum },
      ]
    },
    {
      path: '/user',
      component: AuthLayout,
      meta: { requiresAuth: true, role: 'user' },
      children: [
        { path: '', name: 'UserAlbum', component: UserAlbum },
      ]
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  const isAuthenticated = !!authStore.user;
  const userRole = authStore.user?.role;

  // Redirect to proper dashboard if already logged in
  if ((to.path === '/login' || to.path === '/register') && isAuthenticated) {
    if (userRole === 'admin') {
      return next('/admin')
    } else if (userRole === 'user') {
      return next('/user')
    }
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next('/login')
  }

  // Check if user has required role
  if (to.meta.requiresAuth && to.meta.role && userRole !== to.meta.role) {
    return next('/login') // or show a 403 page
  }

  next()
})

export default router
