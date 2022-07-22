<template>
    <b-card-code title="Listado de usuarios" no-body>
        <div class="m-2">
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
                    <b-button
                        class="mr-50"
                        variant="primary"
                        :to="{ name: 'admin-incorporacion-add' }"
                    >
                        Agregar
                    </b-button>
                    <b-button v-b-modal.modal-obs variant="secondary"
                        >Validaciones
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
            <!-- <template
                v-for="(field, index) in fields"
                #[`cell(${field.key})`]="data"
            >
                <span :key="index"></span>
                {{ data.value }}
                <span v-for="sss in parameterValidate">{{ sss }}</span>
            </template> -->
            <template #cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template #cell(action)="data">
                <div class="text-nowrap">
                    <feather-icon
                        :id="`user-row-${data.item.id}-send-icon`"
                        @click="
                            $router.push({
                                name: 'admin-incorporacion-edit',
                                params: { incorporacionId: data.item.id },
                            })
                        "
                        icon="EditIcon"
                        class="cursor-pointer text-primary"
                        size="16"
                    />
                    <b-tooltip
                        title="Editar incorporacion"
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
                        title="Eliminar Incorproacion"
                        :target="`invoice-row-${data.item.id}-preview-icon`"
                    />
                </div>
            </template>

            <template
                v-for="(field, index) in parameterValidate"
                #[`cell(${field.name})`]="data"
            >
                <span v-for="sub in data.item.validation">
                    <b-form-group v-if="sub.parameters_id == field.id">
                        <b-form-checkbox
                            class="custom-control-success"
                            checked="1"
                            true-value="1"
                            false-value="0"
                            switch
                            inline
                            :disabled="
                                $can(field.permiso, 'ACL') ? false : true
                            "
                            @click.native.prevent="check($event, sub)"
                            :value="sub.status"
                        >
                            {{ checked(sub.status) }}
                        </b-form-checkbox>
                    </b-form-group>
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
        <b-modal
            ref="my-modal-obs"
            id="modal-obs"
            centered
            hide-footer
            title="Asignar validaciones"
        >
            <b-card-body>
                <validation-observer ref="observerForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="observar"
                    >
                        <b-col md="12">
                            <b-form-input hidden name="register-name" />
                            <b-form-group>
                                <h4>Periodo</h4>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="motivo de observacion"
                                >
                                    <v-select
                                        label="name"
                                        :options="periodoList"
                                        v-model="periodo"
                                        placeholder="Seleccione"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <hr />
                        <b-col cols="12" class="mt-1">
                            <b-alert v-if="show" show variant="warning">
                                <div class="alert-body">
                                    <feather-icon
                                        class="mr-25"
                                        icon="AlertCircleIcon"
                                    />
                                    <span class="ml-25"
                                        >No se encontraron
                                        incorporaciones.</span
                                    >
                                </div>
                            </b-alert>

                            <div v-if="!show && periodo.id != null">
                                <h5>
                                    Total Integrantes : {{ members.length }}
                                </h5>
                                <small class="text-muted">
                                    seleccione las validaciones para el grupo
                                </small>

                                <b-form-group
                                    v-for="item in parameterValidate"
                                    v-bind:data="item"
                                    v-bind:key="item.id"
                                >
                                    <b-form-checkbox
                                        class="mt-1"
                                        v-model="validationChecks"
                                        :value="item.id"
                                        v-bind:true-value="1"
                                        v-bind:false-value="0"
                                    >
                                        {{ item.name }}
                                    </b-form-checkbox>
                                </b-form-group>
                            </div>
                        </b-col>
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="setValidation"
                                :disabled="
                                    members.length == 0 ||
                                    validationChecks.length == 0
                                "
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email, length } from "@validations";
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
    BFormCheckbox,
    BFormCheckboxGroup,
    BInputGroupAppend,
    BButton,
    BCardBody,
    BTooltip,
    BForm,
    BAlert,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import moment from "moment";
import "moment/locale/es";

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        BAlert,
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
        BFormCheckbox,
        BFormCheckboxGroup,
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
            show: 0,
            validationChecks: [],
            members: [],
            periodoList: [],
            periodo: "",
            incorporacionID: "",
            selected: [],
            activo: "",
            parameterValidate: [],
            show: false,
            items: [],
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
                    key: "index",
                    label: "#",
                    sortable: true,
                },

                { key: "nombres", label: "Nombre", sortable: true },
                { key: "dni", label: "Dni", sortable: true },
                { key: "cargo", label: "Cargo", sortable: true },
                { key: "modalidad_contrato", label: "C.L.", sortable: true },
                { key: "fecha_inicio", label: "Fecha Ingreso", sortable: true },
                {
                    key: "fecha_nacimiento",
                    label: "Fecha Nacimiento",
                    sortable: true,
                },
                { key: "status", label: "Status", sortable: true },
                // {
                //     key: "validation",
                //     label: "validaciones",
                //     sortable: true,
                // },
                { key: "action", label: "Action", sortable: true },
            ],
            /* eslint-disable global-require */
            // codeKitchenSink,
        };
    },
    watch: {
        periodo: function (val, oldval) {
            console.log(this.periodo.id);
            this.$http
                .get("/api/auth/incorporaciones/members/" + this.periodo.id)
                .then((response) => {
                    this.members = response.data;
                    if (this.members.length == 0) {
                        this.show = 1;
                    } else {
                        this.show = 0;
                    }
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
        cellAttributes(slot) {
            console.log("fer");
            return ["#cell(" + slot + ')="data"'];
        },
    },
    mounted() {
        this.getList();
        this.getParameter();
        this.getPeriodo();
        // Set the initial number of items
        this.totalRows = this.items.length;
    },
    methods: {
        getPeriodo() {
            moment.locale("es");
            var minDate = new Date();
            var sumDate = new Date();
            var sum = sumDate.setMonth(sumDate.getMonth() + 1);
            this.periodoList.push({
                name: moment(sum).format("Y-MMMM"),
                id: moment(sum).format("Y-MM"),
            });
            for (let step = 0; step < 3; step++) {
                minDate.setMonth(minDate.getMonth() - step);
                this.periodoList.push({
                    name: moment(minDate).format("Y-MMMM"),
                    id: moment(minDate).format("Y-MM"),
                });
            }
        },
        check: function (e, data) {
            this.$http
                .post("/api/auth/incorporaciones/status", {
                    id: data.id,
                    status: data.status,
                })
                .then((res) => {
                    this.getList();
                });
        },
        checked(data) {
            if (data == 1) {
                return "SI";
            } else {
                return "NO";
            }
        },
        onFiltered(filteredItems) {
            console.log("fer");
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        getList() {
            this.$http.get("/api/auth/incorporaciones/").then((res) => {
                this.items = res.data.validacion;
                this.totalRows = this.items.length;
                this.show = false;
            });
        },
        setValidation() {
            this.$http
                .post("/api/auth/incorporaciones/validation", {
                    periodo: this.periodo,
                    validaciones: this.validationChecks,
                    members: this.members,
                })
                .then(() => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "Registrado Correctamente",
                            icon: "CoffeeIcon",
                            variant: "success",
                        },
                    });
                    this.getList();
                    this.$refs["my-modal-obs"].hide();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getParameter() {
            this.$http
                .get("/api/auth/parameter_check/" + "validacion")
                .then((response) => {
                    this.parameterValidate = response.data;
                    if (this.parameterValidate.length > 0) {
                        this.fields.pop();
                        this.parameterValidate.forEach((value, index) => {
                            if (this.$can(value.permiso, "ACL")) {
                                this.fields.push({
                                    key: value.name,
                                    label: value.name,
                                    sortable: false,
                                });
                            }
                        });
                        this.fields.push({
                            key: "action",
                            label: "Action",
                            sortable: true,
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        // this.row = this.tableBasic;
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
