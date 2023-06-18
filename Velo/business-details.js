import { showBusinessById, getUserByEmail, getLocationLatLong } from 'backend/laravel-api.jsw';
import wixLocation from 'wix-location';

let query = wixLocation.query;

$w.onReady(async function () {
    var businessResult = await showBusinessById(query.bid);
    $w('#repeater1').data = [];
    $w('#repeater1').onItemReady(($item, itemData, index) => {
        $item('#serviceName').text = itemData.serviceName;
    });

    if (businessResult.status == "Success") {
        $w('#businessName').text = businessResult.data?.business_name.length ? businessResult.data.business_name : "-";
        $w('#businessType').text = businessResult.data?.business_type.length ? businessResult.data.business_type : "-";
        $w('#businessAddress').text = businessResult.data?.business_address.length ? businessResult.data.business_address : "-";
        $w('#pricePerPerson').text = businessResult.data?.price_per_person.length ? "PKR " + businessResult.data.price_per_person + "/- Price Per Person" : "-";
        $w('#businessContact').text = businessResult.data?.business_mobile_number.length ? businessResult.data.business_mobile_number + "(" + $w('#businessName').text + ")" : "-";
        $w('#coverPhoto').src = businessResult.data?.cover_photo;
        $w('#about').text = businessResult.data?.business_details.length ? businessResult.data.business_details : "-";
		$w('#peopleCapacity').text = businessResult.data?.people_capacity_min ? businessResult.data.people_capacity_min + " - " + businessResult.data.people_capacity_max + " Capacity" : "-";
		$w('#availabillity').text = businessResult.data?.start_time ? businessResult.data.start_time + " - " + businessResult.data.end_time + " Available Hours" : "-";

        if (businessResult.data?.images.length) {
            var imagesPayload = [];
            var imagesString = businessResult.data.images;
            const imagesArray = imagesString.split(", ");
            for (let i = 0; i < imagesArray.length; i++) {
                imagesPayload.push({
                    "type": "image",
                    "title": $w('#businessName').text + " Image " + i + 1,
                    "src": imagesArray[i]
                })
            }
            $w("#gallery1").items = imagesPayload;
        }

        if (businessResult.data?.services_and_amenities.length) {
            var servicesString = businessResult.data.services_and_amenities;
            const servicesArray = servicesString.split(", ");
            var repearterData = [];
            for (let i = 0; i < servicesArray.length; i++) {
                repearterData.push({
                    "_id": "Service" + i,
                    "serviceName": servicesArray[i]
                });
            }
            $w('#repeater1').data = repearterData;
        }

        if (businessResult.data?.business_address.length) {
            var locationResult = await getLocationLatLong(businessResult.data.business_address);
            if (locationResult.results && locationResult.results.length > 0) {
                const { lat, lng } = locationResult.results[0].geometry;
                $w("#googleMaps1").location = {
                    "latitude": lat,
                    "longitude": lng,
                    "description": $w('#businessName').text
                };
                console.log("Latitude:", lat);
                console.log("Longitude:", lng);
            } else {
                console.log("No results found for the address.");
            }
        }

    }

});

export function aboutBtn_click(event) {
    $w("#text31").scrollTo();
}

export function galleryBtn_click(event) {
    $w("#text33").scrollTo();
}

export function amenitiesBtn_click(event) {
    $w("#text34").scrollTo();
}

export function locationBtn_click(event) {
    $w("#text36").scrollTo();
}

export function button1_click(event) {
    wixLocation.to("/edit-business?bid=" + query.bid);
}

export function accomodationBtn_click(event) {
	$w("#text37").scrollTo();
}