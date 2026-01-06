<template>
  <div class="home-container">
    <!-- 语言切换按钮 -->
    <div class="language-switcher">
      <!-- 移动端使用 Vant -->
      <van-action-sheet
        v-if="deviceStore.isMobile"
        v-model:show="showLanguageMenu"
        :actions="languageActions"
        cancel-text="取消"
        @select="onLanguageSelect"
        @cancel="showLanguageMenu = false"
      />
      <van-button
        v-if="deviceStore.isMobile"
        round
        size="small"
        class="language-btn-mobile"
        @click="showLanguageMenu = true"
      >
        <template #icon>
          <Icon icon="mdi:web" class="language-icon" />
        </template>
        {{ currentLanguage === 'zh-HK' ? '中文' : 'EN' }}
      </van-button>

      <!-- PC端使用 Element Plus -->
      <el-dropdown
        v-else
        trigger="click"
        @command="handleLanguageChange"
        placement="bottom-end"
      >
        <el-button
          round
          class="language-btn-desktop"
        >
          <template #icon>
            <Icon icon="mdi:web" class="language-icon-desktop" />
          </template>
          {{ currentLanguage === 'zh-HK' ? '中文' : 'EN' }}
        </el-button>
        <template #dropdown>
          <el-dropdown-menu>
            <el-dropdown-item
              command="zh-HK"
              :class="{ 'is-active': currentLanguage === 'zh-HK' }"
            >
              <el-icon><Check v-if="currentLanguage === 'zh-HK'" /></el-icon>
              中文
            </el-dropdown-item>
            <el-dropdown-item
              command="en-US"
              :class="{ 'is-active': currentLanguage === 'en-US' }"
            >
              <el-icon><Check v-if="currentLanguage === 'en-US'" /></el-icon>
              English
            </el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>

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
import { ref, watch } from 'vue'
import { useDeviceStore } from '@/stores/device'
import { useI18n } from 'vue-i18n'
import { Check } from '@element-plus/icons-vue'
import { Icon } from '@iconify/vue'

const deviceStore = useDeviceStore()
const { locale } = useI18n()

// 语言切换相关
const currentLanguage = ref(locale.value)
const showLanguageMenu = ref(false)

// 移动端语言选项
const languageActions = [
  { name: '中文', value: 'zh-HK' },
  { name: 'English', value: 'en-US' },
]

// 切换语言
const handleLanguageChange = (lang) => {
  locale.value = lang
  currentLanguage.value = lang
  localStorage.setItem('locale', lang)
}

// 移动端语言选择
const onLanguageSelect = (action) => {
  if (action && action.value) {
    handleLanguageChange(action.value)
  }
  showLanguageMenu.value = false
}

// 监听语言变化
watch(() => locale.value, (newVal) => {
  currentLanguage.value = newVal
})
</script>

<style scoped>
.home-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 1rem 2rem 2rem;
  position: relative;
}

/* 语言切换按钮 */
.language-switcher {
  position: absolute;
  top: 1rem;
  right: 1rem;
  z-index: 10;
}

.language-btn-mobile {
  background: #f5f5f5;
  border: 1px solid #e0e0e0;
  color: #667eea;
  font-weight: 500;
  padding: 0.375rem 0.625rem;
}

.language-btn-mobile:active {
  background: #eeeeee;
}

/* 确保 Vant 按钮内容居中 */
:deep(.language-btn-mobile) {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

:deep(.language-btn-mobile .van-button__content) {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  width: 100%;
  line-height: 1;
  padding: 0;
}

:deep(.language-btn-mobile .van-icon) {
  margin-right: 0;
  flex-shrink: 0;
  display: flex !important;
  align-items: center;
  visibility: visible !important;
  opacity: 1 !important;
  color: inherit;
  font-size: 16px;
}

.language-icon {
  font-size: 16px;
  color: inherit;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-right: 0;
  flex-shrink: 0;
}

.language-btn-desktop {
  background: #f5f5f5 !important;
  border: 1px solid #e0e0e0 !important;
  color: #667eea !important;
  font-weight: 500;
  transition: all 0.3s;
  padding: 0.5rem 0.75rem !important;
  min-width: auto;
  border-radius: 9999px !important;
}

.language-btn-desktop:hover {
  background: #eeeeee !important;
  border-color: #667eea !important;
}

/* 确保 Element Plus 按钮内容居中 */
:deep(.language-btn-desktop) {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  padding: 0.5rem 0.75rem !important;
  background: #f5f5f5 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 9999px !important;
}

:deep(.language-btn-desktop .el-button__content) {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 0;
  line-height: 1;
  padding: 0 !important;
}

.language-icon-desktop {
  font-size: 16px;
  color: inherit;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-right: 0;
}

/* Element Plus 下拉菜单样式 */
:deep(.el-dropdown-menu) {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  border: none;
  padding: 0.5rem 0;
}

:deep(.el-dropdown-menu__item) {
  padding: 0.75rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 14px;
}

:deep(.el-dropdown-menu__item.is-active) {
  background: rgba(102, 126, 234, 0.1);
  color: #667eea;
  font-weight: 500;
}

:deep(.el-dropdown-menu__item:hover) {
  background: rgba(102, 126, 234, 0.05);
}

/* Vant ActionSheet 样式 */
:deep(.van-action-sheet__item) {
  font-size: 16px;
}

:deep(.van-action-sheet__item--active) {
  color: #667eea;
  font-weight: 500;
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

/* 移动端响应式设计 */
@media (max-width: 768px) {
  .home-container {
    padding: 0.5rem 1rem 2rem;
    justify-content: center;
    padding-top: 0;
    padding-bottom: 0;
  }

  .home-container > h1 {
    margin-top: -15vh;
  }

  .button-group {
    max-width: 100%;
  }
}
</style>

