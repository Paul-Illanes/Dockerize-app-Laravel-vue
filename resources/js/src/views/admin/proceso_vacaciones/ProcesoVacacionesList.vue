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
            ref="refProcesoVacacionesListTable"
            :items="fetchProcesoVacaciones"
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
            <template #cell(periodo_vacaciones)="data">
                <span class="text-nowrap">
                    {{ changePeriodo(data.value) }}
                </span>
            </template>
            <template #cell(progreso)="data">
                <b-badge v-if="data.item.id" pill variant="success">
                    {{ "OK" }}
                </b-badge>
                <b-badge v-else-if="!data.item.id" pill variant="warning">
                    {{ "Pend" }}
                </b-badge>
            </template>
            <template #cell(status)="data">
                <b-badge
                    class="cursor-pointer"
                    v-if="data.item.status"
                    pill
                    variant="danger"
                    @click="check_status(0, data.item.id)"
                >
                    {{ "Cerrado" }}
                </b-badge>
                <b-badge
                    class="cursor-pointer"
                    v-else-if="!data.item.status"
                    pill
                    variant="primary"
                    @click="check_status(1, data.item.id)"
                >
                    {{ "Abierto" }}
                </b-badge>
            </template>
            <template #cell(periodo_vacaciones)="data">
                <span class="text-nowrap">
                    {{ changePeriodo(data.value) }}
                </span>
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
import useProcesoVacacionesList from "./useProcesoVacacionesList";
import procesoVacacionesStoreModule from "./procesoVacacionesStoreModule";
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
            check: "",
        };
    },
    methods: {
        check_status(status, id) {
            this.$http
                .put("/api/auth/areas_vacaciones/updateStatus/" + id, {
                    status: status,
                })
                .then((res) => {
                    // console.log(res);
                    this.refetchData();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        changePeriodo(dato) {
            if (dato) {
                return dato + "-" + (dato + 1);
            } else {
                return null;
            }
        },
    },
    setup() {
        const PROCESO_VACACIONES_APP_STORE_MODULE_NAME =
            "app-proceso-vacaciones";

        // Register module
        if (!store.hasModule(PROCESO_VACACIONES_APP_STORE_MODULE_NAME))
            store.registerModule(
                PROCESO_VACACIONES_APP_STORE_MODULE_NAME,
                procesoVacacionesStoreModule
            );
        // UnRegister on leave
        onUnmounted(() => {
            if (store.hasModule(PROCESO_VACACIONES_APP_STORE_MODULE_NAME))
                store.unregisterModule(
                    PROCESO_VACACIONES_APP_STORE_MODULE_NAME
                );
        });

        const statusOptions = ["Todos", "Nuevos", "Activos", "Baja"];

        const {
            fetchProcesoVacaciones,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refProcesoVacacionesListTable,

            refetchData,
            resolveInvoiceStatusVariantAndIcon,
            resolvePaperStatusVariant,
        } = useProcesoVacacionesList();

        return {
            fetchProcesoVacaciones,
            tableColumns,
            perPage,
            currentPage,
            totalInvoices,
            dataMeta,
            perPageOptions,
            searchQuery,
            sortBy,
            isSortDirDesc,
            refProcesoVacacionesListTable,

            statusOptions,

            refetchData,

            avatarText,
            resolveInvoiceStatusVariantAndIcon,
            resolvePaperStatusVariant,
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
