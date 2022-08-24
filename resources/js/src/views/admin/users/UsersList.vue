<template>
    <b-card-code title="Listado de usuarios" no-body>
        <!-- <b-card-body>
            <div
                class="d-flex justify-content-between flex-wrap mb-2"
                v-if="$can('users-create', 'ACL')"
            >
                <b-button variant="primary" :to="{ name: 'admin-users-add' }">
                    Add User
                </b-button>
            </div>
            <div class="d-flex justify-content-between flex-wrap">
                
                <b-form-group
                    label="Ordenar"
                    label-size="sm"
                    label-align-sm="left"
                    label-cols-sm="4"
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
                            <b-button :disabled="!filter" @click="filter = ''">
                                Limpiar
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </div>
        </b-card-body> -->

        <div class="m-2">
            <b-row>
                <!-- Per Page --

                <!-- Search -->
                <b-col cols="12" md="6"> </b-col>
            </b-row>
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
        <b-overlay :show="show" opacity="0.40" variant="success" blur="2px">
            <template #overlay>
                <div class="text-center text-info">
                    <feather-icon icon="ClockIcon" size="24" />
                    <b-card-text id="cancel-label">
                        Cargando datos...
                    </b-card-text>
                </div>
            </template>
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
                        <!-- page length -->
                        <!-- <b-form-group
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
                </b-form-group> -->

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
        </b-overlay>
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

                { key: "name", label: "Nombre", sortable: true },
                { key: "lastname", label: "Apellidos", sortable: true },
                { key: "email", label: "Email", sortable: true },
                { key: "pin", label: "pin", sortable: true },
                { key: "roles[0].name", label: "Rol", sortable: true },
                { key: "action", label: "Action", sortable: true },
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
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    created() {
        this.show = true;
        // this.row = this.tableBasic;
        this.$http.get("/api/auth/users/").then((res) => {
            this.items = res.data.data;
            this.totalRows = this.items.length;
            this.show = false;
        });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
