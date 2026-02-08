import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import postcsspxtoviewport from 'postcss-px-to-viewport-8-plugin'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  base: '/travel/',
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  css: {
    postcss: {
      plugins: [
        postcsspxtoviewport({
          viewportWidth: 375, // 设计稿宽度，通常为 375 或 750
          unitPrecision: 5,
          viewportUnit: 'vw',
          selectorBlackList: [],
          minPixelValue: 1,
          mediaQuery: false,
        })
      ]
    }
  },
  server: {
    proxy: {
      '/cms': {
        target: 'http://localhost', // 假设本地服务器端口，需根据实际情况调整
        changeOrigin: true
      }
    }
  },
  build: {
    outDir: '../app',
    emptyOutDir: true
  }
})
