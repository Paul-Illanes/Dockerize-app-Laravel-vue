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
        action: 'users-index',
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
        action: 'papeletas-index',
        icon: 'PlusIcon',
      },
      {
        title: 'Ver c. de turno',
        route: 'admin-turno-list',
        resource: 'ACL',
        action: 'papeletas-index',
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
    ],
  },
]
