<template>
    <div>
        <!-- <h4>Papeletas</h4> -->
        <div class="mb-1">
            <b-row>
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
                            class="mr-1"
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
            <template #cell(es_suspension)="data">
                {{ changeSuspension(data.item.es_suspension) }}
            </template>
            <template #cell(fecha_inicio)="data">
                {{ changeDate(data.item.fecha_inicio) }}
            </template>
            <template #cell(fecha_final)="data">
                {{ changeDate(data.item.fecha_inicio) }}
            </template>
            <template #cell(periodo)="data">
                {{ changePeriodo(data.item.periodo) }}
            </template>
        </b-table>
        <div class="mx-2 mb-0">
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
    </div>
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
import moment from "moment";

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
    props: {
        taskTags: "",
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

                { key: "tipo", label: "Tip. Doc", sortable: true },
                { key: "es_suspension", label: "Uso", sortable: true },
                { key: "fecha_inicio", label: "Inicio", sortable: true },
                { key: "fecha_final", label: "Final", sortable: true },
                { key: "nro_dias", label: "Dias", sortable: true },
                { key: "periodo", label: "Periodo", sortable: true },
            ],
            /* eslint-disable global-require */
            // codeKitchenSink,
        };
    },
    watch: {
        taskTags: function (val, oldval) {
            this.$http
                .get("/api/auth/vacaciones/report/documentos/" + val.id)
                .then((response) => {
                    console.log(response);
                    this.items = response.data;
                    this.totalRows = this.items.length;
                    // this.show = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
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
        changeDate(dato) {
            return moment(String(dato)).format("MM-DD-YYYY");
        },
        changeSuspension(dato) {
            if (!dato) {
                return "USO";
            } else {
                return "SUSPENDIDO";
            }
        },
        changePeriodo(dato) {
            if (dato) {
                var year = dato - 1;
                var result = year + "-" + dato;
                return result;
            } else {
                return null;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    created() {},
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
