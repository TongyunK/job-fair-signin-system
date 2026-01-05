import { createRouter, createWebHistory } from 'vue-router'
import { useDeviceStore } from '@/stores/device'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/Home.vue'),
  },
  {
    path: '/signin',
    name: 'SignIn',
    component: () => import('@/views/SignIn.vue'),
  },
  {
    path: '/queue',
    name: 'Queue',
    component: () => import('@/views/Queue.vue'),
  },
  {
    path: '/admin',
    name: 'Admin',
    component: () => import('@/views/Admin.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

