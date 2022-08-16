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
                        variant="primary"
                        :to="{ name: 'admin-informecese-add' }"
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
            ref="refInformeCeseListTable"
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
            <template #cell(fecha_ingreso)="data">
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
                    <b-tooltip
                        title="Detalles"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
                    />
                    <feather-icon
                        @click="
                            $router.push({
                                name: 'admin-informecese-edit',
                                params: { informeId: data.item.id },
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
                            <dt class="col-sm-4 text-right">
                                Fecha de ingreso
                            </dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_ingreso) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Fecha cese</dt>
                            <dd class="col-sm-8">
                                {{ changeDate(modalData.fecha_cese) }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Faltas</dt>
                            <dd class="col-sm-8">
                                {{ modalData.faltas }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Tardanzas</dt>
                            <dd class="col-sm-8">
                                {{ modalData.tardanzas }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Licencias S/G. haber
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.licencias }}
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
                                Sancion Disciplinaria
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.sancion_disciplinaria }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Huelga</dt>
                            <dd class="col-sm-8">
                                {{ modalData.huelga }}
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
                                Vacaciones No Gozadas
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.vacaciones_no_gozadas }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Periodo de pago BONO de productividad
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.bono_pago }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Descuento de pago BONO de productividad por no
                                corresponder
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.descuento_bono_pago }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Guardias hospitalarias
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.guardias }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Horas Extras</dt>
                            <dd class="col-sm-8">
                                {{ modalData.horas_extras }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">Horas RPCT</dt>
                            <dd class="col-sm-8">
                                {{ modalData.horas_rpct }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">PCT</dt>
                            <dd class="col-sm-8">
                                {{ modalData.pct }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Notas Adicionales
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.notas_adicionales }}
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col-sm-4 text-right">
                                Zona Menor Desarrollo
                            </dt>
                            <dd class="col-sm-8">
                                {{ modalData.zona_menor_desarrollo }}
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
                            <dt class="col-sm-4 text-right">Informe PDF</dt>
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
import VuePdfApp from "vue-pdf-app";
import "vue-pdf-app/dist/icons/main.css";
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
import useInformeCeseList from "./useInformeCeseList";
import informeCeseStoreModule from "./informeCeseStoreModule";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import { parameter_pluck } from "@/helpers/index.js";
import axios from "axios";
export default {
    components: {
        VuePdfApp,
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
            informeId: "",
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
        limpiar_archivo() {
            this.archivo = "";
        },
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

        generarInforme(data) {
            this.$http
                .post("/api/auth/informe_cese/generar_informe", {
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
                .get("/api/auth/informe_cese/file/" + name.path_informe, {
                    responseType: "blob", // important
                })
                .then((response) => {
                    // Service that handles ajax call
                    this.archivo = window.URL.createObjectURL(
                        new Blob([response.data], { type: "application/pdf" })
                    );
                    console.log(response);
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
        const CESE_APP_STORE_MODULE_NAME = "app-cese";

        // Register module
        if (!store.hasModule(CESE_APP_STORE_MODULE_NAME))
            store.registerModule(
                CESE_APP_STORE_MODULE_NAME,
                informeCeseStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(CESE_APP_STORE_MODULE_NAME))
                store.unregisterModule(CESE_APP_STORE_MODULE_NAME);
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
            refInformeCeseListTable,

            statusFilter,
            typeFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,
        } = useInformeCeseList();

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
            refInformeCeseListTable,

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
