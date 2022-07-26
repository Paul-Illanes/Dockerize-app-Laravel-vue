export default [
  // *===============================================---*
  // *--------- USER ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/users/list',
    name: 'admin-users-list',
    component: () => import('@/views/admin/users/UsersList.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-index',
    },
  },
  {
    path: '/admin/roles/list',
    name: 'admin-roles-list',
    component: () => import('@/views/admin/rols/RoleList.vue'),
    meta: {
      resource: 'ACL',
      action: 'roles-index',
    },
  },
  {
    path: '/admin/roles/edit/:roleId',
    name: 'admin-roles-edit',
    component: () => import('@/views/admin/rols/RoleEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'roles-edit',
    },
  },
  {
    path: '/admin/roles/add',
    name: 'admin-roles-add',
    component: () => import('@/views/admin/rols/RolAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'roles-create',
    },
  },
  {
    path: '/admin/users/add',
    name: 'admin-users-add',
    component: () => import('@/views/admin/users/UserAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-create',
    },
  },
  {
    path: '/admin/users/edit/:userId',
    name: 'admin-users-edit',
    component: () => import('@/views/admin/users/UserEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-edit',
    },
  },
  {
    path: '/admin/permisos/list',
    name: 'admin-permission-list',
    component: () => import('@/views/admin/Permissions/PermissionList.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-index',
    },
  },
  {
    path: '/admin/permisos/edit/:permisoId',
    name: 'admin-permission-edit',
    component: () => import('@/views/admin/Permissions/PermissionsEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-edit',
    },
  },
  {
    path: '/admin/permiso/add',
    name: 'admin-permission-add',
    component: () => import('@/views/admin/Permissions/PermissionsAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'users-create',
    },
  },
  // *===============================================---*
  // *--------- cambios de turno---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/cambios_de_turno/papeletas/list',
    name: 'admin-papeletas-list',
    component: () => import('@/views/admin/cambios_de_turno/papeletas/PapeletasList.vue'),
    meta: {
      resource: 'ACL',
      action: 'papeletas-index',
    },
  },
  {
    path: '/admin/cambios_de_turno/papeletas/edit/:papeletaId',
    name: 'admin-papeleta-edit',
    component: () => import('@/views/admin/cambios_de_turno/papeletas/PapeletasEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'papeletas-edit',
    },
  },
  {
    path: '/admin/cambios_de_turno/papeletas/add',
    name: 'admin-papeletas-add',
    component: () => import('@/views/admin/cambios_de_turno/papeletas/PapeletasAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'papeletas-create',
    },
  },
  // *===============================================---*
  // *--------- reporte de vacaciones---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/informacion/reporte_vacaciones/list',
    name: 'admin-reporte_vacaciones-list',
    component: () => import('@/views/admin/informacion/reporte_vacaciones/ReporteVacacionesList.vue'),
    meta: {
      resource: 'ACL',
      action: 'vacaciones_documentos-report',
    },
  },
  // *===============================================---*
  // *--------- incorporaciones---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/incorporaciones/list',
    name: 'admin-incorporacion-list',
    component: () => import('@/views/admin/incorporaciones/IncorporacionList.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-index',
    },
  },
  {
    path: '/admin/incorporaciones/add',
    name: 'admin-incorporacion-add',
    component: () => import('@/views/admin/incorporaciones/IncorporacionAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-create',
    },
  },
  {
    path: '/admin/incorporaciones/edit/:incorporacionId',
    name: 'admin-incorporacion-edit',
    component: () => import('@/views/admin/incorporaciones/IncorporacionEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-edit',
    },
  },
  // *===============================================---*
  // *--------- cambio de turno--- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/cambio_de_turno/list',
    name: 'admin-turno-list',
    component: () => import('@/views/admin/cambios_de_turno/cambio_turno/TurnoList.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-index',
    },
  },
  {
    path: '/admin/cambio_de_turno/add',
    name: 'admin-turno-add',
    component: () => import('@/views/admin/cambios_de_turno/cambio_turno/TurnoAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-create',
    },
  },
  {
    path: '/admin/cambio_de_turno/edit/:turnoId',
    name: 'admin-turno-edit',
    component: () => import('@/views/admin/cambios_de_turno/cambio_turno/TurnoEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'incorporaciones-edit',
    },
  },
]
