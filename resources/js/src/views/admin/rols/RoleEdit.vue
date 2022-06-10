<template>
    <b-card-code title="Listado de Permisos por ROL" no-body>
        <b-card-body>
            <b-form-group v-if="selectedPermissions">
                <v-select
                    v-model="selected"
                    label="name"
                    item-value="id"
                    item-text="name"
                    :options="roles"
                />
            </b-form-group>

            <div class="d-flex justify-content-between flex-wrap">
                <!-- sorting  -->
                <b-form-group
                    label="Ordenar"
                    label-size="sm"
                    label-align-sm="left"
                    label-cols-sm="4"
                    label-for="sortBySelect"
                    class="mr-1 mb-md-0 white-nowrap"
                >
                    <b-input-group size="sm">
                        <b-form-select
                            id="sortBySelect"
                            v-model="sortBy"
                            :options="sortOptions"
                        >
                            <template #first>
                                <option value="">none</option>
                            </template>
                        </b-form-select>
                        <b-form-select
                            v-model="sortDesc"
                            size="sm"
                            :disabled="!sortBy"
                        >
                            <option :value="false">Asc</option>
                            <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>

                <!-- filter -->
                <b-form-group
                    label="Filtrar"
                    label-cols-sm="2"
                    label-align-sm="left"
                    label-size="sm"
                    label-for="filterInput"
                    class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="filterInput"
                            v-model="filter"
                            type="search"
                            placeholder="Escriba para buscar..."
                        />
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">
                                Limpiar
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </div>
        </b-card-body>

        <b-table
            striped
            hover
            responsive
            class="position-relative"
            :per-page="perPage"
            :current-page="currentPage"
            :items="permissions"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
        >
            <template #cell(name)="data">
                <!-- <b-list-group-item
                        v-for="permission in permissions"
                        :key="permission.name"
                    > -->
                <b-form-checkbox
                    @change="check($event)"
                    v-model="selectedPermissions"
                    :value="data.value"
                    >{{ data.value }}</b-form-checkbox
                >
                <!-- </b-list-group-item> -->
            </template>
        </b-table>
        <b-overlay :show="show" opacity="0.40" variant="success" blur="2px">
            <template #overlay>
                <div class="text-center text-info">
                    <feather-icon icon="ClockIcon" size="24" />
                    <b-card-text id="cancel-label">
                        Cargando datos...
                    </b-card-text>
                </div>
            </template>
            <b-card-body class="d-flex justify-content-between flex-wrap pt-0">
                <!-- page length -->
                <b-form-group
                    label="Mostrar"
                    label-cols="6"
                    label-align="left"
                    label-size="sm"
                    label-for="sortBySelect"
                    class="text-nowrap mb-md-0 mr-1"
                >
                    <b-form-select
                        id="perPageSelect"
                        v-model="perPage"
                        size="sm"
                        inline
                        :options="pageOptions"
                    />
                </b-form-group>

                <!-- pagination -->
                <div>
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        first-number
                        last-number
                        prev-class="prev-item"
                        next-class="next-item"
                        class="mb-0"
                    >
                        <template #prev-text>
                            <feather-icon icon="ChevronLeftIcon" size="18" />
                        </template>
                        <template #next-text>
                            <feather-icon icon="ChevronRightIcon" size="18" />
                        </template>
                    </b-pagination>
                </div>
            </b-card-body>
        </b-overlay>
    </b-card-code>
    <!-- <b-card no-body>
        <b-card-body>
            <b-card-title>Permisos</b-card-title>
            <b-card-sub-title>Permisos acorde al rol: </b-card-sub-title>
            <div>
                <b-form-input
                    label="name"
                    v-model="name"
                    name="name"
                    class="mt-2 mb-2"
                    disabled
                ></b-form-input>
            </div>
            <div class="mb-2">
                <b-list-group>
                    <b-list-group-item
                        v-for="permission in permissions"
                        :key="permission.name"
                    >
                        <b-form-checkbox
                            v-model="selectedPermissions"
                            :value="permission.name"
                            >{{ permission.description }}</b-form-checkbox
                        >
                    </b-list-group-item>
                </b-list-group>
            </div>
            <b-button icon-pack="feather" icon="icon-save" @click="updateRole">
                Actualizar
            </b-button>
        </b-card-body> -->

    <!-- {{ selectedPermissions }} -->
    <!-- <b-table striped responsive :items="selectedPermissions" class="mb-0">
            <template #cell(name)="data">
                {{ data.value }}
            </template>
            <template #cell()="data">
                <b-form-checkbox disabled :checked="data.value" />
            </template>
        </b-table> -->
    <!-- </b-card> -->
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BOverlay,
    BListGroup,
    BListGroupItem,
    BRow,
    BCol,
    BLink,
    BButton,
    BForm,
    BFormCheckbox,
    BFormSelect,
    BFormGroup,
    BPagination,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BImg,
    BCardTitle,
    BCardText,
    BCard,
    BTable,
    BCardBody,
    BCardSubTitle,
} from "bootstrap-vue";
import vSelect from "vue-select";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
export default {
    components: {
        vSelect,
        BOverlay,
        BFormSelect,
        BPagination,
        BCardCode,
        BListGroup,
        BListGroupItem,
        BCard,
        BTable,
        BRow,
        BImg,
        BCol,
        BLink,
        BButton,
        BForm,
        BCardText,
        BCardTitle,
        BFormCheckbox,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        // validations
        BCardSubTitle,
        BCardBody,
        ValidationProvider,
        ValidationObserver,
    },

    data() {
        return {
            selected: "",
            roles: [],
            selectedPermissions: [],
            permissions: [],
            name: "",
            show: false,
            items: [],
            perPage: 10,
            pageOptions: [10, 50, 100],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            fields: [
                { key: "name", label: "Nombre", sortable: true },
                { key: "description", label: "Descripcion", sortable: true },
            ],
        };
    },
    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter((f) => f.sortable)
                .map((f) => ({ text: f.label, value: f.key }));
        },
    },
    watch: {
        selected: function (val, oldval) {
            this.show = true;
            this.$http
                .get("/api/auth/roles/detail/" + this.selected.id)
                .then((response) => {
                    // console.log(response.data.data.permissions);
                    this.selectedPermissions = response.data.data.permissions;
                    this.name = response.data.data.name;
                    this.show = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        // this.row = this.tableBasic;
        this.$http
            .get("/api/auth/roles/" + this.$route.params.roleId)
            .then((res) => {
                this.selected = {
                    id: res.data.data.id,
                    name: res.data.data.name,
                };
            });
        this.$http.get("/api/auth/roles/").then((res) => {
            this.roles = res.data;
            console.log(this.roles);
        });
    },
    mounted() {
        this.getPermission();
        this.getDetail();
        this.totalRows = this.permissions.length;
    },
    methods: {
        check: function (e) {
            this.$http
                .post("/api/auth/roles/update/" + this.selected.id, {
                    name: this.selected.name,
                    permissions: this.selectedPermissions,
                })
                .then(() => {
                    console.log("actualizado");
                    // this.$toast({
                    //     component: ToastificationContent,
                    //     position: "top-right",
                    //     props: {
                    //         title: `Success`,
                    //         icon: "CoffeeIcon",
                    //         variant: "success",
                    //         text: `Actualizado correctamente!`,
                    //     },
                    // });
                })
                .catch((error) => {
                    for (let item in error.response.data.errors) {
                        this.errors.add({
                            scope: null,
                            field: item,
                            rule: "required",
                            msg: error.response.data.errors[item][0],
                        });
                    }
                    // this.$vs.notify({
                    //     title: "Failed",
                    //     text: error.response.data.message,
                    //     position: "top-right",
                    //     color: "danger",
                    // });
                });
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        getDetail() {
            this.show = true;
            this.$http
                .get("/api/auth/roles/detail/" + this.$route.params.roleId)
                .then((response) => {
                    // console.log(response.data.data.permissions);
                    this.selectedPermissions = response.data.data.permissions;
                    this.name = response.data.data.name;
                    this.show = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getPermission() {
            this.$http
                .get("/api/auth/permissions")
                .then((response) => {
                    this.permissions = response.data.data;
                    this.totalRows = this.permissions.length;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        // updateRole() {
        //     this.$http
        //         .post("/api/auth/roles/update/" + this.$route.params.roleId, {
        //             name: this.name,
        //             permissions: this.selectedPermissions,
        //         })
        //         .then(() => {
        //             this.$toast({
        //                 component: ToastificationContent,
        //                 position: "top-right",
        //                 props: {
        //                     title: `Success`,
        //                     icon: "CoffeeIcon",
        //                     variant: "success",
        //                     text: `Actualizado correctamente!`,
        //                 },
        //             });
        //             this.$router.push({ name: "admin-roles-list" });
        //         })
        //         .catch((error) => {
        //             console.log(error);
        //             for (let item in error.response.data.errors) {
        //                 this.errors.add({
        //                     scope: null,
        //                     field: item,
        //                     rule: "required",
        //                     msg: error.response.data.errors[item][0],
        //                 });
        //             }
        //             this.$vs.notify({
        //                 title: "Failed",
        //                 text: error.response.data.message,
        //                 position: "top-right",
        //                 color: "danger",
        //             });
        //         });
        // },
        consoleVal() {
            console.log(this.selectedPermissions);
        },
    },
};
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
