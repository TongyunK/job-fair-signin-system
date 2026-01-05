<template>
  <div class="signin-container">
    <h1 class="text-2xl font-bold mb-6">{{ $t('signin.title') }}</h1>
    
    <!-- 移动端使用 Vant -->
    <van-form v-if="deviceStore.isMobile" @submit="onSubmit">
      <van-cell-group inset>
        <van-field
          v-model="form.name"
          name="name"
          :label="$t('signin.name')"
          :placeholder="$t('signin.name')"
          :rules="[{ required: true, message: '请输入姓名' }]"
        />
        <van-field
          v-model="form.phone"
          name="phone"
          type="tel"
          :label="$t('signin.phone')"
          :placeholder="$t('signin.phone')"
          :rules="[{ required: true, message: '请输入手机号' }]"
        />
        <van-field
          v-model="form.email"
          name="email"
          type="email"
          :label="$t('signin.email')"
          :placeholder="$t('signin.email')"
        />
        <van-field
          v-model="form.company"
          name="company"
          :label="$t('signin.company')"
          :placeholder="$t('signin.company')"
        />
        <van-field
          v-model="form.position"
          name="position"
          :label="$t('signin.position')"
          :placeholder="$t('signin.position')"
        />
      </van-cell-group>
      <div class="submit-btn">
        <van-button round block type="primary" native-type="submit" :loading="loading">
          {{ $t('signin.submit') }}
        </van-button>
      </div>
    </van-form>

    <!-- PC端使用 Element Plus -->
    <el-form v-else :model="form" :rules="rules" ref="formRef" label-width="100px" class="signin-form">
      <el-form-item :label="$t('signin.name')" prop="name">
        <el-input v-model="form.name" :placeholder="$t('signin.name')" />
      </el-form-item>
      <el-form-item :label="$t('signin.phone')" prop="phone">
        <el-input v-model="form.phone" :placeholder="$t('signin.phone')" />
      </el-form-item>
      <el-form-item :label="$t('signin.email')" prop="email">
        <el-input v-model="form.email" type="email" :placeholder="$t('signin.email')" />
      </el-form-item>
      <el-form-item :label="$t('signin.company')" prop="company">
        <el-input v-model="form.company" :placeholder="$t('signin.company')" />
      </el-form-item>
      <el-form-item :label="$t('signin.position')" prop="position">
        <el-input v-model="form.position" :placeholder="$t('signin.position')" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit" :loading="loading">
          {{ $t('signin.submit') }}
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useDeviceStore } from '@/stores/device'
import { useQueueStore } from '@/stores/queue'
import { ElMessage } from 'element-plus'
import { showToast } from 'vant'

const router = useRouter()
const deviceStore = useDeviceStore()
const queueStore = useQueueStore()

const form = reactive({
  name: '',
  phone: '',
  email: '',
  company: '',
  position: '',
})

const loading = ref(false)
const formRef = ref(null)

const rules = {
  name: [{ required: true, message: '请输入姓名', trigger: 'blur' }],
  phone: [
    { required: true, message: '请输入手机号', trigger: 'blur' },
    { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur' },
  ],
  email: [{ type: 'email', message: '请输入正确的邮箱', trigger: 'blur' }],
}

const onSubmit = async () => {
  if (!deviceStore.isMobile && formRef.value) {
    try {
      await formRef.value.validate()
    } catch (error) {
      return false
    }
  }

  loading.value = true
  try {
    const result = await queueStore.getQueueNumber(form)
    if (deviceStore.isMobile) {
      showToast.success('签到成功！')
    } else {
      ElMessage.success('签到成功！')
    }
    router.push({
      name: 'Queue',
      query: { number: result.number },
    })
  } catch (error) {
    if (deviceStore.isMobile) {
      showToast.fail('签到失败，请重试')
    } else {
      ElMessage.error('签到失败，请重试')
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.signin-container {
  min-height: 100vh;
  padding: 2rem;
  max-width: 600px;
  margin: 0 auto;
}

.signin-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.submit-btn {
  padding: 1rem;
}
</style>

