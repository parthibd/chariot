<template>
    <v-container fluid>
        <canvas class="background"/>
        <v-data-table
            style="position: relative"
            :headers="headers"
            :items="metrics"
            :items-per-page="10"
            class="elevation-1">
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
    import Particles from "particlesjs"

    export default {
        name: "Metrics",
        beforeDestroy() {
            this.pjsInstance.destroy()
        },
        created() {
            this.$vuetify.theme.dark = true;
            this.getPeerMetrics()
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
        data() {
            return {
                pjsInstance: null,
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
    .background {
        position: absolute;
        display: block;
        top: 0;
        left: 0;
        z-index: 0;
    }
</style>
