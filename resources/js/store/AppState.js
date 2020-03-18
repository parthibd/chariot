import {localApiInstance} from "../globals";
import {TOKEN_KEY} from "../constants";
import {
    getAllVehicles,
    getDashboardStats,
    getDepartmentalVehicles, getEventTypes,
    getVehiclesOnHire
} from "../localApiService";

const jwtDecode = require("jwt-decode");

const MUTATION_TYPES = {
    SET_TOKEN: "SET_TOKEN",
    DELETE_TOKEN: "DELETE_TOKEN",
    SET_USER_DATA: "SET_USER_DATA",
};

export const ACTION_TYPES = {
    LOGIN: "LOGIN",
    LOGOUT: "LOGOUT",
    INIT_AXIOS_INSTANCES: "INIT_AXIOS_INSTANCES",
    INIT_STORE: "INIT_STORE",
};

export default {
    state: {
        accessToken: null,
        user: null,
    },
    actions: {
        [ACTION_TYPES.LOGIN]({commit, state}, data) {
            return new Promise((resolve, reject) => {
                localApiInstance.post('login', {
                    username: data.username,
                    password: data.password
                }).then(response => {
                    if (response.data.success == true) {
                        let token = response.data.token;
                        let user = jwtDecode(token);
                        commit(MUTATION_TYPES.SET_TOKEN, token);
                        commit(MUTATION_TYPES.SET_USER_DATA, user);

                        localStorage.clear();
                        localStorage.setItem(TOKEN_KEY, response.data.token);
                        localApiInstance.defaults.headers["Authorization"] = `Bearer ${token}`;
                        resolve(token)
                    } else
                        reject("Wrong Credentials")
                })
            })
        },
        [ACTION_TYPES.INIT_AXIOS_INSTANCES]() {
            localApiInstance.defaults.headers["Authorization"] = `Bearer ${localStorage.getItem(TOKEN_KEY)}`;
        },
        [ACTION_TYPES.INIT_STORE]({commit, dispatch}) {
            let user = jwtDecode(localStorage.getItem(TOKEN_KEY));
            commit(MUTATION_TYPES.SET_USER_DATA, user);
            dispatch(ACTION_TYPES.INIT_AXIOS_INSTANCES);
        }
    },
    mutations: {
        [MUTATION_TYPES.SET_TOKEN](state, token) {
            state.accessToken = token
        },

        [MUTATION_TYPES.SET_USER_DATA](state, user) {
            state.user = user
        },
    },
    getters: {
        getCurrentUser: state => state.user,
    }
}
