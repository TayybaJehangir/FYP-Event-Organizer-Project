import { addNewBusiness, getUserByEmail } from 'backend/laravel-api.jsw';
import { authentication, currentMember } from 'wix-members';
const isLoggedIn = authentication.loggedIn();

var coverPhoto;
var images = [];
var vendorId;

$w.onReady(async function () {

    if (isLoggedIn) {
        console.log('Member is logged in');
        const loggedInMember = await currentMember.getMember();
        var loginEmail = loggedInMember.loginEmail;

        var userDetails = await getUserByEmail({ email: loginEmail });
        console.log('Member is logged in:', loggedInMember, userDetails);
        vendorId = userDetails.data.id;
        console.log("Vendor ID: ", vendorId);

    } else {
        console.log('Member is not logged in');
        //Open a lightbox for signup
        var option = {
            mode: 'signup',
            modal: true
        }
        authentication.promptLogin(option);
    }

    $w("#uploadCoverPhotoBtn").onChange(() => {
        $w("#uploadCoverPhotoBtn").uploadFiles()
            .then((uploadedFiles) => {
                uploadedFiles.forEach(uploadedFile => {
                    console.log('File url:', uploadedFile.fileUrl);
                    coverPhoto = uploadedFile.fileUrl;
                })
                console.log("uploaded!");
            })
            .catch((uploadError) => {
                let errCode = uploadError.errorCode; // 7751
                let errDesc = uploadError.errorDescription; // "Error description"
            });
    })

    $w("#imagesBtn").onChange(() => {
        $w("#imagesBtn").uploadFiles()
            .then((uploadedFiles) => {
                uploadedFiles.forEach(uploadedFile => {
                    console.log('File url:', uploadedFile.fileUrl);
                    images.push(uploadedFile.fileUrl);
                })
                console.log("uploaded!");
            })
            .catch((uploadError) => {
                let errCode = uploadError.errorCode; // 7751
                let errDesc = uploadError.errorDescription; // "Error description"
            });
    })

});

export async function submit_click(event) {
    let selectedValues = $w("#services").value;
    let selectedValuesString = selectedValues.join(", ");

    var payload = {
        "vendor_id": vendorId,
        "business_name": $w('#businessName').value,
        "business_type": $w('#businessType').value,
        "business_mobile_number": $w('#businessNumber').value,
        "business_address": $w('#businessAddress').value.formatted,
        "business_details": $w('#businessAbout').value,
        "people_capacity_min": $w('#capacityMin').value,
        "people_capacity_max": $w('#capacityMax').value,
        "start_time": $w('#startTime').value,
        "end_time": $w('#endTime').value,
        "price_per_person": $w('#pricePerPerson').value,
        "services_and_amenities": selectedValuesString,
        "cover_photo": coverPhoto,
        "images": images.join(", ")
    };
    console.log("PAYLOAD: ", payload);
	
    let response = await addNewBusiness(payload);
	console.log("Business Response: ", response);
}