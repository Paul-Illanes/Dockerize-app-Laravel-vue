<template>
    <b-card-code title="Listado de Papeletas" no-body>
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
                    <b-button
                        v-if="$can('users-create', 'ACL')"
                        variant="primary"
                        :to="{ name: 'admin-users-add' }"
                    >
                        Agregar Papeleta
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
                {{ data.index + 1 }}
            </template>
            <template #cell(verified)="data">
                <div class="text-center p-0">
                    <b-badge
                        :variant="data.item.verified ? 'success' : 'warning'"
                        >{{ verified(data.item.verified) }}</b-badge
                    >
                </div>
            </template>
            <template #cell(active)="data">
                <div class="text-center p-0">
                    <b-badge
                        :variant="data.item.active ? 'success' : 'warning'"
                        >{{ verified(data.item.active) }}</b-badge
                    >
                </div>
            </template>
            <template #cell(action)="data">
                <div class="text-nowrap">
                    <feather-icon
                        :id="`user-row-${data.item.id}-send-icon`"
                        @click="
                            $router.push({
                                name: 'admin-users-edit',
                                params: { userId: data.item.id },
                            })
                        "
                        icon="EditIcon"
                        class="cursor-pointer text-primary"
                        size="16"
                    />
                    <b-tooltip
                        title="Editar Usuario"
                        class="cursor-pointer"
                        :target="`user-row-${data.item.id}-send-icon`"
                    />

                    <feather-icon
                        :id="`invoice-row-${data.item.id}-preview-icon`"
                        icon="TrashIcon"
                        size="16"
                        class="mx-1 cursor-pointer text-danger"
                    />
                    <b-tooltip
                        title="Eliminar Usuario"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
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
    BTooltip,
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
        BTooltip,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            show: false,
            items: [],
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

                { key: "dependencia", label: "Depenendencia", sortable: true },
                { key: "fecha", label: "Fecha", sortable: true },
                { key: "dni", label: "DNI", sortable: false },
                { key: "nombres", label: "Nombres", sortable: true },
                { key: "tipo_permiso", label: "T. permiso", sortable: false },
                { key: "fecha_salida", label: "Fecha Salida", sortable: false },
                {
                    key: "fecha_retorno",
                    label: "Fecha Retorno",
                    sortable: false,
                },
                { key: "status", label: "Estado", sortable: true },
                { key: "action", label: "Action", sortable: false },
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
    },
    created() {
        this.show = true;
        // this.row = this.tableBasic;
        this.$http.get("/api/clients/papeletas/").then((res) => {
            console.log(res.data);
            this.items = res.data;
            this.totalRows = this.items.length;
        });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
