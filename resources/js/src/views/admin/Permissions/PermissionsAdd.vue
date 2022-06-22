<template>
    <b-card-code title="Agregar Permiso" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="6">
                                <b-form-group
                                    label="Nombre"
                                    label-for="register-username"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="name"
                                        vid="name"
                                        rules="required"
                                    >
                                        <b-form-input
                                            id="register-name"
                                            v-model="name"
                                            name="register-name"
                                            style="text-transform: uppercase"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            placeholder=""
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <b-form-group
                                    label="Descripcion"
                                    label-for="register-descripcion"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="descripcion"
                                        vid="descripcion"
                                        rules="required"
                                    >
                                        <b-form-input
                                            id="register-descripcion"
                                            v-model="descripcion"
                                            name="register-descripcion"
                                            style="text-transform: uppercase"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            placeholder=""
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
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
} from "bootstrap-vue";
import { required } from "@validations";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

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
    },

    data() {
        return {
            descripcion: "",
            name: "",
        };
    },
    created() {
        this.$http
            .get("/api/auth/permissions")
            .then((response) => {
                this.permissions = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
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
                        .post("/api/auth/permission/create", {
                            name: this.name,
                            description: this.descripcion,
                        })
                        .then(() => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Permiso registrado correctamente`,
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
@import "~@core/scss/vue/pages/page-auth.scss";
</style>
