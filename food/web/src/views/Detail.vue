<template>
  <div class="detail-page" v-if="item">
    <div class="header">
      <van-icon name="arrow-left" color="#fff" size="20" @click="router.back()" class="back-icon" />
    </div>
    
    <van-image :src="item.cover_url" width="100%" height="250" fit="cover" />
    
    <div class="info-card">
      <h1 class="title">{{ item.title }}</h1>
      <div class="subtitle" v-if="item.subtitle">{{ item.subtitle }}</div>
      
      <div class="meta-info">
        <div class="meta-row" v-if="item.address">
          <van-icon name="location-o" />
          <span>{{ item.address }}</span>
        </div>
        <div class="meta-row" v-if="item.phone">
          <van-icon name="phone-o" />
          <span>{{ item.phone }}</span>
        </div>
        <div class="meta-row" v-if="item.business_hours">
          <van-icon name="clock-o" />
          <span>{{ item.business_hours }}</span>
        </div>
      </div>
    </div>

    <div class="content-card" v-if="item.content">
      <div class="section-title">详情介绍</div>
      <div class="rich-content" v-html="item.content"></div>
    </div>
  </div>
  
  <div v-else-if="loading" class="loading-state">
    <van-loading vertical color="#fff">加载中...</van-loading>
  </div>
  
  <div v-else class="error-state">
    <span>加载失败</span>
    <van-button size="small" @click="fetchDetail">重试</van-button>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getDetail, type FoodItem } from '../api/food'

const route = useRoute()
const router = useRouter()
const item = ref<FoodItem | null>(null)
const loading = ref(true)

const fetchDetail = async () => {
  loading.value = true
  const id = Number(route.params.id)
  try {
    const res = await getDetail(id)
    item.value = res.item
  } catch {
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchDetail()
})
</script>

<style scoped>
.detail-page {
  min-height: 100vh;
  background: #f7f8fa;
  padding-bottom: 40px;
  overflow-x: hidden;
}

.header {
  position: absolute;
  top: 16px;
  left: 16px;
  z-index: 10;
}

.back-icon {
  background: rgba(0,0,0,0.3);
  padding: 8px;
  border-radius: 50%;
}

.info-card {
  background: #fff;
  border-radius: 16px 16px 0 0;
  margin-top: -20px;
  position: relative;
  padding: 24px 16px;
  z-index: 1;
}

.title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  margin: 0 0 8px 0;
}

.subtitle {
  font-size: 14px;
  color: #666;
  margin-bottom: 16px;
}

.meta-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
  color: #666;
  font-size: 14px;
}

.meta-row {
  display: flex;
  align-items: center;
  gap: 6px;
}

.content-card {
  margin-top: 12px;
  background: #fff;
  padding: 16px;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 12px;
  padding-left: 8px;
  border-left: 4px solid var(--qd-bg);
}

.rich-content {
  font-size: 15px;
  line-height: 1.6;
  color: #333;
  word-break: break-all;
  overflow-wrap: break-word;
}
.rich-content :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 8px 0;
}

.loading-state, .error-state {
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: var(--qd-bg);
  color: #fff;
}
</style>
