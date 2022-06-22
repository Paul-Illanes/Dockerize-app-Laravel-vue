<template>
    <b-card-code title="Crear Papeleta" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="4">
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
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
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
                                        />
                                    </b-input-group-append>
                                </b-input-group>
                            </b-col>

                            <b-col md="1" v-if="horas">
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

                            <b-col md="1" v-if="horas">
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
                            <b-col md="8">
                                <label>Observacion</label>
                                <b-form-textarea
                                    id="textarea-default"
                                    placeholder="......"
                                    rows="3"
                                    v-model="observacion"
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
} from "bootstrap-vue";
import { required } from "@validations";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";

export default {
    components: {
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
    },

    data() {
        return {
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
    methods: {
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
                    this.$http
                        .post("/api/auth/papeleta/create", {
                            tipo_permiso_id: this.selectedType.id,
                            dni: this.selectedUser.id,
                            fecha_salida: this.fecha_salida,
                            fecha_retorno: this.fecha_retorno,
                            hora_salida: this.hora_salida,
                            hora_retorno: this.hora_retorno,
                            observacion: this.observacion,
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
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>
