import {InputFileHelper} from "../helpers/InputFileHelper";
import {PlaceUpdate} from "../components/forms/PlaceUpdate";

$(function() {
    new PlaceUpdate('.uf-form-create');
    InputFileHelper.init();
});