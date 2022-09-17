<template>
    <b-card-code title="Crear Contrato" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
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
                                    <label>Tipo de contrato</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="tipoContrato"
                                    >
                                        <v-select
                                            label="name"
                                            :options="tipo_contrato"
                                            v-model="tipoContrato"
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
                                    <label>Regimen Laboral</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="regimenLaboral"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="regimenLaboral"
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
                                    <label>DNI</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="empleado_dni"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="empleadoDni"
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
                                    <label>Ruc</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="empleado_ruc"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="empleadoRuc"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <b-form-group>
                                    <label>Direccion</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules=""
                                        name="direccion"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="direccion"
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
                                    <label>Cargo/Puesto</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="cargo"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="cargo"
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
                                    <label>Prestacion Servicio</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="prestacionServicio"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="prestacionServicio"
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
                                    <label>Remuneracion</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="remuneracion"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="remuneracion"
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
                                    <label>Fecha de inicio</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha_inicio"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_inicio"
                                            type="date"
                                        />
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Fecha de termino</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha_termino"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_termino"
                                            type="date"
                                        />
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Fecha firma de contrato</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha_firma"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_firma"
                                            type="date"
                                        />
                                    </validation-provider>
                                </b-form-group>
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

export default {
    components: {
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
            tipo_contrato: [
                { id: "PRIMIGENIO", name: "PRIMIGENIO" },
                { id: "RENOVACION", name: "RENOVACION" },
                { id: "PRORROGA", name: "PRORROGA" },
            ],
            users: [],
            persona: "",
            tipoContrato: "",
            regimenLaboral: "",
            empleadoDni: "",
            cargo: "",
            prestacionServicio: "",
            direccion: "",
            empleadoRuc: "",
            remuneracion: "",
            fecha_inicio: "",
            fecha_termino: "",
            fecha_firma: "",
        };
    },
    watch: {
        persona: function (val, oldval) {
            this.$http
                .get("/api/auth/contrato/getpersona/" + val.id)
                .then((response) => {
                    this.regimenLaboral = response.data.c_l;
                    this.empleadoDni = response.data.dni;
                    this.cargo = response.data.cargo;
                    this.prestacionServicio = response.data.dependencia;
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        this.$http
            .get("/api/auth/users/pluck")
            .then((response) => {
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        // await axios.get('/sanctum/csrf-cookie')
        // this.$http
        //     .get("/api/auth/persona/estructura")
        //     .then((response) => {
        //         this.substructura = response.data;
        //     })
        //     .catch((error) => {
        //         console.log(error);
        //     });
    },
    methods: {
        back() {
            this.$router.back();
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/contrato/create", {
                            tipo_contrato: this.tipoContrato.id,
                            regimen_laboral: this.regimenLaboral,
                            empleado_dni: this.empleadoDni,
                            cargo: this.cargo,
                            lugar_prestacion_servicio: this.prestacionServicio,
                            empleado_direccion: this.direccion,
                            empleado_ruc: this.empleadoRuc,
                            remuneracion: this.remuneracion,
                            fecha_inicio: this.fecha_inicio,
                            fecha_termino: this.fecha_termino,
                            fecha_firma_contrato: this.fecha_firma,
                        })
                        .then(() => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Papeleta registrado correctamente`,
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
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>
