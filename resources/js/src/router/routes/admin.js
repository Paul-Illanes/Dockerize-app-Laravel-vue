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
]
