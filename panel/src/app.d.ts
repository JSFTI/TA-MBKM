interface Role{
  id?: number
  name?: string
  display_name?: string
  created_at?: string
  updated_at?: string | null
}

interface UserSession{
  email?: string
  id?: number
  name?: string
  profile_picture?: string | null
  permissions?: string[]
  role?: Role
  role_id?: string
}