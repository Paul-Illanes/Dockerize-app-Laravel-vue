<template>
    <div>
        <b-card-code title="Reporte por personal" no-body>
            <b-row class="mx-2">
                <b-col cols="12" md="6">
                    <b-form-group>
                        <label style="font-weight: 700">Personal</label>
                        <v-select
                            v-model="selected"
                            label="name"
                            item-value="id"
                            item-text="name"
                            :options="users"
                            placeholder="Seleccione"
                        />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-card-code>
        <b-card-code title="Documentos">
            <documento-list :task-tags="taskTags"></documento-list>
        </b-card-code>
        <b-card-code title="Papeletas">
            <papeleta-list
                :task-tags="taskTags"
                @clicked="onClickChild"
            ></papeleta-list>
        </b-card-code>
        <b-card-code class="mb-2" title="Vacaciones">
            <vacacion-list :task-tags="taskTags"></vacacion-list>
        </b-card-code>
    </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import Ripple from "vue-ripple-directive";
import {
    BRow,
    BCol,
    BFormGroup,
    BInputGroup,
    BFormInput,
    BButton,
    BCardBody,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import vacacionList from "./VacList.vue";
import papeletaList from "./PapList.vue";
import documentoList from "./DocList.vue";

export default {
    components: {
        BCardCode,
        vSelect,
        vacacionList,
        papeletaList,
        documentoList,
        BRow,
        BCol,
        BFormGroup,
        BInputGroup,
        BFormInput,
        BButton,
        BCardBody,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            isEventHandlerSidebarActive: "",
            show: false,
            users: [],
            selected: "",
            taskTags: "",
            changePage: "",
        };
    },
    watch: {
        selected: function (val, oldval) {
            this.taskTags = val;
        },
    },
    methods: {
        onClickChild(value) {
            console.log(value); // someValue
            this.taskTags = value;
        },
    },
    created() {
        this.$http
            .get("/api/auth/users/pluck")
            .then((response) => {
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
