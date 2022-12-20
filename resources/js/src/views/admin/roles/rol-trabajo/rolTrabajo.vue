<template>
    <b-card title="Programacion Vacacional">
        <b-card-actions title="Filtros" action-collapse>
            <b-row>
                <b-col md="6">
                    <!-- <b-form-group
                        label="Ejecucion Vacacional:"
                        label-for="h-first-name"
                        label-cols-md="4"
                    >
                        <v-select
                            label="name"
                            :options="periodos"
                            v-model="periodo"
                            name="superstructura"
                        />
                    </b-form-group> -->
                </b-col>
                <b-col md="6">
                    <p class="mt-50">Periodo Vacacional:</p>
                </b-col>
            </b-row>
        </b-card-actions>
        <div id="exc">
            <vue-excel-editor v-model="jsondata" no-footer filter-row>
                <vue-excel-column
                    field="user"
                    label="User ID"
                    type="string"
                    width="80px"
                />
                <vue-excel-column
                    field="name"
                    label="Name"
                    type="string"
                    width="150px"
                />
                <vue-excel-column
                    field="phone"
                    label="Contact"
                    type="string"
                    width="130px"
                />
                <vue-excel-column
                    field="gender"
                    label="Gender"
                    type="select"
                    width="50px"
                    :options="['F', 'M', 'U']"
                />
                <vue-excel-column
                    field="age"
                    label="Age"
                    type="number"
                    width="70px"
                />
                <vue-excel-column
                    field="birth"
                    label="Date Of Birth"
                    type="date"
                    width="80px"
                />
            </vue-excel-editor>
        </div>
    </b-card>
</template>
<script>
import BCardActions from "@core/components/b-card-actions/BCardActions.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import {
    BImg,
    BForm,
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    BCardText,
    BCardBody,
    BListGroup,
    BFormGroup,
    BListGroupItem,
    BFormCheckboxGroup,
    BFormCheckbox,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { avatarText } from "@core/utils/filter";
import vSelect from "vue-select";
import { onUnmounted } from "@vue/composition-api";
import store from "@/store";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import axios from "axios";
export default {
    components: {
        BImg,
        BForm,
        BListGroup,
        BListGroupItem,
        BCardText,
        BCardBody,
        BCard,
        BRow,
        BCol,
        BFormInput,
        BButton,
        BTable,
        BMedia,
        BAvatar,
        BLink,
        BBadge,
        BDropdown,
        BDropdownItem,
        BPagination,
        BTooltip,
        BFormGroup,
        vSelect,
        BCardActions,
        BFormCheckboxGroup,
        BFormCheckbox,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            jsondata: [
                {
                    user: "hc",
                    name: "Harry Cole",
                    phone: "1-415-2345678",
                    gender: "M",
                    age: 25,
                    birth: "1997-07-01",
                },
                {
                    user: "sm",
                    name: "Simon Minolta",
                    phone: "1-123-7675682",
                    gender: "M",
                    age: 20,
                    birth: "1999-11-12",
                },
                {
                    user: "ra",
                    name: "Raymond Atom",
                    phone: "1-456-9981212",
                    gender: "M",
                    age: 19,
                    birth: "2000-06-11",
                },
                {
                    user: "ag",
                    name: "Mary George",
                    phone: "1-556-1245684",
                    gender: "F",
                    age: 22,
                    birth: "2002-08-01",
                },
                {
                    user: "kl",
                    name: "Kenny Linus",
                    phone: "1-891-2345685",
                    gender: "M",
                    age: 29,
                    birth: "1990-09-01",
                },
            ],
        };
    },
    methods: {},
    created() {
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/rol-detalle/")
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>

<style lang="scss">
#exc {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-sweetalert.scss";
</style>
