//Get Vendor ID, Get all the businesses of the vendor
// The details should include "Status", "Array of businesses" -> Business_name, business_type, business_locatoion, business_capacity
import { authentication, currentMember } from 'wix-members';
import { getUserByEmail, getVendorAllBusiness, getBusinessesByAddress } from 'backend/laravel-api.jsw';
import wixLocation from 'wix-location';

const isLoggedIn = authentication.loggedIn();

var repeaterData = [];

$w.onReady(async function () {

    $w('#repeater1').onItemReady(($item, itemData, index) => {
        $item('#coverImg').src = itemData.cover_photo;
        $item('#text26').text = itemData.capacity;
        $item('#text27').text = itemData.price_per_person;
        $item('#text28').text = itemData.imagesCount;
        $item('#text29').text = itemData.business_name;
        $item('#text30').text = itemData.business_type;
        $item('#text31').text = itemData.business_address;

        $item('#container2').onClick(() => {
            //Redirect the user to the detail page with id in the URL
            console.log("Business ID: ", itemData.business_id);
            wixLocation.to("/show-business-details?bid=" + itemData.business_id);
        });
    });

    if (isLoggedIn) {
        console.log('Member is logged in');
        const loggedInMember = await currentMember.getMember();
        var loginEmail = loggedInMember.loginEmail;

        var userDetails = await getUserByEmail({ email: loginEmail });
        console.log('Member is logged in:', loggedInMember, userDetails);
        var userRole;

        if (userDetails.status == "Success") {
            userRole = userDetails.data.role;
            $w('#text25').text = "Welcome " + userDetails.data.first_name + " " + userDetails.data.last_name;
        }
    }
});

export async function searchBtn(event) {

    var enteredLocation = $w('#addressInput1').value.city;
    console.log("Location: ", $w('#addressInput1').value);
    var businessResults = await getBusinessesByAddress(enteredLocation);
    if (businessResults.status == "Success" && businessResults.data?.length > 0) {
        $w('#noFound').collapse();
        for (let i = 0; i < businessResults.data.length; i++) {
            var obj1 = businessResults.data[i];

            //Images Count is pending
            var imagesCount;
            if (obj1.images.length) {
                var imagesString = obj1.images;
                const imagesArray = imagesString.split(", ");
                imagesCount = imagesArray.length;
            }

            repeaterData.push({
                "_id": "business" + obj1.id,
                "business_id": obj1.id,
                "business_name": obj1.business_name,
                "business_type": obj1.business_type,
                "business_address": obj1.business_address,
                "capacity": obj1.people_capacity_min + " - " + obj1.people_capacity_max,
                "cover_photo": obj1.cover_photo,
                "price_per_person": obj1.price_per_person,
                "imagesCount": imagesCount.toString()
            });
        }
        $w('#repeater1').data = repeaterData;
        $w('#repeater1').expand();
    } else {
        $w('#noFound').expand();
    }
}