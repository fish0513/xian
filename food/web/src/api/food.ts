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

export interface FoodItem {
  id: number
  title: string
  subtitle?: string
  cover_url?: string
  address?: string
  phone?: string
  business_hours?: string
  content?: string
  is_recommended?: number
  is_pinned?: number
}

export interface Category {
  id: number
  code: string
  name: string
  items: FoodItem[]
}

export interface CoverResponse {
  categories: Category[]
}

export interface ListResponse {
  categories: Category[]
}

export const getCover = () => {
  return api.get<any, CoverResponse>('/api/food/cover')
}

export const getList = (params: { category_id?: number; category_code?: string; limit?: number; offset?: number }) => {
  return api.get<any, ListResponse>('/api/food/list', { params })
}

export const getDetail = (id: number) => {
  return api.get<any, { item: FoodItem }>('/api/food/detail', { params: { id } })
}
