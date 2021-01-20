import {Listing} from "../components/listing/Listing";

$(function() {
    let listing = new Listing($('.js-listing'));
    listing.initDeleteEvent();
});