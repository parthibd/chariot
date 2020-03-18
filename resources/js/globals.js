import axios from "axios"
import {TOKEN_KEY} from "./constants";
import {VUE_APP_SERVER_URL} from "./env";

export const localApiInstance = axios.create({
    baseURL: `${VUE_APP_SERVER_URL}/api`,
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem(TOKEN_KEY)}`
    },
    withCredentials: false
});
