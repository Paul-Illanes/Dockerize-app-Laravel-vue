<template>
    <!-- Table Container Card -->
    <b-card no-body>
        <div class="m-2">
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
                        v-if="$can('legajos_informes_cese-create', 'ACL')"
                        variant="primary"
                        :to="{ name: 'admin-legajocese-add' }"
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
            ref="refLegajosCeseListTable"
            :items="fetchCeses"
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
            <template #cell(ultimo_dia_labor)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <template #cell(fecha_cese)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <template #cell(PDF)="data">
                <div class="text-nowrap">
                    <b-button
                        v-if="data.item.path_informe"
                        variant="gradient-danger"
                        class="btn-icon rounded-circle"
                        v-on:click="verInforme(data.item)"
                        v-b-modal.modal-obs
                    >
                        <feather-icon icon="FileIcon" />
                    </b-button>
                    <b-button
                        variant="gradient-warning"
                        class="btn-icon rounded-circle"
                        v-on:click="generarInforme(data.item)"
                    >
                        <feather-icon icon="SendIcon" />
                    </b-button>
                    <!-- <b-button
                        v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                        variant="flat-success"
                        class="btn-icon"
                    >
                        <feather-icon icon="SendIcon" />
                        G
                    </b-button> -->
                    <!-- <feather-icon
                        :id="`pdf-${data.item.id}-informe`"
                        icon="FileIcon"
                        v-on:click="generarInforme(data.item)"
                        size="16"
                        class="cursor-pointer text-warning"
                    />
                    <b-tooltip
                        title="Generar Informe"
                        :target="`pdf-${data.item.id}-informe`"
                    />

                    <feather-icon
                        v-if="data.item.path_informe"
                        :id="`view-${data.item.id}-pdf`"
                        icon="EyeIcon"
                        v-b-modal.modal-obs
                        v-on:click="verInforme(data.item)"
                        size="16"
                        class="cursor-pointer text-info"
                    />
                    <b-tooltip
                        title="Ver pdf"
                        :target="`view-${data.item.id}-pdf`"
                    /> -->
                </div>
            </template>
            <!-- Column: Id -->
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
                    <!-- <b-tooltip
                        title="Detalles"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
                    /> -->
                    <feather-icon
                        v-if="$can('legajos_informes_cese-edit', 'ACL')"
                        @click="
                            $router.push({
                                name: 'admin-legajocese-edit',
                                params: { legajoId: data.item.id },
                            })
                        "
                        :id="`invoice-row-${data.item.id}-edit-icon`"
                        icon="EditIcon"
                        class="mx-1 cursor-pointer text-success"
                        size="16"
                    />

                    <!-- <b-tooltip
                        title="Editar"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.id}-edit-icon`"
                    /> -->
                    <feather-icon
                        v-if="$can('legajos_informes_cese-edit', 'ACL')"
                        :id="`invoice-row-${data.item.id}-delete-icon`"
                        icon="Trash2Icon"
                        class="cursor-pointer text-danger"
                        size="16"
                        @click="confirmDelete(data.item.id)"
                    />
                    <!-- <b-tooltip
                        title="Eliminar Papeleta"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.id}-delete-icon`"
                    /> -->
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
            title="Personal informe ceses"
        >
            <b-card-body>
                <b-row>
                    <b-col md="12">
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Nombres</dt>
                            <dd class="col-sm-8">
                                {{ modalData.nombre }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">DNI</dt>
                            <dd class="col-sm-8">
                                {{ modalData.dni }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Codigo planilla</dt>
                            <dd class="col-sm-8">
                                {{ modalData.codigo_planilla }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Fecha de nacimiento
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_nacimiento) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Fecha de ingreso
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_ingreso) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Ultimo dia labor
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.ultimo_dia_labor) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Fecha cese</dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_cese) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Regimen laboral</dt>
                            <dd class="col-sm-8">
                                {{ modalData.regimen_laboral }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Grupo Ocupacional
                            </dt>
                            <dd class="col-sm-8">
                                {{ grupo_ocupacional }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Numero de documento con el cual se cesa al
                                trabajador o funcionario
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.numero_documento_cese }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Motivo cese</dt>
                            <dd class="col-sm-8">
                                {{ modalData.motivo_cese }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Regimen Pensionario
                            </dt>
                            <dd class="col-sm-8">
                                {{ regimen_pensionario }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Linea Carrera</dt>
                            <dd class="col-sm-8">
                                {{ linea_carrera }}
                            </dd>
                        </dl>

                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Condicion Laboral
                            </dt>
                            <dd class="col-sm-8">
                                {{ condicion_laboral }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Modalidad Contrato
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalidad_contrato }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Dependencia</dt>
                            <dd class="col-sm-8">
                                {{ dependencia }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Licencias S/G. haber
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.licencia_sg_haber }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Sancion Disciplinaria
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.sancion_disciplinaria }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Licencia C/G/H COVID 19
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.licencia_covid }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Permisos Particulares
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.permisos_particulares }}
                            </dd>
                        </dl>

                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                A Cuenta Vacaciones
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.acuenta_vacaciones }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Tiempo servicio</dt>
                            <dd class="col-sm-8">
                                {{ modalData.tiempo_servicio }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Total Tpo. Deducible
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.total_tpo_deducible }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Total Tpo. serv. a EsSalud
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.total_tpo_essalud }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">NIT</dt>
                            <dd class="col-sm-8">
                                {{ modalData.nit }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Numero Informe</dt>
                            <dd class="col-sm-8">
                                {{ modalData.numero_informe }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Fecha Informe</dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_informe) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Path informe</dt>
                            <dd class="col-sm-8">
                                {{ modalData.path_informe }}
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
            size="lg"
            title="Previzualizar informe"
            @hide="limpiar_archivo"
        >
            <embed
                :src="archivo"
                frameborder="0"
                width="100%"
                height="800px"
                class="p-0 m-0"
            />
        </b-modal>
    </b-card>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import {
    BImg,
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
import useLegajosCeseList from "./useLegajosCeseList";
import legajosCeseStoreModule from "./LegajosCeseStoreModule";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import { parameter_pluck } from "@/helpers/index.js";
import axios from "axios";
export default {
    components: {
        BImg,
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
            archivo: "",
            legajoId: "",
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
            //variables de modal
            grupo_ocupacional: "",
            regimen_laboral: "",
            regimen_pensionario: "",
            linea_carrera: "",
            condicion_laboral: "",
            modalidad_contrato: "",
            dependencia: "",
        };
    },
    mounted() {
        // Set the initial number of items
        this.refreshStatus = 1;
    },

    methods: {
        limpiar_archivo() {
            this.archivo = "";
        },
        parameterName() {
            this.$http
                .get("/api/auth/legajo_cese/parameterName")
                .then((response) => {
                    return response.data.value;

                    // this.users = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getModalDetail(data) {
            this.$http
                .post("/api/auth/legajo_cese/modalName", {
                    grupo_ocupacional: data.grupo_ocupacional,
                    regimen_laboral: data.regimen_laboral,
                    regimen_pensionario: data.regimen_pensionario,
                    linea_carrera: data.linea_carrera,
                    condicion_laboral: data.condicion_laboral,
                    modalidad_contrato: data.modalidad_contrato,
                    dependencia: data.dependencia,
                })
                .then((response) => {
                    this.grupo_ocupacional = response.data.grupo_ocupacional[0];
                    this.regimen_laboral = response.data.regimen_laboral[0];
                    this.regimen_pensionario =
                        response.data.regimen_pensionario[0];
                    this.linea_carrera = response.data.linea_carrera[0];
                    this.condicion_laboral = response.data.condicion_laboral[0];
                    this.modalidad_contrato =
                        response.data.modalidad_contrato[0];
                    this.dependencia = response.data.dependencia[0];
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
                            this.$refs.observerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },

        generarInforme(data) {
            this.$http
                .post("/api/auth/legajo_cese/generar_informe", {
                    informe_id: data.id,
                })
                .then((response) => {
                    this.refreshStatus = 1;
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "Informe generado",
                            icon: "CoffeeIcon",
                            variant: "success",
                        },
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        verInforme(name) {
            this.$http
                .get("/api/auth/legajo_cese/file/" + name.path_informe, {
                    responseType: "blob", // important
                })
                .then((response) => {
                    // Service that handles ajax call
                    this.archivo = window.URL.createObjectURL(
                        new Blob([response.data], { type: "application/pdf" })
                    );

                    // window.open(url);
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
                        .post("/api/auth/informe_cese/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Accion realizada",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `informe de cese eliminado correctamente`,
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
        const LEGAJO_CESE_APP_STORE_MODULE_NAME = "app-legajo-cese";

        // Register module
        if (!store.hasModule(LEGAJO_CESE_APP_STORE_MODULE_NAME))
            store.registerModule(
                LEGAJO_CESE_APP_STORE_MODULE_NAME,
                legajosCeseStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(LEGAJO_CESE_APP_STORE_MODULE_NAME))
                store.unregisterModule(LEGAJO_CESE_APP_STORE_MODULE_NAME);
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
            fetchCeses,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refLegajosCeseListTable,

            statusFilter,
            typeFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,
        } = useLegajosCeseList();

        return {
            fetchCeses,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refLegajosCeseListTable,

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
