import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import i18n from './i18n'
import './style.css'

// 移动端使用 Vant
import Vant from 'vant'
import 'vant/lib/index.css'

// PC端使用 Element Plus
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'

const app = createApp(App)

// 注册 Element Plus 图标
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
  app.component(key, component)
}

app.use(createPinia())
app.use(router)
app.use(i18n)

// 根据设备类型选择UI框架
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
if (isMobile) {
  app.use(Vant)
} else {
  app.use(ElementPlus)
}

app.mount('#app')

