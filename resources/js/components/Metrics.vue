<template>
    <v-container fluid>
        <v-data-table
            :headers="headers"
            :items="metrics"
            :items-per-page="10"
            class="elevation-1"
        >
            <template v-slot:body="{ items }">
                <tbody>
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.peer.name}}</td>
                    <td class="">{{ item.public_key}}</td>
                    <td class="">{{ item.preshared_key }}</td>
                    <td class="">{{ item.endpoint }}</td>
                    <td class="">{{ item.allowed_ips }}</td>
                    <td class="">{{moment(item.latest_handshake).format('MMMM Do YYYY, h:mm:ss a') }}</td>
                    <td class="">{{ (item.transfer_rx/(1024*1024)).toFixed(4) }}</td>
                    <td class="">{{ (item.transfer_tx/(1024*1024)).toFixed(4) }}</td>
                    <td class="">{{ item.persistent_keepalive }}</td>
                </tr>
                </tbody>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
    import {getPeerMetrics} from "../localApiService";
    import moment from "moment"

    export default {
        name: "Metrics",
        created() {
            this.$vuetify.theme.dark = true;
            this.getPeerMetrics()
        },
        data() {
            return {
                headers: [
                    {
                        text: 'Name',
                        align: 'start',
                        sortable: false,
                        value: 'name',
                    },
                    {text: 'Public Key', value: 'public_key'},
                    {text: 'Preshared Key', value: 'preshared_key'},
                    {text: 'Endpoint', value: 'endpoint'},
                    {text: 'Allowed Ips', value: 'allowed_ips'},
                    {text: 'Latest Handshake', value: 'latest_handshake'},
                    {text: 'Transfer RX [MB]', value: 'transfer_rx'},
                    {text: 'Transfer TX [MB]', value: 'transfer_tx'},
                    {text: 'Persistent Keepalive', value: 'persistent_keepalive'},
                ],
                metrics: []
            }
        },
        methods: {
            getPeerMetrics() {
                getPeerMetrics().then(metrics => {
                    this.metrics = metrics
                })
            },
            moment() {
                return moment();
            }
        }
    }
</script>

<style scoped>

</style>
