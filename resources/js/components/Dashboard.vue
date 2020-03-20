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

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn @click="getClientConfigDownloadUrl(client)" icon>
                                    <v-icon v-on="on">
                                        mdi-share-variant
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Get Config Download Url</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn @click="toggleClientStatus(client)" icon>
                                    <v-icon v-on="on" :color="client.is_active ?'green darken-2':'red darken-2'">
                                        mdi-check
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Toggle Client Status</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    v-on="on"
                                    @click="openEditClientDialog(client)" icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                            </template>
                            <span>Edit Client Name</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" @click="deleteClient(client.public_key)" icon>
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Delete Client</span>
                        </v-tooltip>

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
                <v-card-text class="pt-5">
                    <v-text-field v-model="name" label="Name"/>
                    <small class="grey--text">Enter friendly name for the client.</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="primary" @click="addClient">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <v-dialog v-model="dialogClientConfigDownloadUrl" max-width="500px">
            <v-card>
                <v-card-text class="pt-5">
                    <v-text-field readonly ref="textToCopy" v-model="configDownloadUrl" label="URL"/>
                    <small class="grey--text">Client Config Download Url</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="primary" @click="dialogClientConfigDownloadUrl=false">Ok</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEditUser" max-width="500px">
            <v-card>
                <v-card-text class="pt-5">
                    <v-text-field v-model="name" label="Name"/>
                    <small class="grey--text">Enter friendly name for the client.</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn text color="primary" @click="editClient">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar
            v-model="snackbar"
            :timeout=2000>
            {{ snackbarText }}
        </v-snackbar>
    </v-container>
</template>

<script>
    import {
        addClient,
        deleteClient,
        editClient,
        getAllClients,
        getClientConfigDownloadUrl,
        toggleClientStatus
    } from "../localApiService";
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
            dialogClientConfigDownloadUrl: false,
            name: "",
            currentEditedClient: null,
            pjsInstance: null,
            loadingData: true,
            configDownloadUrl: '',
            snackbar: false,
            snackbarText: ''

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
                this.loadingData = true;
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
            },
            toggleClientStatus(client) {
                this.loadingData = true;
                toggleClientStatus(client.id).then(response => {
                    this.getAllClients();
                })
            },
            getClientConfigDownloadUrl(client) {
                const self = this;
                this.dialogClientConfigDownloadUrl = true;
                getClientConfigDownloadUrl(client.id).then(response => {
                    this.configDownloadUrl = response.url;
                    this.$nextTick(() => {
                        let textToCopy = self.$refs.textToCopy.$el.querySelector('input');
                        textToCopy.select();
                        document.execCommand("copy");
                        self.snackbar = true;
                        self.snackbarText = "Copied to clipboard!";
                    })
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
