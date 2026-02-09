<template>
  <div class="header">
    <img src="@/assets/header.jpg" class="header-bg" alt="header" />
    <div class="header-content">
      <van-icon
        v-if="showBack"
        class="back"
        name="arrow-left"
        color="#fff"
        size="20"
        @click="onBack"
      />
      <div v-else class="spacer"></div>
      <!-- Title removed -->
      <div class="right">
        <slot name="right"></slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title?: string // Made optional as we removed it from display
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
  height: 180px; /* Increased height to full display header image */
  z-index: 100;
  color: #fff;
  background: transparent;
  overflow: hidden;
}

.header-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -1;
}

.header-content {
  height: 44px; /* Maintain standard header bar height for controls */
  padding: 0 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.back {
  cursor: pointer;
  text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.right {
  display: flex;
  justify-content: flex-end;
}

.spacer {
  width: 24px;
}
</style>
