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
                        :to="{ name: 'admin-papeletas-add' }"
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
                        <v-select
                            v-model="typeFilter"
                            :options="typeOptions"
                            class="invoice-filter-select d-inline-block ml-50 w-100"
                            placeholder="Filtrar por Tipo de permiso"
                        >
                            <template #selected-option="{ label }">
                                <span class="text-truncate overflow-hidden">
                                    {{ label }}
                                </span>
                            </template>
                        </v-select>
                    </div>
                </b-col>
            </b-row>
        </div>

        <b-table
            ref="refInvoiceListTable"
            :items="fetchInvoices"
            responsive
            :fields="tableColumns"
            primary-key="id"
            :sort-by.sync="sortBy"
            show-empty
            empty-text="No matching records found"
            :sort-desc.sync="isSortDirDesc"
            :refresh="refreshStatus"
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
            <template #cell(fecha_salida)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
                </span>
            </template>
            <template #cell(fecha_retorno)="data">
                <span class="text-nowrap">
                    {{ changeDate(data.value) }}
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
                            @click="updateStatus(2, data.item.id)"
                            class="align-middle ml-50"
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
                                name: 'admin-papeleta-edit',
                                params: { papeletaId: data.item.id },
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
            <b-modal
                id="modal-lg"
                centered
                hide-footer
                size="lg"
                :title="'Papeleta número: ' + modalData.nro_papeleta"
            >
                <b-card-body>
                    <p class="text-danger text-center" style="font-weigth: 900">
                        Recuerde registrar el número de papeleta, en el formato
                        físico, para su presentación en la oficina de Recursos
                        Humanos
                    </p>
                    <b-row>
                        <b-col md="6" class="m-0 p-0">
                            <b-list-group>
                                <b-list-group-item
                                    >Número de papeleta</b-list-group-item
                                >
                                <b-list-group-item>Nombres</b-list-group-item>
                                <b-list-group-item
                                    >Fecha de generación</b-list-group-item
                                >
                                <b-list-group-item
                                    >Tipo de papeleta</b-list-group-item
                                >
                                <b-list-group-item
                                    >Fecha de salida</b-list-group-item
                                >
                                <b-list-group-item
                                    >Hora de salida</b-list-group-item
                                >
                                <b-list-group-item
                                    >Fecha de retorno</b-list-group-item
                                >
                                <b-list-group-item
                                    >Hora de retorno</b-list-group-item
                                >
                                <b-list-group-item>Estado</b-list-group-item>
                                <b-list-group-item
                                    >Creado por</b-list-group-item
                                >
                            </b-list-group>
                        </b-col>
                        <b-col md="6" class="m-0 p-0">
                            <b-list-group>
                                <b-list-group-item
                                    ><b-badge variant="secondary"
                                        >V-{{ modalData.nro_papeleta }}</b-badge
                                    ></b-list-group-item
                                >
                                <b-list-group-item>{{
                                    modalData.nombres
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.fecha_generacion
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.tipo_papeleta
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.fecha_salida
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.hora_salida
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.fecha_retorno
                                }}</b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.hora_retorno
                                }}</b-list-group-item>
                                <b-list-group-item>
                                    <b-badge
                                        :variant="changeColor(modalData.estado)"
                                        >{{
                                            changeStatus(modalData.estado)
                                        }}</b-badge
                                    >
                                </b-list-group-item>
                                <b-list-group-item>{{
                                    modalData.creado_por
                                }}</b-list-group-item>
                            </b-list-group>
                        </b-col>
                    </b-row>
                </b-card-body>
            </b-modal>
        </div>
    </b-card>
</template>

<script>
import {
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
    BListGroupItem,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { avatarText } from "@core/utils/filter";
import vSelect from "vue-select";
import { onUnmounted } from "@vue/composition-api";
import store from "@/store";
import usePapeletaList from "./usePapeletaList";
import papeletaStoreModule from "./papeletaStoreModule";
// import moment from "moment";
import Ripple from "vue-ripple-directive";

export default {
    components: {
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

        vSelect,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            papeletaID: "",
            modalData: {
                nro_papeleta: "",
                nombres: "",
                fecha_generacion: "",
                tipo_papeleta: "",
                fecha_salida: "",
                hora_salida: "-",
                fecha_retorno: "",
                hora_retorno: "-",
                estado: "",
                creado_por: "",
            },
        };
    },
    mounted() {
        // Set the initial number of items
        this.refreshStatus = 1;
    },
    methods: {
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
                        .post("/api/auth/papeleta/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Accion realizada",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Papeleta eliminada correctamente`,
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
            this.modalData.nombres = data.nombres;
            this.modalData.fecha_generacion = data.fecha;
            this.modalData.tipo_papeleta = data.tipo_permiso;
            this.modalData.fecha_salida = data.fecha_salida;
            this.modalData.hora_salida =
                data.hora_salida != null ? data.hora_salida : "-";
            this.modalData.fecha_retorno = data.fecha_retorno;
            this.modalData.hora_retorno =
                data.hora_retorno != null ? data.hora_retorno : "-";
            this.modalData.estado = data.status;
            this.modalData.creado_por = data.nombres;
        },
        changeDate(dato) {
            console.log("fer");
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
                .post("/api/auth/papeleta/updateStatus/" + id, {
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
        const PAPELETA_APP_STORE_MODULE_NAME = "app-papeleta";

        // Register module
        if (!store.hasModule(PAPELETA_APP_STORE_MODULE_NAME))
            store.registerModule(
                PAPELETA_APP_STORE_MODULE_NAME,
                papeletaStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(PAPELETA_APP_STORE_MODULE_NAME))
                store.unregisterModule(PAPELETA_APP_STORE_MODULE_NAME);
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
            refInvoiceListTable,

            statusFilter,
            typeFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolveClientAvatarVariant,
            resolvePaperStatusVariant,
        } = usePapeletaList();

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
            refInvoiceListTable,

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
