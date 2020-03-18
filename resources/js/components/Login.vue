<template>
    <v-app id="inspire">
        <v-content>
            <v-container
                class="fill-height"
                fluid
            >
                <v-row
                    align="center"
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="8"
                        md="4"
                    >
                        <v-card class="elevation-12">
                            <v-toolbar
                                color="primary"
                                dark
                                flat
                            >
                                <v-toolbar-title>Login</v-toolbar-title>
                                <v-spacer/>
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                    <v-text-field
                                        v-model="username"
                                        label="Login"
                                        name="login"
                                        prepend-icon="mdi-account"
                                        type="text"
                                    />

                                    <v-text-field
                                        v-model="password"
                                        id="password"
                                        label="Password"
                                        name="password"
                                        prepend-icon="mdi-lock"
                                        type="password"
                                    />
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer/>
                                <v-btn @click="login" color="primary">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import {ACTION_TYPES} from "../store/AppState";

    export default {
        data() {
            return {
                username: "",
                password: ""
            }
        },
        created() {
            this.$vuetify.theme.dark = true;
            this.$store.commit('SET_LAYOUT', "simple-layout");
        },
        methods: {
            login() {
                this.$store.dispatch(ACTION_TYPES.LOGIN, {
                    username: this.username,
                    password: this.password
                }).then(() => {
                    this.$router.push({name: "dashboard"})
                }).catch(err => {
                    alert(err)
                })
            }
        }
    }
</script>
