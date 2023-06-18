import { fetch } from 'wix-fetch';

const basePublicURL = "https://brenzmedia.com/laravel-api/FYP-Event-Organizer-Project/public/";

export async function testDatabaseConnection() {
    fetch(basePublicURL + "db-test", { "method": "get" })
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject("Fetch did not succeed");
            }
        })
        .then(json => console.log(json))
        .catch(err => console.log(err));
}

export function login(params) {
    var bodyPayload = {};
    var path = 'api/';
    if (params.password) {
        path += 'login';
        bodyPayload.password = params.password;
    } else {
        path += 'getUserDetails';
    }
    bodyPayload.email = params.email;

    let requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyPayload)
    };

    return fetch(basePublicURL + path, requestOptions)
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject('Fetch did not succeed');
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export function signup(params) {
    var bodyPayload = {
        "first_name": params.first_name,
        "last_name": params.last_name,
        "email": params.email,
        "password": params.password,
        "phone": params.phone,
        "role": params.role
    };

    var path = 'api/register';

    let requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyPayload)
    };

    return fetch(basePublicURL + path, requestOptions)
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject('Fetch did not succeed');
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}


export function getUserByEmail(params) {
    var bodyPayload = {
        "email": params.email
    };

    var path = 'api/user';

    let requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyPayload)
    };

    return fetch(basePublicURL + path, requestOptions)
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject('Fetch did not succeed');
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export function addNewBusiness(params) {
    var bodyPayload = {
        "vendor_id": params.vendor_id,
        "business_name": params.business_name,
        "business_type": params.business_type,
        "business_mobile_number": params.business_mobile_number,
        "business_address": params.business_address,
        "business_details": params.business_details,
        "people_capacity_min": params.people_capacity_min,
        "people_capacity_max": params.people_capacity_max,
        "start_time": params.start_time,
        "end_time": params.end_time,
        "price_per_person": params.price_per_person,
        "services_and_amenities": params.services_and_amenities,
        "cover_photo": params.cover_photo,
        "images": params.images
    };

    var path = 'api/business';

    let requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyPayload)
    };

    return fetch(basePublicURL + path, requestOptions)
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject('Fetch did not succeed');
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export async function getVendorAllBusiness(vendor_id) {
    return fetch(basePublicURL + 'api/vendor/' + vendor_id + '/businesses', { "method": "get" })
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject("Fetch did not succeed");
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export async function showBusinessById(business_id) {
    return fetch(basePublicURL + 'api/business/' + business_id, { "method": "get" })
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject("Fetch did not succeed");
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export async function getLocationLatLong(location) {
    return fetch(`https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(location)}&key=c336d03eca9c406eba3e6865e09c5c02`, { "method": "get" })
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject("Fetch did not succeed");
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export function updateBusiness(bid, params) {
    var bodyPayload = {
        "business_name": params.business_name,
        "business_type": params.business_type,
        "business_mobile_number": params.business_mobile_number,
        "business_address": params.business_address,
        "business_details": params.business_details,
        "people_capacity_min": params.people_capacity_min,
        "people_capacity_max": params.people_capacity_max,
        "start_time": params.start_time,
        "end_time": params.end_time,
        "price_per_person": params.price_per_person,
        "services_and_amenities": params.services_and_amenities
    };

    var path = 'api/business/' + bid;

    let requestOptions = {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyPayload)
    };

    return fetch(basePublicURL + path, requestOptions)
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject('Fetch did not succeed');
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}

export async function getBusinessesByAddress(address) {
    return fetch(basePublicURL + 'api/business/search/' + address, { "method": "get" })
        .then((httpResponse) => {
            if (httpResponse.ok) {
                return httpResponse.json();
            } else {
                return Promise.reject("Fetch did not succeed");
            }
        })
        .then((response) => {
            console.log("Response: ", response);
            return response;
        })
        .catch(err => console.log(err));
}