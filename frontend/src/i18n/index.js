import { createI18n } from 'vue-i18n'
import zhHK from './locales/zh-HK.json'
import enUS from './locales/en-US.json'

const messages = {
  'zh-HK': zhHK,
  'en-US': enUS,
}

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('locale') || 'zh-HK',
  fallbackLocale: 'zh-HK',
  messages,
})

export default i18n

