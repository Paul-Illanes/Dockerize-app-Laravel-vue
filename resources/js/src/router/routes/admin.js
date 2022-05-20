export default [
  // *===============================================---*
  // *--------- USER ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/users/list',
    name: 'admin-users-list',
    component: () => import('@/views/admin/users/UsersList.vue'),
  },

]
