import request from './request'

export const queueApi = {
  // 获取排队号
  getNumber(userInfo) {
    return request.post('/queue/get-number', userInfo)
  },
  // 查询排队状态
  checkStatus(number) {
    return request.get(`/queue/status/${number}`)
  },
  // 获取当前排队情况
  getCurrentQueue() {
    return request.get('/queue/current')
  },
}

