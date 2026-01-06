<template>
  <div class="signin-container">
    <!-- 背景装饰 -->
    <div class="background-decoration">
      <div class="decoration-circle circle-1"></div>
      <div class="decoration-circle circle-2"></div>
      <div class="decoration-circle circle-3"></div>
    </div>

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

    <!-- 主要内容区域 -->
    <div class="signin-content">
      <!-- 头部标题区域 -->
      <div class="signin-header">
        <div class="header-icon">
          <el-icon v-if="!deviceStore.isMobile" :size="48" class="icon">
            <User />
          </el-icon>
          <van-icon v-else name="user-circle-o" size="48" class="icon" />
        </div>
        <h1 class="signin-title">{{ $t('signin.title') }}</h1>
        <p class="signin-subtitle">{{ $t('signin.subtitle') }}</p>
      </div>

      <!-- 移动端使用 Vant -->
      <div v-if="deviceStore.isMobile" class="mobile-form-wrapper">
        <van-form @submit="onSubmit" class="mobile-form">
          <van-cell-group inset class="form-group">
            <van-field
              v-model="form.name"
              name="name"
              :label="$t('signin.name')"
              :placeholder="$t('signin.namePlaceholder')"
              :rules="[{ required: true, message: '請輸入姓名' }]"
              left-icon="contact"
              clearable
            />
            <van-field
              v-model="form.phone"
              name="phone"
              type="tel"
              :label="$t('signin.phone')"
              :placeholder="$t('signin.phonePlaceholder')"
              :rules="[
                { required: true, message: '請輸入手機號碼' },
                { pattern: /^1[3-9]\d{9}$/, message: '請輸入正確的手機號碼' }
              ]"
              left-icon="phone"
              clearable
            />
            <van-field
              v-model="form.email"
              name="email"
              type="email"
              :label="$t('signin.email')"
              :placeholder="$t('signin.emailPlaceholder')"
              clearable
            >
              <template #left-icon>
                <Icon icon="mdi:email" class="custom-icon" />
              </template>
            </van-field>
            <van-field
              v-model="form.position"
              name="position"
              :label="$t('signin.position')"
              :placeholder="$t('signin.positionPlaceholder')"
              left-icon="manager"
              clearable
            />
          </van-cell-group>
          <div class="submit-btn-wrapper">
            <van-button 
              round 
              block 
              type="primary" 
              native-type="submit" 
              :loading="loading"
              class="submit-btn"
            >
              {{ loading ? $t('signin.submitting') : $t('signin.submit') }}
            </van-button>
          </div>
        </van-form>
      </div>

      <!-- PC端使用 Element Plus -->
      <div v-else class="desktop-form-wrapper">
        <el-card class="form-card" shadow="hover">
          <el-form 
            :model="form" 
            :rules="rules" 
            ref="formRef" 
            label-width="120px" 
            class="desktop-form"
            label-position="left"
          >
            <el-form-item :label="$t('signin.name')" prop="name">
              <el-input 
                v-model="form.name" 
                :placeholder="$t('signin.namePlaceholder')"
                size="large"
                clearable
              >
                <template #prefix>
                  <el-icon><User /></el-icon>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item :label="$t('signin.phone')" prop="phone">
              <el-input 
                v-model="form.phone" 
                :placeholder="$t('signin.phonePlaceholder')"
                size="large"
                clearable
                maxlength="11"
              >
                <template #prefix>
                  <el-icon><Phone /></el-icon>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item :label="$t('signin.email')" prop="email">
              <el-input 
                v-model="form.email" 
                type="email" 
                :placeholder="$t('signin.emailPlaceholder')"
                size="large"
                clearable
              >
                <template #prefix>
                  <el-icon><Message /></el-icon>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item :label="$t('signin.position')" prop="position">
              <el-input 
                v-model="form.position" 
                :placeholder="$t('signin.positionPlaceholder')"
                size="large"
                clearable
              >
                <template #prefix>
                  <el-icon><Briefcase /></el-icon>
                </template>
              </el-input>
            </el-form-item>
            <el-form-item class="submit-form-item">
              <el-button 
                type="primary" 
                @click="onSubmit" 
                :loading="loading"
                size="large"
                class="submit-btn-desktop"
              >
                <el-icon v-if="!loading"><Check /></el-icon>
                {{ loading ? $t('signin.submitting') : $t('signin.submit') }}
              </el-button>
            </el-form-item>
          </el-form>
        </el-card>
      </div>

      <!-- 底部提示 -->
      <div class="signin-footer">
        <p class="footer-text">
          <el-icon v-if="!deviceStore.isMobile"><InfoFilled /></el-icon>
          <van-icon v-else name="info-o" />
          {{ $t('signin.footerText') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useDeviceStore } from '@/stores/device'
import { useQueueStore } from '@/stores/queue'
import { ElMessage } from 'element-plus'
import { showToast } from 'vant'
import { Icon } from '@iconify/vue'
import { 
  User, 
  Phone, 
  Message, 
  Briefcase, 
  Check,
  InfoFilled
} from '@element-plus/icons-vue'
import { useI18n } from 'vue-i18n'

const router = useRouter()
const deviceStore = useDeviceStore()
const queueStore = useQueueStore()
const { locale, t } = useI18n()

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

const form = reactive({
  name: '',
  phone: '',
  email: '',
  position: '',
})

const loading = ref(false)
const formRef = ref(null)

const rules = computed(() => ({
  name: [{ required: true, message: t('signin.nameRequired'), trigger: 'blur' }],
  phone: [
    { required: true, message: t('signin.phoneRequired'), trigger: 'blur' },
    { pattern: /^1[3-9]\d{9}$/, message: t('signin.phoneValid'), trigger: 'blur' },
  ],
  email: [{ type: 'email', message: t('signin.emailValid'), trigger: 'blur' }],
}))

const onSubmit = async () => {
  if (!deviceStore.isMobile && formRef.value) {
    try {
      await formRef.value.validate()
    } catch (error) {
      return false
    }
  }

  // 移动端表单验证
  if (deviceStore.isMobile) {
    if (!form.name.trim()) {
      showToast.fail(t('signin.nameRequired'))
      return
    }
    if (!form.phone.trim()) {
      showToast.fail(t('signin.phoneRequired'))
      return
    }
    if (form.phone && !/^1[3-9]\d{9}$/.test(form.phone)) {
      showToast.fail(t('signin.phoneValid'))
      return
    }
  }

  loading.value = true
  try {
    const result = await queueStore.getQueueNumber(form)
    if (deviceStore.isMobile) {
      showToast.success(t('signin.success'))
    } else {
      ElMessage.success({
        message: t('signin.success'),
        type: 'success',
        duration: 2000,
      })
    }
    // 延迟跳转，让用户看到成功提示
    setTimeout(() => {
      router.push({
        name: 'Queue',
        query: { number: result.number },
      })
    }, 500)
  } catch (error) {
    const errorMsg = error?.response?.data?.message || error?.message || t('signin.error')
    if (deviceStore.isMobile) {
      showToast.fail(errorMsg)
    } else {
      ElMessage.error({
        message: errorMsg,
        duration: 3000,
      })
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.signin-container {
  min-height: 100vh;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  overflow: hidden;
}

/* 背景装饰 */
.background-decoration {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 0;
}

.decoration-circle {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  animation: float 6s ease-in-out infinite;
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: -100px;
  right: -100px;
  animation-delay: 0s;
}

.circle-2 {
  width: 200px;
  height: 200px;
  bottom: -50px;
  left: -50px;
  animation-delay: 2s;
}

.circle-3 {
  width: 150px;
  height: 150px;
  top: 50%;
  left: 10%;
  animation-delay: 4s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) scale(1);
    opacity: 0.3;
  }
  50% {
    transform: translateY(-20px) scale(1.1);
    opacity: 0.5;
  }
}

/* 语言切换按钮 */
.language-switcher {
  position: absolute;
  top: 1rem;
  right: 1rem;
  z-index: 10;
}

.language-btn-mobile {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  backdrop-filter: blur(10px);
  font-weight: 500;
  padding: 0.375rem 0.625rem;
}

.language-btn-mobile:active {
  background: rgba(255, 255, 255, 0.3);
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
  background: rgba(255, 255, 255, 0.2) !important;
  border: 1px solid rgba(255, 255, 255, 0.3) !important;
  color: white !important;
  backdrop-filter: blur(10px);
  font-weight: 500;
  transition: all 0.3s;
  padding: 0.5rem 0.75rem !important;
  min-width: auto;
  border-radius: 9999px !important;
}

.language-btn-desktop:hover {
  background: rgba(255, 255, 255, 0.3) !important;
  border-color: rgba(255, 255, 255, 0.5) !important;
}

/* 确保 Element Plus 按钮内容居中 */
:deep(.language-btn-desktop) {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  padding: 0.5rem 0.75rem !important;
  background: rgba(255, 255, 255, 0.2) !important;
  border: 1px solid rgba(255, 255, 255, 0.3) !important;
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

/* 主要内容区域 */
.signin-content {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 600px;
}

/* 头部区域 */
.signin-header {
  text-align: center;
  margin-bottom: 2rem;
  color: white;
}

.header-icon {
  margin-bottom: 1rem;
  display: inline-block;
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.3);
  margin: 0 auto 1rem;
}

.header-icon .icon {
  color: white;
}

.signin-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.signin-subtitle {
  font-size: 0.95rem;
  opacity: 0.9;
  font-weight: 300;
}

/* 移动端表单 */
.mobile-form-wrapper {
  margin-bottom: 1.5rem;
}

.mobile-form {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.form-group {
  margin: 0;
}

.submit-btn-wrapper {
  padding: 1.5rem 1rem;
  background: #f7f8fa;
}

.submit-btn {
  height: 50px;
  font-size: 1.1rem;
  font-weight: 600;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.submit-btn:active {
  transform: scale(0.98);
}

/* PC端表单 */
.desktop-form-wrapper {
  margin-bottom: 1.5rem;
}

.form-card {
  border-radius: 20px;
  border: none;
  overflow: hidden;
  background: white;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.desktop-form {
  padding: 1rem 0;
}

.desktop-form :deep(.el-form-item) {
  margin-bottom: 1.5rem;
}

.desktop-form :deep(.el-form-item__label) {
  font-weight: 500;
  color: #333;
  font-size: 15px;
}

.desktop-form :deep(.el-input__wrapper) {
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s;
}

.desktop-form :deep(.el-input__wrapper:hover) {
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.desktop-form :deep(.el-input__wrapper.is-focus) {
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.desktop-form :deep(.el-input__inner) {
  padding-left: 10px;
  font-size: 16px;
}

.submit-form-item {
  margin-top: 2rem;
  margin-bottom: 0 !important;
  text-align: center;
}

.submit-btn-desktop {
  width: 100%;
  height: 50px;
  font-size: 1.1rem;
  font-weight: 600;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  transition: all 0.3s;
}

.submit-btn-desktop:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.submit-btn-desktop:active {
  transform: translateY(0);
}

/* 底部提示 */
.signin-footer {
  text-align: center;
  margin-top: 1rem;
}

.footer-text {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.85rem;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  gap: 0.5rem;
  line-height: 1.5;
}

.footer-text .el-icon,
.footer-text .van-icon {
  font-size: 1rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
}

/* 响应式设计 */
@media (max-width: 768px) {
  .signin-container {
    padding: 1rem 0.5rem 0.75rem;
    align-items: flex-start;
  }

  .signin-content {
    margin-top: 1rem;
    padding-top: 1.25rem;
  }

  .signin-header {
    margin-bottom: 1.25rem;
  }

  .signin-title {
    font-size: 1.5rem;
    margin-bottom: 0.4rem;
  }

  .signin-subtitle {
    font-size: 0.85rem;
  }

  .header-icon {
    width: 60px;
    height: 60px;
    margin-bottom: 0.75rem;
  }

  .header-icon .icon {
    font-size: 32px !important;
  }

  .mobile-form {
    border-radius: 15px;
  }

  .submit-btn-wrapper {
    padding: 1rem;
  }

  .signin-footer {
    margin-top: 0.75rem;
  }
}

@media (min-width: 769px) {
  .signin-content {
    max-width: 700px;
  }

  .form-card {
    padding: 2rem;
  }
}

/* 动画效果 */
.signin-content {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Vant 组件样式优化 */
:deep(.van-cell-group--inset) {
  margin: 0;
  border-radius: 0;
}

:deep(.van-field__label) {
  font-weight: 500;
  color: #333;
}

:deep(.van-field__control) {
  font-size: 16px;
}

:deep(.van-cell) {
  padding: 16px;
}

/* 统一图标样式为实心 */
:deep(.van-field__left-icon) {
  color: #667eea;
  font-size: 18px;
}

:deep(.van-field__left-icon .van-icon) {
  font-weight: 600;
}

/* Iconify 图标样式 */
.custom-icon {
  color: #667eea;
  font-size: 18px;
  display: flex;
  align-items: center;
}

/* Element Plus 图标样式 */
:deep(.el-input__prefix) {
  color: #667eea;
  margin-right: 8px;
}
</style>

