<template>
  <div class="list-page">
    <AppHeader :title="headerTitle" :show-back="true" :on-back="() => router.back()" />

    <div v-if="categoryTabs.length" class="tabs">
      <button
        v-for="c in categoryTabs"
        :key="c.id"
        class="tab"
        :class="{ active: activeCategoryId === c.id }"
        type="button"
        @click="switchCategory(c.id)"
      >
        {{ c.name }}
      </button>
    </div>

    <van-list
      v-model:loading="loading"
      :finished="finished"
      finished-text="没有更多了"
      @load="onLoad"
      class="list-container"
    >
      <FoodRow
        v-for="item in items"
        :key="item.id"
        :title="item.title"
        :subtitle="item.subtitle"
        :cover-url="item.cover_url"
        :on-click="() => goDetail(item.id)"
      />
    </van-list>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getCover, getList, type Category, type FoodItem } from '../api/food'
import AppHeader from '../components/AppHeader.vue'
import FoodRow from '../components/FoodRow.vue'

const route = useRoute()
const router = useRouter()
const items = ref<FoodItem[]>([])
const loading = ref(false)
const finished = ref(false)
const offset = ref(0)
const limit = 10

const categoryTabs = ref<Category[]>([])

const categoryId = computed(() => {
  const v = Number(route.query.category_id)
  return Number.isFinite(v) && v > 0 ? v : 0
})

const categoryCode = computed(() => {
  const v = route.query.category_code
  return v ? String(v).trim() : ''
})

const activeCategoryId = computed(() => categoryId.value)
const headerTitle = computed(() => {
  const current = categoryTabs.value.find(c => c.id === activeCategoryId.value)
  return current?.name || '列表'
})

const reset = () => {
  items.value = []
  offset.value = 0
  finished.value = false
  loading.value = false
}

const initCategory = async () => {
  if (categoryTabs.value.length) {
    return
  }
  try {
    const res = await getCover()
    categoryTabs.value = res.categories || []
  } catch {
    categoryTabs.value = []
  }
}

const ensureCategorySelected = async () => {
  await initCategory()
  if (categoryId.value > 0 || categoryCode.value) {
    return
  }
  const first = categoryTabs.value[0]
  if (first) {
    await router.replace({ path: '/list', query: { category_id: String(first.id) } })
  } else {
    finished.value = true
  }
}

const onLoad = async () => {
  if (!categoryId.value && !categoryCode.value) {
    loading.value = false
    await ensureCategorySelected()
    return
  }
  try {
    const res = await getList({
      category_id: categoryId.value || undefined,
      category_code: categoryCode.value || undefined,
      offset: offset.value,
      limit
    })
    
    if (res.categories && res.categories.length > 0 && res.categories[0]) {
      const newItems = res.categories[0].items || []
      items.value.push(...newItems)
      offset.value += newItems.length
      
      if (newItems.length < limit) {
        finished.value = true
      }
    } else {
      finished.value = true
    }
  } catch (err) {
    finished.value = true
  } finally {
    loading.value = false
  }
}

const goDetail = (id: number) => {
  router.push(`/detail/${id}`)
}

const switchCategory = async (id: number) => {
  if (activeCategoryId.value === id) {
    return
  }
  await router.push({ path: '/list', query: { category_id: String(id) } })
}

onMounted(async () => {
  await ensureCategorySelected()
})

watch(
  () => [route.query.category_id, route.query.category_code],
  async () => {
    await initCategory()
    reset()
  }
)
</script>

<style scoped>
.list-page {
  min-height: 100vh;
  background: var(--qd-bg);
  padding: 16px;
  padding-top: 196px; /* 180px header + 16px padding */
}

.list-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.tabs {
  display: flex;
  gap: 10px;
  overflow-x: auto;
  padding: 10px 0 14px;
}

.tab {
  background: rgba(255, 255, 255, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.35);
  color: #fff;
  border-radius: 999px;
  padding: 6px 12px;
  font-size: 12px;
  white-space: nowrap;
  cursor: pointer;
}

.tab.active {
  background: rgba(255, 255, 255, 0.9);
  border-color: rgba(255, 255, 255, 0.95);
  color: #0056b3;
}
</style>
