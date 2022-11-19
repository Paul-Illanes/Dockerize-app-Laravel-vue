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
      action: 'permisos-index',
    },
  },
  {
    path: '/admin/permisos/edit/:permisoId',
    name: 'admin-permission-edit',
    component: () => import('@/views/admin/Permissions/PermissionsEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'permisos-edit',
    },
  },
  {
    path: '/admin/permiso/add',
    name: 'admin-permission-add',
    component: () => import('@/views/admin/Permissions/PermissionsAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'permisos-create',
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
      action: 'cambio_turnos-index',
    },
  },
  {
    path: '/admin/cambio_de_turno/add',
    name: 'admin-turno-add',
    component: () => import('@/views/admin/cambios_de_turno/cambio_turno/TurnoAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'cambio_turnos-create',
    },
  },
  {
    path: '/admin/cambio_de_turno/edit/:turnoId',
    name: 'admin-turno-edit',
    component: () => import('@/views/admin/cambios_de_turno/cambio_turno/TurnoEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'cambio_turnos-edit',
    },
  },
    // *===============================================---*
  // *--------- baja de personal--- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/baja_de_personal/list',
    name: 'admin-baja-list',
    component: () => import('@/views/admin/baja_personal/BajaPersonalList.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_baja-index',
    },
  },
  {
    path: '/admin/baja_de_personal/add',
    name: 'admin-baja-add',
    component: () => import('@/views/admin/baja_personal/BajaPersonalAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_baja-create',
    },
  },
  {
    path: '/admin/baja_personal/edit/:bajaId',
    name: 'admin-baja-edit',
    component: () => import('@/views/admin/baja_personal/BajaPersonalEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_baja-edit',
    },
  },
  // *===============================================---*
  // *--------- control informe de cese--- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/control_informe_cese/list',
    name: 'admin-informecese-list',
    component: () => import('@/views/admin/control_informe_cese/InformeCeseList.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_informes_cese-index',
    },
  },
  {
    path: '/admin/control_informe_cese/add',
    name: 'admin-informecese-add',
    component: () => import('@/views/admin/control_informe_cese/InformeCeseAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_informes_cese-create',
    },
  },
  {
    path: '/admin/control_informe_cese/edit/:informeId',
    name: 'admin-informecese-edit',
    component: () => import('@/views/admin/control_informe_cese/InformeCeseEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_informes_cese-edit',
    },
  },
    // *===============================================---*
  // *--------- legajos informe de cese--- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/legajos_informe_cese/list',
    name: 'admin-legajocese-list',
    component: () => import('@/views/admin/legajos_informe_cese/LegajosCeseList.vue'),
    meta: {
      resource: 'ACL',
      action: 'legajos_informes_cese-index',
    },
  },
  {
    path: '/admin/legajos_informe_cese/add',
    name: 'admin-legajocese-add',
    component: () => import('@/views/admin/legajos_informe_cese/LegajosCeseAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'legajos_informes_cese-create',
    },
  },
  {
    path: '/admin/legajos_informe_cese/edit/:legajoId',
    name: 'admin-legajocese-edit',
    component: () => import('@/views/admin/legajos_informe_cese/LegajosCeseEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'legajos_informes_cese-edit',
    },
  },
      // *===============================================---*
  // *--------- persona-- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/persona/list',
    name: 'admin-personas-list',
    component: () => import('@/views/admin/persona/PersonaList.vue'),
    meta: {
      resource: 'ACL',
      action: 'personas-index',
    },
  },
  {
    path: '/admin/persona/add',
    name: 'admin-personas-add',
    component: () => import('@/views/admin/persona/PersonaAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'personas-create',
    },
  },
  {
    path: '/admin/persona/edit/:personaId',
    name: 'admin-personas-edit',
    component: () => import('@/views/admin/persona/PersonaEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'personas-edit',
    },
  },
        // *===============================================---*
  // *--------- persona-- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/contratos/list',
    name: 'admin-contratos-list',
    component: () => import('@/views/admin/informacion/contratos/ContratoList.vue'),
    meta: {
      resource: 'ACL',
      action: 'contrato-index',
    },
  },
  {
    path: '/admin/contratos/add',
    name: 'admin-contratos-add',
    component: () => import('@/views/admin/informacion/contratos/ContratoAdd.vue'),
    meta: {
      resource: 'ACL',
      action: 'contrato-create',
    },
  },
  {
    path: '/admin/contratos/edit/:contratoId',
    name: 'admin-contratos-edit',
    component: () => import('@/views/admin/informacion/contratos/ContratoEdit.vue'),
    meta: {
      resource: 'ACL',
      action: 'contrato-edit',
    },
  },
        // *===============================================---*
  // *--------- agrupar persona-- ---------------------------------------*
  // *===============================================---*
  {
    path: '/admin/agrupar/list',
    name: 'admin-agrupar-list',
    component: () => import('@/views/admin/informacion/agrupar_personal/AgruparList.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_grupo-index',
    },
  },
  {
    path: '/admin/agrupar/personal/:areaId',
    name: 'admin-agrupar-personal',
    component: () => import('@/views/admin/informacion/agrupar_personal/AgruparPersonal.vue'),
    meta: {
      resource: 'ACL',
      action: 'personal_grupo-index',
    },
  },
          // *===============================================---*
  // *--------- cronograma-- ---------------------------------------*
  // *===============================================---*
  
  {
    path: '/admin/cronograma',
    name: 'admin-cronograma',
    component: () => import('@/views/admin/informacion/cronograma/CronogramaList.vue'),
    meta: {
      resource: 'ACL',
      action: 'cronograma-index',
    },
  },
  // *===============================================---*
  // *--------- programar vacaiones-- ---------------------------------------*
  // *===============================================---*
  
  {
    path: '/admin/proceso_vacaciones',
    name: 'admin-proceso_vacaciones',
    component: () => import('@/views/admin/proceso_vacaciones/ProcesoVacacionesList.vue'),
    meta: {
      resource: 'ACL',
      action: 'vacaciones_programaciones-create',
    },
  },
    // *===============================================---*
  // *--------- registro vacaiones-- ---------------------------------------*
  // *===============================================---*
  
  {
    path: '/admin/registro_vacaciones',
    name: 'admin-registro_vacaciones',
    component: () => import('@/views/admin/registro_vacaciones/RegistroVacaciones.vue'),
    meta: {
      resource: 'ACL',
      action: 'vacaciones_programaciones-create',
    },
  },
]
