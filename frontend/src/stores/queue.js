import { defineStore } from 'pinia'
import { queueApi } from '@/api/queue'

export const useQueueStore = defineStore('queue', {
  state: () => ({
    currentNumber: null,
    queuePosition: null,
    estimatedWaitTime: null,
    loading: false,
  }),
  actions: {
    async getQueueNumber(userInfo) {
      this.loading = true
      try {
        const response = await queueApi.getNumber(userInfo)
        this.currentNumber = response.data.number
        this.queuePosition = response.data.position
        this.estimatedWaitTime = response.data.estimatedWaitTime
        return response.data
      } catch (error) {
        console.error('获取排队号失败:', error)
        throw error
      } finally {
        this.loading = false
      }
    },
    async checkQueueStatus(number) {
      try {
        const response = await queueApi.checkStatus(number)
        this.queuePosition = response.data.position
        this.estimatedWaitTime = response.data.estimatedWaitTime
        return response.data
      } catch (error) {
        console.error('查询排队状态失败:', error)
        throw error
      }
    },
    reset() {
      this.currentNumber = null
      this.queuePosition = null
      this.estimatedWaitTime = null
    },
  },
})

