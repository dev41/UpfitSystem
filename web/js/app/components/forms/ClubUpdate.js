import {VisualComponent} from "../VisualComponent";
import {Scheduler} from "../Scheduler";
import {ErrorHelper} from "../../helpers/ErrorHelper";
import {InputFileHelper} from "../../helpers/InputFileHelper";
import {Snackbar, SnackbarPosition, SnackbarType} from "../Snackbar";

const actionUrl = {
    SCHEDULE: '/schedule/get-schedule-as-component',
    UPLOAD_IMAGES: 'upload-images',
};

const REGEXP = /[\w_[\d]+]/;

class ClubUpdate extends VisualComponent
{
    constructor(container, clubId)
    {
        super(container);

        this.clubId = clubId;

        this.currentGeoCoords = null;

        let self = this,
            init = function () {
                self.initElements();
                self.initEvents();
            };

        if (navigator.geolocation.getCurrentPosition) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                self.currentGeoCoords = pos;
                init();
            }, function () {
                init();
            });
        } else {
            init();
        }
    }

    initElements()
    {
        this.elements.buttonSchedule = '.js-button-schedule';
        this.elements.buttonSubmitInfo = '.js-button-process-info';
        this.elements.buttonSubmitContacts = '.js-button-process-contacts';
        this.elements.buttonPasswordEye = '.js-password-eye';

        this.elements.navTabs = '.nav-tabs a';

        this.elements.formPopup = $('<div class="uf-popup uf-component" title="">');
        this.elements.formPopup.dialog({
            autoOpen: false,
            width: 1150,

            modal: true,
            resizable: false,
        });
        this.elements.lat = this.container.find('.js-lat');
        this.elements.lng = this.container.find('.js-lng');
        this.elements.country = this.container.find('.js-country');
        this.elements.city = this.container.find('.js-city');
        this.elements.address = this.container.find('.js-address');
    }

    initEvents()
    {
        let self = this,
            hash = window.location.hash;

        if (hash) {
            $('ul.nav a[href="' + hash + '"]').tab('show');
        }

        self.container.on('click', this.elements.navTabs, function () {
            let scrollmem = $('body').scrollTop() || $('html').scrollTop();

            $(this).tab('show');
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });

        this.container.on('click', this.elements.buttonSchedule, function () {
            let button = $(this),
                title = button.attr('data-title');

            $.ajax({
                url: actionUrl.SCHEDULE,
                data: {
                    clubId: self.clubId,
                }
            }).done(function (response) {
                let formPopup = self.elements.formPopup,
                    newForm = response.html;

                formPopup.html(newForm);
                formPopup.dialog({title: title});

                formPopup.dialog('open');

                new Scheduler($('.js-schedule-container'));

                formPopup.closest('.ui-dialog').css('top', (window.innerHeight - formPopup.height()) / 2);

                formPopup.find('.js-button-cancel').on('click', function () {
                    formPopup.dialog('close');
                });
            });
        });

        this.container.on('click', this.elements.buttonSubmitContacts, function (e) {
            e.preventDefault();
            self.lock();
            self.updateClubData($(this));
        });

        this.container.on('click', this.elements.buttonSubmitInfo, function (e) {
            e.preventDefault();

            let formName = $(this).attr('data-form-name'),
                fileInputs = $(formName + ' input[type="file"]'),
                data = new FormData(),
                fileInputName = '';

            fileInputs = fileInputs.toArray();
            fileInputs.map(function (fileInput) {
                fileInputName = (REGEXP.exec($(fileInput).attr('name')));
                $.each($(fileInput)[0].files, function (i, file) {
                    data.append(fileInputName + '[' + i + ']', file);
                });
            });

            self.lock();
            self.updateClubData($(this), data);
        });

        this.container.on('click', this.elements.buttonPasswordEye, function (e) {
            let input = $(this).parents('.input-group-addon').siblings('input');
            if (input.attr('type') === 'text') {
                input.attr('type', 'password');
            } else {
                input.attr('type', 'text');
            }
        });

        self.uploadMap();
    }

    uploadMap()
    {
        let self = this,
            lat = parseFloat(self.elements.lat.val()),
            lng = parseFloat(self.elements.lng.val()),
            coord = {
                lat: 55.7577002,
                lng: 37.6138353
            },
            isCoordSet = false,
            geocoder = new google.maps.Geocoder(),
            marker,
            oldMarker;

        if (this.currentGeoCoords) {
            coord = {
                lat: this.currentGeoCoords.coords.latitude,
                lng: this.currentGeoCoords.coords.longitude,
            };
        }

        if (lat && lng) {
            coord = {lat: lat, lng: lng};
            isCoordSet = true;
        }

        let map = new google.maps.Map(this.container.find('.js-map')[0], {
                zoom: 15,
                center: coord
            }),
            input = this.container.find('.js-pac-input')[0],
            autocomplete = new google.maps.places.Autocomplete(input, {placeIdOnly: true});

        autocomplete.bindTo('bounds', map);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        autocomplete.addListener('place_changed', function () {
            let place = autocomplete.getPlace();

            if (!place.place_id) {
                return;
            }
            geocoder.geocode({'placeId': place.place_id}, function (results, status) {

                if (status !== 'OK') {

                }
                map.setZoom(7);
                map.setCenter(results[0].geometry.location);
            });
        });

        if (isCoordSet) {
            oldMarker = self.newMarker(coord, map);
        }

        google.maps.event.addListener(map, 'click', function (event) {
            if (oldMarker) {
                oldMarker.setMap(null);
            }
            if (marker) {
                marker.setMap(null);
            }
            marker = self.newMarker(event.latLng, map);
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status !== google.maps.GeocoderStatus.OK) {
                    window.alert('Geocoder failed due to: ' + status);
                    return;
                }
                if (results[0]) {
                    let address,
                        city,
                        country;

                    results[0]['address_components'].forEach(function (item) {
                        switch (item.types[0]) {
                            case 'street_number':
                                address = item.long_name + ' ';
                                break;
                            case 'route':
                                address += item.long_name;
                                break;
                            case 'locality':
                                city = item.long_name;
                                break;
                            case 'country':
                                country = item.long_name;
                                break;
                        }
                    });

                    if (address) {
                        self.elements.address.val(address);
                    }

                    if (city) {
                        self.elements.city.val(city);
                    }

                    if (country) {
                        self.elements.country.val(country);
                    }
                }
            });
            self.elements.lat.val(event.latLng.lat().toFixed(4));
            self.elements.lng.val(event.latLng.lng().toFixed(4));
        });
    }

    newMarker(location, map)
    {
        let marker = new google.maps.Marker({
            position: location,
            map: map,
            title: 'Our club',
        });
        marker.setMap(map);
        return marker;
    }

    updateClubData(button, files = null)
    {
        let url = button.attr('data-url'),
            formName = button.attr('data-form-name'),
            message = JSON.parse(button.attr('data-message')),
            form = this.container.find(formName),
            self = this;

        $.ajax({
            url: url + '?id=' + this.clubId,
            method: 'post',
            data: form.serializeArray()
        }).done(function (response) {
            Snackbar.show({
                type: SnackbarType.SUCCESS,
                message: message.success,
            });
        }).fail(function (xhr) {
            ErrorHelper.highlightErrorsByXhrAndForm(xhr, form);
            Snackbar.show({
                type: SnackbarType.ERROR,
                message: message.error,
            });
        }).always(function () {
            self.unlock();
            InputFileHelper.init();
        });

        if (files !== null) {
            $.ajax({
                url: actionUrl.UPLOAD_IMAGES + '?id=' + this.clubId,
                method: 'post',
                contentType: false,
                processData: false,
                data: files

            }).done(function (response) {

            }).fail(function (xhr) {
                Snackbar.show({
                    type: SnackbarType.ERROR,
                    message: message.errorLoadImages,
                });
            }).always(function () {
                self.unlock();
            });
        }
    }
}

export {ClubUpdate}