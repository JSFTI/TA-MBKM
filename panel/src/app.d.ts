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