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
                <div class="d-flex justify-content-between mb-1">
                    <b-form-checkbox
                        id="remember-me"
                        v-model="status"
                        name="checkbox-1"
                    >
                        recuerdame
                    </b-form-checkbox>
                    <b-link>
                        <small>Olvidaste tu password?</small>
                    </b-link>
                </div>

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

                                    sessionStorage.setItem(
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
