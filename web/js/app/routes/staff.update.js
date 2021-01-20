import {ClubPositionsListing} from "../components/listing/ClubPositionListing";
import {InputFileHelper} from "../helpers/InputFileHelper";
import {StaffUpdate} from "../components/forms/StaffUpdate";

$(function () {
    let container = $('.js-edit-staff-container'),
        staffId = container.data('staff_id');
    new StaffUpdate( '.js-nav-tabs', staffId);
    new ClubPositionsListing($('.js-club-position-container'));
    InputFileHelper.init();
});