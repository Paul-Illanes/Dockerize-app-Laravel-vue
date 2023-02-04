export default [
  {
    path: '/cliente/papeletas',
    name: 'cliente-papeleta-index',
    component: () => import('@/views/employee/papeleta/PapeletaList.vue'),
    meta: {
      resource: 'ACL',
      action: 'cliente_papeleta-index',
    },
  },
]
