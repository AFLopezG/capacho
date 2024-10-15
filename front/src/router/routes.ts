import { RouteRecordRaw } from 'vue-router';
import MainLayout from 'layouts/MainLayout.vue'
import IndexPage from 'pages/IndexPage.vue'
import Login from 'pages/Login.vue'
import Venta from 'pages/Venta.vue'
import Servicio from 'pages/Servicios.vue'
import Reporte from 'pages/Reporte.vue'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: IndexPage , meta: { requiresAuth: true } },
      { path: '/servicio', component: Servicio , meta: { requiresAuth: true } },
      { path: '/venta', component: Venta , meta: { requiresAuth: true } },
      { path: '/reporte', component: Reporte , meta: { requiresAuth: true } }
    ],
  },
  {
    path: '/login',
    component: Login
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
