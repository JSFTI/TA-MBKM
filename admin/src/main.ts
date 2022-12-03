import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import routes from 'virtual:generated-pages'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import App from './App.vue'

import '@unocss/reset/tailwind.css'
import 'vuetify/styles'
import './styles/main.css'
import 'uno.css'

const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp(App)
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})
app.use(router)
app.use(vuetify)
app.mount('#app')
