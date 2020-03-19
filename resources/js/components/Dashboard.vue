<template>

    <v-container
        fluid>
        <canvas class="background"/>
        <v-progress-linear
            v-if="loadingData"
            indeterminate
            color="cyan darken-2"
        />
        <v-row dense style="position: relative">
            <v-col
                v-for="client in clients"
                :key="client.id"
                cols="3"
            >
                <v-card>
                    <v-img
                        :src="client.qr_code"
                        class="white--text align-end"
                        contain
                        height="200px">
                    </v-img>

                    <v-card-actions>
                        {{client.name}}
                        <v-spacer/>
                        <v-btn
                            @click="openEditClientDialog(client)" icon>
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn @click="deleteClient(client.public_key)" icon>
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-speed-dial
            v-model="fab"
            :top="top"
            :bottom="bottom"
            :right="right"
            :left="left"
            :direction="direction"
            :open-on-hover="hover"
            :transition="transition"
            style="position: absolute"
        >
            <template v-slot:activator>
                <v-btn
                    v-model="fab"
                    color="blue darken-2"
                    dark
                    fab>
                    <v-icon v-if="fab">mdi-close</v-icon>
                    <v-icon v-else>mdi-account-circle</v-icon>
                </v-btn>
            </template>
            <v-btn
                fab
                dark
                small
                color="indigo"
                @click="dialogAddUser = true"
            >
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-speed-dial>

        <v-dialog v-model="dialogAddUser" max-width="500px">
            <v-card>
                <v-card-text>
                    <v-text-field v-model="name" label="Name"/>
                    <small class="grey--text">Enter friendly name for the client.</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="primary" @click="addClient">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEditUser" max-width="500px">
            <v-card>
                <v-card-text>
                    <v-text-field v-model="name" label="Name"/>
                    <small class="grey--text">Enter friendly name for the client.</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="primary" @click="editClient">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import {addClient, deleteClient, editClient, getAllClients} from "../localApiService";
    import Particles from "particlesjs"

    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
            direction: 'top',
            fab: false,
            fling: false,
            hover: false,
            tabs: null,
            top: false,
            right: true,
            bottom: true,
            left: false,
            transition: 'slide-y-reverse-transition',
            clients: [],
            dialogAddUser: false,
            dialogEditUser: false,
            name: "",
            currentEditedClient: null,
            pjsInstance: null,
            loadingData: true
        }),
        beforeDestroy() {
            this.pjsInstance.destroy()
        },
        created() {
            this.$vuetify.theme.dark = true;
            this.getAllClients()
        },
        mounted() {
            this.pjsInstance = Particles.init
            ({
                selector: '.background',
                color: '#75A5B7',
                maxParticles: 130,
                connectParticles: true,
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            maxParticles: 80
                        }
                    }, {
                        breakpoint: 375,
                        options: {
                            maxParticles: 50
                        }
                    }
                ]
            });
        },
        methods: {
            addClient() {
                this.dialogAddUser = false;
                addClient(this.name).then(() => {
                    this.name = '';
                    this.getAllClients();
                })
            },
            getAllClients() {
                getAllClients().then(clients => {
                    this.clients = clients;
                    this.loadingData = false;
                })
            },
            deleteClient(publicKey) {
                this.loadingData = true;
                deleteClient(publicKey).then((response => {
                    this.getAllClients()
                }))
            },
            openEditClientDialog(client) {
                this.currentEditedClient = client;
                this.dialogEditUser = true;
                this.name = client.name
            },
            editClient() {
                this.loadingData = true;
                this.dialogEditUser = false;
                editClient(this.currentEditedClient.id, this.name).then(response => {
                    this.currentEditedClient = null;
                    this.name = '';
                    this.getAllClients();
                })
            }
        }
    }
</script>

<style scoped>
    .background {
        position: absolute;
        display: block;
        top: 0;
        left: 0;
        z-index: 0;
    }
</style>
