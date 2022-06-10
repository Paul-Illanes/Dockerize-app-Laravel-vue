<template>
    <b-card-text no-body class="mr-5">
        <b-card-title
            title-tag="h2"
            class="font-weight-bold mb-1 text-center text-white"
        >
            <strong> DRH</strong>-ESSALUD
        </b-card-title>
        <validation-observer ref="registerForm" #default="{ invalid }">
            <b-form
                class="auth-register-form p-2 bg-white"
                @submit.prevent="register"
            >
                <b-form-group label="DNI" label-for="register-username">
                    <validation-provider
                        #default="{ errors }"
                        name="username"
                        vid="username"
                        rules="required"
                    >
                        <b-form-input
                            id="register-dni"
                            v-model="username"
                            name="register-dni"
                            :state="errors.length > 0 ? false : null"
                            placeholder="DNI / CE"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group label="Nombres" label-for="register-username">
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
                            :state="errors.length > 0 ? false : null"
                            placeholder="Nombres"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group
                    label="Apellido Paterno"
                    label-for="register-username"
                >
                    <validation-provider
                        #default="{ errors }"
                        name="lastname"
                        vid="lastname"
                        rules="required"
                    >
                        <b-form-input
                            id="register-lastname"
                            v-model="lastname"
                            name="register-lastname"
                            :state="errors.length > 0 ? false : null"
                            placeholder="Apellido paterno"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group
                    label="Apellido Materno"
                    label-for="register-mother_lastname"
                >
                    <validation-provider
                        #default="{ errors }"
                        name="mother_lastname"
                        vid="mother_lastname"
                        rules="required"
                    >
                        <b-form-input
                            id="register-mother_lastname"
                            v-model="mother_lastname"
                            name="register-mother_lastname"
                            :state="errors.length > 0 ? false : null"
                            placeholder="Apellido materno"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group
                    label="Correo electronico personal"
                    label-for="register-email"
                >
                    <validation-provider
                        #default="{ errors }"
                        name="email"
                        vid="email"
                        rules="required|email"
                    >
                        <b-form-input
                            id="register-email"
                            v-model="email"
                            name="register-email"
                            :state="errors.length > 0 ? false : null"
                            placeholder="user@example.com"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group
                    label="Confirme correo electronico personal"
                    label-for="register-c_email"
                >
                    <validation-provider
                        #default="{ errors }"
                        name="c_email"
                        vid="c_email"
                        rules="required|email"
                    >
                        <b-form-input
                            id="register-c_email"
                            v-model="c_email"
                            name="register-c_email"
                            :state="errors.length > 0 ? false : null"
                            placeholder="user@example.com"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group label="Celular" label-for="register-celular">
                    <validation-provider
                        #default="{ errors }"
                        name="celular"
                        vid="celular"
                        rules="required"
                    >
                        <b-form-input
                            id="register-celular"
                            v-model="celular"
                            name="register-celular"
                            :state="errors.length > 0 ? false : null"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group label-for="register-password" label="Password">
                    <validation-provider
                        #default="{ errors }"
                        name="Password"
                        vid="password"
                        rules="required"
                    >
                        <b-input-group
                            class="input-group-merge"
                            :class="errors.length > 0 ? 'is-invalid' : null"
                        >
                            <b-form-input
                                id="register-password"
                                v-model="password"
                                class="form-control-merge"
                                :type="passwordFieldType"
                                :state="errors.length > 0 ? false : null"
                                name="register-password"
                                placeholder="············"
                            />
                            <b-input-group-append is-text>
                                <feather-icon
                                    :icon="passwordToggleIcon"
                                    class="cursor-pointer"
                                    @click="togglePasswordVisibility"
                                />
                            </b-input-group-append>
                        </b-input-group>
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group
                    label-for="register-password"
                    label="Confirmar Password"
                >
                    <validation-provider
                        #default="{ errors }"
                        name="c_assword"
                        vid="c_password"
                        rules="required"
                    >
                        <b-input-group
                            class="input-group-merge"
                            :class="errors.length > 0 ? 'is-invalid' : null"
                        >
                            <b-form-input
                                id="register-c_password"
                                v-model="c_password"
                                class="form-control-merge"
                                :type="passwordFieldType"
                                :state="errors.length > 0 ? false : null"
                                name="register-password"
                                placeholder="············"
                            />
                            <b-input-group-append is-text>
                                <feather-icon
                                    :icon="passwordToggleIcon"
                                    class="cursor-pointer"
                                    @click="togglePasswordVisibility"
                                />
                            </b-input-group-append>
                        </b-input-group>
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group>
                    <b-form-checkbox
                        id="register-privacy-policy"
                        v-model="status"
                        name="checkbox-1"
                    >
                        Acepto los
                        <b-link>terminos & condiciones</b-link>
                    </b-form-checkbox>
                </b-form-group>

                <b-button
                    variant="primary"
                    block
                    type="submit"
                    :disabled="invalid"
                >
                    Crear cuenta
                </b-button>
                <b-card-text class="text-center mt-2">
                    <span>Ya tienes una cuenta? </span>
                    <b-link :to="{ name: 'auth-login' }">
                        <span>Inicia Session</span>
                    </b-link>
                </b-card-text>
            </b-form>
        </validation-observer>
    </b-card-text>

    <!-- <b-row class="auth-inner m-0">
            <b-link class="brand-logo">
                <vuexy-logo />
                <h2 class="brand-text text-primary ml-1">ESSALUD</h2>
            </b-link>

            <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
                <div
                    class="w-100 d-lg-flex align-items-center justify-content-center px-5"
                >
                    <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
                        <div class="text-center">
                            <b-img
                                fluid
                                :src="imgUrl"
                                width="400px"
                                alt="Login V2"
                            />
                        </div>
                        <b-card-title
                            title-tag="h2"
                            class="font-weight-bold mb-1 text-center"
                        >
                            Consideraciones
                        </b-card-title>
                        <b-card-text class="mb-2 text-center">
                            Uso del sistema
                        </b-card-text>
                        <p class="card-text">
                            Debe registrarse con sus datos personales, y su
                            <strong>Correo electrónico personal</strong>.
                        </p>

                        <p class="card-text">
                            Debido a la importancia de la información personal
                            que se gestiona, luego de su registro le llegará un
                            correo electrónico con un código de validación.
                        </p>

                        <p class="card-text">
                            Antes de pasar a otro módulo es necesario esperar
                            que el actual esté completamente cargado.
                        </p>
                        <div class="bg-info p-2">
                            <p class="card-text font-weight-bold">
                                Al registrarme declaro bajo JURAMENTO, la
                                veracidad de la información proporcionada,
                                somentiéndome a la verificación posterior y
                                asumiendo la responsabilidad en caso de
                                comprobarse lo contrario, de acuerdo con la ley
                                del Procedimiento Administrativo General N°
                                27444.
                            </p>
                        </div>
                        <a target="_blank" href="http://172.26.58.5/descargas"
                            >Descargas</a
                        >
                    </b-col>
                </div>
            </b-col>

            <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
                <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
                    <b-card-title class="mb-1"> DRH-ESSALUD 🚀 </b-card-title>
                    <b-card-text class="mb-2"> Crear cuenta nueva </b-card-text>

                    <validation-observer
                        ref="registerForm"
                        #default="{ invalid }"
                    >
                        <b-form
                            class="auth-register-form mt-2"
                            @submit.prevent="register"
                        >
                            <b-form-group
                                label="DNI"
                                label-for="register-username"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="username"
                                    vid="username"
                                    rules="required"
                                >
                                    <b-form-input
                                        id="register-dni"
                                        v-model="username"
                                        name="register-dni"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="DNI / CE"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Nombres"
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
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="Nombres"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Apellido Paterno"
                                label-for="register-username"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="lastname"
                                    vid="lastname"
                                    rules="required"
                                >
                                    <b-form-input
                                        id="register-lastname"
                                        v-model="lastname"
                                        name="register-lastname"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="Apellido paterno"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Apellido Materno"
                                label-for="register-mother_lastname"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="mother_lastname"
                                    vid="mother_lastname"
                                    rules="required"
                                >
                                    <b-form-input
                                        id="register-mother_lastname"
                                        v-model="mother_lastname"
                                        name="register-mother_lastname"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="Apellido materno"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Correo electronico personal"
                                label-for="register-email"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="email"
                                    vid="email"
                                    rules="required|email"
                                >
                                    <b-form-input
                                        id="register-email"
                                        v-model="email"
                                        name="register-email"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="user@example.com"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Confirme correo electronico personal"
                                label-for="register-c_email"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="c_email"
                                    vid="c_email"
                                    rules="required|email"
                                >
                                    <b-form-input
                                        id="register-c_email"
                                        v-model="c_email"
                                        name="register-c_email"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        placeholder="user@example.com"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label="Celular"
                                label-for="register-celular"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="celular"
                                    vid="celular"
                                    rules="required"
                                >
                                    <b-form-input
                                        id="register-celular"
                                        v-model="celular"
                                        name="register-celular"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label-for="register-password"
                                label="Password"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="Password"
                                    vid="password"
                                    rules="required"
                                >
                                    <b-input-group
                                        class="input-group-merge"
                                        :class="
                                            errors.length > 0
                                                ? 'is-invalid'
                                                : null
                                        "
                                    >
                                        <b-form-input
                                            id="register-password"
                                            v-model="password"
                                            class="form-control-merge"
                                            :type="passwordFieldType"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            name="register-password"
                                            placeholder="············"
                                        />
                                        <b-input-group-append is-text>
                                            <feather-icon
                                                :icon="passwordToggleIcon"
                                                class="cursor-pointer"
                                                @click="
                                                    togglePasswordVisibility
                                                "
                                            />
                                        </b-input-group-append>
                                    </b-input-group>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group
                                label-for="register-password"
                                label="Confirmar Password"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="c_assword"
                                    vid="c_password"
                                    rules="required"
                                >
                                    <b-input-group
                                        class="input-group-merge"
                                        :class="
                                            errors.length > 0
                                                ? 'is-invalid'
                                                : null
                                        "
                                    >
                                        <b-form-input
                                            id="register-c_password"
                                            v-model="c_password"
                                            class="form-control-merge"
                                            :type="passwordFieldType"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            name="register-password"
                                            placeholder="············"
                                        />
                                        <b-input-group-append is-text>
                                            <feather-icon
                                                :icon="passwordToggleIcon"
                                                class="cursor-pointer"
                                                @click="
                                                    togglePasswordVisibility
                                                "
                                            />
                                        </b-input-group-append>
                                    </b-input-group>
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group>
                                <b-form-checkbox
                                    id="register-privacy-policy"
                                    v-model="status"
                                    name="checkbox-1"
                                >
                                    Acepto los
                                    <b-link>terminos & condiciones</b-link>
                                </b-form-checkbox>
                            </b-form-group>

                            <b-button
                                variant="primary"
                                block
                                type="submit"
                                :disabled="invalid"
                            >
                                Crear cuenta
                            </b-button>
                        </b-form>
                    </validation-observer>

                    <p class="text-center mt-2">
                        <span>Ya tienes una cuenta?</span>
                        <b-link :to="{ name: 'auth-login' }">
                            <span>&nbsp;Inicia session</span>
                        </b-link>
                    </p>
                </b-col>
            </b-col>

            <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                :disabled="show"
                variant="outline-primary"
                @click="show = true"
            >
                Show overlay
            </b-button>
        </b-row>
        <b-overlay :show="show" no-wrap /> -->
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from "vee-validate";
import VuexyLogo from "@core/layouts/components/Logo.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import {
    BRow,
    BCol,
    BLink,
    BButton,
    BForm,
    BFormCheckbox,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BImg,
    BCardTitle,
    BCardText,
} from "bootstrap-vue";
import { required, email } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";

export default {
    components: {
        VuexyLogo,
        BRow,
        BImg,
        BCol,
        BLink,
        BButton,
        BForm,
        BCardText,
        BCardTitle,
        BFormCheckbox,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        // validations
        ValidationProvider,
        ValidationObserver,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            status: "",
            username: "",
            name: "",
            lastname: "",
            mother_lastname: "",
            email: "",
            c_email: "",
            password: "",
            c_password: "",
            celular: "",
            sideImg: require("@/assets/images/pages/login-v2.svg"),
            // validation
            required,
            email,
        };
    },
    computed: {
        passwordToggleIcon() {
            return this.passwordFieldType === "password"
                ? "EyeIcon"
                : "EyeOffIcon";
        },
        imgUrl() {
            if (store.state.appConfig.layout.skin === "dark") {
                // eslint-disable-next-line vue/no-side-effects-in-computed-properties
                this.sideImg = require("@/assets/images/pages/register-v2-dark.svg");
                return this.sideImg;
            }
            return this.sideImg;
        },
    },
    methods: {
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/register", {
                            username: this.username,
                            name: this.name,
                            lastname: this.lastname,
                            mother_lastname: this.mother_lastname,
                            email: this.email,
                            c_email: this.c_email,
                            celular: this.celular,
                            password: this.password,
                            c_password: this.password,
                        })
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Se envio un codigo de confirmacion a tu correo!`,
                                },
                            });
                            this.$router.push({ name: "auth-login" });
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
