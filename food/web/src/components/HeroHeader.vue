<template>
  <div class="hero">
    <div class="hero-title">{{ title }}</div>
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
defineProps<{ title: string }>()
</script>

<style scoped>
.hero {
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.hero-title {
  font-size: 48px;
  font-weight: 800;
  color: var(--qd-text-strong);
  text-shadow: 2px 2px 0px rgba(255, 255, 255, 0.9);
  letter-spacing: 2px;
}
</style>
