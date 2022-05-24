<template>
    <div class="auth-wrapper auth-v2">
        <!-- form -->
        <validation-observer ref="registerForm" #default="{ invalid }">
            <b-form class="auth-register-form mt-2" @submit.prevent="register">
                <b-row>
                    <b-col md="6">
                        <!-- username -->
                        <b-form-group
                            label="Username"
                            label-for="register-username"
                        >
                            <validation-provider
                                #default="{ errors }"
                                name="Username"
                                vid="username"
                                rules="required"
                            >
                                <b-form-input
                                    id="register-username"
                                    v-model="username"
                                    name="register-username"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="johndoe"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <!-- email -->
                        <b-form-group label="Email" label-for="register-email">
                            <validation-provider
                                #default="{ errors }"
                                name="Email"
                                vid="email"
                                rules="required|email"
                            >
                                <b-form-input
                                    id="register-email"
                                    v-model="userEmail"
                                    name="register-email"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="john@example.com"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <!-- password -->
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
                                        errors.length > 0 ? 'is-invalid' : null
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
                                            @click="togglePasswordVisibility"
                                        />
                                    </b-input-group-append>
                                </b-input-group>
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <!-- password -->
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
                                        errors.length > 0 ? 'is-invalid' : null
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
                                        name="register-c_password"
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
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="12">
                        <b-form-group>
                            <b-form-checkbox
                                id="register-privacy-policy"
                                v-model="status"
                                name="checkbox-1"
                            >
                                I agree to
                                <b-link>privacy policy & terms</b-link>
                            </b-form-checkbox>
                        </b-form-group>
                    </b-col>
                    <b-button
                        variant="primary"
                        block
                        type="submit"
                        :disabled="invalid"
                    >
                        Sign up
                    </b-button>
                </b-row>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from "vee-validate";
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
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
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
            roles: "",
            status: "",
            username: "",
            userEmail: "",
            password: "",
            c_password: "",
            sideImg: require("@/assets/images/pages/register-v2.svg"),
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
        async getRoles() {
            // await axios.get('/sanctum/csrf-cookie')
            await axios
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
                        .post("/api/auth/create", {
                            name: this.username,
                            email: this.userEmail,
                            password: this.password,
                            c_password: this.password,
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "success",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text: `Usuario registrado correctamente!`,
                                },
                            });
                            // this.$router.push({ name: "admin-users-list" });
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
