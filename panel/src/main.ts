import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import routes from 'virtual:generated-pages'
import { createVuetify } from 'vuetify'
import { createPinia } from 'pinia'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import draggableComponent from 'vuedraggable'
import axios from 'axios';
import Toast from 'vue-toastification';
import App from './App.vue'

import 'vuetify/styles'
import 'cropperjs/dist/cropper.min.css'
import './styles/toastification.scss';
import './styles/main.scss'
import 'uno.css'

axios.defaults.baseURL = `${import.meta.env.VITE_API_URL}/api`;

const vuetify = createVuetify({
  components,
  directives,
});

const pinia = createPinia();

const app = createApp(App)
const router = createRouter({
  history: createWebHistory('/panel/'),
  routes,
});

app.use(router);
app.use(vuetify);
app.use(pinia);
app.use(Toast);

app.component('Draggable', draggableComponent);

app.mount('#app')

app.config.globalProperties.window = window
