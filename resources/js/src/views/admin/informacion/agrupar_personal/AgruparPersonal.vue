<template>
    <b-card-code title="Personal por servicio" no-body>
        <b-row class="mx-2">
            <b-col cols="12" md="6">
                <b-form-group>
                    <label style="font-weight: 700">Servicio</label>
                    <v-select
                        v-model="selectedServicio"
                        label="name"
                        item-value="id"
                        item-text="name"
                        :options="servicios"
                        placeholder="Seleccione"
                    />
                    <label style="font-weight: 700">Area</label>
                    <b-form-input
                        id="example-input"
                        type="text"
                        :disabled="!selectedDependencia"
                        v-model="area"
                        @change="fireFilter()"
                    />
                </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
                <b-form-group>
                    <label style="font-weight: 700">Dependencia</label>
                    <v-select
                        v-model="selectedDependencia"
                        label="name"
                        item-value="dni"
                        item-text="name"
                        :options="dependencia"
                        placeholder="Seleccione"
                        :disabled="!selectedServicio"
                    />
                    <label style="font-weight: 700">Personal</label>
                    <v-select
                        v-model="selectedPersonal"
                        label="name"
                        item-value="dni"
                        item-text="name"
                        :options="personal"
                        placeholder="Seleccione"
                        :disabled="!area"
                    />
                </b-form-group>
            </b-col>
        </b-row>
        <div class="m-2">
            <!-- Table Top -->
            <b-row>
                <!-- Per Page -->
                <b-col
                    cols="12"
                    md="6"
                    class="d-flex align-items-center justify-content-start mb-md-0"
                >
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
                    <b-button variant="primary" @click="register" class="mr-1">
                        Actualizar Grupo
                    </b-button>
                    <b-button
                        variant="warning"
                        v-on:click="
                            fields.push({
                                key: 'action',
                                label: 'action',
                                sortable: false,
                            })
                        "
                    >
                        Modificar Grupo
                    </b-button>
                </b-col>

                <!-- Search -->
                <b-col cols="12" md="6">
                    <div class="d-flex align-items-center justify-content-end">
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
                </b-col>
            </b-row>
        </div>
        <b-table
            striped
            hover
            responsive
            class="position-relative"
            :per-page="perPage"
            :current-page="currentPage"
            :items="items"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
        >
            <template #cell(index)="data">
                <div class="text-center" v-if="data.item.id">
                    {{ data.index + 1 }}
                </div>
                <div class="text-center" v-if="!data.item.id">
                    <b-badge variant="danger">Falta registrar</b-badge>
                </div>
            </template>
            <template #cell(active)="data">
                <div class="text-center">
                    <!-- <b-badge
                        :variant="data.item.active ? 'success' : 'warning'"
                        >{{ verified(data.item.active) }}</b-badge
                    > -->
                    <b-dropdown
                        toggle-class="p-0"
                        no-caret
                        pill
                        :variant="data.item.active ? 'success' : 'warning'"
                        class="text-center"
                    >
                        <template #button-content>
                            <b-badge
                                pill
                                :variant="
                                    data.item.active ? 'success' : 'warning'
                                "
                            >
                                {{ verified(data.item.active) }}
                            </b-badge>
                        </template>

                        <b-dropdown-item :disabled="data.item.active == 1">
                            <feather-icon
                                class="text-success"
                                icon="CheckIcon"
                            />
                            <span
                                @click="updateStatus(1, data.item.id)"
                                class="align-middle ml-50"
                                >Si</span
                            >
                        </b-dropdown-item>
                        <b-dropdown-item :disabled="data.item.active == 0">
                            <feather-icon
                                class="text-warning"
                                icon="PauseCircleIcon"
                            />
                            <span
                                @click="updateStatus(0, data.item.id)"
                                class="align-middle ml-50"
                                >No</span
                            >
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </template>
            <template #cell(area)="data">
                <div class="text-center">
                    {{ data.item.area ? data.item.area : area }}
                </div>
            </template>
            <template #cell(action)="data">
                <div class="text-nowrap" v-if="data.item.id">
                    <feather-icon
                        :id="`invoice-row-${data.item.id}-preview-icon`"
                        icon="TrashIcon"
                        size="16"
                        class="mx-1 cursor-pointer text-danger"
                        @click="confirmDelete(data.item.id)"
                    />
                </div>
            </template>
        </b-table>

        <div class="mx-2 mb-2">
            <b-row>
                <b-col
                    cols="12"
                    sm="6"
                    class="d-flex align-items-center justify-content-center justify-content-sm-start"
                >
                </b-col>
                <b-col
                    cols="12"
                    sm="6"
                    class="d-flex align-items-center justify-content-center justify-content-sm-end"
                >
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
                </b-col>
            </b-row>
        </div>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import Ripple from "vue-ripple-directive";
import {
    BRow,
    BCol,
    BOverlay,
    BCardText,
    BTable,
    BAvatar,
    BBadge,
    BFormGroup,
    BFormSelect,
    BPagination,
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BButton,
    BCardBody,
    BDropdown,
    BDropdownItem,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
export default {
    components: {
        BRow,
        BCol,
        BCardText,
        BOverlay,
        BCardCode,
        BTable,
        BAvatar,
        BBadge,
        BFormGroup,
        BFormSelect,
        BPagination,
        BInputGroup,
        BFormInput,
        BInputGroupAppend,
        BButton,
        BCardBody,
        vSelect,
        BDropdown,
        BDropdownItem,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            personal: [],
            servicios: [],
            dependencia: [],
            selectedServicio: "",
            selectedPersonal: "",
            selectedDependencia: "",
            show: false,
            items: [],
            area: "",
            perPage: 5,
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
                    key: "index",
                    label: "#",
                    sortable: true,
                },
                { key: "dni", label: "DNI", sortable: true },
                { key: "name", label: "Nombres", sortable: true },
                { key: "cargo", label: "Cargo", sortable: true },
                { key: "servicio", label: "Servicio", sortable: false },
                { key: "dependencia", label: "Dependencia", sortable: false },
                { key: "area", label: "Area", sortable: false },
                { key: "active", label: "Activo", sortable: true },
            ],
            /* eslint-disable global-require */
            // codeKitchenSink,
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
    mounted() {
        // Set the initial number of items
        this.totalRows = this.items.length;
    },
    methods: {
        repeat(data) {
            // console.log(this.solicitante_turno_selected);
            var dato = this.items.find((item) => item.dni == data);
            if (dato) return true;
        },
        fireFilter() {
            this.$http
                .post("/api/auth/personal_area/getList", {
                    servicio_id: this.selectedServicio.id,
                    dependencia_id: this.selectedDependencia.id,
                    area: this.area,
                })
                .then((response) => {
                    console.log(response);
                    this.personal = response.data.empleados;
                    this.dependencia = response.data.dependencia;
                    this.items = response.data.personal_servicios;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        verified(data) {
            if (data == 1) {
                return "SI";
            } else {
                return "NO";
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        register() {
            if (this.area != "")
                this.$http
                    .post("/api/auth/personal_area/create", {
                        items: this.items,
                        area: this.area,
                    })
                    .then((res) => {
                        console.log(res.data);
                        if (res.status == 202) {
                            this.$toast(
                                {
                                    component: ToastificationContent,
                                    position: "top-right",
                                    props: {
                                        title: res.data.nombres,
                                        icon: "CoffeeIcon",
                                        variant: "success",
                                        text:
                                            `Ya pertenece a al servicio: ` +
                                            res.data.servicio +
                                            `, dependencia: ` +
                                            res.data.dependencia +
                                            ", Area: " +
                                            res.data.area,
                                    },
                                },
                                {
                                    timeout: 8000,
                                }
                            );
                        }
                        this.$toast({
                            component: ToastificationContent,
                            position: "top-right",
                            props: {
                                title: "Registrado Correctamente",
                                icon: "CoffeeIcon",
                                variant: "success",
                                text: `Papeleta registrado correctamente`,
                            },
                        });
                        this.fireFilter();
                    })
                    .catch((error) => {});
            else {
                this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                        title: "Ingrese el area",
                        icon: "CoffeeIcon",
                        variant: "success",
                    },
                });
            }
        },
        confirmDelete(id) {
            this.$swal({
                title: "Estas seguro?",
                text: "No podras revertir esta accion!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-outline-danger ml-1",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    this.$http
                        .post("/api/auth/personal_area/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Personal eliminado del grupo",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            // console.log(res);
                            this.fireFilter();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
        updateStatus(status, id) {
            this.$http
                .post("/api/auth/personal_area/status/" + id, {
                    active: status,
                })
                .then((res) => {
                    this.fireFilter();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    watch: {
        selectedServicio: function (val, oldval) {
            this.fireFilter();
        },
        selectedDependencia: function (val, oldval) {
            this.fireFilter();
        },
        selectedPersonal: function (val, oldval) {
            if (val != 0) {
                if (!this.repeat(val.dni)) {
                    val.active = 0;
                    val.servicio = this.selectedServicio.name;
                    val.servicio_id = this.selectedServicio.id;
                    val.dependencia_id = this.selectedDependencia.id;
                    val.dependencia = this.selectedDependencia.name;
                    this.items.push(val);
                    this.selectedPersonal = 0;
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "El personal seleccionado ya se encuentra en el grupo",
                            icon: "CoffeeIcon",
                            variant: "warning",
                        },
                    });
                }
            }
        },
    },
    created() {
        this.$http.get("/api/auth/personal_area/").then((res) => {
            this.servicios = res.data;
        });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
