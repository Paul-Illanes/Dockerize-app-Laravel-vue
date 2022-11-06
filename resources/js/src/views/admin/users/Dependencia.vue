<template>
    <b-card-code title="dependencias" no-body>
        <b-card-body>
            <div class="d-flex justify-content-between flex-wrap">
                <!-- sorting  -->
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
                <b-form-group
                    label-align-sm="left"
                    label-cols-sm="4"
                    label-for="sortBySelect"
                    class="mr-1 mb-md-0 white-nowrap"
                >
                    <b-form-checkbox
                        v-model="dependenciaAll"
                        v-on:change="dependencia_all"
                        true-value="1"
                        false-value="0"
                    >
                        Seleccionar todo
                    </b-form-checkbox>
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
        </b-card-body>
        <b-table
            striped
            hover
            responsive
            class="position-relative"
            :per-page="perPage"
            :current-page="currentPage"
            :items="dependencias"
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
            <template #cell(name)="data">
                <b-form-checkbox
                    v-model="selectedDependencias"
                    :value="data.item.value"
                    >{{ data.item.name }}</b-form-checkbox
                >
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
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import {
    BFormCheckboxGroup,
    BFormInput,
    BFormCheckbox,
    BFormGroup,
    BInputGroup,
    BInputGroupAppend,
    BForm,
    BCardText,
    BRow,
    BCol,
    BButton,
    BTable,
    BCardBody,
    BFormSelect,
    BPagination,
} from "bootstrap-vue";
import {
    required,
    email,
    confirmed,
    url,
    between,
    alpha,
    integer,
    password,
    min,
    digits,
    alphaDash,
    length,
} from "@validations";
export default {
    components: {
        BCardText,
        BFormCheckbox,
        BFormCheckboxGroup,
        BCardCode,
        ValidationProvider,
        ValidationObserver,
        BFormInput,
        BFormGroup,
        BInputGroup,
        BInputGroupAppend,
        BForm,
        BRow,
        BCol,
        BButton,
        vSelect,
        BTable,
        BCardBody,
        BFormSelect,
        BPagination,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            //table
            perPage: 10,
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
                    key: "name",
                    label: "Nombre",
                    sortable: true,
                },
                {
                    key: "value",
                    label: "Valor",
                    sortable: true,
                },
            ],
            dependenciaAll: [],
            selectedDependencias: [],
            dependencias: [],
        };
    },

    mounted() {
        this.totalRows = this.dependencias.length;
    },
    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter((f) => f.sortable)
                .map((f) => ({ text: f.label, value: f.key }));
        },
    },

    created() {
        this.$http.get("/api/auth/dependencia/").then((res) => {
            this.dependencias = res.data;
            this.totalRows = this.dependencias.length;
        });
    },
    methods: {
        detail: function (data) {
            this.selectedDependencias = data;
        },
        register: function (id) {
            this.$http
                .post("/api/auth/users/dependencias", {
                    id: id,
                    dependencias: this.selectedDependencias,
                })
                .then((res) => {
                    console.log("dependencia registrada");
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        update: function (id) {
            this.$http
                .post("/api/auth/users/dependencias_update", {
                    id: id,
                    dependencias: this.selectedDependencias,
                })
                .then((res) => {
                    console.log("dependencia actualizada");
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        back() {
            this.$router.back();
        },
        scrollToTop() {
            window.scrollTo(0, 0);
        },
        dependencia_all: function (event) {
            // console.log(event);
            if (this.dependenciaAll.length == 1) {
                this.dependencias.forEach((item) => {
                    this.selectedDependencias.push(item.value);
                });
                // this.selectedPermisos.shift();
            } else {
                this.selectedDependencias = [];
            }
        },
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
