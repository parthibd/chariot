<template>
    <v-app id="inspire">
        <v-navigation-drawer
            fixed
            v-model="drawer"
            app>
            <v-list dense>
                <v-list-item>
                    <v-list-item-action>
                        <v-icon>mdi-home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name:'dashboard'}">Home</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item>
                    <v-list-item-action>
                        <v-icon>mdi-chart-areaspline</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name:'metrics'}">Metrics</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-on:click="logout">
                    <v-list-item-action>
                        <v-icon>mdi-power-plug-off</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            Logout
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar
            app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title>Chariot</v-toolbar-title>
        </v-app-bar>
        <v-content>
            <keep-alive>
                <router-view/>
            </keep-alive>
        </v-content>
    </v-app>
</template>

<script>

    import {TOKEN_KEY} from "../constants";

    export default {
        data: () => ({
            drawer: false,
            userInfo: {},
        }),
        created() {
            this.userInfo = this.$store.getters.getCurrentUser
        },
        methods: {
            logout() {
                localStorage.removeItem(TOKEN_KEY);
                this.$router.push({name: "login"});
            },
        },
    }
</script>
<style>

</style>
