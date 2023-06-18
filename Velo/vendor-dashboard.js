//Get Vendor ID, Get all the businesses of the vendor
// The details should include "Status", "Array of businesses" -> Business_name, business_type, business_locatoion, business_capacity
import { authentication, currentMember } from 'wix-members';
import { getUserByEmail, getVendorAllBusiness } from 'backend/laravel-api.jsw';
import wixLocation from 'wix-location';

const isLoggedIn = authentication.loggedIn();

var repeaterData = [];

$w.onReady(async function () {

    $w('#repeater1').onItemReady(($item, itemData, index) => {
        $item('#coverImg').src = itemData.cover_photo;
        $item('#text26').text = itemData.business_name;
        $item('#text27').text = itemData.business_type;
        $item('#text28').text = itemData.business_address;
        $item('#text29').text = itemData.capacity;

        $item('#container2').onClick(() => {
			//Redirect the user to the detail page with id in the URL
			console.log("Business ID: ", itemData.business_id);
            wixLocation.to("/business-details?bid=" + itemData.business_id);
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
            if (userRole == 3) {
                //Show a popup alert that you need to sign up as a vendor
            } else if (userRole == 2) {
                //Vendor Role
                //Get the dashborad data
                var dashboradResult = await getVendorAllBusiness(userDetails.data.id);
                if (dashboradResult.status == "Success" && dashboradResult.data?.length > 0) {
                    $w('#noFound').collapse();
                    for (let i = 0; i < dashboradResult.data.length; i++) {
                        var obj1 = dashboradResult.data[i];
                        repeaterData.push({
                            "_id": "business" + obj1.id,
                            "business_id": obj1.id,
                            "business_name": obj1.business_name,
                            "business_type": obj1.business_type,
                            "business_address": obj1.business_address,
                            "capacity": obj1.people_capacity_min + " - " + obj1.people_capacity_max,
                            "cover_photo": obj1.cover_photo
                        });
                    }
                    $w('#repeater1').data = repeaterData;
                    $w('#repeater1').expand();
                } else {
                    $w('#noFound').expand();
                }
            }
        }

    } else {
        console.log('Member is not logged in');
        //Open a lightbox for signup
        var option = {
            mode: 'signup',
            modal: true
        }
        authentication.promptLogin(option);
    }

});