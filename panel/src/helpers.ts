export function baseUrl(url: string) {
  if (url[0] === '/')
    url = url.substring(1)

  return `${import.meta.env.VITE_API_URL}/${url}`
}
