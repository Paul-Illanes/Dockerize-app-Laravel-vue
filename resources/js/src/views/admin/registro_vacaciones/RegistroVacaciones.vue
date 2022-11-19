<template>
    <b-card title="Programacion Vacacional">
        <b-card-actions title="Filtros" action-collapse>
            <b-row>
                <b-col md="6">
                    <b-form-group
                        label="Ejecucion Vacacional:"
                        label-for="h-first-name"
                        label-cols-md="4"
                    >
                        <v-select
                            label="name"
                            :options="periodos"
                            v-model="periodo"
                            name="superstructura"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <p class="mt-50">
                        Periodo Vacacional: {{ changePeriodo(periodo.id) }}
                    </p>
                </b-col>
                <b-col md="3">
                    <b-form-group>
                        <label for="">Sup Estructura</label>
                        <v-select
                            label="name"
                            :options="supestructuras"
                            v-model="supestructura"
                            name="superstructura"
                            placeholer="Sup estructura"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group>
                        <label for="">Dependencia</label>
                        <v-select
                            label="name"
                            :options="dependencias"
                            v-model="dependencia"
                            name="superstructura"
                            placeholer="Dependencia"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="3">
                    <b-form-group>
                        <label for="">areas</label>
                        <v-select
                            label="name"
                            :options="areas"
                            v-model="area"
                            name="superstructura"
                            placeholer="Area"
                        />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-card-actions>

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
                    <!-- <b-button
                        variant="primary"
                        :to="{ name: 'admin-users-add' }"
                    >
                        Agregar Usuario
                    </b-button> -->

                    <b-button
                        variant="primary"
                        @click="aprobar()"
                        class="mr-1"
                        v-if="items.length > 0 && !cerrar_status"
                    >
                        Aprobar todos
                    </b-button>
                    <b-button
                        variant="warning"
                        @click="cerrar_proceso()"
                        v-if="
                            cerrar_status &&
                            !closeStatus.status &&
                            periodo.status == 1
                        "
                        class="mr-1"
                    >
                        Cerrar proceso
                    </b-button>
                    <b-button
                        variant="secondary"
                        @click="generar_pdf()"
                        v-if="closeStatus.status == 1"
                    >
                        Generar PDF
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
            <template #cell(condicion_laboral_id)="data">
                <div class="text-center p-0">
                    {{ cl_to_regimen_laboral(data.item.condicion_laboral_id) }}
                </div>
            </template>
            <template #cell(periodo_vacacional)="data">
                <div
                    class="text-center p-0"
                    v-if="data.item.periodo_vacacional"
                >
                    {{ changePeriodo(data.item.periodo_vacacional) }}
                </div>
            </template>
            <template #cell(programacion)="data">
                <div v-if="data.item.periodo_vacacional">
                    <!-- {{ data.item.anio_programacion_solicitado }} -->
                    <!-- {{ data.item.anio_programacion_reportado }} -->
                    <p class="text-nowrap">
                        {{
                            periodo.status == 1
                            ? data.item.anio_programacion_solicitado
                            : data.item.anio_programacion_reportado,

                        }}
                        {{
                            changeDate(
                                periodo.status == 1
                                    ? data.item.mes_programacion_solicitado
                                    : data.item.mes_programacion_reportado
                            )
                        }}
                    </p>
                </div>
            </template>
            <template #cell(-)="data">
                <div v-if="periodo.status == 1">
                    <div
                        class="text-nowrap"
                        v-if="
                            data.item.periodo_vacacional &&
                            data.item.estado_presentacion == '-1'
                        "
                    >
                        <feather-icon
                            :id="`invoice-row-${data.item.id}-send-icon`"
                            icon="Edit2Icon"
                            class="cursor-pointer text-primary"
                            size="16"
                            @click="setModal(data.item)"
                        />
                        <feather-icon
                            :id="`invoice-row-${data.item.id}-preview-icon`"
                            icon="TrashIcon"
                            size="16"
                            class="mx-1 text-danger"
                            @click="confirmDelete(data.item.id)"
                        />
                    </div>
                    <div
                        v-else-if="!data.item.periodo_vacacional"
                        class="text-center"
                    >
                        <feather-icon
                            :id="`invoice-row-${data.item.id}-create-icon`"
                            icon="PlusSquareIcon"
                            class="cursor-pointer text-success"
                            size="16"
                            @click="setModal(data.item)"
                        />
                    </div>
                </div>
            </template>
            <template #cell(status)="data">
                <div v-if="periodo.status == 1">
                    <b-button
                        v-if="data.item.estado_presentacion == '0'"
                        size="sm"
                        variant="flat-success"
                        :disabled="periodo.status == 1 ? true : false"
                    >
                        Procesado
                    </b-button>
                    <b-button
                        v-else-if="data.item.estado_presentacion == '-1'"
                        size="sm"
                        variant="flat-primary"
                        :disabled="periodo.status == 1 ? true : false"
                    >
                        Pendiente
                    </b-button>
                </div>
            </template>
            <template #cell(button)="data">
                <div v-if="periodo.status == 1">
                    <b-button
                        v-if="data.item.estado_presentacion == '0'"
                        size="sm"
                        variant="warning"
                        @click="estado(-1, data.item.id)"
                        :disabled="closeStatus.status == 1 ? true : false"
                    >
                        Modificar
                    </b-button>
                    <b-button
                        v-else-if="data.item.estado_presentacion == '-1'"
                        size="sm"
                        variant="success"
                        @click="estado(0, data.item.id)"
                    >
                        Validar
                    </b-button>
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
        <b-modal
            ref="my-modal-edit"
            id="modal-edit"
            centered
            hide-footer
            title="Programacion devacaciones jefe"
            size="lg"
            no-close-on-backdrop
            @hide="doSometing"
        >
            <b-card-body>
                <validation-observer ref="editForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="observar"
                    >
                        <b-col md="12">
                            <b-row style="font-size: 12px">
                                <b-col cols="3"
                                    ><b>DNI:</b> {{ modalData.dni }}
                                </b-col>
                                <b-col cols="5"
                                    ><b>Nombre:</b> {{ modalData.nombres }}
                                </b-col>
                                <b-col cols="4"
                                    ><b>Periodo Vacacional:</b>
                                    {{
                                        changePeriodo(
                                            modalData.periodo_vacacional
                                                ? modalData.periodo_vacacional
                                                : periodo.id
                                        )
                                    }}
                                </b-col>
                                <!-- segunda fila -->
                                <b-col cols="3"
                                    ><b>Regimen:</b>
                                    {{
                                        cl_to_regimen_laboral(
                                            modalData.condicion_laboral_id
                                        )
                                    }}
                                </b-col>
                                <b-col cols="5"
                                    ><b>Fecha Nacimiento:</b>
                                    {{ modalData.fecha_nacimiento }}
                                </b-col>

                                <b-col cols="4"
                                    ><b>Ejecucion Vacacion:</b>

                                    {{
                                        ejecucionPeriodo(
                                            modalData.periodo_vacacional
                                                ? modalData.periodo_vacacional
                                                : periodo.id
                                        )
                                    }}
                                </b-col>
                                <b-col cols="3"> </b-col>
                                <b-col cols="5"
                                    ><b>Fecha Ingreso:</b>
                                    {{ modalData.fecha_ingreso }}
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="12">
                            <b-form-group>
                                <label>Mes Programacion</label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    :custom-messages="customMessages"
                                >
                                    <v-select
                                        label="name"
                                        :options="meses"
                                        v-model="selectedMes"
                                        name="name"
                                        placeholder="Seleccione"
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
                                @click.prevent="observar"
                            >
                                Guardar
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-card-body>
        </b-modal>
    </b-card>
</template>

<script>
import BCardActions from "@core/components/b-card-actions/BCardActions.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import Ripple from "vue-ripple-directive";
import { required } from "@validations";
import {
    BCard,
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
    BFormCheckbox,
    BForm,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import moment from "moment";
import "moment/locale/es";

export default {
    components: {
        BCardActions,
        BCard,
        BRow,
        BCol,
        BCardText,
        BOverlay,
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
        BFormCheckbox,
        BForm,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            customMessages: {
                required: "El campo es requerido",
            },
            supestructuras: [],
            dependencias: [],
            areas: [],
            periodos: [],
            periodo: "",
            supestructura: "",
            dependencia: "",
            area: "",
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
            check: "",
            fields: [
                {
                    key: "index",
                    label: "#",
                    sortable: true,
                },

                { key: "nombres", label: "Nombres", sortable: true },
                { key: "cargo", label: "Cargo", sortable: true },
                { key: "fecha_ingreso", label: "F. Ingreso", sortable: false },
                { key: "condicion_laboral_id", label: "R.L", sortable: true },
                {
                    key: "periodo_vacacional",
                    label: "Periodo Vacacional",
                    sortable: false,
                },
                { key: "programacion", label: "Prog. Vac.", sortable: false },
                { key: "-", label: " ", sortable: false },
                { key: "status", label: " ", sortable: false },
                { key: "button", label: " ", sortable: false },
            ],
            modalData: [],
            meses: [],
            selectedMes: "",
            estado_presentacion: "",
            cerrar_status: false,
            count: 0,
            closeStatus: "",
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
        // $(this.$refs.vuemodal).on("hidden.bs.modal", this.doSomethingOnHidden)
    },
    methods: {
        generar_pdf() {
            this.$http
                .post("/api/auth/registro_vacaciones/pdf", {
                    id: this.area.id,
                    periodo: this.periodo.id,
                })
                .then((response) => {
                    this.$http
                        .get(
                            "/api/auth/registro_vacaciones/file/" +
                                response.data,
                            {
                                responseType: "blob", // important
                            }
                        )
                        .then((res) => {
                            // Service that handles ajax call
                            const url = window.URL.createObjectURL(
                                new Blob([res.data], {
                                    type: "application/pdf",
                                })
                            );
                            window.open(url);
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
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cerrar_proceso() {
            this.$http
                .post("/api/auth/registro_vacaciones/cerrarProcesoJefe", {
                    id: this.area.id,
                    periodo: this.periodo.id,
                })
                .then(() => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "Cerrado Correctamente",
                            icon: "CoffeeIcon",
                            variant: "success",
                            text: `Proceso de programación de vacaciones CERRADO con éxito.`,
                        },
                    });
                    this.getCronograma();
                    this.getCloseStatus();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cerrar_check() {
            this.count = 0;
            this.cerrar_status = false;
            if (this.items.length > 0) {
                this.items.forEach((element) => {
                    if (element.estado_presentacion == 0) {
                        this.count = this.count + 1;
                    }
                });
                if (this.count == this.items.length) {
                    this.cerrar_status = true;
                }
            }
        },
        estado(status, id) {
            this.$http
                .post("/api/auth/registro_vacaciones/status", {
                    id: id,
                    status: status,
                })
                .then(() => {
                    this.getCronograma();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        aprobar() {
            var result = true;
            this.items.forEach((element) => {
                if (!element.periodo_vacacional) {
                    result = false;
                }
            });
            if (result) {
                this.$http
                    .post("/api/auth/registro_vacaciones/aprobar", {
                        items: this.items,
                    })
                    .then(() => {
                        this.$toast({
                            component: ToastificationContent,
                            position: "top-right",
                            props: {
                                title: "Actualizado Correctamente",
                                icon: "CoffeeIcon",
                                variant: "success",
                                text: `Se aprobaron todas las vacaciones`,
                            },
                        });
                        this.getCronograma();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                        title: "Ocurrio un incoveniente",
                        icon: "CoffeeIcon",
                        variant: "success",
                        text: `Todos los registros deben tener vacaciones asignadas`,
                    },
                });
            }
        },
        observar() {
            this.$refs.editForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/registro_vacaciones/jefe", {
                            mes: this.selectedMes.id,
                            periodo_vacacional: this.modalData
                                .periodo_vacacional
                                ? this.modalData.periodo_vacacional
                                : this.periodo.id,
                            dni: this.modalData.dni,
                            cod_planilla: this.modalData.cod_planilla,
                            nombres: this.modalData.nombres,
                            regimen_laboral:
                                this.modalData.condicion_laboral_id,
                            fecha_ingreso: this.modalData.fecha_ingreso,
                            estado_presentacion: this.modalData
                                .estado_presentacion
                                ? this.modalData.estado_presentacion
                                : "-1",
                            obs_1: this.modalData.obs_1,
                        })
                        .then(() => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Actualizado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Se modifico el mes de programacion`,
                                },
                            });
                            this.getCronograma();
                            this.$refs["my-modal-edit"].hide();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
        doSometing() {
            this.meses = [];
            this.selectedMes = "";
            this.modalData = [];
        },
        setModal(data) {
            this.modalData = data;
            this.$http
                .post("/api/auth/registro_vacaciones/getMeses", {
                    condicion_laboral: this.modalData.condicion_laboral_id,
                    fecha_ingreso: this.modalData.fecha_ingreso,
                })
                .then((response) => {
                    this.meses = response.data;
                    // console.log(this.meses);
                    if (this.modalData.id) {
                        this.getName(
                            data.anio_programacion_solicitado,
                            data.mes_programacion_solicitado
                        );
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
            this.$refs["my-modal-edit"].show();
        },
        getName(year, month) {
            var periodo = year + "-" + month;
            if (month == "01") {
                this.selectedMes = { id: periodo, name: "ENERO-" + year };
            }
            if (month == "02") {
                this.selectedMes = { id: periodo, name: "FEBRERO-" + year };
            }
            if (month == "03") {
                this.selectedMes = { id: periodo, name: "MARZO-" + year };
            }
            if (month == "04") {
                this.selectedMes = { id: periodo, name: "ABRIL-" + year };
            }
            if (month == "05") {
                this.selectedMes = { id: periodo, name: "MAYO-" + year };
            }
            if (month == "06") {
                this.selectedMes = { id: periodo, name: "JUNIO-" + year };
            }
            if (month == "07") {
                this.selectedMes = { id: periodo, name: "JULIO-" + year };
            }
            if (month == "08") {
                this.selectedMes = { id: periodo, name: "AGOSTO-" + year };
            }
            if (month == "09") {
                this.selectedMes = { id: periodo, name: "SETIEMBRE-" + year };
            }
            if (month == "10") {
                this.selectedMes = { id: periodo, name: "OCTUBRE-" + year };
            }
            if (month == "11") {
                this.selectedMes = { id: periodo, name: "NOVIEMBRE-" + year };
            }
            if (month == "12") {
                this.selectedMes = { id: periodo, name: "DICIEMBRE-" + year };
            }
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
                        .post("/api/auth/registro_vacaciones/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Accion realizada",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Se elimino la vacacion asociada`,
                                },
                            });
                            // console.log(res);
                            this.getCronograma();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
        changeDate(month) {
            // var date = new Date();
            // date = month;
            var fecha = moment(month).format("-MM");
            return fecha;
        },
        changePeriodo(data) {
            return data - 1 + "-" + data;
        },
        ejecucionPeriodo(data) {
            var fecha = 1 + Number(data);
            return data + "-" + fecha;
        },
        cl_to_regimen_laboral(data) {
            //$cod_cl = strtolower($data);
            if (data == 1) {
                return "276";
            }
            if (data == 4) {
                return "728";
            }
            if (data == 5) {
                return "728";
            }
            if (data == 6) {
                return "728";
            }
            if (data == 9) {
                return "1057";
            }
            if (data == 10) {
                return "1057";
            }
            if (data == 1) {
                return "276";
            }
        },
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
        getDependencia() {
            this.$http
                .post("/api/auth/personal_area/getDependencia", {
                    cod: this.supestructura.value,
                })
                .then((response) => {
                    this.dependencias = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getArea() {
            this.$http
                .post("/api/auth/registro_vacaciones/areaList", {
                    sup: this.supestructura.value,
                    dep: this.dependencia.value,
                })
                .then((response) => {
                    this.areas = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getCronograma() {
            this.$http
                .post("/api/auth/registro_vacaciones/getCronograma", {
                    supestructura_id: this.supestructura.value,
                    dependencia_id: this.dependencia.value,
                    area_id: this.area.id,
                    year: this.periodo.id,
                })
                .then((response) => {
                    this.items = response.data;
                    this.totalRows = this.items.length;
                    this.cerrar_check();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getCloseStatus() {
            if (this.area.id) {
                this.$http
                    .post("/api/auth/registro_vacaciones/getCloseStatus", {
                        id: this.area.id,
                        periodo: this.periodo.id,
                    })
                    .then((response) => {
                        this.closeStatus = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
    },
    watch: {
        periodo: function (val, oldval) {
            this.getCronograma();
            this.getCloseStatus();
        },
        supestructura: function (val, oldval) {
            this.getDependencia();
            this.dependencia = "";
        },
        dependencia: function (val, oldval) {
            this.getArea();
            this.area = "";
        },
        area: function (val, oldval) {
            this.getCronograma();
            this.getCloseStatus();
        },
    },
    created() {
        this.$http.get("/api/auth/personal_area/").then((res) => {
            this.supestructuras = res.data;
        });
        this.$http
            .get("/api/auth/registro_vacaciones/cronogramaList")
            .then((res) => {
                this.periodos = res.data;
                var per = this.ejecucionPeriodo(this.periodos[0].id);
                this.periodo = {
                    name: per,
                    id: this.periodos[0].id,
                    status: this.periodos[0].status,
                };
            });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
