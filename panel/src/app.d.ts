interface Role{
  id?: number
  name?: string
  display_name?: string
  created_at?: string
  updated_at?: string | null
}

interface User{
  email?: string
  id?: number
  name?: string
  profile_picture?: string | null
  permissions?: string[]
  role?: Role
  role_id?: string
}

interface ApiInvalidFeedback{
  status: string
  message?: string
  errors?: Record<string, string>
}

interface ApiPagination<T>{
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