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
                        <b-row>
                            <b-col md="6">
                                <b-form-group>
                                    <label>Solicitante</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="selectedUser"
                                    >
                                        <v-select
                                            label="name"
                                            :options="users"
                                            v-model="selectedUser"
                                            name="users"
                                            :disabled="SelectedVac != ''"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <b-form-group>
                                    <label>tipo de Permiso</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="selectedType"
                                    >
                                        <v-select
                                            label="name"
                                            :options="permisos"
                                            v-model="selectedType"
                                            name="permisos"
                                            :disabled="SelectedVac != ''"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="12">
                                <b-form-group>
                                    <b-form-checkbox
                                        v-model="dias"
                                        name="check-button"
                                        switch
                                        inline
                                    >
                                        Permiso por dias
                                    </b-form-checkbox>
                                    <b-form-checkbox
                                        v-model="horas"
                                        name="check-button"
                                        switch
                                        inline
                                        :disabled="
                                            selectedType.name ==
                                            'A CUENTA DE VACACIONES'
                                        "
                                    >
                                        Permiso por horas
                                    </b-form-checkbox>
                                </b-form-group>
                            </b-col>
                            <b-col md="3" v-if="dias">
                                <label>Fecha de salida</label>
                                <b-input-group class="mb-1">
                                    <b-form-input
                                        id="example-input"
                                        v-model="fecha_salida"
                                        type="text"
                                        placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                        show-decade-nav
                                        :disabled="SelectedVac != ''"
                                    />
                                    <b-input-group-append>
                                        <b-form-datepicker
                                            v-model="fecha_salida"
                                            show-decade-nav
                                            button-only
                                            button-variant="outline-primary"
                                            right
                                            size="sm"
                                            locale="es-ES"
                                            aria-controls="example-input"
                                            @context="onContext"
                                            :disabled="SelectedVac != ''"
                                        />
                                    </b-input-group-append>
                                </b-input-group>
                            </b-col>

                            <b-col md="3" v-if="dias">
                                <label>Fecha de retorno</label>
                                <b-input-group class="mb-1">
                                    <b-form-input
                                        id="example-input"
                                        v-model="fecha_retorno"
                                        type="text"
                                        placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                        show-decade-nav
                                        :disabled="SelectedVac != ''"
                                    />
                                    <b-input-group-append>
                                        <b-form-datepicker
                                            v-model="fecha_retorno"
                                            show-decade-nav
                                            button-only
                                            button-variant="outline-primary"
                                            right
                                            size="sm"
                                            locale="es-ES"
                                            aria-controls="example-input"
                                            @context="onContext"
                                            :disabled="SelectedVac != ''"
                                        />
                                    </b-input-group-append>
                                </b-input-group>
                            </b-col>
                            <b-col
                                md="12"
                                v-if="
                                    show &&
                                    SelectedVac == '' &&
                                    vacaciones != 'vacio'
                                "
                            >
                                <b-table
                                    responsive
                                    :items="vacaciones"
                                    :fields="fields"
                                    class="mb-0"
                                    v-if="vacaciones.length >= 1"
                                >
                                    <template #cell(pend)="data">
                                        {{
                                            data.item.dias_pendientes -
                                            data.item.dias_usados
                                        }}
                                    </template>
                                    <template #cell(action)="data">
                                        <b-button
                                            variant="primary"
                                            size="sm"
                                            :disabled="
                                                data.item.pend == 0 ||
                                                data.item.pend < limitDays
                                            "
                                            @click="vacacion(data.item)"
                                        >
                                            Elegir
                                        </b-button>
                                    </template>
                                </b-table>
                                <b-card
                                    border-variant="danger"
                                    bg-variant="transparent"
                                    title="Ocurrio un error"
                                    class="shadow-none"
                                    v-if="
                                        vacaciones.length == 0 &&
                                        vacaciones != 'vacio'
                                    "
                                >
                                    <b-card-text>
                                        El solicitante aun no completo un
                                        periodo laboral, no cuenta con
                                        vacaciones
                                    </b-card-text>
                                </b-card>
                            </b-col>

                            <b-col md="12" v-if="SelectedVac">
                                <b-card
                                    border-variant="info"
                                    bg-variant="transparent"
                                    title="Periodo seleccionado: "
                                    class="shadow-none"
                                >
                                    <b-card-text>
                                        {{ SelectedVac }}, (dias:
                                        {{ limitDays }})
                                    </b-card-text>
                                </b-card>
                            </b-col>

                            <b-col md="3" v-if="horas">
                                <b-form-group>
                                    <label>Hora de salida</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="hora_salida"
                                    >
                                        <flat-pickr
                                            v-model="hora_salida"
                                            class="form-control"
                                            :config="{
                                                enableTime: true,
                                                noCalendar: true,
                                                dateFormat: 'H:i',
                                            }"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>

                            <b-col md="3" v-if="horas">
                                <label>Hora de retorno</label>
                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="hora_retorno"
                                >
                                    <flat-pickr
                                        v-model="hora_retorno"
                                        class="form-control"
                                        :config="{
                                            enableTime: true,
                                            noCalendar: true,
                                            dateFormat: 'H:i',
                                        }"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12">
                                <label>Observacion</label>
                                <b-form-textarea
                                    id="textarea-default"
                                    placeholder="......"
                                    rows="3"
                                    v-model="observacion"
                                />
                                {{ SelectedVac }}
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
            limitDays: "",
            diasVac: "",
            show: false,
            SelectedVac: "",
            formatted: "",
            selected: "",
            descripcion: "",
            selectedUser: "",
            observacion: "",
            users: [],
            permisos: [],
            selectedType: "",
            activo: "",
            horas: "",
            dias: "",
            fecha_salida: "",
            fecha_retorno: "",
            hora_salida: "",
            hora_retorno: "",
            vacaciones: [],
            fields: [
                "periodo",
                "dias_pendientes",
                "dias_usados",
                "pend",
                {
                    key: "pend",
                    label: "pend",
                },
                {
                    key: "action",
                    label: "action",
                },
            ],
        };
    },
    created() {
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/users/pluck")
            .then((response) => {
                console.log(response);
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        this.$http
            .get("/api/auth/users/tipo_permisos")
            .then((response) => {
                console.log(response);
                this.permisos = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    watch: {
        selectedType: function (val, oldval) {
            if (
                val.name == "A CUENTA DE VACACIONES" &&
                this.selectedUser != ""
            ) {
                // this.show = true;
                this.$http
                    .get("/api/auth/vacaciones/report/" + this.selectedUser.id)
                    .then((response) => {
                        console.log(response.data);
                        this.vacaciones = response.data;
                        console.log(this.vacaciones);
                        this.show = true;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.vacaciones = "vacio";
            }
        },
        selectedUser: function (val, oldval) {
            if (this.selectedType.name == "A CUENTA DE VACACIONES") {
                // this.show = true;
                this.$http
                    .get("/api/auth/vacaciones/report/" + this.selectedUser.id)
                    .then((response) => {
                        this.vacaciones = response.data;
                        (this.show = true), console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        fecha_retorno: function (val, oldval) {
            var fecha1 = moment(this.fecha_salida);
            var fecha2 = moment(this.fecha_retorno);
            this.limitDays = fecha2.diff(fecha1, "days");
        },
        fecha_salida: function (val, oldval) {
            var fecha1 = moment(this.fecha_salida);
            var fecha2 = moment(this.fecha_retorno);
            this.limitDays = fecha2.diff(fecha1, "days");
        },
    },
    methods: {
        vacacion(data) {
            if (!this.fecha_salida || !this.fecha_retorno) {
                this.$swal({
                    title: "Primero elije las fechas",
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                    showClass: {
                        popup: "animate__animated animate__fadeIn",
                    },
                    buttonsStyling: false,
                });
                this.dias = true;
            }
            if (this.fecha_salida && this.fecha_retorno) {
                this.SelectedVac = data.anio;
                console.log(data);
            }
        },
        onContext(ctx) {
            // The date formatted in the locale, or the `label-no-date-selected` string
            this.formatted = ctx.selectedFormatted;
            // The following will be an empty string until a valid date is entered
            this.selected = ctx.selectedYMD;
        },
        back() {
            this.$router.back();
        },

        getRoles() {
            // await axios.get('/sanctum/csrf-cookie')
            this.$http
                .get("/api/roles")
                .then((response) => {
                    this.roles = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    if (this.selectedType.id == 4) {
                        if (this.SelectedVac) {
                            this.$http
                                .post("/api/auth/papeleta/create", {
                                    periodo: this.SelectedVac,
                                    tipo_permiso_id: this.selectedType.id,
                                    dni: this.selectedUser.id,
                                    fecha_salida: this.fecha_salida,
                                    fecha_retorno: this.fecha_retorno,
                                    hora_salida: this.hora_salida,
                                    hora_retorno: this.hora_retorno,
                                    observacion: this.observacion,
                                })
                                .then((res) => {
                                    if (res.status == 202) {
                                        this.$toast({
                                            component: ToastificationContent,
                                            position: "top-right",
                                            props: {
                                                title: "Ocurrio un error",
                                                icon: "CoffeeIcon",
                                                variant: "danger",
                                                text: `No se encontro el contrato de personal`,
                                            },
                                        });
                                    } else {
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
                                    }

                                    // this.$router.back();
                                })
                                .catch((error) => {
                                    this.$refs.registerForm.setErrors(
                                        error.response.data.errors
                                    );
                                });
                        } else if (this.vacaciones.length == 0) {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Ocurrio un error",
                                    icon: "CoffeeIcon",
                                    variant: "danger",
                                    text: `El personal elegido no puede generar a cuenta de vacaciones`,
                                },
                            });
                        } else {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Ocurrio un error",
                                    icon: "CoffeeIcon",
                                    variant: "danger",
                                    text: `Elija un periodo`,
                                },
                            });
                        }
                    } else {
                        this.$http
                            .post("/api/auth/papeleta/create", {
                                periodo: this.SelectedVac,
                                tipo_permiso_id: this.selectedType.id,
                                dni: this.selectedUser.id,
                                fecha_salida: this.fecha_salida,
                                fecha_retorno: this.fecha_retorno,
                                hora_salida: this.hora_salida,
                                hora_retorno: this.hora_retorno,
                                observacion: this.observacion,
                            })
                            .then((res) => {
                                if (res.status == 202) {
                                    this.$toast({
                                        component: ToastificationContent,
                                        position: "top-right",
                                        props: {
                                            title: "Ocurrio un error",
                                            icon: "CoffeeIcon",
                                            variant: "danger",
                                            text: `No se encontro el contrato de personal`,
                                        },
                                    });
                                } else {
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
                                }

                                // this.$router.back();
                            })
                            .catch((error) => {
                                this.$refs.registerForm.setErrors(
                                    error.response.data.errors
                                );
                            });
                    }
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
