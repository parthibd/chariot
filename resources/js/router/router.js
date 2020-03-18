import VueRouter from 'vue-router'
import LoginComponent from "../components/Login.vue"
import DashboardComponent from "../components/Dashboard";

export const router = new VueRouter({
    routes: [
        {
            path: '/login',
            name: "login",
            component: LoginComponent
        },
        {
            path: "/dashboard",
            name: "dashboard",
            component: DashboardComponent
        }
    ]
});
