<template>
    <b-card-code title="Crear Papeleta" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2 mr-2"
                        @submit.prevent="register"
                    >
                        <section id="blockquotes" class="p-1" v-if="detail">
                            <b-row class="p-2 border">
                                <b-col md="6" class="mb-50">
                                    <b>Nombre :</b> {{ detail.nombre }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>DNI :</b> {{ detail.dni }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>Cod. Planilla :</b>
                                    {{ detail.codigo_planilla }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>Fecha Nacimiento :</b>
                                    {{ detail.fecha_nacimiento }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>Fecha Ingreso :</b>
                                    {{ detail.fecha_ingreso }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>Cargo :</b> {{ detail.cargo }}
                                </b-col>
                                <b-col md="3" class="mb-50">
                                    <b>Regimen Laboral :</b>
                                    {{ detail.regimen_laboral }}
                                </b-col>
                            </b-row>
                        </section>
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
                                        name="numero informe"
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
                                    <label>Faltas</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="Faltas"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="faltas"
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
                                    <label>Tardanzas</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="tardanzas"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="tardanzas"
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
                                    <label>Sancion Disciplinaria</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="sancion disciplinaria"
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
                                    <label>Huelga</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="huelga"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="huelga"
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
                            <b-col md="3">
                                <b-form-group>
                                    <label>Vacaciones no gozadas</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="vacaciones no gozadas"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="vacacionesNoGozadas"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <label
                                    >Periodo de pago BONO de
                                    productividad</label
                                >
                                <b-form-input
                                    id="example-input"
                                    v-model="bonoPago"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label
                                    >Descuento de pago BONO de productividad por
                                    no corresponder</label
                                >
                                <b-form-input
                                    id="example-input"
                                    v-model="descuentoBonoPago"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Guardias hospitalarias</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="guardiasHospitalarias"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Horas extras</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="horasExtras"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Horas RPCT</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="horasRpct"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>PCT</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="pct"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Notas adicionales</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="notasAdicionales"
                                    type="text"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Zona menor desarrollo</label>
                                <b-form-input
                                    id="example-input"
                                    v-model="zonaMenorDesarrollo"
                                    type="text"
                                />
                            </b-col>

                            <b-col cols="12" class="mt-2 mb-5">
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
                        </b-row>
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

export default {
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
            persona: "",
            nit: "",
            numeroInforme: "",
            fechaInforme: "",
            faltas: "",
            tardanzas: "",
            licencias: "",
            permisosParticulares: "",
            sancionDisciplinaria: "",
            huelga: "",
            licenciaCovid: "",
            vacacionesNoGozadas: "",
            bonoPago: "",
            descuentoBonoPago: "",
            guardiasHospitalarias: "",
            horasExtras: "",
            horasRpct: "",
            pct: "",
            notasAdicionales: "",
            zonaMenorDesarrollo: "",
            //selects
            users: [],
            detail: "",
        };
    },
    watch: {
        persona: function (val, oldval) {
            this.$http
                .get("/api/auth/informe_cese/detalle/" + val.id)
                .then((response) => {
                    this.detail = response.data;
                    console.log(response);
                    // this.show = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
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
    },
    methods: {
        back() {
            this.$router.back();
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/informe_cese/create", {
                            nombre: this.persona.name,
                            baja_id: this.persona.id,
                            nit: this.nit,
                            numero_informe: this.numeroInforme,
                            fecha_informe: this.fechaInforme,
                            faltas: this.faltas,
                            tardanzas: this.tardanzas,
                            licencias: this.licencias,
                            permisos_particulares: this.permisosParticulares,
                            sancion_disciplinaria: this.sancionDisciplinaria,
                            huelga: this.huelga,
                            licencia_covid: this.licenciaCovid,
                            vacaciones_no_gozadas: this.vacacionesNoGozadas,
                            bono_pago: this.bonoPago,
                            descuento_bono_pago: this.descuentoBonoPago,
                            guardias: this.guardiasHospitalarias,
                            horas_extras: this.horasExtras,
                            horas_rpct: this.horasRpct,
                            pct: this.pct,
                            notas_adicionales: this.notasAdicionales,
                            zona_menor_desarrollo: this.zonaMenorDesarrollo,
                        })
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.$router.back();
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
