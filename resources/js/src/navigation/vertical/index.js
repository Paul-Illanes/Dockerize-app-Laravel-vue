export default [
  {
    title: 'Home',
    route: 'home',
    icon: 'HomeIcon',
  },
  {
    title: 'Administracion',
    icon: 'UsersIcon',

    /** Both group child have `resource` and `action` defined
     * So, If I omit defining `resource` and `action` for group
     *    => then group will be hidden if both child are hidden
     *
     * Conclusion: You can omit defining `resource` and `action` for group if you want this kind of behavior
     */
    children: [
      {
        title: 'Usuarios',
        route: 'admin-users-list',
        resource: 'ACL',
        action: 'users-index',
        icon: 'UserIcon',
      },
      {
        title: 'Roles',
        route: 'admin-roles-list',
        resource: 'ACL',
        action: 'roles-index',
        icon: 'UserCheckIcon',
      },
      {
        title: 'Permisos',
        route: 'admin-permission-list',
        resource: 'ACL',
        action: 'permisos-index',
        icon: 'UserXIcon',
      },
    ],
  },
  {
    title: 'Registrar',
    icon: 'BookOpenIcon',
    children: [
      {
        title: 'Papeletas',
        route: 'admin-papeletas-list',
        resource: 'ACL',
        action: 'papeletas-index',
        icon: 'FileTextIcon',
      },
      {
        title: 'Registrar c. de turno',
        route: 'admin-turno-add',
        resource: 'ACL',
        action: 'cambio_turnos-add',
        icon: 'PlusIcon',
      },
      {
        title: 'Ver c. de turno',
        route: 'admin-turno-list',
        resource: 'ACL',
        action: 'cambio_turnos-index',
        icon: 'ListIcon',
      },
    ],
  },
  {
    title: 'Informacion',
    icon: 'InfoIcon',
    children: [
      {
        title: 'Reporte Vacaciones',
        route: 'admin-reporte_vacaciones-list',
        resource: 'ACL',
        action: 'vacaciones_documentos-report',
        icon: 'CalendarIcon',
      },
    ],
  },
  {
    title: 'Procesos',
    icon: 'ListIcon',
    children: [
      {
        title: 'Incorporaciones',
        route: 'admin-incorporacion-list',
        resource: 'ACL',
        action: 'incorporaciones-index',
        icon: 'PlusIcon',
      },
      {
        title: 'Baja de personal',
        route: 'admin-baja-list',
        resource: 'ACL',
        action: 'personal_bajas-index',
        icon: 'UserXIcon',
      },
      {
        title: 'Control Informe de cese',
        route: 'admin-informecese-list',
        resource: 'ACL',
        action: 'personal_informes_cese-index',
        icon: 'EditIcon',
      },
      {
        title: 'Legajos Informe de cese',
        route: 'admin-legajocese-list',
        resource: 'ACL',
        action: 'legajos_informes_cese-index',
        icon: 'EditIcon',
      },
      
    ],
  },
]
