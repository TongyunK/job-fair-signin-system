<template>
  <div class="queue-container">
    <h1 class="text-2xl font-bold mb-6">{{ $t('queue.title') }}</h1>
    
    <div v-if="queueStore.currentNumber" class="queue-info">
      <div class="number-display">
        <h2>{{ $t('queue.yourNumber') }}</h2>
        <div class="number">{{ queueStore.currentNumber }}</div>
      </div>
      
      <div class="status-info">
        <div class="info-item">
          <span class="label">{{ $t('queue.position') }}:</span>
          <span class="value">{{ queueStore.queuePosition || '--' }}</span>
        </div>
        <div class="info-item">
          <span class="label">{{ $t('queue.estimatedWait') }}:</span>
          <span class="value">{{ queueStore.estimatedWaitTime || '--' }} 分钟</span>
        </div>
      </div>
      
      <button @click="checkStatus" class="check-btn" :disabled="queueStore.loading">
        {{ queueStore.loading ? $t('common.loading') : $t('queue.checkStatus') }}
      </button>
    </div>
    
    <div v-else class="no-number">
      <p>您还没有排队号，请先进行签到</p>
      <router-link to="/signin" class="btn-primary">
        {{ $t('signin.title') }}
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useQueueStore } from '@/stores/queue'

const route = useRoute()
const queueStore = useQueueStore()

const checkStatus = async () => {
  if (queueStore.currentNumber) {
    await queueStore.checkQueueStatus(queueStore.currentNumber)
  }
}

onMounted(() => {
  const number = route.query.number
  if (number) {
    queueStore.currentNumber = number
    checkStatus()
  }
})
</script>

<style scoped>
.queue-container {
  min-height: 100vh;
  padding: 2rem;
  max-width: 600px;
  margin: 0 auto;
}

.queue-info {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.number-display {
  text-align: center;
  margin-bottom: 2rem;
}

.number-display h2 {
  font-size: 1.2rem;
  color: #666;
  margin-bottom: 1rem;
}

.number {
  font-size: 4rem;
  font-weight: bold;
  color: #667eea;
  line-height: 1;
}

.status-info {
  margin-bottom: 2rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  padding: 1rem 0;
  border-bottom: 1px solid #eee;
}

.info-item:last-child {
  border-bottom: none;
}

.label {
  color: #666;
}

.value {
  font-weight: 600;
  color: #333;
}

.check-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.check-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.check-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.no-number {
  text-align: center;
  padding: 3rem 1rem;
}

.no-number p {
  margin-bottom: 2rem;
  color: #666;
  font-size: 1.1rem;
}

.btn-primary {
  display: inline-block;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
}
</style>

