<template>
    <b-card-code title="Registro de usuario">
        <validation-observer ref="simpleRules">
            <b-form>
                <b-row>
                    <!--  This field is required-->
                    <b-col md="3">
                        <b-form-group>
                            <label>Nombres</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required|alpha"
                                name="nombres"
                            >
                                <b-form-input
                                    v-model="name"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Nombres"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!--Enter Number between 10 & 20 -->
                    <b-col md="3">
                        <b-form-group>
                            <label>Apellido Paterno</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required|alpha"
                                name="lastname"
                            >
                                <b-form-input
                                    v-model="lastname"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="apellido paterno"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Must only consist of numbers-->
                    <b-col md="3">
                        <b-form-group>
                            <label>Apellido Materno</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required|alpha"
                                name="mother_lastname"
                            >
                                <b-form-input
                                    v-model="mother_lastname"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="apellido materno"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="3">
                        <b-form-group>
                            <label>email</label>
                            <validation-provider
                                #default="{ errors }"
                                name="email"
                                rules="required|email"
                            >
                                <b-form-input
                                    v-model="email"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Email"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Must match the specified regular expression : ^([0-9]+)$ - numbers only -->
                    <b-col md="2">
                        <b-form-group>
                            <label>Usuario</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required|integer"
                                name="username"
                            >
                                <b-form-input
                                    v-model="username"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="dni del usuario"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!--  Only alphabetic characters-->
                    <b-col md="3">
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
                    <!-- <b-col md="3">
                        <b-form-group>
                            <label>Password</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required"
                                name="password"
                            >
                                <b-form-input
                                    v-model="password"
                                    type="password"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="ingrese su password"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col> -->
                    <b-col md="3">
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

                    <!-- Length should not be less than the specified length : 3 -->
                    <b-col md="2">
                        <b-form-group>
                            <label>PIN</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="integer"
                                name="pin"
                            >
                                <b-form-input
                                    v-model="pin"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder=""
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="2">
                        <b-form-group>
                            <label>Celular</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="integer"
                                name="celular"
                            >
                                <b-form-input
                                    v-model="celular"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="celular"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <!--Password Input Field -->
                    <b-col md="4">
                        <b-form-group>
                            <label>Rol</label>

                            <validation-provider
                                #default="{ errors }"
                                rules="required"
                                name="selectedRole"
                            >
                                <v-select
                                    label="name"
                                    :options="roles"
                                    v-model="selectedRole"
                                    name="role"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="1">
                        <b-form-group>
                            <label>Verificado</label>
                            <validation-provider
                                #default="{ errors }"
                                rules=""
                                name="verified"
                            >
                                <b-form-checkbox
                                    class="mt-1"
                                    v-model="verified"
                                >
                                    Check
                                </b-form-checkbox>
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="1">
                        <b-form-group>
                            <label>Activo</label>
                            <validation-provider
                                #default="{ errors }"
                                rules=""
                                name="activo"
                            >
                                <b-form-checkbox class="mt-1" v-model="activo">
                                    Activo
                                </b-form-checkbox>
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- The digits field must be numeric and exactly contain 3 digits -->
                    <!-- submit button -->
                    <b-col cols="12">
                        <b-button
                            variant="primary"
                            type="submit"
                            @click.prevent="register"
                        >
                            Registrar
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import {
    BFormInput,
    BFormCheckbox,
    BFormGroup,
    BInputGroup,
    BInputGroupAppend,
    BForm,
    BCardText,
    BRow,
    BCol,
    BButton,
} from "bootstrap-vue";
import {
    required,
    email,
    confirmed,
    url,
    between,
    alpha,
    integer,
    password,
    min,
    digits,
    alphaDash,
    length,
} from "@validations";
export default {
    components: {
        BCardText,
        BFormCheckbox,
        BCardCode,
        ValidationProvider,
        ValidationObserver,
        BFormInput,
        BFormGroup,
        BInputGroup,
        BInputGroupAppend,
        BForm,
        BRow,
        BCol,
        BButton,
        vSelect,
    },
    mixins: [togglePasswordVisibility],
    data() {
        return {
            email: "",
            username: "",
            name: "",
            lastname: "",
            mother_lastname: "",
            active: "",
            password: "",
            c_password: "",
            pin: "",
            verified: "",
            activo: "",
            celular: "",
            roles: [],
            selectedRole: "",
            // selected: ["B", "C"],
            //nuevos
            // name: "",
            // passwordValue: "",
            // passwordCon: "",
            // emailValue: "",
            // number: "",
            // numberRange: "",
            // numberRegx: "",
            // URL: "",
            // Alphabetic: "",
            // digitValue: "",
            // digitValue2: "",
            // character: "",
        };
    },
    mounted() {
        this.getRoles();
    },
    computed: {
        passwordToggleIcon() {
            return this.passwordFieldType === "password"
                ? "EyeIcon"
                : "EyeOffIcon";
        },
    },
    methods: {
        async getRoles() {
            // await axios.get('/sanctum/csrf-cookie')
            this.$http
                .get("/api/auth/roles")
                .then((response) => {
                    this.roles = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        register() {
            this.$refs.simpleRules.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/users/create", {
                            name: this.name,
                            lastname: this.lastname,
                            mother_lastname: this.mother_lastname,
                            username: this.username,
                            email: this.email,
                            password: this.password,
                            celular: this.celular,
                            pin: this.pin,
                            verified: this.verified,
                            activo: this.activo,
                            c_password: this.c_password,
                            roles: this.selectedRole,
                        })
                        .then((res) => {
                            console.log("fer");
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
                            console.log("xd");
                            this.$refs.simpleRules.setErrors(
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
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
