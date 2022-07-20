<template>
    <b-card-code title="Agregar usuario y asignar sus permisos" no-body>
        <!-- form -->
        <b-row>
            <b-col md="4">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="12">
                                <b-form-group
                                    label="Nombre"
                                    label-for="register-username"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="name"
                                        vid="name"
                                        rules="required"
                                    >
                                        <b-form-input
                                            id="register-name"
                                            v-model="name"
                                            name="register-name"
                                            style="text-transform: uppercase"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            placeholder="nombre del rol"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>

                            <b-col md="12">
                                <b-button
                                    variant="primary"
                                    block
                                    @click="register"
                                    :disabled="invalid"
                                >
                                    Registrar
                                </b-button>
                                <p class="text-secondary" v-if="name != ''">
                                    Seleccione los permisos antes de dar click
                                    en registrar
                                    <span
                                        v-if="selectedPermissions.length > 0"
                                        class="text-success"
                                        >permisos seleccionados:
                                        {{ selectedPermissions.length }}</span
                                    >
                                    <span
                                        v-if="selectedPermissions.length == 0"
                                        class="text-danger"
                                        >permisos seleccionados:
                                        {{ selectedPermissions.length }}</span
                                    >
                                </p>
                            </b-col>
                        </b-row>
                    </b-form>
                </validation-observer>
            </b-col>
            <b-col md="8">
                <b-card-body>
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
                                    <b-button
                                        :disabled="!filter"
                                        @click="filter = ''"
                                    >
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
                <b-overlay
                    :show="show"
                    opacity="0.40"
                    variant="success"
                    blur="2px"
                >
                    <template #overlay>
                        <div class="text-center text-info">
                            <feather-icon icon="ClockIcon" size="24" />
                            <b-card-text id="cancel-label">
                                Cargando datos...
                            </b-card-text>
                        </div>
                    </template>
                    <b-card-body
                        class="d-flex justify-content-between flex-wrap pt-0"
                    >
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
                                    <feather-icon
                                        icon="ChevronLeftIcon"
                                        size="18"
                                    />
                                </template>
                                <template #next-text>
                                    <feather-icon
                                        icon="ChevronRightIcon"
                                        size="18"
                                    />
                                </template>
                            </b-pagination>
                        </div>
                    </b-card-body>
                </b-overlay>
            </b-col>
        </b-row>
    </b-card-code>
</template>

<script>
/* eslint-disable global-require */
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BRow,
    BCol,
    BLink,
    BButton,
    BForm,
    BFormSelect,
    BFormCheckbox,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BPagination,
    BInputGroupAppend,
    BImg,
    BCardTitle,
    BListGroup,
    BCardText,
    BCardBody,
    BCardSubTitle,
    BOverlay,
    BTable,
} from "bootstrap-vue";
import { required, email, length } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BOverlay,
        BTable,
        BRow,
        BImg,
        BCol,
        BLink,
        BButton,
        BPagination,
        BForm,
        BFormSelect,
        BCardText,
        BCardTitle,
        BListGroup,
        BFormCheckbox,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        BCardBody,
        BCardSubTitle,
        BCardCode,
        // validations
        ValidationProvider,
        ValidationObserver,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            permissions: [],
            status: "",
            name: "",
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
    created() {
        this.$http
            .get("/api/auth/permissions")
            .then((response) => {
                this.permissions = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        check: function (e) {
            console.log(e);
        },
        getRoles() {
            // await axios.get('/sanctum/csrf-cookie')
            this.$http
                .get("/api/roles")
                .then((response) => {
                    this.roles = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                   
                    this.$http
                        .post("/api/auth/roles/create", {
                            name: this.name,
                            permissions: this.selectedPermissions,
                        })
                        .then(() => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Rol registrado correctamente`,
                                },
                            });
                            this.$router.push({ name: "admin-roles-list" });
                        })
                        .catch((error) => {
                            this.$refs.registerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
    },
};
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/pages/page-auth.scss";
</style>
