import {StaffPositionClubListing} from "../components/listing/StaffPositionClubListing";
import {CustomerClubListing} from "../components/listing/CustomerClubListing";
import {AttributeClubListing} from "../components/listing/AttributeClubListing";
import {ClubUpdate} from "../components/forms/ClubUpdate";
import {InputFileHelper} from "../helpers/InputFileHelper";

$(function() {

    let container = $('.js-edit-club-container'),
        clubId = container.data('club_id');

    new ClubUpdate('.js-nav-tabs', clubId);
    new StaffPositionClubListing($('.js-staff-position-container'), clubId);
    new CustomerClubListing($('.js-customers-container'), clubId);
    new AttributeClubListing($('.js-attributes-container'), clubId);
    InputFileHelper.init();
});