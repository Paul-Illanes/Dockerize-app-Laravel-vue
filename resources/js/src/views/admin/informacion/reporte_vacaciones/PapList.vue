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
                        :id="`invoice-row-${data.item.id}-preview-icon`"
                        icon="SendIcon"
                        v-on:click="setModalData(data.item)"
                        v-b-modal.modal-lg
                        size="16"
                        class="cursor-pointer text-info"
                    />
                </div>
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
            <b-modal
                ref="my-modal"
                id="modal-lg"
                centered
                hide-footer
                size="xs"
                title="Asignar periodo vacacional"
                no-close-on-backdrop
            >
                <b-card-body>
                    <validation-observer ref="asignarForm">
                        <b-form
                            class="auth-register-form mb-1"
                            @submit.prevent="asignar"
                        >
                            <b-col md="12">
                                <b-form-input hidden name="register-name" />
                                <b-form-group>
                                    <label>Seleccione Periodo</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="periodo"
                                    >
                                        <v-select
                                            label="periodo"
                                            item-value="anio"
                                            item-text="periodo"
                                            :options="vacaciones"
                                            v-model="selectedVac"
                                            name="vacaciones"
                                            placeholder="Seleccione"
                                        />

                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="12" v-if="selectedVac">
                                <p>
                                    <b>Dias: </b
                                    >{{ selectedVac.dias_pendientes }}
                                </p>

                                <p>
                                    <b>Usados: </b>{{ selectedVac.dias_usados }}
                                </p>

                                <p><b>Pendientes: </b>{{ selectedVac.pend }}</p>
                            </b-col>
                            <b-col cols="12" class="mt-1">
                                <b-button
                                    variant="primary"
                                    type="submit"
                                    @click.prevent="asignar"
                                >
                                    Registrar
                                </b-button>
                            </b-col>
                        </b-form>
                    </validation-observer>
                </b-card-body>
            </b-modal>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
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
    BForm,
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
        BForm,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    props: {
        taskTags: "",
    },
    data() {
        return {
            persistName: "",
            persistId: "",
            selectedVac: "",
            show: false,
            items: [],
            vacaciones: [],
            perPage: 5,
            pageOptions: [3, 5, 10, 50],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            tdd: "",
            modalData: {
                periodo: "",
                dni: "",
                fecha_inicio: "",
                fecha_final: "",
                nro_dias: "",
                papeleta_id: "",
            },
            fields: [
                {
                    key: "index",
                    label: "#",
                    sortable: true,
                },

                { key: "fecha_salida", label: "Inicio", sortable: true },
                { key: "fecha_retorno", label: "Retorno", sortable: true },
                { key: "tdd", label: "Nro. Dias", sortable: true },
                { key: "observacion", label: "Observacion", sortable: true },
                { key: "action", label: "action" },
            ],
            /* eslint-disable global-require */
            // codeKitchenSink,
        };
    },
    watch: {
        taskTags: function (val, oldval) {
            this.$http
                .get("/api/auth/vacaciones/report/papeletas/" + val.id)
                .then((response) => {
                    console.log(response);
                    this.items = response.data;
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
        asignar() {
            this.$refs.asignarForm.validate().then((success) => {
                if (success) {
                    this.persistId = this.taskTags.id;
                    this.persistName = this.taskTags.name;
                    if (this.tdd > this.selectedVac.pend) {
                        this.$toast({
                            component: ToastificationContent,
                            position: "top-right",
                            props: {
                                title: "Ocurrio un inconveniente",
                                icon: "CoffeeIcon",
                                variant: "warning",
                                text: `El periodo seleccioando no cuenta con los dias suficientes, elija otro periodo`,
                            },
                        });
                    } else {
                        this.$http
                            .post("/api/auth/papeleta/asignar", {
                                papeleta_id: this.modalData.papeleta_id,
                                periodo: this.selectedVac.anio,
                                dni: this.modalData.dni,
                                nro_dias: this.modalData.nro_dias,
                                fecha_inicio: this.modalData.fecha_inicio,
                                fecha_final: this.modalData.fecha_final,
                            })
                            .then((response) => {
                                this.$emit("clicked", {
                                    name: "anonimo",
                                    id: "00000001",
                                });
                                this.$toast({
                                    component: ToastificationContent,
                                    position: "top-right",
                                    props: {
                                        title: "Registrado Correctamente",
                                        icon: "CoffeeIcon",
                                        variant: "success",
                                        text: `Papeleta Asignada correctamente`,
                                    },
                                });
                                this.$refs["my-modal"].hide();
                                this.$emit("clicked", {
                                    name: this.persistName,
                                    id: this.persistId,
                                });
                            })
                            .catch((error) => {
                                this.$refs.asignarForm.setErrors(
                                    error.response.data.errors
                                );
                            });
                    }
                }
            });
        },
        setModalData(data) {
            this.$http
                .get("/api/auth/vacaciones/report/periodo/" + data.dni)
                .then((response) => {
                    console.log(response.data);
                    this.vacaciones = response.data;
                    // this.show = true;
                })
                .catch((error) => {
                    console.log(error);
                });
            this.modalData.dni = data.dni;
            this.modalData.periodo = data.anio;
            this.modalData.fecha_inicio = data.fecha_salida;
            this.modalData.fecha_final = data.fecha_retorno;
            this.modalData.nro_dias = data.tdd;
            this.modalData.papeleta_id = data.id;
            this.tdd = data.tdd;
            this.persistName = "";
            this.persistId = "";
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
