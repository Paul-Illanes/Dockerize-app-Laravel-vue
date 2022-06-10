<template>
    <b-card-text no-body class="mr-5">
        <b-card-title
            title-tag="h2"
            class="font-weight-bold mb-1 text-center text-white"
        >
            <strong> DRH</strong>-ESSALUD
        </b-card-title>
        <validation-observer ref="loginValidation">
            <b-form class="auth-login-form p-2 bg-white" @submit.prevent>
                <b-form-group label="Usuario" label-for="login-email">
                    <validation-provider
                        #default="{ errors }"
                        name="username"
                        rules="required"
                    >
                        <b-form-input
                            id="login-email"
                            v-model="userName"
                            :state="errors.length > 0 ? false : null"
                            name="login-email"
                            placeholder=""
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group>
                    <div class="d-flex justify-content-between">
                        <label for="login-password">Password</label>
                        <b-link
                            :to="{
                                name: 'auth-forgot-password-v2',
                            }"
                        >
                            <small>Olvidaste tu password?</small>
                        </b-link>
                    </div>
                    <validation-provider
                        #default="{ errors }"
                        name="Password"
                        rules="required"
                    >
                        <b-input-group
                            class="input-group-merge"
                            :class="errors.length > 0 ? 'is-invalid' : null"
                        >
                            <b-form-input
                                id="login-password"
                                v-model="password"
                                :state="errors.length > 0 ? false : null"
                                class="form-control-merge"
                                :type="passwordFieldType"
                                name="login-password"
                                placeholder="············"
                            />
                            <b-input-group-append is-text>
                                <feather-icon
                                    class="cursor-pointer"
                                    :icon="passwordToggleIcon"
                                    @click="togglePasswordVisibility"
                                />
                            </b-input-group-append>
                        </b-input-group>
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>

                <b-form-group>
                    <b-form-checkbox
                        id="remember-me"
                        v-model="status"
                        name="checkbox-1"
                    >
                        recuerdame
                    </b-form-checkbox>
                </b-form-group>

                <b-button
                    type="submit"
                    variant="primary"
                    block
                    @click="validationForm"
                >
                    Ingresar
                </b-button>
            </b-form>
        </validation-observer>
        <b-card-text class="text-center m-0 bg-light">
            <span>Nuevo en la plataforma? </span>
            <b-link :to="{ name: 'auth-register' }">
                <span>&nbsp;Crear cuenta</span>
            </b-link>
        </b-card-text>
        <b-card-text class="m-0 bg-light p-2">
            <span style="color: #000000; font-size: 18px; font-weight: 500"
                >SOPORTE
            </span>
            <hr />
            <p
                v-for="item in items"
                class="mb-2"
                style="font-size: 14px; font-weight: 500"
            >
                {{ item.name }} {{ item.lastname }} {{ item.mother_lastname }}
                <br />
                Correo: {{ item.email }}
                <br />
                Teléfono: {{ item.telefono }}
            </p>
        </b-card-text>
    </b-card-text>
    <!-- <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
            <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
                <b-card-title
                    title-tag="h2"
                    class="font-weight-bold mb-1 text-center"
                >
                    DIVISION DE RECURSOS HUMANOS
                </b-card-title>
                <b-card-text class="mb-2 text-center">
                    Plataforma Digital
                </b-card-text>

                <validation-observer ref="loginValidation">
                    <b-form class="auth-login-form mt-2" @submit.prevent>
                        <b-form-group label="Usuario" label-for="login-email">
                            <validation-provider
                                #default="{ errors }"
                                name="username"
                                rules="required"
                            >
                                <b-form-input
                                    id="login-email"
                                    v-model="userName"
                                    :state="errors.length > 0 ? false : null"
                                    name="login-email"
                                    placeholder=""
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>

                        <b-form-group>
                            <div class="d-flex justify-content-between">
                                <label for="login-password">Password</label>
                                <b-link
                                    :to="{
                                        name: 'auth-forgot-password-v2',
                                    }"
                                >
                                    <small>Forgot Password?</small>
                                </b-link>
                            </div>
                            <validation-provider
                                #default="{ errors }"
                                name="Password"
                                rules="required"
                            >
                                <b-input-group
                                    class="input-group-merge"
                                    :class="
                                        errors.length > 0 ? 'is-invalid' : null
                                    "
                                >
                                    <b-form-input
                                        id="login-password"
                                        v-model="password"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        class="form-control-merge"
                                        :type="passwordFieldType"
                                        name="login-password"
                                        placeholder="············"
                                    />
                                    <b-input-group-append is-text>
                                        <feather-icon
                                            class="cursor-pointer"
                                            :icon="passwordToggleIcon"
                                            @click="togglePasswordVisibility"
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
                                id="remember-me"
                                v-model="status"
                                name="checkbox-1"
                            >
                                recuerdame
                            </b-form-checkbox>
                        </b-form-group>

                        <b-button
                            type="submit"
                            variant="primary"
                            block
                            @click="validationForm"
                        >
                            Ingresar
                        </b-button>
                    </b-form>
                </validation-observer>

                <b-card-text class="text-center mt-2">
                    <span>Nuevo en la plataforma? </span>
                    <b-link :to="{ name: 'auth-register' }">
                        <span>&nbsp;Crear cuenta</span>
                    </b-link>
                </b-card-text>
            </b-col>
        </b-col> -->

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
                        <b-card-text class="mb-2">
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
                    <b-card-title
                        title-tag="h2"
                        class="font-weight-bold mb-1 text-center"
                    >
                        DIVISION DE RECURSOS HUMANOS
                    </b-card-title>
                    <b-card-text class="mb-2 text-center">
                        Plataforma Digital
                    </b-card-text>

                    <validation-observer ref="loginValidation">
                        <b-form class="auth-login-form mt-2" @submit.prevent>
                            <b-form-group
                                label="Usuario"
                                label-for="login-email"
                            >
                                <validation-provider
                                    #default="{ errors }"
                                    name="username"
                                    rules="required"
                                >
                                    <b-form-input
                                        id="login-email"
                                        v-model="userName"
                                        :state="
                                            errors.length > 0 ? false : null
                                        "
                                        name="login-email"
                                        placeholder=""
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>

                            <b-form-group>
                                <div class="d-flex justify-content-between">
                                    <label for="login-password">Password</label>
                                    <b-link
                                        :to="{
                                            name: 'auth-forgot-password-v2',
                                        }"
                                    >
                                        <small>Forgot Password?</small>
                                    </b-link>
                                </div>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Password"
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
                                            id="login-password"
                                            v-model="password"
                                            :state="
                                                errors.length > 0 ? false : null
                                            "
                                            class="form-control-merge"
                                            :type="passwordFieldType"
                                            name="login-password"
                                            placeholder="············"
                                        />
                                        <b-input-group-append is-text>
                                            <feather-icon
                                                class="cursor-pointer"
                                                :icon="passwordToggleIcon"
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
                                    id="remember-me"
                                    v-model="status"
                                    name="checkbox-1"
                                >
                                    recuerdame
                                </b-form-checkbox>
                            </b-form-group>

                            <b-button
                                type="submit"
                                variant="primary"
                                block
                                @click="validationForm"
                            >
                                Ingresar
                            </b-button>
                        </b-form>
                    </validation-observer>

                    <b-card-text class="text-center mt-2">
                        <span>Nuevo en la plataforma? </span>
                        <b-link :to="{ name: 'auth-register' }">
                            <span>&nbsp;Crear cuenta</span>
                        </b-link>
                    </b-card-text>
                </b-col>
            </b-col>
        </b-row> -->
</template>

<script>
/* eslint-disable global-require */
import {
    isUserLoggedIn,
    getUserData,
    getHomeRouteForLoggedInUser,
} from "@/auth/utils";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import VuexyLogo from "@core/layouts/components/Logo.vue";
import {
    BCard,
    BCardBody,
    BCardHeader,
    BCardTitle,
    BRow,
    BCol,
    BLink,
    BFormGroup,
    BFormInput,
    BInputGroupAppend,
    BInputGroup,
    BFormCheckbox,
    BCardText,
    BImg,
    BForm,
    BButton,
    BMedia,
    BMediaBody,
} from "bootstrap-vue";
import { required, email } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BRow,
        BCardBody,
        BCardHeader,
        BCard,
        BCol,
        BLink,
        BFormGroup,
        BFormInput,
        BInputGroupAppend,
        BInputGroup,
        BFormCheckbox,
        BCardText,
        BCardTitle,
        BImg,
        BForm,
        BButton,
        BMedia,
        BMediaBody,
        VuexyLogo,
        ValidationProvider,
        ValidationObserver,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            mystyle: {
                backgroundcolor: "#16a085",
            },
            items: [],
            status: "",
            password: "",
            userName: "",
            sideImg: require("@/assets/images/pages/login-v2.svg"),
            fondoImg: require("@/assets/images/pages/login/fondo-login.jpg"),
            logoImg: require("@/assets/images/logo/essalud.png"),
            //lidation rulesimport store from '@/store/index'
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
                this.sideImg = require("@/assets/images/pages/login-v2-dark.svg");
                return this.sideImg;
            }
            return this.sideImg;
        },
    },
    created() {
        // this.row = this.tableBasic;
        this.$http.get("/api/auth/soporte/").then((res) => {
            console.log(res.data);
            this.items = res.data;
        });
    },
    methods: {
        validationForm() {
            this.$refs.loginValidation.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/login", {
                            username: this.userName,
                            password: this.password,
                        })
                        .then((response) => {
                            // const { userData } = response.data

                            useJwt.setToken(response.data.accessToken);
                            useJwt.setRefreshToken(response.data.refreshToken);
                            this.$http
                                .get("/api/auth/user", {
                                    email: this.userEmail,
                                    password: this.password,
                                })
                                .then((response) => {
                                    const userData = response.data;

                                    localStorage.setItem(
                                        "userData",
                                        JSON.stringify(response.data)
                                    );

                                    this.$ability.update(userData.ability);
                                    this.$router
                                        .replace(
                                            getHomeRouteForLoggedInUser(
                                                userData.role
                                            )
                                        )
                                        .then(() => {
                                            this.$toast({
                                                component:
                                                    ToastificationContent,
                                                position: "top-right",
                                                props: {
                                                    title: `Bienvenido ${
                                                        userData.name ||
                                                        userData.email
                                                    }`,
                                                    icon: "CoffeeIcon",
                                                    variant: "success",
                                                    text: `Iniciaste session como ${userData.role}.!`,
                                                },
                                            });
                                        });
                                });

                            // this.$ability.update(userData.ability)

                            // ? This is just for demo purpose as well.
                            // ? Because we are showing eCommerce app's cart items count in navbar
                            // this.$store.commit('app-ecommerce/UPDATE_CART_ITEMS_COUNT', userData.extras.eCommerceCartItemsCount)

                            // ? This is just for demo purpose. Don't think CASL is role based in this case, we used role in if condition just for ease
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$refs.loginForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
    },
};
</script>

<style lang="scss">
@import "@core/scss/vue/pages/page-auth.scss";
</style>
