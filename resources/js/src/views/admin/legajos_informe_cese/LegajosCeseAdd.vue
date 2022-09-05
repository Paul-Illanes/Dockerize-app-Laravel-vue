<template>
    <b-card-code title="Crear Legajos Informe Cese" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        ref="form"
                        :style="{ height: trHeight }"
                        class="auth-register-form mt-2 ml-2 mr-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="6">
                                <b-form-group>
                                    <label>Persona</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Persona"
                                    >
                                        <v-select
                                            label="name"
                                            :options="users"
                                            v-model="persona"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>NIT</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="nit"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="nit"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Numero informe</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="numeroInforme"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="numeroInforme"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Fecha informe</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha informe"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fechaInforme"
                                            type="date"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Cod. Planilla</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="codigo planilla"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="codPlanilla"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Fecha nacimiento</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="fecha nacimiento"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fechaNacimiento"
                                            type="date"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Regimen Laboral</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="regimen laboral"
                                    >
                                        <v-select
                                            label="name"
                                            :options="regimen"
                                            v-model="regimenLaboral"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Grupo Ocupacional</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="grupo"
                                    >
                                        <v-select
                                            label="name"
                                            :options="grupo"
                                            v-model="grupoOcupacional"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Numero Documento Cese</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="numero informe"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="numeroDocumentoCese"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Regimen Pensionario</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="regimen pensionario"
                                    >
                                        <v-select
                                            label="name"
                                            :options="pensionario"
                                            v-model="regimenPensionario"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Linea Carrera</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="linea carrera"
                                    >
                                        <v-select
                                            label="name"
                                            :options="linea"
                                            v-model="lineaCarrera"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Condicion Laboral</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="numero informe"
                                    >
                                        <v-select
                                            label="name"
                                            :options="condicion"
                                            v-model="condicionLaboral"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Modalidad Contrato</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="modalidad contrato"
                                    >
                                        <v-select
                                            label="name"
                                            :options="contrato"
                                            v-model="modalidadContrato"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Dependencia</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="numero informe"
                                    >
                                        <v-select
                                            label="name"
                                            :options="dependencias"
                                            v-model="dependencia"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <!-- <b-col md="4">
                                <b-form-group>
                                    <label>Licencia S/G. Haber</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="licencias"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="licencias"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Sancion Disciplinaria</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="numero informe"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="sancionDisciplinaria"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Licencia C/G/H COVID-19</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="licencia covid"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="licenciaCovid"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="5">
                                <b-form-group>
                                    <label>Permisos Particulares</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="permisos particualres"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="permisosParticulares"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>A cuenta de vacaciones</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="sancion disciplinaria"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="aCuentaVacaciones"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col> -->
                            <b-col md="3">
                                <b-form-group>
                                    <label>Tiempo Servicio</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="huelga"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="tiempoServicio"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>

                            <b-col md="3">
                                <b-form-group>
                                    <label>Total Tiempo Deducible</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="vacaciones no gozadas"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="totalTiempoDeducible"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <label>Total tiempo servicio ESSALUD</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="essalud"
                                    type="text"
                                />
                            </b-col>

                            <!-- <b-col cols="12" class="">
                                <b-button
                                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                    variant="primary"
                                    @click="repeateAgain"
                                    class="pr-1"
                                >
                                    <feather-icon icon="PlusIcon" />
                                    <span>Agregar</span>
                                </b-button>
                            </b-col> -->
                        </b-row>
                        <!-- Row Loop -->
                        <b-row
                            v-for="(item, index) in items"
                            :id="item.id"
                            :key="item.id"
                            ref="row"
                            class="mt-1"
                        >
                            <b-col md="12">
                                <b-row>
                                    <!-- Item Name -->
                                    <b-col md="4">
                                        <b-form-group
                                            label="Tipo de licencia"
                                            label-for="item-name"
                                        >
                                            <validation-provider
                                                #default="{ errors }"
                                                rules=""
                                                name="licencia"
                                            >
                                                <v-select
                                                    label="name"
                                                    :options="licenciaSelect"
                                                    v-model="item.licencia"
                                                    name="licencia"
                                                    placeholder="Seleccione"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <!-- Cost -->
                                    <b-col md="3">
                                        <b-form-group
                                            label="Fecha Inicio"
                                            label-for="fecha_inicio"
                                        >
                                            <b-form-input
                                                id="fecha_inicio"
                                                type="date"
                                                v-model="item.fecha_inicio"
                                            />
                                        </b-form-group>
                                    </b-col>

                                    <!-- Quantity -->
                                    <b-col md="3">
                                        <b-form-group
                                            label="Fecha Termino"
                                            label-for="fecha_termino"
                                        >
                                            <b-form-input
                                                id="fecha_termino"
                                                type="date"
                                                v-model="item.fecha_termino"
                                            />
                                        </b-form-group>
                                    </b-col>

                                    <!-- Remove Button -->
                                    <b-col lg="2" md="12" class="mb-50">
                                        <b-button
                                            v-ripple.400="
                                                'rgba(234, 84, 85, 0.15)'
                                            "
                                            variant="outline-danger"
                                            class="mt-0 mt-md-2"
                                            @click="removeItem(index)"
                                        >
                                            <feather-icon
                                                icon="XIcon"
                                                class="mr-25"
                                            />
                                            <span>Eliminar</span>
                                        </b-button>
                                    </b-col>
                                    <b-col cols="12">
                                        <hr />
                                    </b-col>
                                </b-row>
                            </b-col>
                        </b-row>
                        <b-col cols="12" class="mb-5 pb-2">
                            <b-button
                                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                variant="primary"
                                @click="repeateAgain"
                                class=""
                            >
                                <feather-icon icon="PlusIcon" />
                                <span>Agregar</span>
                            </b-button>
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="register"
                            >
                                Registrar
                            </b-button>
                            <b-button variant="danger" @click="back()">
                                Volver
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-col>
        </b-row>
    </b-card-code>
</template>

<script>
/* eslint-disable global-require */
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import flatPickr from "vue-flatpickr-component";
import {
    BCard,
    BRow,
    BCol,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BCardTitle,
    BListGroup,
    BCardText,
    BCardBody,
    BCardSubTitle,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormTextarea,
    BTable,
    BFormRadioGroup,
    BFormRadio,
} from "bootstrap-vue";
import { required } from "@validations";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import moment from "moment";
import "animate.css";
import Ripple from "vue-ripple-directive";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { heightTransition } from "@core/mixins/ui/transition";

export default {
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    components: {
        vueDropzone: vue2Dropzone,
        BCard,
        BRow,
        BCol,
        BButton,
        BForm,
        BCardText,
        BCardTitle,
        BListGroup,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        BCardBody,
        BCardSubTitle,
        BCardCode,
        // validatons
        ValidationProvider,
        ValidationObserver,
        vSelect,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        flatPickr,
        BFormTextarea,
        BTable,
        BFormRadioGroup,
        BFormRadio,
    },

    data() {
        return {
            nextTodoId: 1,
            items: [
                {
                    id: 1,
                    licencia: "",
                    fecha_inicio: "",
                    fecha_termino: "",
                },
            ],
            licenciaSelect: [],
            persona: "",
            nit: "",
            numeroInforme: "",
            fechaInforme: "",
            codPlanilla: "",
            fechaNacimiento: "",
            regimenLaboral: "",
            grupoOcupacional: "",
            numeroDocumentoCese: "",
            regimenPensionario: "",
            lineaCarrera: "",
            condicionLaboral: "",
            modalidadContrato: "",
            dependencia: "",
            licencias: "",
            sancionDisciplinaria: "",
            licenciaCovid: "",
            permisosParticulares: "",
            aCuentaVacaciones: "",
            tiempoServicio: "",
            totalTiempoDeducible: "",
            tiempoServicio: "",
            essalud: "",
            regimen: [],
            grupo: [],
            pensionario: [],
            linea: [],
            condicion: [],
            contrato: [],
            dependencias: [],
            //selects
            users: [],
        };
    },
    mounted() {
        this.initTrHeight();
    },
    destroyed() {
        window.removeEventListener("resize", this.initTrHeight);
    },
    created() {
        window.addEventListener("resize", this.initTrHeight);
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/informe_cese/getuser")
            .then((response) => {
                console.log(response);
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        this.$http
            .get("/api/auth/legajo_cese/select")
            .then((response) => {
                this.regimen = response.data.regimen_laboral;
                this.grupo = response.data.grupo_ocupacional;
                this.pensionario = response.data.regimen_pensionario;
                this.linea = response.data.linea_carrera;
                this.condicion = response.data.condicion_laboral;
                this.contrato = response.data.condicion_contrato;
                this.modalidad = response.data.dependencia;
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
        this.getParameter();
    },
    methods: {
        getParameter() {
            this.$http
                .get("/api/auth/parameter/" + "legajos_licencias")
                .then((response) => {
                    this.licenciaSelect = response.data;
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        repeateAgain() {
            this.items.push({
                id: (this.nextTodoId += this.nextTodoId),
            });

            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        initTrHeight() {
            this.trSetHeight(null);
            this.$nextTick(() => {
                this.trSetHeight(this.$refs.form.scrollHeight);
            });
        },
        back() {
            this.$router.back();
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/legajo_cese/create", {
                            lincencia_array: this.items,
                            nombre: this.persona.name,
                            baja_id: this.persona.id,
                            codigo_planilla: this.codPlanilla,
                            fecha_nacimiento: this.fechaNacimiento,
                            nit: this.nit,
                            numero_informe: this.numeroInforme,
                            fecha_informe: this.fechaInforme,
                            regimen_laboral:
                                this.regimenLaboral != null
                                    ? this.regimenLaboral.id
                                    : "",
                            grupo_ocupacional:
                                this.grupoOcupacional != null
                                    ? this.grupoOcupacional.id
                                    : "",
                            numero_documento_cese: this.numeroDocumentoCese,
                            regimen_pensionario:
                                this.regimenPensionario != null
                                    ? this.regimenPensionario.id
                                    : "",
                            linea_carrera:
                                this.lineaCarrera != null
                                    ? this.lineaCarrera.id
                                    : "",
                            condicion_laboral:
                                this.condicionLaboral != null
                                    ? this.condicionLaboral.id
                                    : "",
                            modalidad_contrato:
                                this.modalidadContrato != null
                                    ? this.modalidadContrato.id
                                    : "",
                            dependencia:
                                this.dependencia != null
                                    ? this.dependencia.id
                                    : "",
                            // licencia_sg_haber: this.licencias,
                            // sancion_disciplinaria: this.sancionDisciplinaria,
                            // licencia_covid: this.licenciaCovid,
                            // permisos_particulares: this.permisosParticulares,
                            // acuenta_vacaciones: this.aCuentaVacaciones,
                            tiempo_servicio: this.tiempoServicio,
                            total_tpo_deducible: this.totalTiempoDeducible,
                            tiempo_servicio: this.tiempoServicio,
                            total_tpo_essalud: this.essalud,
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            // this.$router.back();
                        })
                        .catch((error) => {
                            this.$refs.registerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
    },
};
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
