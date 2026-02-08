<template>
  <div class="home-page">
    <AppHeader :show-back="false" />

    <div v-if="loading" class="state-container">
      <van-loading color="#fff" />
    </div>
    <div v-else-if="error" class="state-container error">
      {{ error }}
      <van-button size="small" @click="fetchData">重试</van-button>
    </div>

    <div v-else class="content">
      <div v-for="category in categories" :key="category.id" class="section">
        <SectionTitle :title="category.name">
          <template #actions>
            <button class="more-btn" type="button" @click="goList(category.id)">更多</button>
          </template>
        </SectionTitle>
        
        <div class="card-list">
          <FoodRow
            v-for="item in category.items"
            :key="item.id"
            :title="item.title"
            :subtitle="item.subtitle"
            :cover-url="item.cover_url"
            :on-click="() => goDetail(item.id)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getCover, type Category } from '../api/travel'
import FoodRow from '../components/FoodRow.vue'
import AppHeader from '../components/AppHeader.vue'
import SectionTitle from '../components/SectionTitle.vue'

const router = useRouter()
const categories = ref<Category[]>([])
const loading = ref(true)
const error = ref('')

const fetchData = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await getCover()
    categories.value = res.categories
  } catch (err) {
    error.value = '加载失败，请检查网络'
  } finally {
    loading.value = false
  }
}

const goDetail = (id: number) => {
  router.push(`/detail/${id}`)
}

const goList = (categoryId: number) => {
  router.push(`/list?category_id=${categoryId}`)
}

onMounted(() => {
  fetchData()
})
</script>

<style scoped>
.home-page {
  min-height: 100vh;
  padding-bottom: 40px;
  padding-top: 200px;
  background: var(--qd-bg);
}

.section {
  margin-bottom: 30px;
  padding: 0 16px;
}

.more-btn {
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.35);
  color: #fff;
  border-radius: 999px;
  padding: 6px 12px;
  font-size: 12px;
  cursor: pointer;
}

.card-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.state-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 200px;
  color: #fff;
  gap: 12px;
}
</style>
