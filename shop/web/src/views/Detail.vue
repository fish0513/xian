<template>
  <div class="detail-page" v-if="item">
    <div class="header">
      <van-icon name="arrow-left" color="#fff" size="20" @click="router.back()" class="back-icon" />
    </div>
    
    <van-image v-if="item.shop_logo" :src="item.shop_logo" width="100%" height="250" fit="cover" />
    
    <div class="info-card">
      <h1 class="title">{{ item.shop_name }}</h1>
    </div>

    <div class="content-card" v-if="item.shop_intro">
      <div class="section-title">店铺介绍</div>
      <div class="rich-content" v-html="item.shop_intro"></div>
    </div>

    <div class="content-card" v-if="imageList.length">
      <div class="section-title">店铺图片</div>
      <div class="image-grid">
        <van-image v-for="(src, idx) in imageList" :key="idx" :src="src" width="100%" height="200" fit="cover" />
      </div>
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
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getDetail, type ShopItem } from '../api/shop'

const route = useRoute()
const router = useRouter()
const item = ref<ShopItem | null>(null)
const loading = ref(true)

const imageList = computed(() => {
  const raw = item.value?.shop_images
  if (!raw) {
    return []
  }
  return raw
    .split(/[\s,]+/)
    .map(v => v.trim())
    .filter(Boolean)
})

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

.image-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
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
