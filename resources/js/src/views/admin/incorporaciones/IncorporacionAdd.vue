<template>
    <div>
        <form-wizard
            color="#7367F0"
            :title="null"
            :subtitle="null"
            layout="vertical"
            finish-button-text="Registrar"
            next-button-text="Siguiente"
            back-button-text="Anterior"
            class="vertical-steps steps-transparent mb-0"
            @on-complete="formSubmitted"
        >
            <!-- account details tab -->
            <tab-content
                title="C. de trabajo"
                :before-change="validationPeriodo"
                icon="feather icon-file-text"
            >
                <validation-observer ref="periodoInfo" tag="form">
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Centro de trabajo</h5>
                            <small class="text-muted">
                                Ingresa la informacion.
                            </small>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Organo" label-for="organo">
                                <b-form-input
                                    id="organo"
                                    v-model="organo"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Periodo" label-for="periodo">
                                <validation-provider
                                    #default="{ errors }"
                                    name="periodo"
                                    rules="required"
                                >
                                    <v-select
                                        id="periodo"
                                        v-model="periodo_select"
                                        :options="periodoList"
                                        label="name"
                                        placeholder="seleccione"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Centro" label-for="centro">
                                <v-select
                                    id="centro"
                                    v-model="centro_select"
                                    :options="parameterCentro"
                                    label="name"
                                    placeholder="seleccione"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Area" label-for="area">
                                <v-select
                                    id="area"
                                    v-model="area_select"
                                    :options="parameterArea"
                                    label="name"
                                    placeholder="seleccione"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3">
                            <b-form-group label="Cod Area" label-for="cod-area">
                                <b-form-input
                                    id="cod-area"
                                    v-model="cod_area"
                                    type="text"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3">
                            <b-form-group label="Plaza" label-for="plaza">
                                <b-form-input
                                    v-model="plaza"
                                    id="plaza"
                                    type="text"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </validation-observer>
            </tab-content>

            <!-- personal details tab -->
            <tab-content
                title="D. Personales"
                icon="feather icon-user"
                :before-change="validationForm"
            >
                <validation-observer ref="personalInfo" tag="form">
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Datos Personales</h5>
                            <small class="text-muted"
                                >Ingrese sus datos personales</small
                            >
                        </b-col>
                        <b-col md="4" class="mb-2">
                            <b-form-group
                                label="Apellido Paterno"
                                label-for="apaterno"
                            >
                                <b-form-input
                                    id="apaterno"
                                    v-model="apellido_paterno"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="4" class="mb-2">
                            <b-form-group
                                label="Apellido Materno"
                                label-for="amaterno"
                            >
                                <b-form-input
                                    id="amaterno"
                                    v-model="apellido_materno"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="4" class="mb-2">
                            <b-form-group label="Nombres" label-for="nombres">
                                <b-form-input
                                    id="nombres"
                                    v-model="nombres"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group label="Genero" label-for="genero">
                                <v-select
                                    id="genero"
                                    v-model="genero_select"
                                    :options="parameterGenero"
                                    label="name"
                                    placeholder="seleccione"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group
                                label="Estado Civil"
                                label-for="ecivil"
                            >
                                <v-select
                                    id="estadocivil"
                                    v-model="estadoCivil_select"
                                    :options="parameterEstadoCivil"
                                    label="name"
                                    placeholder="seleccione"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group
                                label="Domicilio"
                                label-for="domicilio"
                            >
                                <b-form-input
                                    id="domicilio"
                                    v-model="domicilio"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group label="Dni" label-for="dni">
                                <validation-provider
                                    #default="{ errors }"
                                    name="dni"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="dni"
                                        id="dni"
                                        placeholder=""
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group
                                label="Fecha Nacimiento"
                                label-for="fnacimiento"
                            >
                                <b-form-input
                                    type="date"
                                    v-model="fecha_nacimiento"
                                    id="fnacimiento"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="3" class="mb-2">
                            <b-form-group
                                label="Nivel Educativo"
                                label-for="neducativo"
                            >
                                <v-select
                                    id="niveleducativo"
                                    v-model="nivelEducativo_select"
                                    :options="parameterNivelEducativo"
                                    label="name"
                                    placeholder="seleccione"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="2" class="mb-2">
                            <b-form-group label="Celular" label-for="celular">
                                <b-form-input
                                    id="celular"
                                    v-model="celular"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="4" class="mb-2">
                            <b-form-group label="Email" label-for="email">
                                <b-form-input
                                    v-model="email"
                                    id="email"
                                    type="email"
                                    placeholder=""
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </validation-observer>
            </tab-content>

            <!-- address -->
            <tab-content title="S. de pensiones" icon="feather icon-map-pin">
                <b-row>
                    <b-col cols="12" class="mb-2">
                        <h5 class="mb-0">Sistema de pensiones</h5>
                        <small class="text-muted">Enter Your Address.</small>
                    </b-col>
                    <b-col md="4">
                        <b-form-group
                            label="Sistema Pension"
                            label-for="spension"
                        >
                            <v-select
                                id="sistemapension"
                                v-model="sistemaPension_select"
                                :options="parameterSistemaPension"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col md="4">
                        <b-form-group label="Cod AFP" label-for="codafp">
                            <b-form-input
                                id="codafp"
                                v-model="cod_afp"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Nombre AFP" label-for="nombreafp">
                            <b-form-input
                                id="nombreafp"
                                v-model="nombre_apf"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="AFP Cuz" label-for="afpcuz">
                            <b-form-input
                                id="afpcuz"
                                v-model="afp_cuz"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="3">
                        <b-form-group label="Fecha AFP" label-for="fechaafp">
                            <b-form-input
                                id="fechaafp"
                                type="date"
                                v-model="fecha_afp"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </tab-content>

            <!-- social link -->
            <tab-content title="D. Laborales" icon="feather icon-link">
                <b-row>
                    <b-col cols="12" class="mb-2">
                        <h5 class="mb-0">Datos Laborales</h5>
                        <small class="text-muted">Ingrese su informacion</small>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Cod Cargo" label-for="codcargo">
                            <b-form-input
                                id="codcargo"
                                v-model="cod_cargo"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Cargo" label-for="cargo">
                            <b-form-input
                                id="cargo"
                                v-model="cargo"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group
                            label="Especialidad"
                            label-for="especialidad"
                        >
                            <v-select
                                id="especialidad"
                                v-model="especialidad_select"
                                :options="parameterEspecialidad"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Nivel" label-for="nivel">
                            <v-select
                                id="nivel"
                                v-model="nivel_select"
                                :options="parameterNivel"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Fecha Inicio" label-for="finicio">
                            <b-form-input
                                v-model="fecha_inicio"
                                id="finicio"
                                type="date"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group
                            label="Fecha Termino"
                            label-for="ftermino"
                        >
                            <b-form-input
                                v-model="fecha_termino"
                                id="ftermino"
                                type="date"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </tab-content>
            <!-- remuneraciones -->
            <tab-content title="Remuneracion" icon="feather icon-link">
                <b-row>
                    <b-col cols="12" class="mb-2">
                        <h5 class="mb-0">Remuneraciones</h5>
                        <small class="text-muted">Ingrese su informacion</small>
                    </b-col>
                    <b-col md="4" class="mb-1">
                        <b-form-group
                            label="Bonificacion"
                            label-for="bonificacion"
                        >
                            <b-form-input
                                id="bonificacion"
                                v-model="bonificacion"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-1">
                        <b-form-group
                            label="Remuneracion"
                            label-for="remuneracion"
                        >
                            <b-form-input
                                id="remuneracion"
                                v-model="remuneracion"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-1">
                        <b-form-group
                            label="Ingreso Mensual"
                            label-for="ingresomensual"
                        >
                            <b-form-input
                                id="ingresomensual"
                                v-model="ingresoMensual"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>

                    <b-col md="4" class="mb-1">
                        <b-form-group label="Banco" label-for="banco">
                            <v-select
                                id="banco"
                                v-model="banco_select"
                                :options="parameterBanco"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-1">
                        <b-form-group
                            label="Nro. Cuenta Banco"
                            label-for="nrocuenta"
                        >
                            <b-form-input
                                id="nrocuenta"
                                v-model="numeroCuentaBanco"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </tab-content>
            <!-- motivo de contratacion -->
            <tab-content title="contratacion" icon="feather icon-link">
                <b-row>
                    <b-col cols="12" class="mb-2">
                        <h5 class="mb-0">Motivo de contratacion</h5>
                        <small class="text-muted">Ingrese su informacion</small>
                    </b-col>

                    <b-col md="4">
                        <b-form-group
                            label="Motivo contrato"
                            label-for="motivocontrato"
                        >
                            <v-select
                                id="motivocontrato"
                                v-model="motivoContrato_select"
                                :options="parameterMotivoContrato"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group
                            label="Modalidad Contrato"
                            label-for="modalidadcontrato"
                        >
                            <v-select
                                id="modalidadcontrato"
                                v-model="modalidadContrato_select"
                                :options="parameterModalidadContrato"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group
                            label="Beneficiario Ley 30555"
                            label-for="beneficiarioley"
                        >
                            <b-form-input
                                id="beneficiarioley"
                                v-model="beneficiarioLey30555"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4">
                        <b-form-group label="Ruc" label-for="ruc">
                            <b-form-input
                                id="ruc"
                                v-model="ruc"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </tab-content>
            <!-- proceso de seleccion -->
            <tab-content title="Seleccion" icon="feather icon-link">
                <b-row>
                    <b-col cols="12" class="mb-2">
                        <h5 class="mb-0">Proceso de seleccion</h5>
                        <small class="text-muted">Ingrese su informacion</small>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="N. proceso selecion"
                            label-for="procesoseleccion"
                        >
                            <b-form-input
                                v-model="numeroProcesoSeleccion"
                                id="procesoseleccion"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Fecha proceso"
                            label-for="fechaproceso"
                        >
                            <b-form-input
                                v-model="fechaProceso"
                                type="date"
                                id="fechaproceso"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Situacion Proceso"
                            label-for="situacionproceso"
                        >
                            <v-select
                                id="situacionproceso"
                                v-model="situacionProceso_select"
                                :options="parameterSituacionProceso"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Personal Deja Cargo"
                            label-for="personaldejacargo"
                        >
                            <b-form-input
                                v-model="personalDejaCargo"
                                id="personaldejacargo"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Tipo Ausencia"
                            label-for="tipoausencia"
                        >
                            <v-select
                                id="area"
                                v-model="tipoAusencia_select"
                                :options="parameterTipoAusencia"
                                label="name"
                                placeholder="seleccione"
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Fecha Inicio Ausencia"
                            label-for="fechainicioausencia"
                        >
                            <b-form-input
                                type="date"
                                v-model="fechaInicioAusencia"
                                id="fechainicioausencia"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Fecha Fin Ausencia"
                            label-for="fechafinausencia"
                        >
                            <b-form-input
                                v-model="fechaFinAusencia"
                                type="date"
                                id="fechafinausencia"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Mes Alta Planilla"
                            label-for="mesaltaplanilla"
                        >
                            <b-form-input
                                v-model="mesAltaPlanilla"
                                type="date"
                                id="mesaltaplanilla"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                    <b-col md="4" class="mb-2">
                        <b-form-group
                            label="Suspension Cuarta"
                            label-for="suspensioncuarta"
                        >
                            <b-form-input
                                v-model="suspensionCuarta"
                                id="suspensioncuarta"
                                placeholder=""
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </tab-content>
        </form-wizard>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { BRow, BCol, BFormGroup, BFormInput } from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import moment from "moment";
import "moment/locale/es";

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        FormWizard,
        TabContent,
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        vSelect,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
    },
    data() {
        return {
            required,
            organo: "",
            periodo_select: "",
            centro_select: "",
            area_select: "",
            cod_area: "",
            plaza: "",
            apellido_paterno: "",
            apellido_materno: "",
            nombres: "",
            genero_select: "",
            estadoCivil_select: "",
            domicilio: "",
            dni: "",
            fecha_nacimiento: "",
            nivelEducativo_select: "",
            celular: "",
            email: "",
            sistemaPension_select: "",
            cod_afp: "",
            nombre_apf: "",
            afp_cuz: "",
            fecha_afp: "",
            cod_cargo: "",
            cargo: "",
            especialidad_select: "",
            nivel_select: "",
            fecha_inicio: "",
            fecha_termino: "",
            bonificacion: "",
            remuneracion: "",
            ingresoMensual: "",
            banco_select: "",
            numeroCuentaBanco: "",
            motivoContrato_select: "",
            modalidadContrato_select: "",
            beneficiarioLey30555: "",
            ruc: "",
            numeroProcesoSeleccion: "",
            fechaProceso: "",
            situacionProceso_select: "",
            personalDejaCargo: "",
            tipoAusencia_select: "",
            fechaInicioAusencia: "",
            fechaFinAusencia: "",
            mesAltaPlanilla: "",
            suspensionCuarta: "",
            //selects
            parameterCentro: [],
            parameterArea: [],
            parameterGenero: [],
            parameterEstadoCivil: [],
            parameterNivelEducativo: [],
            parameterSistemaPension: [],
            parameterEspecialidad: [],
            parameterNivel: [],
            parameterBanco: [],
            parameterMotivoContrato: [],
            parameterModalidadContrato: [],
            parameterSituacionProceso: [],
            parameterTipoAusencia: [],
            //periodo
            periodoList: [],
        };
    },
    mounted() {
        this.getPeriodo();
        this.getParameter("incorporacion-select-centro", "centro");
        this.getParameter("incorporacion-select-area", "area");
        this.getParameter("incorporacion-select-genero", "genero");
        this.getParameter("incorporacion-select-estadocivil", "estadocivil");
        this.getParameter(
            "incorporacion-select-niveleducativo",
            "niveleducativo"
        );
        this.getParameter(
            "incorporacion-select-sistemapension",
            "sistemapension"
        );
        this.getParameter("incorporacion-select-especialidad", "especialidad");
        this.getParameter("incorporacion-select-nivel", "nivel");
        this.getParameter("incorporacion-select-banco", "banco");
        this.getParameter(
            "incorporacion-select-motivocontrato",
            "motivocontrato"
        );
        this.getParameter(
            "incorporacion-select-modalidadcontrato",
            "modalidadcontrato"
        );
        this.getParameter(
            "incorporacion-select-situacionproceso",
            "situacionproceso"
        );
        this.getParameter("incorporacion-select-tipoausencia", "tipoausencia");
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
        getParameter(name, model) {
            this.$http
                .get("/api/auth/parameter/" + name)
                .then((response) => {
                    if (model == "centro") {
                        this.parameterCentro = response.data;
                    }
                    if (model == "area") {
                        this.parameterArea = response.data;
                    }
                    if (model == "genero") {
                        this.parameterGenero = response.data;
                    }
                    if (model == "estadocivil") {
                        this.parameterEstadoCivil = response.data;
                    }
                    if (model == "niveleducativo") {
                        this.parameterNivelEducativo = response.data;
                    }
                    if (model == "sistemapension") {
                        this.parameterSistemaPension = response.data;
                    }
                    if (model == "especialidad") {
                        this.parameterArea = response.data;
                    }
                    if (model == "nivel") {
                        this.parameterEspecialidad = response.data;
                    }
                    if (model == "banco") {
                        this.parameterBanco = response.data;
                    }
                    if (model == "motivocontrato") {
                        this.parameterMotivoContrato = response.data;
                    }
                    if (model == "modalidadcontrato") {
                        this.parameterModalidadContrato = response.data;
                    }
                    if (model == "situacionproceso") {
                        this.parameterSituacionProceso = response.data;
                    }
                    if (model == "tipoausencia") {
                        this.parameterTipoAusencia = response.data;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        validationForm() {
            return new Promise((resolve, reject) => {
                this.$refs.personalInfo.validate().then((success) => {
                    if (success) {
                        resolve(true);
                    } else {
                        reject();
                    }
                });
            });
        },
        validationPeriodo() {
            return new Promise((resolve, reject) => {
                this.$refs.periodoInfo.validate().then((success) => {
                    if (success) {
                        resolve(true);
                    } else {
                        reject();
                    }
                });
            });
        },
        formSubmitted() {
            this.$http
                .post("/api/auth/incorporaciones/create", {
                    organo: this.organo,
                    periodo: this.periodo_select.name,
                    centro:
                        this.centro_select.name != null
                            ? this.centro_select.name
                            : null,
                    area:
                        this.area_select.name != null
                            ? this.area_select.name
                            : null,
                    cod_area: this.cod_area,
                    plaza: this.plaza,
                    apellido_paterno: this.apellido_paterno,
                    apellido_materno: this.apellido_materno,
                    nombres: this.nombres,
                    sexo:
                        this.genero_select.name != null
                            ? this.genero_select.name
                            : null,
                    estado_civil:
                        this.estadoCivil_select.name != null
                            ? this.estadoCivil_select.name
                            : null,
                    domicilio: this.domicilio,
                    dni: this.dni,
                    fecha_nacimiento: this.fecha_nacimiento,
                    nivel_educativo:
                        this.nivelEducativo_select.name != null
                            ? this.nivelEducativo_select.name
                            : null,
                    sistema_pension:
                        this.sistemaPension_select.name != null
                            ? this.sistemaPension_select.name
                            : null,
                    nombre_afp: this.nombre_afp,
                    cod_afp: this.cod_afp,
                    afp_cuz: this.afp_cuz,
                    fecha_afp: this.fecha_afp,
                    cod_cargo: this.cod_cargo,
                    cargo: this.cargo,
                    especialidad:
                        this.especialidad_select.name != null
                            ? this.especialidad_select.name
                            : null,
                    nivel:
                        this.nivel_select.name != null
                            ? this.nivel_select.name
                            : null,
                    fecha_inicio: this.fecha_inicio,
                    fecha_termino: this.fecha_termino,
                    bonificacion: this.bonificacion,
                    remuneracion: this.remuneracion,
                    ingreso_mensual: this.ingresoMensual,
                    banco:
                        this.banco_select.name != null
                            ? this.banco_select.name
                            : null,
                    numero_cuenta_banco: this.numeroCuentaBanco,
                    motivo_contrato:
                        this.motivoContrato_select.name != null
                            ? this.motivoContrato_select.name
                            : null,
                    modalidad_contrato:
                        this.modalidadContrato_select.name != null
                            ? this.modalidadContrato_select.name
                            : null,
                    beneficiario_ley30555: this.beneficiarioLey30555,
                    ruc: this.ruc,
                    numero_proceso_seleccion: this.numeroProcesoSeleccion,
                    fecha_proceso: this.fechaProceso,
                    situacion_proceso: this.situacionProceso,
                    personal_deja_Cargo: this.personalDejaCargo,
                    tipo_ausencia:
                        this.tipoAusencia_select.name != null
                            ? this.tipoAusencia_select.name
                            : null,
                    fecha_inicio_ausencia: this.fechaInicioAusencia,
                    fecha_fin_ausencia: this.fechaFinAusencia,
                    alta_baja: this.altaBaja,
                    mes_alta_planilla: this.mesAltaPlanilla,
                    suspension_cuarta: this.suspensionCuarta,
                    celular: this.celular,
                    correo: this.email,
                })
                .then(() => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "Registrado Correctamente",
                            icon: "CoffeeIcon",
                            variant: "success",
                            text: `Registrado correctamente`,
                        },
                    });
                    this.$router.back();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-wizard.scss";
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
