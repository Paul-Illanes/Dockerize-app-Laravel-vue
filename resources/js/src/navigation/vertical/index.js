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
    ],
  },
]
