<template>
  <div class="home-container">
    <h1 class="text-3xl font-bold text-center mb-8">{{ $t('signin.title') }}</h1>
    <div class="button-group">
      <router-link to="/signin" class="btn-primary">
        {{ $t('signin.title') }}
      </router-link>
      <router-link to="/queue" class="btn-secondary">
        {{ $t('queue.checkStatus') }}
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { useDeviceStore } from '@/stores/device'

const deviceStore = useDeviceStore()
</script>

<style scoped>
.home-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

.button-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
  max-width: 400px;
}

.btn-primary,
.btn-secondary {
  padding: 1rem 2rem;
  border-radius: 8px;
  text-align: center;
  text-decoration: none;
  font-size: 1.1rem;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
  background: white;
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-secondary:hover {
  background: #667eea;
  color: white;
}
</style>

