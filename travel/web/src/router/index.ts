import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'TravelHome',
    component: () => import('../views/TravelHome.vue')
  },
  {
    path: '/list',
    name: 'TravelList',
    component: () => import('../views/TravelList.vue')
  },
  {
    path: '/detail/:id',
    name: 'TravelDetail',
    component: () => import('../views/TravelDetail.vue')
  },
  {
    path: '/travel',
    redirect: '/'
  },
  {
    path: '/travel/list',
    redirect: (to: any) => {
      return { path: '/list', query: to.query }
    }
  }
]

const router = createRouter({
  history: createWebHistory('/travel/'),
  routes
})

export default router
