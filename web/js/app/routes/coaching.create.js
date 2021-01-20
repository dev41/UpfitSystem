import {CoachingForm} from "../components/forms/CoachingForm";
import {InputFileHelper} from "../helpers/InputFileHelper";

$(function() {
    new CoachingForm($('.uf-form-create'));
    InputFileHelper.init();
});