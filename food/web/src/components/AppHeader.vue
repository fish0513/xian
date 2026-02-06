<template>
  <div class="header">
    <van-icon
      v-if="showBack"
      class="back"
      name="arrow-left"
      color="#fff"
      size="20"
      @click="onBack"
    />
    <div v-else class="spacer"></div>
    <div class="title">{{ title }}</div>
    <div class="right">
      <slot name="right"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  showBack?: boolean
  onBack?: () => void
}>()
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 44px;
  padding: 0 16px;
  display: grid;
  grid-template-columns: 24px 1fr 24px;
  align-items: center;
  gap: 8px;
  z-index: 100;
  color: #fff;
  background: transparent;
}
.title {
  text-align: center;
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 1px;
}
.back {
  cursor: pointer;
}
.right {
  display: flex;
  justify-content: flex-end;
}
.spacer {
  width: 24px;
}
</style>
