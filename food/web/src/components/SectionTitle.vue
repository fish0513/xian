<template>
  <div class="wrap">
    <div class="title">{{ title }}</div>
    <div class="actions">
      <slot name="actions"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ title: string }>()
</script>

<style scoped>
.wrap {
  position: relative;
  margin-bottom: 16px;
}
.title {
  text-align: center;
  font-size: 24px;
  color: #fff;
  font-weight: 500;
  letter-spacing: 2px;
}
.actions {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}
</style>
