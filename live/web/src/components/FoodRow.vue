<template>
  <div class="food-card" role="button" tabindex="0" @click="onClick">
    <div class="card-left">
      <div class="item-name">{{ title }}</div>
      <div class="item-sub" v-if="subtitle">{{ subtitle }}</div>
    </div>
    <div class="card-right">
      <van-image :src="coverUrl" fit="cover" class="item-img" />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  subtitle?: string
  coverUrl?: string
  onClick?: () => void
}>()
</script>

<style scoped>
.food-card {
  background-color: var(--qd-card-bg);
  border-radius: 12px;
  padding: 0;
  display: flex;
  height: 100px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: relative;
}
.food-card::before {
  content: '';
  position: absolute;
  left: -6px;
  top: 50%;
  transform: translateY(-50%);
  width: 12px;
  height: 12px;
  background-color: var(--qd-bg);
  border-radius: 50%;
}
.card-left {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 24px;
  padding-right: 12px;
}
.item-name {
  font-size: 20px;
  color: var(--qd-text-strong);
  font-weight: 700;
}
.item-sub {
  font-size: 12px;
  color: var(--qd-text-sub);
  margin-top: 4px;
}
.card-right {
  width: 140px;
  position: relative;
}
.item-img {
  width: 100%;
  height: 100%;
  display: block;
}
</style>
