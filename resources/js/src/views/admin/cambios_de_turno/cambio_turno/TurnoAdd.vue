<template>
    <b-card-code title="Registrar cambio de turno" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2 mr-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="4">
                                <b-form-group>
                                    <label>SEÑOR(A)</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="nombre de jefe"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="nombre_jefe"
                                            type="text"
                                            placeholder="nombre de jefe"
                                            autocomplete="on"
                                            show-decade-nav
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>JEFE DE SERVICIO DE:</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="nombre de servicio"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="nombre_servicio"
                                            type="text"
                                            placeholder="nombre del servicio"
                                            autocomplete="on"
                                            show-decade-nav
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <b-form-group>
                                    <label>Yo:</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="solicitante"
                                    >
                                        <v-select
                                            label="name"
                                            :options="personas"
                                            v-model="solicitante_id"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                    {{ solicitante_id }}
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <b-form-group>
                                    <label
                                        >SOLICITO(A) CAMBIO DE TURNO CON:</label
                                    >
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="aceptante"
                                    >
                                        <v-select
                                            label="name"
                                            :options="personas"
                                            v-model="aceptante_id"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="12" class="mt-50">
                                <h5>QUIEN TRABAJARA MI TURNO DE:</h5>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Turno aceptante</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="turno aceptante"
                                    >
                                        <v-select
                                            label="name"
                                            :options="parameters_turno"
                                            v-model="aceptante_turno"
                                            :disabled="
                                                solicitante_id ? false : true
                                            "
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha aceptante"
                                    >
                                        <label>Fecha aceptante</label>
                                        <!-- <b-form-input
                                            id="example-input"
                                            v-model="
                                                aceptante_turno_selected.fecha_turno
                                            "
                                            type="date"
                                        /> -->
                                        <v-select
                                            label="name"
                                            :options="aceptante_actividades"
                                            v-model="aceptante_fecha"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                    {{ aceptante_fecha }}
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="hora ingreso aceptante"
                                    >
                                        <label>Hora de ingreso</label>
                                        <b-form-input
                                            id="example-input"
                                            v-model="aceptante_hora_inicio"
                                            type="time"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="hora salida aceptante"
                                    >
                                        <label>Hora de salida</label>
                                        <b-form-input
                                            id="example-input"
                                            v-model="aceptante_hora_fin"
                                            type="time"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="12" class="mt-50">
                                <h5>YO TRABAJARE EL TURNO DE:</h5>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Turno solicitante</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="turno solicitante"
                                    >
                                        <v-select
                                            label="name"
                                            :options="parameters_turno"
                                            v-model="solicitante_turno"
                                            :disabled="
                                                aceptante_id ? false : true
                                            "
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                    {{ solicitante_turno }}
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha solicitante"
                                    >
                                        <label>Fecha solicitante</label>
                                        <!-- <b-form-input
                                            id="example-input"
                                            v-model="fecha_solicitante"
                                            type="date"
                                        /> -->
                                        <v-select
                                            label="name"
                                            :options="solicitante_actividades"
                                            v-model="solicitante_fecha"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                    {{ solicitante_fecha }}
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="hora ingreso solicitante"
                                    >
                                        <label>Hora de ingreso</label>
                                        <b-form-input
                                            id="example-input"
                                            v-model="solicitante_hora_inicio"
                                            type="time"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="hora salida solicitante"
                                    >
                                        <label>Hora de salida</label>
                                        <b-form-input
                                            id="example-input"
                                            v-model="solicitante_hora_fin"
                                            type="time"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <label>Motivo</label>
                                <b-form-textarea
                                    id="textarea-default"
                                    placeholder="......"
                                    rows="3"
                                    v-model="motivo"
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
            nombre_jefe: "",
            nombre_servicio: "",
            //solicitante
            solicitante_id: "",
            solicitante_turno: "",
            solicitante_fecha: "",
            solicitante_hora_inicio: "",
            solicitante_hora_fin: "",

            //aceptante
            aceptante_id: "",
            aceptante_turno: "",
            aceptante_fecha: "",
            aceptante_hora_inicio: "",
            aceptante_hora_fin: "",
            // hora_ingreso_aceptante: "",
            // hora_salida_aceptante: "",
            // aceptante_turno_selected: "",
            // fecha_aceptante: "",
            // hora_ingreso_solicitante: "",
            // hora_salida_solicitante: "",
            // solicitante_turno_selected: "",
            // fecha_solicitante: "",
            motivo: "",
            parameters_turno: [],
            personas: [],
            solicitante_actividades: [],
            aceptante_actividades: [],
            //
        };
    },
    created() {
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/getpersonas")
            .then((response) => {
                console.log(response);
                this.personas = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    mounted() {
        // this.getParameter("turnos");
        this.getActividades();
    },
    watch: {
        aceptante_fecha: function (val, oldval) {
            this.aceptante_hora_inicio = val.hora_inicio;
            this.aceptante_hora_fin = val.hora_fin;
        },
        solicitante_fecha: function (val, oldval) {
            this.solicitante_hora_inicio = val.hora_inicio;
            this.solicitante_hora_fin = val.hora_fin;
        },
        aceptante_turno: function (val, oldval) {
            console.log(this.aceptante_id, val.id);
            this.$http
                .post("/api/auth/cambios_turno/getFechas/", {
                    id: this.solicitante_id.id,
                    actividad: val.id,
                })
                .then((response) => {
                    this.aceptante_actividades = response.data;
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        solicitante_turno: function (val, oldval) {
            console.log(this.solicitante_id.id, val.id);
            this.$http
                .post("/api/auth/cambios_turno/getFechas/", {
                    id: this.aceptante_id.id,
                    actividad: val.id,
                })
                .then((response) => {
                    this.solicitante_actividades = response.data;
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        solicitante_id: function (val, oldval) {
            if (val.id != "") {
                this.$http
                    .get("/api/auth/cambios_turno/verify_solicitante/" + val.id)
                    .then((response) => {
                        if (response.data >= 2) {
                            this.$swal({
                                title: val.name,
                                text: "Ud ha llegado al limite de cambios de turno, no puede registar más cambios de turno para este mes.",
                                icon: "info",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                                buttonsStyling: false,
                            });
                            this.solicitante_id = "";
                        } else {
                            console.log("test");
                            // this.$http
                            //     .get("/api/auth/cambios_turno/getRol/" + val.id)
                            //     .then((response) => {
                            //         this.test = response.data;
                            //         console.log(response);
                            //     })
                            //     .catch((error) => {
                            //         console.log(error);
                            //     });
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        aceptante_id: function (val, oldval) {
            if (val.id != "") {
                this.$http
                    .get("/api/auth/cambios_turno/verify_aceptante/" + val.id)
                    .then((response) => {
                        if (response.data >= 2) {
                            this.$swal({
                                title: val.name,
                                text: "ha llegado al limite de cambios de turno, no puede registar más cambios de turno para este mes",
                                icon: "info",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                                buttonsStyling: false,
                            });
                            this.aceptante_id = "";
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
    },
    methods: {
        getParameter(name) {
            this.$http
                .get("/api/auth/parameter_turno/" + name)
                .then((response) => {
                    console.log(response);
                    this.parameters_turno = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getActividades() {
            this.$http
                .get("/api/auth/cambios_turno/getActividades")
                .then((response) => {
                    console.log(response);
                    this.parameters_turno = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        back() {
            this.$router.back();
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/cambios_turno/create", {
                            nombre_jefe: this.nombre_jefe,
                            nombre_servicio: this.nombre_servicio,
                            solicitante_id: this.solicitante_id.id,
                            aceptante_id: this.aceptante_id.id,
                            turno_origen: this.solicitante_turno.id,
                            turno_cambio: this.aceptante_turno.id,
                            origen_fecha: this.solicitante_fecha.fecha_turno,
                            origen_ingreso: this.solicitante_hora_inicio,
                            origen_salida: this.solicitante_hora_fin,
                            cambio_fecha: this.aceptante_fecha.fecha_turno,
                            cambio_ingreso: this.aceptante_hora_inicio,
                            cambio_salida: this.aceptante_hora_fin,
                            motivo: this.motivo,
                            rol_aceptante: this.aceptante_fecha.id,
                            rol_solicitante: this.solicitante_fecha.id,
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

                            this.$router.push({
                                name: "admin-turno-list",
                            });
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
@import "~@core/scss/vue/libs/vue-sweetalert.scss";
</style>
