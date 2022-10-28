<template>
    <b-card-code title="Personal por servicio" no-body>
        <b-row class="mx-50">
            <b-col cols="4">
                <label style="font-weight: 700">Ejecucion Vacacional</label>
                <v-select
                    v-model="periodo"
                    label="name"
                    item-value="value"
                    item-text="name"
                    :options="periodos"
                    placeholder="Seleccione"
                />
            </b-col>
            <b-col cols="4">
                <p class="mt-3">periodo vacacional:</p>
            </b-col>
        </b-row>
        <b-row class="mx-50">
            <b-col cols="4">
                <label style="font-weight: 700">Sup Estructura</label>
                <v-select
                    v-model="supestructura"
                    label="name"
                    item-value="value"
                    item-text="name"
                    :options="supestructuras"
                    placeholder="Seleccione"
                />
            </b-col>
            <b-col cols="4">
                <b-form-group>
                    <label style="font-weight: 700">Dependencia</label>
                    <v-select
                        v-model="dependencia"
                        label="name"
                        item-value="value"
                        item-text="name"
                        :options="dependencias"
                        placeholder="Seleccione"
                    />
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group>
                    <label style="font-weight: 700">area</label>
                    <v-select
                        v-model="area"
                        label="name"
                        item-value="value"
                        item-text="name"
                        :options="areas"
                        placeholder="Seleccione"
                    />
                </b-form-group>
            </b-col>
        </b-row>
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

        <!-- <div class="card-body p-0">
            <div class="table-responsive">
                <table
                    class="table-sm table-striped table-bordered display"
                    style="width: 100%"
                    id="table"
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
                    <thead class="thead">
                        <tr>
                            <th style="min-width: 150px">Personal</th>
                            <th
                                style="font-size: 0.8rem"
                                class="no-sort"
                                v-for="(dia, index) in months"
                            >
                                <span
                                    v-if="index < 12"
                                    style="writing-mode: vertical-rl"
                                    class="text-secondary text-uppercase"
                                    >{{ dia }}</span
                                >
                                <span
                                    v-else-if="index >= 12"
                                    style="writing-mode: vertical-rl"
                                    class="text-danger text-uppercase"
                                    >{{ dia }}</span
                                >
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items">
                            <td style="font-size: 0.8rem">
                                {{ item.nombres }}
                            </td>
                            <td
                                style="font-size: 0.8rem"
                                v-for="index in 12"
                                :key="index"
                            >
                                <span
                                    v-for="(vac, index) in item.vac1"
                                    style="font-size: 11px"
                                    class="badge badge-secondary"
                                    v-if="vac.mes_programacion_reportado"
                                >
                                    VAC
                                </span>
                            </td>

                            <td style="font-size: 0.8rem"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
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
                <div class="text-center">
                    {{ data.index + 1 }}
                </div>
            </template>
            <template #cell(nro0)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 1"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro1)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 2"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro2)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 3"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro3)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 4"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro4)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 5"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro5)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 6"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro6)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 7"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro7)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 8"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro8)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 9"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro9)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 10"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro10)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 11"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro11)="data">
                <li style="list-style: none" v-for="vac in data.item.vac1">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vac.mes_programacion_reportado == 12"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro12)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 1"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro13)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 2"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro14)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 3"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro15)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 4"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro16)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 5"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro17)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 6"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro18)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 7"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro19)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 8"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro120)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 9"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro21)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 10"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro22)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 11"
                    >
                        vac
                    </span>
                </li>
            </template>
            <template #cell(nro23)="data">
                <li style="list-style: none" v-for="vaca in data.item.vac2">
                    <span
                        style="font-size: 11px"
                        class="badge badge-secondary"
                        v-if="vaca.mes_programacion_reportado == 12"
                    >
                        vac
                    </span>
                </li>
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
        <b-modal
            ref="my-modal-area"
            id="modal-change"
            centered
            hide-footer
            title="Registrar nueva area"
            no-close-on-backdrop
        >
            <b-card-body>
                <validation-observer ref="areaForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="registro_area"
                    >
                        <b-col md="12">
                            <label style="font-weight: 700"
                                >Sub Estructura</label
                            >
                            <v-select
                                v-model="supestructura"
                                label="name"
                                item-value="value"
                                item-text="name"
                                :options="supestructuras"
                                placeholder="Seleccione"
                            />
                            <label style="font-weight: 700">Dependencia</label>
                            <v-select
                                v-model="dependencia"
                                label="name"
                                item-value="value"
                                item-text="name"
                                :options="dependencias"
                                placeholder="Seleccione"
                                :disabled="!supestructura ? true : false"
                            />
                            <b-form-group>
                                <label>Area </label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="area"
                                >
                                    <b-form-input
                                        id="example-input"
                                        type="text"
                                        :disabled="!dependencia"
                                        v-model="area"
                                        ref="inputarea"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <hr />
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="registro_area"
                            >
                                Registrar
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-card-body>
        </b-modal>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import Ripple from "vue-ripple-directive";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BForm,
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
    BDropdown,
    BDropdownItem,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import { required } from "@validations";

export default {
    components: {
        BForm,
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
        BDropdown,
        BDropdownItem,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            supestructuras: [],
            dependencias: [],
            areas: [],
            periodos: [],
            months: [],
            periodo: "",
            supestructura: "",
            dependencia: "",
            area: "",
            items: [],
            perPage: 10,
            pageOptions: [10, 20, 50],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            fields: [
                {
                    key: "nombres",
                    label: "Personal",
                    sortable: true,
                },
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
        getVacaciones(dni, mes, periodo) {
            this.$http
                .post("/api/auth/cronograma/getVacaciones", {
                    dni: dni,
                    mes: mes,
                    periodo: periodo,
                })
                .then((response) => {
                    if (response.data) return true;
                    else return false;
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
        getDependencia() {
            this.$http
                .post("/api/auth/cronograma/getDependencia", {
                    cod: this.supestructura.value,
                })
                .then((response) => {
                    this.dependencias = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getAreas() {
            this.$http
                .post("/api/auth/cronograma/search_areas", {
                    dependencia: this.dependencia.value,
                    supestructura: this.supestructura.value,
                })
                .then((res) => {
                    this.areas = res.data;
                });
        },
        getPersonal() {
            this.$http
                .post("/api/auth/cronograma/getPersonal", {
                    dependencia: this.dependencia.value,
                    supestructura: this.supestructura.value,
                    area: this.area.name,
                })
                .then((res) => {
                    this.items = res.data;
                    console.log(res.data);
                });
        },
    },
    watch: {
        supestructura: function (val, oldval) {
            this.getDependencia();
        },
        dependencia: function (val, oldval) {
            this.getAreas();
        },
        area: function (val, oldval) {
            this.getPersonal();
        },
        periodo: function (val, oldval) {
            this.$http
                .get("/api/auth/cronograma/get_months/" + this.periodo.id)
                .then((response) => {
                    // console.log(response);
                    // console.log(response);
                    this.months = JSON.parse(JSON.stringify(response.data));
                    // console.log(this.months);
                    if (this.months.length > 0) {
                        this.months.forEach((value, index) => {
                            this.fields.push({
                                thStyle:
                                    "writing-mode: vertical-lr; font-size: .8rem",
                                key: "nro" + index,
                                label: value,
                                sortable: false,
                            });
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        this.$http.get("/api/auth/cronograma/").then((res) => {
            console.log(res.data.years);
            this.supestructuras = res.data.supestructura;
            this.periodos = res.data.years;
        });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
.cascade {
    writing-mode: vertical-lr;
    border: 1px solid gray !important;
    font-size: 0.8rem;
}
.th {
    writing-mode: vertical-lr;
}
</style>
