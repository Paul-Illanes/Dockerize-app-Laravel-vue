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
                        v-if="$can('personas-create', 'ACL')"
                        variant="primary"
                        :to="{ name: 'admin-personas-add' }"
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
            ref="refPersonaListTable"
            :items="fetchPersona"
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
                            @click="updateStatus(1, data.item.dni)"
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
                            @click="updateStatus(0, data.item.dni)"
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
                            @click="updateStatus(3, data.item.dni)"
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
                        v-if="$can('personas-edit', 'ACL')"
                        @click="
                            $router.push({
                                name: 'admin-personas-edit',
                                params: { personaId: data.item.dni },
                            })
                        "
                        :id="`invoice-row-${data.item.dni}-edit-icon`"
                        icon="EditIcon"
                        class="mx-1 cursor-pointer text-success"
                        size="16"
                    />

                    <b-tooltip
                        title="Editar Persona"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.dni}-edit-icon`"
                    />
                    <feather-icon
                        v-if="$can('personas-destroy', 'ACL')"
                        :id="`invoice-row-${data.item.dni}-delete-icon`"
                        icon="Trash2Icon"
                        class="cursor-pointer text-danger"
                        size="16"
                        @click="confirmDelete(data.item.dni)"
                    />
                    <b-tooltip
                        title="Eliminar Persona"
                        class="cursor-pointer"
                        :target="`invoice-row-${data.item.dni}-delete-icon`"
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
import usePersonaList from "./usePersonaList";
import personaStoreModule from "./personaStoreModule";
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
            //observacion
            selectedObs: "",
            emailPersonal: "",
            papeletaIdObs: "",
            //
            personaId: "",
            parameters: [],
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
        observar() {
            this.$refs.observerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/papeleta/observar", {
                            id: this.papeletaIdObs,
                            observacion_id: this.selectedObs.id,
                            emailPersonal: this.emailPersonal,
                        })
                        .then(() => {
                            this.refreshStatus = 1;

                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Observacion registrado correctamente`,
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
                        .post("/api/auth/persona/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Accion realizada",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Persona eliminada correctamente`,
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
            return moment(String(dato)).format("MM/DD/YYYY");
        },
        changeStatus(dato) {
            if (dato == "0") return "Nuevos";
            if (dato == "1") return "Activos";
            if (dato == "2") return "Baja";
        },
        changeColor(dato) {
            if (dato == "0") return "primary";
            if (dato == "1") return "success";
            if (dato == "2") return "secondary";
        },
        updateStatus(status, id) {
            this.$http
                .post("/api/auth/persona/updateStatus/" + id, {
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
        const PERSONA_APP_STORE_MODULE_NAME = "app-persona";

        // Register module
        if (!store.hasModule(PERSONA_APP_STORE_MODULE_NAME))
            store.registerModule(
                PERSONA_APP_STORE_MODULE_NAME,
                personaStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(PERSONA_APP_STORE_MODULE_NAME))
                store.unregisterModule(PERSONA_APP_STORE_MODULE_NAME);
        });

        const statusOptions = ["Todos", "Nuevos", "Activos", "Baja"];

        const {
            fetchPersona,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refPersonaListTable,

            statusFilter,

            refetchData,
            refreshStatus,

            resolveInvoiceStatusVariantAndIcon,
            resolvePaperStatusVariant,
        } = usePersonaList();

        return {
            fetchPersona,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refPersonaListTable,

            statusFilter,
            statusOptions,

            refetchData,

            avatarText,
            resolveInvoiceStatusVariantAndIcon,
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
