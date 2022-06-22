<template>
    <div class="misc-wrapper">
        <div class="misc-inner p-2 p-sm-3">
            <div class="w-100">
                <div class="text-center pt-2">
                    <b-img fluid :src="logoImg" alt="Login V2" />
                </div>
                <h1 class="brand-text text-white ml-1 pt-3 text-center">
                    RED ASISTENCIAL CUSCO
                </h1>
                <h2 class="brand-text text-white ml-1 text-center">
                    DIVISION DE RECURSOS HUMANOS
                </h2>
                <h2 class="brand-text text-white ml-1 text-center">
                    PLATAFORMA VIRTUAL
                </h2>
                <b-row class="match-height mt-5">
                    <b-col lg="8">
                        <b-card-text no-body class="ml-5">
                            <b-card-title
                                title-tag="h2"
                                class="font-weight-bold mb-1 text-center text-white"
                            >
                                Consideraciones
                            </b-card-title>
                            <div
                                style="background-color: #007bff"
                                class="p-2 text-white"
                            >
                                <b-card-text class="mb-2">
                                    Uso del sistema
                                </b-card-text>
                                <p class="card-text">
                                    Debe registrarse con sus datos personales, y
                                    su
                                    <strong>Correo electrónico personal</strong
                                    >.
                                </p>

                                <p class="card-text">
                                    Debido a la importancia de la información
                                    personal que se gestiona, luego de su
                                    registro le llegará un correo electrónico
                                    con un código de validación.
                                </p>

                                <p class="card-text">
                                    Antes de pasar a otro módulo es necesario
                                    esperar que el actual esté completamente
                                    cargado.
                                </p>
                            </div>
                            <div
                                class="bg-white p-2"
                                :style="{ color: '#000000' }"
                            >
                                <p class="card-text font-weight-bold">
                                    Al registrarme declaro bajo JURAMENTO, la
                                    veracidad de la información proporcionada,
                                    somentiéndome a la verificación posterior y
                                    asumiendo la responsabilidad en caso de
                                    comprobarse lo contrario, de acuerdo con la
                                    ley del Procedimiento Administrativo General
                                    N° 27444.
                                </p>
                            </div>
                            <a
                                target="_blank"
                                href="http://172.26.58.5/descargas"
                                >Descargas</a
                            >
                        </b-card-text>
                        <!-- <div
                    class="w-100 d-lg-flex align-items-center justify-content-center px-5 text-white"
                >
                    <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
                        <b-card-title
                            title-tag="h2"
                            class="font-weight-bold mb-1 text-center text-white"
                        >
                            Consideraciones
                        </b-card-title>
                        <div style="background-color: #007bff" class="p-2">
                            <b-card-text class="mb-2">
                                Uso del sistema
                            </b-card-text>
                            <p class="card-text">
                                Debe registrarse con sus datos personales, y su
                                <strong>Correo electrónico personal</strong>.
                            </p>

                            <p class="card-text">
                                Debido a la importancia de la información
                                personal que se gestiona, luego de su registro
                                le llegará un correo electrónico con un código
                                de validación.
                            </p>

                            <p class="card-text">
                                Antes de pasar a otro módulo es necesario
                                esperar que el actual esté completamente
                                cargado.
                            </p>
                        </div>
                        <div class="bg-white p-2" :style="{ color: '#000000' }">
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
                </div> -->
                    </b-col>
                    <b-col lg="4" md="6">
                        <router-view></router-view>

                        <!-- <div
                    class="w-100 d-lg-flex align-items-center justify-content-center text-white"
                >
                    <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
                        <b-card-title
                            title-tag="h2"
                            class="font-weight-bold mb-1 text-center text-white"
                        >
                            Consideraciones
                        </b-card-title>
                        <div style="background-color: #007bff" class="p-2">
                            <b-card-text class="mb-2">
                                Uso del sistema
                            </b-card-text>
                            <p class="card-text">
                                Debe registrarse con sus datos personales, y su
                                <strong>Correo electrónico personal</strong>.
                            </p>

                            <p class="card-text">
                                Debido a la importancia de la información
                                personal que se gestiona, luego de su registro
                                le llegará un correo electrónico con un código
                                de validación.
                            </p>

                            <p class="card-text">
                                Antes de pasar a otro módulo es necesario
                                esperar que el actual esté completamente
                                cargado.
                            </p>
                        </div>
                        <div class="bg-white p-2" :style="{ color: '#000000' }">
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
                </div> -->
                    </b-col>
                </b-row>
            </div>
        </div>
    </div>
</template>

<script>
/* eslint-disable global-require */
import {
    isUserLoggedIn,
    getUserData,
    getHomeRouteForLoggedInUser,
} from "@/auth/utils";
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import VuexyLogo from "@core/layouts/components/Logo.vue";
import {
    BRow,
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
} from "bootstrap-vue";
import { required, email } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import DarkToggler from "../../../@core/layouts/components/app-navbar/components/DarkToggler.vue";

export default {
    components: {
        BRow,
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
        VuexyLogo,
        ValidationProvider,
        ValidationObserver,
        DarkToggler,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            mystyle: {
                backgroundcolor: "#16a085",
            },
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
<style>
body {
    height: 100%;
}

.sidebar {
    width: 12%;
    height: 100%;
    float: left;
    background-color: red;
}

.main {
    margin-left: 12%;
    height: 100%;
    background-color: blue;
}
</style>
