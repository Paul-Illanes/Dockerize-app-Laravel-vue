<template>
    <!-- Table Container Card -->
    <b-card no-body>
        <div class="m-2">
            <b-row>
                <!-- Per Page -->
                <b-col
                    cols="12"
                    md="6"
                    class="d-flex align-items-center justify-content-start mb-2"
                >
                    <label style="font-weight: 700">Estado</label>
                    <v-select
                        v-model="statusFilter"
                        :options="statusOptions"
                        :clearable="false"
                        class="invoice-filter-select d-inline-block ml-50 mr-5"
                    />
                </b-col>
            </b-row>
            <!-- Table Top -->
            <b-row>
                <!-- Per Page -->
                <b-col
                    cols="12"
                    md="6"
                    class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
                >
                    <label>Entries</label>
                    <v-select
                        v-model="perPage"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="perPageOptions"
                        :clearable="false"
                        class="per-page-selector d-inline-block ml-50 mr-1"
                    />
                    <b-button
                        variant="primary"
                        :to="{ name: 'admin-turno-add' }"
                    >
                        Agregar
                    </b-button>
                </b-col>

                <!-- Search -->
                <b-col cols="12" md="6">
                    <div class="d-flex align-items-center justify-content-end">
                        <b-form-input
                            v-model="searchQuery"
                            class="d-inline-block mr-1"
                            placeholder="Buscar por nombres o dni..."
                        />
                    </div>
                </b-col>
            </b-row>
        </div>

        <b-table
            ref="refTurnoListTable"
            :items="fetchInvoices"
            responsive
            :fields="tableColumns"
            primary-key="id"
            :sort-by.sync="sortBy"
            show-empty
            empty-text="No matching records found"
            :sort-desc.sync="isSortDirDesc"
            class="position-relative"
        >
            <template #cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <!-- fechas -->
            <template #cell(fecha)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <template #cell(turno_cambio)="data">
                <span class="text-nowrap">
                    {{ data.value }} - {{ changeDate(data.item.cambio_fecha) }}
                    <br />
                    {{ data.item.cambio_ingreso }} -
                    {{ data.item.cambio_salida }}
                </span>
            </template>
            <template #cell(turno_origen)="data">
                <span class="text-nowrap">
                    {{ data.value }} - {{ changeDate(data.item.origen_fecha) }}
                    <br />
                    {{ data.item.origen_ingreso }} -
                    {{ data.item.origen_salida }}
                </span>
            </template>
            <!-- Column: Id -->
            <!-- Column: Status -->
            <template #cell(status)="data">
                <b-dropdown
                    toggle-class="p-0"
                    no-caret
                    pill
                    :variant="changeColor(data.item.status)"
                    class="text-center"
                >
                    <template #button-content>
                        <b-badge pill :variant="changeColor(data.item.status)">
                            {{ changeStatus(data.item.status) }}
                        </b-badge>
                    </template>

                    <b-dropdown-item :disabled="data.item.status == 1">
                        <feather-icon class="text-success" icon="CheckIcon" />
                        <span
                            @click="updateStatus(1, data.item.id)"
                            class="align-middle ml-50"
                            >Aprobar</span
                        >
                    </b-dropdown-item>
                    <b-dropdown-item :disabled="data.item.status == 0">
                        <feather-icon
                            class="text-warning"
                            icon="PauseCircleIcon"
                        />
                        <span
                            @click="updateStatus(0, data.item.id)"
                            class="align-middle ml-50"
                            >Pendiente</span
                        >
                    </b-dropdown-item>
                    <b-dropdown-item :disabled="data.item.status == 2">
                        <feather-icon
                            class="text-danger"
                            icon="AlertTriangleIcon"
                        />
                        <span
                            @click="helper(data.item)"
                            class="align-middle ml-50"
                            v-b-modal.modal-obs
                            >Observar</span
                        >
                    </b-dropdown-item>
                    <b-dropdown-item :disabled="data.item.status == 3">
                        <feather-icon
                            class="text-secondary"
                            icon="DeleteIcon"
                        />
                        <span
                            @click="updateStatus(3, data.item.id)"
                            class="align-middle ml-50"
                            >Anular</span
                        >
                    </b-dropdown-item>
                </b-dropdown>
            </template>

            <!-- Column: Actions -->
            <template #cell(actions)="data">
                <div class="text-nowrap">
                    <feather-icon
                        :id="`invoice-row-${data.item.id}-preview-icon`"
                        icon="EyeIcon"
                        v-on:click="setModalData(data.item)"
                        v-b-modal.modal-lg
                        size="16"
                        class="cursor-pointer text-info"
                    />
                    <b-tooltip
                        title="Detalles"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
                    />
                    <feather-icon
                        @click="
                            $router.push({
                                name: 'admin-turno-edit',
                                params: { turnoId: data.item.id },
                            })
                        "
                        :id="`invoice-row-${data.item.id}-edit-icon`"
                        icon="EditIcon"
                        class="mx-1 cursor-pointer text-success"
                        size="16"
                    />

                    <b-tooltip
                        title="Editar Papeleta"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.id}-edit-icon`"
                    />
                    <feather-icon
                        :id="`invoice-row-${data.item.id}-delete-icon`"
                        icon="Trash2Icon"
                        class="cursor-pointer text-danger"
                        size="16"
                        @click="confirmDelete(data.item.id)"
                    />
                    <b-tooltip
                        title="Eliminar Papeleta"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.id}-delete-icon`"
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
                    <span class="text-muted"
                        >Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
                        {{ dataMeta.of }} entries</span
                    >
                </b-col>
                <!-- Pagination -->
                <b-col
                    cols="12"
                    sm="6"
                    class="d-flex align-items-center justify-content-center justify-content-sm-end"
                >
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="totalInvoices"
                        :per-page="perPage"
                        first-number
                        last-number
                        class="mb-0 mt-1 mt-sm-0"
                        prev-class="prev-item"
                        next-class="next-item"
                    >
                        <template #prev-text>
                            <feather-icon icon="ChevronLeftIcon" size="18" />
                        </template>
                        <template #next-text>
                            <feather-icon icon="ChevronRightIcon" size="18" />
                        </template>
                    </b-pagination>
                </b-col>
            </b-row>
        </div>
        <b-modal
            id="modal-lg"
            centered
            hide-footer
            size="xl"
            :title="'Papeleta número: ' + modalData.nro_papeleta"
        >
            <b-card-body>
                <p class="text-danger text-center" style="font-weigth: 900">
                    Recuerde registrar el número de cambio de turno, en el
                    formato físico, para su presentación en la oficina de
                    Recursos Humanos
                </p>
                <b-row>
                    <b-col md="6">
                        <b-row>
                            <b-col md="6" class="m-0 p-0">
                                <b-list-group>
                                    <b-list-group-item
                                        >Número de cambio de
                                        turno</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Nombre del jefe</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Solicitante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Fecha turno
                                        solicitante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Aceptante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Fecha turno
                                        aceptante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Motivo</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Creado por</b-list-group-item
                                    >
                                </b-list-group>
                            </b-col>
                            <b-col md="6" class="m-0 p-0">
                                <b-list-group>
                                    <b-list-group-item
                                        ><b-badge variant="secondary">{{
                                            modalData.nro_papeleta
                                        }}</b-badge></b-list-group-item
                                    >
                                    <b-list-group-item>{{
                                        modalData.nombre_jefe
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.solicitante_nombre
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.fecha_turno_solicitante
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.aceptante_nombre
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.fecha_turno_aceptante
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.motivo
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.creado_por
                                    }}</b-list-group-item>
                                </b-list-group>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col md="6">
                        <b-row>
                            <b-col md="6" class="m-0 p-0">
                                <b-list-group>
                                    <b-list-group-item
                                        >Fecha de generacion</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Nombre de servicio</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Turno solicitante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Hora Ingreso -
                                        Salida</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Turno Aceptante</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Hora Ingreso -
                                        Salida</b-list-group-item
                                    >
                                    <b-list-group-item
                                        >Estado</b-list-group-item
                                    >
                                </b-list-group>
                            </b-col>
                            <b-col md="6" class="m-0 p-0">
                                <b-list-group>
                                    <b-list-group-item
                                        >{{ modalData.fecha_generacion }}
                                    </b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.nombre_servicio
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.turno_solicitante
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.hora_ingreso_salida_solicitante
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.turno_aceptante
                                    }}</b-list-group-item>
                                    <b-list-group-item>{{
                                        modalData.hora_ingreso_salida_aceptante
                                    }}</b-list-group-item>
                                    <b-list-group-item>
                                        <b-badge
                                            :variant="
                                                changeColor(modalData.estado)
                                            "
                                            >{{
                                                changeStatus(modalData.estado)
                                            }}</b-badge
                                        >
                                    </b-list-group-item>
                                </b-list-group>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </b-card-body>
        </b-modal>
        <b-modal
            ref="my-modal-obs"
            id="modal-obs"
            centered
            hide-footer
            title="Observar cambio de turno"
        >
            <b-card-body>
                <validation-observer ref="observerForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="observar"
                    >
                        <b-col md="12">
                            <b-form-input hidden name="register-name" />
                            <b-form-group>
                                <label>Motivo de observacion</label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="motivo de observacion"
                                >
                                    <v-select
                                        label="name"
                                        :options="parameters"
                                        v-model="selectedObs"
                                        name="users"
                                        placeholder="Seleccione"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <!-- <b-col md="12">
                            <b-form-checkbox
                                class="mt-1"
                                v-model="emailPersonal"
                                v-bind:true-value="1"
                                v-bind:false-value="0"
                            >
                                Notificar por email al empleado
                            </b-form-checkbox>
                        </b-col> -->
                        <hr />
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="observar"
                            >
                                Registrar
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-card-body>
        </b-modal>
    </b-card>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import {
    BForm,
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    BCardText,
    BCardBody,
    BListGroup,
    BFormGroup,
    BListGroupItem,
    BFormCheckboxGroup,
    BFormCheckbox,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { avatarText } from "@core/utils/filter";
import vSelect from "vue-select";
import { onUnmounted } from "@vue/composition-api";
import store from "@/store";
import useTurnoList from "./useTurnoList";
import turnoStoreModule from "./turnoStoreModule";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import { parameter_pluck } from "@/helpers/index.js";

export default {
    components: {
        BForm,
        BListGroup,
        BListGroupItem,
        BCardText,
        BCardBody,
        BCard,
        BRow,
        BCol,
        BFormInput,
        BButton,
        BTable,
        BMedia,
        BAvatar,
        BLink,
        BBadge,
        BDropdown,
        BDropdownItem,
        BPagination,
        BTooltip,
        BFormGroup,
        vSelect,
        BFormCheckboxGroup,
        BFormCheckbox,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            turnoId: "",
            //observacion
            selectedObs: "",
            cambioTurnoIdObs: "",
            parameters: [],
            modalObserver: {
                nombres: "",
                permiso: "",
                fecha_generacion: "",
                dni: "",
                id: "",
            },
            modalData: {
                nro_papeleta: "",
                nombre_jefe: "",
                solicitante_nombre: "",
                fecha_turno_solicitante: "-",
                aceptante_nombre: "",
                fecha_turno_aceptante: "-",
                motivo: "",
                creado_por: "",
                fecha_generacion: "",
                nombre_servicio: "",
                turno_solicitante: "",
                hora_ingreso_salida_solicitante: "",
                turno_aceptante: "",
                hora_ingreso_salida_aceptante: "",
                estado: "",
            },
        };
    },
    mounted() {
        // Set the initial number of items
        this.refreshStatus = 1;
    },

    methods: {
        observar() {
            this.$refs.observerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post(
                            "/api/auth/cambios_turno/obs/" +
                                this.cambioTurnoIdObs,
                            {
                                observacion_id: this.selectedObs.id,
                            }
                        )
                        .then(() => {
                            this.refreshStatus = 1;
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Observacion registrado correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.$refs["my-modal-obs"].hide();
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$refs.observerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
        helper(data) {
            this.$http
                .get("/api/auth/parameter/" + "obs-cambio_turno")
                .then((response) => {
                    this.parameters = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            this.cambioTurnoIdObs = data.id;
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
                        .post("/api/auth/cambios_turno/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Documento eliminado",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            // console.log(res);
                            this.refreshStatus = 1;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
        setModalData(data) {
            this.modalData.nro_papeleta = data.nro_papeleta;
            this.modalData.nombre_jefe = data.nombre_jefe;
            this.modalData.solicitante_nombre = data.solicitante_nombre;
            this.modalData.fecha_turno_solicitante = data.cambio_fecha;
            this.modalData.aceptante_nombre = data.aceptante_nombre;
            this.modalData.fecha_turno_aceptante = data.origen_fecha;
            this.modalData.motivo = data.motivo;
            this.modalData.creado_por = "-";
            //segudno
            this.modalData.fecha_generacion = data.fecha;
            this.modalData.nombre_servicio = data.nombre_servicio;
            this.modalData.turno_solicitante = data.turno_origen;
            this.modalData.hora_ingreso_salida_solicitante =
                data.origen_ingreso + "-" + data.origen_salida;
            this.modalData.turno_aceptante = data.turno_cambio;
            this.modalData.hora_ingreso_salida_aceptante =
                data.cambio_ingreso + "-" + data.cambio_salida;
            this.modalData.estado = data.status;
        },
        changeDate(dato) {
            return moment(String(dato)).format("MM/DD/YYYY");
        },
        changeStatus(dato) {
            if (dato == "0") return "Pendiente";
            if (dato == "1") return "Aprobado";
            if (dato == "2") return "Observado";
            if (dato == "3") return "Anulado";
        },
        changeColor(dato) {
            if (dato == "0") return "warning";
            if (dato == "1") return "success";
            if (dato == "2") return "danger";
            if (dato == "3") return "secondary";
        },
        updateStatus(status, id) {
            this.$http
                .post("/api/auth/cambios_turno/updateStatus/" + id, {
                    status: status,
                })
                .then((res) => {
                    // console.log(res);
                    this.refreshStatus = 1;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    setup() {
        const TURNO_APP_STORE_MODULE_NAME = "app-turno";

        // Register module
        if (!store.hasModule(TURNO_APP_STORE_MODULE_NAME))
            store.registerModule(TURNO_APP_STORE_MODULE_NAME, turnoStoreModule);
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(TURNO_APP_STORE_MODULE_NAME))
                store.unregisterModule(TURNO_APP_STORE_MODULE_NAME);
        });

        const statusOptions = ["Pendientes", "Procesados"];
        const typeOptions = [
            "PARTICULAR",
            "COMISION DE SERVICIO",
            "COMPENSACION HORAS EXTRAS",
            "A CUENTA DE VACACIONES",
            "ONOMASTICO",
            "LUTO",
            "PATERNIDAD",
            "OTROS",
        ];

        const {
            fetchInvoices,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refTurnoListTable,

            statusFilter,
            typeFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,
        } = useTurnoList();

        return {
            fetchInvoices,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refTurnoListTable,

            statusFilter,
            typeFilter,

            typeOptions,
            statusOptions,

            refetchData,

            avatarText,
            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,

            refreshStatus,
        };
    },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}

.invoice-filter-select {
    min-width: 190px;

    ::v-deep .vs__selected-options {
        flex-wrap: nowrap;
    }

    ::v-deep .vs__selected {
        width: 100px;
    }
}
</style>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-sweetalert.scss";
</style>
