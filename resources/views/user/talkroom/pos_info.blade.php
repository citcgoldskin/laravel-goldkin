@extends('user.layouts.app')
@section('title', '位置情報')
<style>
    body, #info_ttl {
        background: #FFF;
    }
    .switch_01 label {
        width: 50px !important;
    }
</style>
@section('content')
    @include('user.layouts.header_info2')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    @php
        $obj_user = Auth::guard('web')->check() ? Auth::user() : null;
        $can_share_location = 0;
        $lrs_id = 0; // lesson_request_schedule's id
        $is_share_location = 0;
        if (!is_null($obj_user) && isset($obj_lrs) && is_object($obj_lrs)) {
            $can_share_location = $obj_lrs->can_share_location;
            $lrs_id = $obj_lrs->lrs_id ;
            if ($can_share_location) {
                $is_share_location = $obj_lrs->getIsShareLocation($obj_user->id);
            }
        }
    @endphp
    <div id="contents">
        <!--main_-->
        <section class="pt0">

            <input type="hidden" name="is_share_location" id="is_share_location" value="{{ $is_share_location }}">
            <div class="white_box effect_shadow">
                <div class="switch_box">
                    <div class="switch_01 flex-space">
                        <p>
                            <a class="modal-syncer button-link" data-target="{{ isset($available_cancel) && $available_cancel ? 'modal-msg_cancel' : '' }}">
                                <strong>位置情報を共有</strong>
                            </a>
                        </p>
                        <input name="commitment" type="checkbox" id="sw1-1" value="{{ $is_share_location ? 1 : 0 }}" {{ $is_share_location ? 'checked' : '' }} {{ $can_share_location ? '' : 'disabled' }}>
                        <label for="sw1-1">
                        </label>
                    </div>
                </div>
                <div class="kome_txt pt0_b10">
                    <p class="mark_left mark_kome">
                        レッスン開始またはレッスン時刻から30分経過で自動で共有オフになります。
                    </p>
                </div>
            </div>
        </section>

        <section id="location_map">
            <div id="map">
            </div>
        </section>
    </div><!-- /contents -->

    <footer>
        <div class="location_info type_under">
            <p id="marked_address"></p>
        </div>
    </footer>

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap">
        <div id="modal-msg_cancel" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        コウハイとのレッスンを<br>
                        キャンセルします<br>
                        よろしいですか？
                    </h2>

                    <div class="modal_txt">
                        <p>
                            現在のキャンセル料<br>
                            <strong>1,000</strong><small>円</small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange">
                    <a href="#">OK</a> <!--D-25.php-->
                </div>
                <div class="btn_base btn_gray-line">
                    <a href="#">キャンセル</a><!--D-25.php-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="https://cdn.klokantech.com/maptilerlayer/v1/index.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&region=JP&language=ja&callback=initMap&libraries=places" defer></script>
    <script>
        var map;
        var markers = [];
        /*var temp_location_senpai = {lat: 40.1338352, lng: 124.3937872};
        var temp_location_target = {lat: 40.1358352, lng: 124.3957872};
        var temp_location_koupai = {lat: 40.1318352, lng: 124.3938872};*/

        var timeout_map;
        /*var timeout_condition = 0;*/

        $(document).ready(function () {
            timeout_map = setInterval(getMapLocation, 20000);
            initMap();

            $('#sw1-1').change(function(e) {
                let is_share_location = $('#is_share_location').val();

                // set share option
                $.ajax({
                    type: "post",
                    url: '{{ route('user.talkroom.set_share_location') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        request_schedule_id: "{{ $lrs_id }}",
                        is_share_location: is_share_location,
                        user_id: "{{ Auth::user() ? Auth::user()->id : 0 }}",
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result.result == "success") {
                            getMapLocation();
                            if(is_share_location == 1) {
                                $('#is_share_location').val(0);
                            } else {
                                $('#is_share_location').val(1);
                            }
                        }
                    }
                });
            });
        });

        // map
        function initMap() {

            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude =  parseFloat(position.coords.latitude);
                var longitude =  parseFloat(position.coords.longitude);

                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: new google.maps.LatLng(latitude, longitude)
                });

                var geocoder = new google.maps.Geocoder();
                var latlng = {
                    lat: latitude,
                    lng: longitude
                };

                // show current address
                geocoder
                    .geocode({ location: latlng })
                    .then((response) => {
                        if (response.results[0]) {
                            $('#marked_address').text(response.results[0].formatted_address);
                        } else {
                            // error
                        }
                    })
                    .catch((e) => window.alert("Geocoder failed due to: " + e));

                // map click event
                google.maps.event.addListener(map, 'click', function(event) {
                    geocoder.geocode({
                        'latLng': event.latLng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                $('#marked_address').text(results[0].formatted_address);
                            }
                        }
                    });
                });

                // get map info
                getMapLocation();

                // addYourLocationButton(map, myMarker);

            });
        }

        function addYourLocationButton(map, marker)
        {
            var controlDiv = document.createElement('div');

            var firstChild = document.createElement('button');
            firstChild.style.backgroundColor = '#fff';
            firstChild.style.border = 'none';
            firstChild.style.outline = 'none';
            firstChild.style.width = '28px';
            firstChild.style.height = '28px';
            firstChild.style.borderRadius = '2px';
            firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
            firstChild.style.cursor = 'pointer';
            firstChild.style.marginRight = '10px';
            firstChild.style.padding = '0px';
            firstChild.title = 'Your Location';
            controlDiv.appendChild(firstChild);

            var secondChild = document.createElement('div');
            secondChild.style.margin = '5px';
            secondChild.style.width = '18px';
            secondChild.style.height = '18px';
            secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
            secondChild.style.backgroundSize = '180px 18px';
            secondChild.style.backgroundPosition = '0px 0px';
            secondChild.style.backgroundRepeat = 'no-repeat';
            secondChild.id = 'you_location_img';
            firstChild.appendChild(secondChild);

            google.maps.event.addListener(map, 'dragend', function() {
                $('#you_location_img').css('background-position', '0px 0px');
            });

            firstChild.addEventListener('click', function() {
                var imgX = '0';
                var animationInterval = setInterval(function(){
                    if(imgX == '-18') imgX = '0';
                    else imgX = '-18';
                    $('#you_location_img').css('background-position', imgX+'px 0px');
                }, 500);
                if(navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        marker.setPosition(latlng);
                        map.setCenter(latlng);
                        clearInterval(animationInterval);
                        $('#you_location_img').css('background-position', '-144px 0px');
                    });
                }
                else{
                    clearInterval(animationInterval);
                    $('#you_location_img').css('background-position', '0px 0px');
                }
            });

            controlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
        }

        function addMarker(marker) {
            markers.push(marker);
        }

        function addMarkerByPosition(position) {
            const marker = new google.maps.Marker({
                position,
                map,
            });

            markers.push(marker);
        }

        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function hideMarkers() {
            setMapOnAll(null);
        }

        // Shows any markers currently in the array.
        function showMarkers() {
            setMapOnAll(map);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            hideMarkers();
            markers = [];
        }

        function initialMarker(user_location, lesson_areas) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude =  parseFloat(position.coords.latitude);
                var longitude =  parseFloat(position.coords.longitude);

                var geocoder = new google.maps.Geocoder();

                var blueDot = {
                    fillColor: '#4285F4',
                    fillOpacity: 1,
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    strokeColor: 'rgb(255,255,255)',
                    strokeWeight: 2,
                };

                // set blue dot for current location
                var myMarker = new google.maps.Marker({
                    // map: map,
                    icon: blueDot,
                    position: new google.maps.LatLng(latitude, longitude)
                });

                google.maps.event.addListener(myMarker, 'click', function(event) {
                    geocoder.geocode({
                        'latLng': event.latLng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                $('#marked_address').text(results[0].formatted_address);
                            }
                        }
                    });
                });

                addMarker(myMarker);

                // blue dot circle with radius(200m)
                var accuracyCircle = new google.maps.Circle({
                    center: new google.maps.LatLng(latitude, longitude),
                    fillColor: '#61a0bf',
                    fillOpacity: 0.4,
                    radius: 200,
                    strokeColor: '#1bb6ff',
                    strokeOpacity: 0.4,
                    strokeWeight: 1,
                    zIndex: 1,
                });

                addMarker(accuracyCircle);
                //accuracyCircle.setMap(map);

                // user location
                if (user_location != "" && user_location.lat != undefined && user_location.lat != "") {

                    let icon_user = {
                        url: "{{asset('assets/user/img/user_location.png')}}",
                        scaledSize: new google.maps.Size(50, 50), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(0, 0) // anchor
                    }
                    var userMarker = new google.maps.Marker({
                        position: new google.maps.LatLng(user_location.lat, user_location.lng),
                        icon: icon_user
                    });

                    google.maps.event.addListener(userMarker, 'click', function(event) {
                        geocoder.geocode({
                            'latLng': event.latLng
                        }, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[0]) {
                                    $('#marked_address').text(results[0].formatted_address);
                                }
                            }
                        });
                    });

                    addMarker(userMarker);
                }

                // lesson area location
                if (lesson_areas.length > 0) {
                    for (let i=0; i < lesson_areas.length; i ++) {
                        if (lesson_areas[i].lat != undefined && lesson_areas[i].lat != "") {
                            let targetMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(lesson_areas[i].lat, lesson_areas[i].lng),
                            });

                            google.maps.event.addListener(targetMarker, 'click', function(event) {
                                geocoder.geocode({
                                    'latLng': event.latLng
                                }, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        if (results[0]) {
                                            $('#marked_address').text(results[0].formatted_address);
                                        }
                                    }
                                });
                            });

                            addMarker(targetMarker);
                        }
                    }
                }

                showMarkers();

            });
        }

        function getMapLocation() {

            // get locations
            $.ajax({
                type: "post",
                url: '{{ route('user.talkroom.get_map_location') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    request_schedule_id: "{{ $lrs_id }}",
                    user_id: "{{ Auth::user() ? Auth::user()->id : 0 }}",
                },
                dataType: 'json',
                success: function (result) {
                    if (result.result == "success") {
                        let user_location = "";
                        let lesson_areas = [];
                        if (result.area.user_location != undefined && result.area.user_location != "") {
                            user_location = JSON.parse(result.area.user_location);
                        }

                        if (result.area.lesson_areas != undefined && result.area.lesson_areas.length > 0) {
                            for (let i=0; i < result.area.lesson_areas.length - 1; i ++) {
                                let position = result.area.lesson_areas[i].position;
                                if (position != "") {
                                    position = JSON.parse(position);
                                    lesson_areas.push(position);
                                }
                            }
                        }

                        // remove markers
                        deleteMarkers();

                        // render markers
                        initialMarker(user_location, lesson_areas);
                    }

                }
            });
        }
    </script>
@endsection
<!-- ********************************************************* -->

