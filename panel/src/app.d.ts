type Role = {
  id?: number
  name?: string
  display_name?: string
  created_at?: string
  updated_at?: string | null
}

type User = {
  email?: string
  id?: number
  name?: string
  profile_picture?: string | null
  permissions?: string[]
  role?: Role
  role_id?: string
}

type ApiInvalidFeedback = {
  status: string
  message?: string
  errors?: Record<string, string>
}

type ApiPagination<T> = {
  current_page: number,
  data: T[],
  first_page_url: string,
  from: number
  last_page: number
  last_page_url: string
  links: {
    url: null | string
    label: string
    active: boolean
  }
  next_page_url: string
  path: string
  per_page: number
  prev_page_url: null | string
  to: number
  total: number
}

type Carousel = {
  id?: number
  // title?: string | null
  filename?: string
  url?: string
  // hyperlink?: string | null
  priority?: number | null
  approved?: boolean
  approved_at?: null | string
  created_at?: string
  updated_at?: null | string
}

type Product = {
  id?: number
  thumbnail_id?: number | null
  thumbnail?: ProductImage
  category_id?: number | null
  category?: Category
  name?: string
  price?: number
  detail?: string | null
  stock?: number | null
  published?: boolean
  created_at?: string
  updated_at?: string | null
  images?: ProductImage[]
  tags?: Tag[]
}

type ProductImage = {
  id?: number
  product_id?: number
  product?: Product
  filename?: string
  url?: string
  priority?: number | null
  created_at?: string
  updated_at?: string | null
}

type Category = {
  id?: number
  name?: string
}

type Tag = {
  id?: number
  name?: string
  products_count?: number
}