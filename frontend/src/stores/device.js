import { defineStore } from 'pinia'

export const useDeviceStore = defineStore('device', {
  state: () => ({
    isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
  }),
  getters: {
    isDesktop: (state) => !state.isMobile,
  },
})

