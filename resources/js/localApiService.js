import {localApiInstance} from "./globals";

export function getUserRoles() {
    return localApiInstance.get("users/roles").then(response => {
        return response.data
    })
}

export function addUser(fullName, username, password, roleId, divisionId) {
    return localApiInstance.post("users", {
        full_name: fullName,
        username: username,
        password: password,
        role_id: roleId,
    }).then(response => {
        return response
    })
}

export function getAllUsers() {
    return localApiInstance.get("users").then(response => {
        return response.data
    })
}

export function getUserWithId(id) {
    return localApiInstance.get(`users/${id}`).then(response => {
        return response.data.data
    })
}

export function editUserWithId(id, username, password, roleId, divisionId) {
    return localApiInstance.patch(`users/${id}`, {
        username: username,
        password: password,
        role_id: roleId,
    }).then(response => {
        return response.data
    })
}

export function deleteUserWithId(id) {
    return localApiInstance.delete(`users/${id}`).then(response => {
        return response.data
    })
}

export function getAllClients() {
    return localApiInstance.get(`client`).then(response => {
        return response.data
    })
}

export function addClient(name) {
    return localApiInstance.put(`client`, null, {
        params: {
            name: name
        }
    }).then(response => {
        return response.data
    })
}

export function deleteClient(publicKey) {
    return localApiInstance.delete(`client`, {
        params: {
            public_key: publicKey
        }
    }).then(response => {
        return response.data
    })
}

export function editClient(id, name) {
    return localApiInstance.patch(`client/${id}`, null, {
        params: {
            name: name
        }
    }).then(response => {
        return response.data
    })
}

export function toggleClientStatus(id) {
    return localApiInstance.patch(`client/status/${id}`).then(response => {
        return response.data
    })
}


export function getPeerMetrics() {
    return localApiInstance.get(`metric`).then(response => {
        return response.data
    })
}
