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
                            <b-col md="3">
                                <b-form-group>
                                    <label>Codigo Planilla</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="codigo_planilla"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="codigo_planilla"
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
                                    <label>Superstructura</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="superstructura"
                                    >
                                        <v-select
                                            label="name"
                                            :options="substructura"
                                            v-model="superstructura"
                                            name="superstructura"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="8">
                                <b-form-group>
                                    <label>Apellidos y Nombres</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="nombres"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="nombres"
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
                                    <label>DNI</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="dni"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="dni"
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
                                    <label>Estructura</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="estructura"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="estructura"
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
                                    <label>Cargo</label>

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
                                    <label>Fecha de ingreso</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha_ingreso"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_ingreso"
                                            type="date"
                                        />
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Fecha de nacimiento</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha_nacimiento"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_nacimiento"
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
            codigo_planilla: "",
            superstructura: "",
            nombres: "",
            dni: "",
            estructura: "",
            cargo: "",
            fecha_ingreso: "",
            fecha_nacimiento: "",
            substructura: [],
        };
    },
    created() {
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/persona/superstructura")
            .then((response) => {
                this.substructura = response.data;
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
                        .post("/api/auth/persona/create", {
                            cod_planilla: this.codigo_planilla,
                            sub_structura: this.superstructura.id,
                            nombres: this.nombres,
                            dni: this.dni,
                            estrucura: this.estructura,
                            cargo: this.cargo,
                            fecha_ingreso: this.fecha_ingreso,
                            fecha_nacimiento: this.fecha_nacimiento,
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
