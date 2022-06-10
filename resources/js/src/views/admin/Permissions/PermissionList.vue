<template>
    <b-card-code title="Permisos" no-body>
        <b-card-body>
            <div
                class="d-flex justify-content-between flex-wrap mb-2"
                v-if="$can('roles-create', 'ACL')"
            >
                <b-button
                    variant="primary"
                    :to="{ name: 'admin-permission-add' }"
                >
                    Agregar Permiso
                </b-button>
            </div>
            <div class="d-flex justify-content-between flex-wrap">
                <!-- sorting  -->
                <b-form-group
                    label="Ordenar"
                    label-size="sm"
                    label-align-sm="left"
                    label-cols-sm="2"
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
                            placeholder="Escriba para buscar"
                        />
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">
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
            :items="items"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
        >
            <template #cell(action)="data">
                <div class="text-nowrap">
                    <feather-icon
                        :id="`user-row-${data.item.id}-send-icon`"
                        @click="
                            $router.push({
                                name: 'admin-permission-edit',
                                params: { permisoId: data.item.id },
                            })
                        "
                        icon="EditIcon"
                        class="cursor-pointer text-primary"
                        size="16"
                    />

                    <b-tooltip
                        title="Editar Permiso"
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
                        title="Eliminar Permiso"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
                    />
                </div>
            </template>
        </b-table>

        <b-card-body class="d-flex justify-content-between flex-wrap pt-0">
            <!-- page length -->
            <b-form-group
                label="Per Page"
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
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import {
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

export default {
    components: {
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
        BTooltip,
    },
    data() {
        return {
            permisoId: 0,
            items: [],
            perPage: 5,
            pageOptions: [3, 5, 10],
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

                { key: "description", label: "Descripcion", sortable: true },
                { key: "action", label: "action" },
            ],
            /* eslint-disable global-require */
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
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    created() {
        // this.row = this.tableBasic;
        this.$http.get("/api/auth/permission/").then((res) => {
            console.log(res);
            this.items = res.data;
            this.totalRows = this.items.length;
        });
    },
};
</script>
