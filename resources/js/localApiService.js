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
        division_id: divisionId
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
        division_id: divisionId
    }).then(response => {
        return response.data
    })
}

export function deleteUserWithId(id) {
    return localApiInstance.delete(`users/${id}`).then(response => {
        return response.data
    })
}

export function getAllVehicles() {
    return localApiInstance.get(`vehicles`).then(response => {
        return response.data
    })
}

export function getVehicle(id, cancelToken) {
    return localApiInstance.get(`vehicles/${id}`, {
        cancelToken: cancelToken
    }).then(response => {
        return response.data
    })
}

export function getDepartmentalVehicles() {
    return localApiInstance.get(`vehicles/dv`).then(response => {
        return response.data
    })
}

export function getVehiclesOnHire() {
    return localApiInstance.get(`vehicles/voh`).then(response => {
        return response.data
    })
}

export function getRouteReports(params, token) {
    return localApiInstance.get(`reports/route`, {
        params: {
            ...params
        },
        cancelToken: token
    }).then(response => {
        return response.data
    })
}

export function getSummaryReport(params) {
    return localApiInstance.get(`reports/summary`, {
        params: {
            ...params
        }
    }).then(response => {
        return response.data
    })
}

export function getEventReports(params, token) {
    return localApiInstance.get(`reports/event`, {
        params: {
            ...params
        },
        cancelToken: token
    }).then(response => {
        return response.data
    })
}

export function getTripReports(params, token) {
    return localApiInstance.get(`reports/trip`, {
        params: {
            ...params
        },
        cancelToken: token
    }).then(response => {
        return response.data
    })
}

export function getLiveTrack(deviceId, cancelTokenSource) {
    return localApiInstance.get(`livetrack`, {
        cancelToken: cancelTokenSource.token,
        params: {
            deviceId: deviceId
        }
    }).then(response => {
        return {response, track: response.data}
    })
}

export function stopLiveTrack(deviceId) {
    return localApiInstance.delete(`livetrack`, {
        params: {
            deviceId: deviceId
        }
    }).then(response => {
        return response.data
    })
}

export function getDashboardStats() {
    return localApiInstance.get(`dashboard/stats`).then(response => {
        return response.data
    })
}

export function getEventTypes() {
    return localApiInstance.get(`events/types`).then(response => {
        return response.data
    })
}

export function getUnresolvedComplaints() {
    return localApiInstance.get(`complaints/unresolved`).then(response => {
        return response.data
    })
}

export function getAllDivisions() {
    return localApiInstance.get(`divisions`).then(response => {
        return response.data
    })
}

export function filterComplaints(dateFrom, dateTo, divisionId) {
    return localApiInstance.get(`complaints/unresolved`, {
        params: {
            date_from: dateFrom,
            date_to: dateTo,
            division_id: divisionId
        }
    }).then(response => {
        return response.data
    })
}
