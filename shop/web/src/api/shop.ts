import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || '/cms',
  timeout: 10000
})

api.interceptors.response.use(
  response => {
    return response.data
  },
  error => {
    console.error('API Error:', error)
    return Promise.reject(error)
  }
)

export interface ShopItem {
  id: number
  category_id: number
  shop_name: string
  shop_logo?: string
  shop_images?: string
  shop_intro?: string
  is_first_store?: number
  is_recommended?: number
  is_pinned?: number
  sort_order?: number
  is_active?: number
  created_at?: string
  updated_at?: string
}

export interface Category {
  id: number
  code: string
  name: string
  items: ShopItem[]
}

export interface CoverResponse {
  categories: Category[]
}

export interface ListResponse {
  categories: Category[]
}

export const getCover = () => {
  return api.get<any, CoverResponse>('/api/shop/cover')
}

export const getList = (params: { category_id?: number; category_code?: string; limit?: number; offset?: number }) => {
  return api.get<any, ListResponse>('/api/shop/list', { params })
}

export const getDetail = (id: number) => {
  return api.get<any, { item: ShopItem }>('/api/shop/detail', { params: { id } })
}

