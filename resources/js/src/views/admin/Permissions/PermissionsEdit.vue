<template>
    <b-card-code title="Agregar Permiso" no-body>
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
                                        name="description"
                                        vid="description"
                                        rules="required"
                                    >
                                        <b-form-input
                                            id="register-descripcion"
                                            v-model="description"
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
                                    @click.prevent="update"
                                >
                                    Actualizar
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BListGroup,
    BListGroupItem,
    BRow,
    BCol,
    BButton,
    BForm,
    BFormGroup,
    BPagination,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BCardTitle,
    BCardText,
    BCard,
    BCardBody,
    BCardSubTitle,
} from "bootstrap-vue";
import { required } from "@validations";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
export default {
    components: {
        BCardCode,
        BListGroup,
        BListGroupItem,
        BCard,
        BRow,
        BCol,
        BButton,
        BForm,
        BCardText,
        BCardTitle,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        // validations
        BCardSubTitle,
        BCardBody,
        ValidationProvider,
        ValidationObserver,
    },

    data() {
        return {
            name: "",
            description: "",
        };
    },

    mounted() {
        this.getDetail();
    },
    methods: {
        back() {
            this.$router.back();
        },
        getDetail() {
            this.$http
                .get(
                    "/api/auth/permission/detail/" +
                        this.$route.params.permisoId
                )
                .then((response) => {
                    this.name = response.data.name;
                    this.description = response.data.description;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        update() {
            this.$http
                .post(
                    "/api/auth/permission/update/" +
                        this.$route.params.permisoId,
                    {
                        name: this.name,
                        description: this.description,
                    }
                )
                .then(() => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: `Success`,
                            icon: "CoffeeIcon",
                            variant: "success",
                            text: `Actualizado correctamente!`,
                        },
                    });
                    this.$router.back();
                })
                .catch((error) => {
                    console.log(error);
                    for (let item in error.response.data.errors) {
                        this.errors.add({
                            scope: null,
                            field: item,
                            rule: "required",
                            msg: error.response.data.errors[item][0],
                        });
                    }
                    this.$vs.notify({
                        title: "Failed",
                        text: error.response.data.message,
                        position: "top-right",
                        color: "danger",
                    });
                });
        },
    },
};
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
