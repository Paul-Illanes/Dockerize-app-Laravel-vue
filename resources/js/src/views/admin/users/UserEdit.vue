<template>
    <div>
        <b-card-code title="Registro de usuario">
            <validation-observer ref="simpleRules">
                <b-form>
                    <b-row>
                        <!--  This field is required-->
                        <b-col md="3">
                            <b-form-group>
                                <label>Nombres</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="nombres"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="name"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="Nombres"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <!--Enter Number between 10 & 20 -->
                        <b-col md="3">
                            <b-form-group>
                                <label>Apellido Paterno</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="lastname"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="lastname"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="apellido paterno"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <!-- Must only consist of numbers-->
                        <b-col md="3">
                            <b-form-group>
                                <label>Apellido Materno</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="mother_lastname"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="mother_lastname"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="apellido materno"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="3">
                            <b-form-group>
                                <label>email</label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="email"
                                    rules="required|email"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="email"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="Email"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <!-- Must match the specified regular expression : ^([0-9]+)$ - numbers only -->
                        <b-col md="3">
                            <b-form-group>
                                <label>Usuario</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="required|min:8"
                                    name="username"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="username"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="dni del usuario"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <!--  Only alphabetic characters-->
                        <b-col md="3" v-if="pass">
                            <!-- password -->
                            <b-form-group
                                label="Password"
                                label-for="a-password"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="Password"
                                    vid="Password"
                                    rules="required"
                                    :custom-messages="customMessages"
                                >
                                    <b-input-group
                                        class="input-group-merge"
                                        :class="
                                            errors.length > 0
                                                ? 'is-invalid'
                                                : null
                                        "
                                    >
                                        <b-form-input
                                            id="a-password"
                                            v-model="password"
                                            class="form-control-merge"
                                            :type="passwordFieldType"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            name="register-password"
                                            placeholder="············"
                                        />
                                        <b-input-group-append is-text>
                                            <feather-icon
                                                :icon="passwordToggleIcon"
                                                class="cursor-pointer"
                                                @click="
                                                    togglePasswordVisibility
                                                "
                                            />
                                        </b-input-group-append>
                                    </b-input-group>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <b-col md="3" v-if="pass">
                            <!-- password -->
                            <b-form-group
                                label="Confirm Password"
                                label-for="ac-password"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="Confirm Password"
                                    rules="required|confirmed:Password"
                                    :custom-messages="customMessages"
                                >
                                    <b-input-group
                                        class="input-group-merge"
                                        :class="
                                            errors.length > 0
                                                ? 'is-invalid'
                                                : null
                                        "
                                    >
                                        <b-form-input
                                            id="ac-password"
                                            v-model="passValue"
                                            class="form-control-merge"
                                            :type="passwordFieldType"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            name="register-c_password"
                                            placeholder="············"
                                        />
                                        <b-input-group-append is-text>
                                            <feather-icon
                                                :icon="passwordToggleIcon"
                                                class="cursor-pointer"
                                                @click="
                                                    togglePasswordVisibility
                                                "
                                            />
                                        </b-input-group-append>
                                    </b-input-group>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>

                        <!-- Length should not be less than the specified length : 3 -->
                        <!-- <b-col md="2">
                            <b-form-group>
                                <label>PIN</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="integer"
                                    name="pin"
                                >
                                    <b-form-input
                                        v-model="pin"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder=""
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col> -->
                        <b-col md="3">
                            <b-form-group>
                                <label>Celular</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="integer"
                                    name="celular"
                                    :custom-messages="customMessages"
                                >
                                    <b-form-input
                                        v-model="celular"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="celular"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <!--Password Input Field -->
                        <b-col md="4">
                            <b-form-group>
                                <label>Rol</label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="selectedRole"
                                    :custom-messages="customMessages"
                                >
                                    <v-select
                                        multiple
                                        label="name"
                                        :options="roles"
                                        v-model="selectedRole"
                                        name="role"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="1">
                            <b-form-group>
                                <label>Verificado</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules=""
                                    name="verified"
                                >
                                    <b-form-checkbox
                                        class="mt-1"
                                        v-model="verified"
                                        v-bind:true-value="1"
                                        v-bind:false-value="0"
                                    >
                                        Check
                                    </b-form-checkbox>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="1">
                            <b-form-group>
                                <label>Activo</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules=""
                                    name="activo"
                                >
                                    <b-form-checkbox
                                        class="mt-1"
                                        v-model="activo"
                                        v-bind:true-value="1"
                                        v-bind:false-value="0"
                                    >
                                        Activo
                                    </b-form-checkbox>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="1">
                            <b-button
                                class="mt-1"
                                variant="danger"
                                sm
                                @click="pass = !pass"
                                >Cambiar Contraseña</b-button
                            >
                        </b-col>
                        <!-- The digits field must be numeric and exactly contain 3 digits -->
                        <!-- submit button -->
                    </b-row>
                </b-form>
            </validation-observer>
        </b-card-code>
        <b-card-code title="Superstructuras">
            <b-form-group label="Listado de superstructuras :">
                <b-form-checkbox
                    v-model="superstructurasAll"
                    v-on:change="superstructuras_all"
                    true-value="1"
                    false-value="0"
                >
                    Seleccionar todo
                </b-form-checkbox>
                <b-form-checkbox-group
                    id="checkbox-group-2"
                    name="flavour-2"
                    class="demo-inline-spacing"
                >
                    <b-row>
                        <b-col
                            v-for="item in superstructuras"
                            v-bind:data="item"
                            v-bind:key="item.value"
                            lg="3"
                            md="6"
                        >
                            <b-form-checkbox
                                v-model="selectedSuperstructuras"
                                :value="item.value"
                            >
                                {{ item.name }}
                            </b-form-checkbox>
                        </b-col>
                    </b-row>
                </b-form-checkbox-group>
            </b-form-group>
        </b-card-code>
        <dependencia-list ref="childComponent" />
        <b-card-code title="Permisos">
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
                    <b-form-group
                        label-align-sm="left"
                        label-cols-sm="4"
                        label-for="sortBySelect"
                        class="mr-1 mb-md-0 white-nowrap"
                    >
                        <b-form-checkbox
                            v-model="permissionsAll"
                            v-on:change="permisos_all"
                            true-value="1"
                            false-value="0"
                        >
                            Seleccionar todo
                        </b-form-checkbox>
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
                :items="permisos"
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
                        v-model="selectedPermisos"
                        :value="data.value"
                        >{{ data.value }}</b-form-checkbox
                    >
                    <!-- </b-list-group-item> -->
                </template>
            </b-table>

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
            <b-col cols="12" class="mt-2">
                <b-button
                    variant="primary"
                    type="submit"
                    @click.prevent="register"
                >
                    Actualizar
                </b-button>
                <b-button variant="danger" @click="back()"> Volver </b-button>
            </b-col>
        </b-card-code>
    </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import DependenciaList from "./Dependencia.vue";
import {
    BFormCheckboxGroup,
    BFormInput,
    BFormCheckbox,
    BFormGroup,
    BInputGroup,
    BInputGroupAppend,
    BForm,
    BCardText,
    BRow,
    BCol,
    BButton,
    BTable,
    BCardBody,
    BFormSelect,
    BPagination,
} from "bootstrap-vue";
import {
    required,
    email,
    confirmed,
    url,
    between,
    alpha,
    integer,
    password,
    min,
    digits,
    alphaDash,
    length,
} from "@validations";
export default {
    components: {
        BCardText,
        BFormCheckbox,
        BFormCheckboxGroup,
        BCardCode,
        ValidationProvider,
        ValidationObserver,
        BFormInput,
        BFormGroup,
        BInputGroup,
        BInputGroupAppend,
        BForm,
        BRow,
        BCol,
        BButton,
        vSelect,
        BTable,
        BCardBody,
        BFormSelect,
        BPagination,
        DependenciaList,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            customMessages: {
                required: "El campo es requerido",
                email: "Ingrese un email valido",
                password:
                    "Su Contraseña debe contener al menos una mayúscula, una minúscula, un carácter especial y un dígito",
                min: "Su Contraseña debe tener minimo 8 caracteres",
                max: "Su dni no puede tener mas de 8 caracteres",
                confirmed: "Las Contraseñas no coinciden",
            },
            //table
            perPage: 10,
            pageOptions: [3, 5, 10, 50],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            fields: [
                {
                    key: "name",
                    label: "Nombre",
                    sortable: true,
                },
                {
                    key: "description",
                    label: "Descripcion",
                    sortable: true,
                },
            ],
            //variables
            permissionsAll: [],
            superstructurasAll: [],
            superstructuras: [],
            permisos: [],
            email: "",
            username: "",
            name: "",
            lastname: "",
            mother_lastname: "",
            active: "",
            password: "",
            pass: "",
            passValue: "",
            pin: "",
            verified: "",
            activo: "",
            celular: "",
            roles: [],
            selectedRole: "",
            selectedSuperstructuras: [],
            selectedPermisos: [],
            selectedPermissions: [],
            prev: [],
        };
    },

    mounted() {
        this.getSupestructura();
        this.getRoles();
        this.totalRows = this.permisos.length;
    },
    computed: {
        passwordToggleIcon() {
            return this.passwordFieldType === "password"
                ? "EyeIcon"
                : "EyeOffIcon";
        },
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
                this.permisos = response.data.data;
                this.totalRows = this.permisos.length;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
        cambiar_pass() {
            this.pass = true;
        },
        getSupestructura() {
            this.$http.get("/api/auth/superstructura/").then((res) => {
                this.superstructuras = res.data;
                this.getDetail();
            });
        },
        dep: function (data) {
            this.$refs.childComponent.detail(data);
        },
        dep_update: function (id) {
            this.$refs.childComponent.update(id);
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        getDetail() {
            this.$http
                .get("/api/auth/users/detail/" + this.$route.params.userId)
                .then((response) => {
                    this.email = response.data.user.email;
                    this.username = response.data.user.username;
                    this.name = response.data.user.name;
                    this.lastname = response.data.user.lastname;
                    this.mother_lastname = response.data.user.mother_lastname;
                    this.active = response.data.user.active;
                    this.pin = response.data.user.pin;
                    this.verified =
                        response.data.user.verified == 1 ? "true" : "false";
                    this.activo =
                        response.data.user.active == 1 ? "true" : "false";
                    this.celular = response.data.user.celular;
                    this.selectedSuperstructuras = response.data.supestructura;
                    this.selectedRole = response.data.roles;
                    this.selectedPermisos = response.data.permissions;
                    this.dep(response.data.dependencia);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        back() {
            this.$router.back();
        },
        scrollToTop() {
            window.scrollTo(0, 0);
        },
        permisos_all: function (event) {
            // console.log(event);
            if (this.permissionsAll.length == 1) {
                this.permisos.forEach((item) => {
                    this.selectedPermisos.push(item.name);
                });
                // this.selectedPermisos.shift();
            } else {
                this.selectedPermisos = [];
            }
        },
        superstructuras_all: function (event) {
            if (this.superstructurasAll.length == 1) {
                this.selectedSuperstructuras = [];
                this.superstructuras.forEach((item) => {
                    this.selectedSuperstructuras.push(item.value);
                });
            } else {
                this.selectedSuperstructuras = [];
            }
        },
        getRoles() {
            // await axios.get('/sanctum/csrf-cookie')
            this.$http
                .get("/api/auth/roles/pluck")
                .then((response) => {
                    this.roles = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        register() {
            this.$refs.simpleRules.validate().then((success) => {
                if (success) {
                    this.$http
                        .post(
                            "/api/auth/users/update/" +
                                this.$route.params.userId,
                            {
                                name: this.name,
                                lastname: this.lastname,
                                mother_lastname: this.mother_lastname,
                                username: this.username,
                                email: this.email,
                                password: this.password,
                                celular: this.celular,
                                pin: this.pin,
                                verified: this.verified,
                                active: this.activo,
                                c_password: this.c_password,
                                roles: this.selectedRole,
                                permisos: this.selectedPermisos,
                                structuras: this.selectedSuperstructuras,
                            }
                        )
                        .then((res) => {
                            this.dep_update(this.$route.params.userId);
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "success",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Usuario actualizado correctamente!`,
                                },
                            });

                            this.$router.back();
                        })
                        .catch((error) => {
                            this.$refs.simpleRules.setErrors(
                                error.response.data.errors
                            );
                            this.scrollToTop();
                        });
                } else {
                    this.scrollToTop();
                }
            });
        },
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
