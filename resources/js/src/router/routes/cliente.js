export default [
    {
        path: "/cliente/papeletas",
        name: "cliente-papeleta-index",
        component: () => import("@/views/employee/papeleta/PapeletaList.vue"),
        meta: {
            resource: "ACL",
            action: "cliente_papeleta-index",
        },
    },
    {
        path: "/cliente/cambios_de_turno/papeletas/add",
        name: "cliente-papeletas-add",
        component: () => import("@/views/employee/papeleta/PapeletaAdd.vue"),
        meta: {
            resource: "ACL",
            action: "papeletas-create",
        },
    },
];
