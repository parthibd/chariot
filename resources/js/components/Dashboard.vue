<template>

    <v-container
        fluid>
        <v-row dense>
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
                        {{client.ip}}
                        <v-spacer/>
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
                @click="addClient"
            >
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-speed-dial>
    </v-container>
</template>

<script>
    import {addClient, deleteClient, getAllClients} from "../localApiService";

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
            cards: [
                {title: 'Pre-fab homes', src: 'https://cdn.vuetifyjs.com/images/cards/house.jpg', flex: 3},
                {title: 'Favorite road trips', src: 'https://cdn.vuetifyjs.com/images/cards/road.jpg', flex: 3},
                {title: 'Best airlines', src: 'https://cdn.vuetifyjs.com/images/cards/plane.jpg', flex: 3},
            ],
            clients: []
        }),
        created() {
            this.$vuetify.theme.dark = true;
            this.getAllClients()
        },
        methods: {
            addClient() {
                addClient().then(() => {
                    this.getAllClients();
                })
            },
            getAllClients() {
                getAllClients().then(clients => {
                    this.clients = clients;
                })
            },
            deleteClient(publicKey) {
                deleteClient(publicKey).then((response => {
                    this.getAllClients()
                }))
            }
        }
    }
</script>
