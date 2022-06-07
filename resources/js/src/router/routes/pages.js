export default [
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/error/Error404.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
    },
  },
  {
    path: '/login',
    name: 'auth-login',
    component: () => import('@/views/pages/authentication/Login.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/second-page',
    name: 'second-page',
    component: () => import('@/views/SecondPage.vue'),
    meta: {
      resource: 'ACL',
      action: 'read',
    },
  },

  {
    path: '/register',
    name: 'auth-register',
    component: () => import('@/views/pages/authentication/Register.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/pages/miscellaneous/not-authorized',
    name: 'misc-not-authorized',
    component: () => import('@/views/pages/miscellaneous/NotAuthorized.vue'),
    meta: {
      layout: 'full',
      action: 'read',
      resource: 'Auth',
    },
  },
]
