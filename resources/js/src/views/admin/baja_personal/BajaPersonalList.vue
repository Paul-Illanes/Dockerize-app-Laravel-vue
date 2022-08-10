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
                        :to="{ name: 'admin-baja-add' }"
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
            select
            striped
            hover
            ref="refBajaPersonalListTable"
            :items="fetchBajas"
            responsive
            :fields="tableColumns"
            primary-key="id"
            :sort-by.sync="sortBy"
            show-empty
            empty-text="No matching records found"
            :sort-desc.sync="isSortDirDesc"
            class="position-relative"
        >
            <template #row-details="row">
                <b-card>
                    <b-row class="p-0 m-0">
                        <b-list-group horizontal="md">
                            <b-list-group-item
                                ><span>Periodo : </span><br />
                                {{ row.item.periodo }}</b-list-group-item
                            >
                            <b-list-group-item>
                                Motivo baja : <br />
                                {{ row.item.baja }}</b-list-group-item
                            >
                            <b-list-group-item
                                >Documento sustento : <br />
                                {{
                                    row.item.documento_sustento
                                }}</b-list-group-item
                            >
                            <b-list-group-item
                                >Archivo sustento : <br />

                                <b-button
                                    v-if="row.item.path_documento"
                                    v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                    variant="flat-success"
                                    class="btn-icon"
                                    @click="getFile(row.item.path_documento)"
                                >
                                    <feather-icon icon="FileIcon" />
                                </b-button>
                                {{ row.item.path_documento }}
                            </b-list-group-item>
                        </b-list-group>
                    </b-row>
                </b-card>
            </template>
            <template #cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <!-- fechas -->
            <template #cell(fecha_ultimo_dia)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <template #cell(fecha_cese)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <!-- Column: Id -->
            <!-- Column: Status -->
            <template #cell(status_baja)="data">
                <b-dropdown
                    toggle-class="p-0"
                    no-caret
                    pill
                    :variant="changeColor(data.item.status_baja)"
                    class="text-center"
                >
                    <template #button-content>
                        <b-badge
                            pill
                            :variant="changeColor(data.item.status_baja)"
                        >
                            {{ changeStatus(data.item.status_baja) }}
                        </b-badge>
                    </template>

                    <b-dropdown-item :disabled="data.item.status_baja == 1">
                        <feather-icon class="text-success" icon="CheckIcon" />
                        <span
                            @click="updateStatus(1, data.item.id)"
                            class="align-middle ml-50"
                            >Aprobar</span
                        >
                    </b-dropdown-item>
                    <b-dropdown-item :disabled="data.item.status_baja == 0">
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
                    <b-dropdown-item :disabled="data.item.status_baja == 2">
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
                    <b-dropdown-item :disabled="data.item.status_baja == 3">
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

            <!-- Column: Issued Date -->
            <template #cell(issuedDate)="data">
                <span class="text-nowrap">
                    {{ data.value }}
                </span>
            </template>

            <!-- Column: Actions -->
            <template #cell(actions)="data">
                <div class="text-nowrap">
                    <feather-icon
                        @click="data.toggleDetails"
                        :id="`row-${data.item.id}-mas-icon`"
                        :icon="data.detailsShowing ? 'MinusIcon' : 'PlusIcon'"
                        :class="
                            data.detailsShowing
                                ? 'mx-1 cursor-pointer text-danger'
                                : 'mx-1 cursor-pointer text-success'
                        "
                        size="16"
                    />

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
                                name: 'admin-baja-edit',
                                params: { bajaId: data.item.id },
                            })
                        "
                        :id="`invoice-row-${data.item.id}-edit-icon`"
                        icon="EditIcon"
                        class="mx-1 cursor-pointer text-success"
                        size="16"
                    />

                    <b-tooltip
                        title="Editar"
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
            size="lg"
            title="Detalle Personal Baja"
        >
            <b-card-body>
                <b-row>
                    <b-col md="12">
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Nombres</dt>
                            <dd class="col-sm-8">
                                {{ modalData.nombres }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">DNI</dt>
                            <dd class="col-sm-8">
                                {{ modalData.dni }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Fecha de nacimiento
                            </dt>
                            <dd class="col-sm-8">
                                {{ fecha_nacimiento }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Codigo planilla</dt>
                            <dd class="col-sm-8">
                                {{ modalData.cod_planilla }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Regimen Laboral</dt>
                            <dd class="col-sm-8">
                                {{ modalData.c_l }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Cargo</dt>
                            <dd class="col-sm-8">
                                {{ modalData.cargo }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Nivel</dt>
                            <dd class="col-sm-8">
                                {{ modalData.nivel }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Grupo</dt>
                            <dd class="col-sm-8">
                                {{ modalData.grupo }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Dependencia</dt>
                            <dd class="col-sm-8">
                                {{ modalData.dependencia }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Fecha ingreso</dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_ingreso) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Estado</dt>
                            <dd class="col-sm-8">
                                <b-badge
                                    :variant="
                                        changeColor(modalData.status_baja)
                                    "
                                    >{{
                                        changeStatus(modalData.status_baja)
                                    }}</b-badge
                                >
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Archivo</dt>
                            <dd class="col-sm-8">
                                {{ modalData.path_documento }}
                            </dd>
                        </dl>

                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Periodo Movimiento planilla
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.periodo }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Fecha ultimo dia
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_ultimo_dia) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Fecha cese</dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_cese) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Motivo baja</dt>
                            <dd class="col-sm-8">
                                {{ modalData.baja }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">NIT</dt>
                            <dd class="col-sm-8">
                                {{ modalData.nit }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Posicion</dt>
                            <dd class="col-sm-8">
                                {{ modalData.posicion }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Plaza</dt>
                            <dd class="col-sm-8">
                                {{ modalData.plaza }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Origen documento
                            </dt>
                            <dd class="col-sm-8">
                                {{ origen_dependencia }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Tipo documento</dt>
                            <dd class="col-sm-8">
                                {{ tipo_documento }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Numero documento
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.numero_documento }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Fecha documento sustento
                            </dt>
                            <dd class="col-sm-8">
                                {{
                                    changeDate(
                                        modalData.fecha_documento_sustento
                                    )
                                }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Documento sustento
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.documento_sustento }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Observacion</dt>
                            <dd class="col-sm-8">
                                {{ modalData.observacion }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Creacion - fecha
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.created_at) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Creacion - usuario
                            </dt>
                            <dd class="col-sm-8">
                                {{ created_by }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                ultima modificacion - fecha
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.updated_at) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                ultima modificacion - usuario
                            </dt>
                            <dd class="col-sm-8">
                                {{ updated_by }}
                            </dd>
                        </dl>
                    </b-col>
                </b-row>
            </b-card-body>
        </b-modal>
        <b-modal
            ref="my-modal-obs"
            id="modal-obs"
            centered
            hide-footer
            title="Observar baja personal"
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
import useBajaPersonalList from "./useBajaPersonalList";
import bajaPersonalStoreModule from "./bajaPersonalStoreModule";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import { parameter_pluck } from "@/helpers/index.js";
import axios from "axios";
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
            bajaId: "",
            //observacion
            selectedObs: "",
            emailPersonal: "",
            bajaIdObs: "",
            //
            file: "",
            parameters: [],
            modalData: "",
            fecha_nacimiento: "",
            created_by: "",
            updated_by: "",
            tipo_documento: "",
            origen_dependencia: "",
        };
    },
    mounted() {
        // Set the initial number of items
        this.refreshStatus = 1;
    },

    methods: {
        getModalDetail(data) {
            this.$http
                .post("/api/auth/personal_bajas/modal", {
                    fecha_nacimiento: data.dni,
                    origen_dependencia_id: data.origen_dependencia_id,
                    tipo_documento_id: data.tipo_documento_id,
                    created_by: data.created_by,
                    updated_by: data.updated_by,
                })
                .then((response) => {
                    this.fecha_nacimiento = response.data.fecha_nacimiento[0];
                    this.updated_by = response.data.updated_by[0];
                    this.created_by = response.data.created_by[0];
                    this.tipo_documento = response.data.tipo_documento[0];
                    this.origen_dependencia =
                        response.data.dependencia_origen[0];
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        observar() {
            this.$refs.observerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post(
                            "/api/auth/personal_bajas/obs/" + this.bajaIdObs,
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
                .get("/api/auth/parameter/" + "obs-papeletas")
                .then((response) => {
                    this.parameters = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            this.bajaIdObs = data.id;
        },
        getFile(name) {
            this.$http
                .get("/api/auth/personal_bajas/file/" + name, {
                    responseType: "blob", // important
                })
                .then((response) => {
                    // Service that handles ajax call
                    const url = window.URL.createObjectURL(
                        new Blob([response.data], { type: "application/pdf" })
                    );
                    window.open(url);
                })
                .catch((error) => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "No se encontro el archivo",
                            icon: "CoffeeIcon",
                            variant: "danger",
                        },
                    });
                });
        },
        confirmDelete(id) {
            console.log(id);
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
                        .post("/api/auth/personal_bajas/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Accion realizada",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Baja personal eliminada correctamente`,
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
            this.getModalDetail(data);
            this.modalData = data;

            console.log(this.modalData);
            // this.modalData.nro_papeleta = data.nro_papeleta;
            // this.modalData.nombres = data.nombres;
            // this.modalData.fecha_generacion = data.fecha;
            // this.modalData.tipo_papeleta = data.tipo_permiso;
            // this.modalData.fecha_salida = data.fecha_salida;
            // this.modalData.hora_salida =
            //     data.hora_salida != null ? data.hora_salida : "-";
            // this.modalData.fecha_retorno = data.fecha_retorno;
            // this.modalData.hora_retorno =
            //     data.hora_retorno != null ? data.hora_retorno : "-";
            // this.modalData.estado = data.status;
            // this.modalData.creado_por = data.nombres;
        },
        changeDate(dato) {
            return moment(String(dato)).format("DD/MM/YYYY");
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
                .post("/api/auth/personal_bajas/updateStatus/" + id, {
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
        const BAJA_APP_STORE_MODULE_NAME = "app-baja";

        // Register module
        if (!store.hasModule(BAJA_APP_STORE_MODULE_NAME))
            store.registerModule(
                BAJA_APP_STORE_MODULE_NAME,
                bajaPersonalStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(BAJA_APP_STORE_MODULE_NAME))
                store.unregisterModule(BAJA_APP_STORE_MODULE_NAME);
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
            fetchBajas,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refBajaPersonalListTable,

            statusFilter,
            typeFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,
        } = useBajaPersonalList();

        return {
            fetchBajas,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refBajaPersonalListTable,

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
