
var chart = AmCharts.makeChart("visitors", {
    "theme": "light",
    "color": "#9f9e9e",
    "type": "serial",
	"startDuration": 2,
    "dataProvider": [{
        "country": "USA",
        "visits": 4025,
        "color": "#0097ff"
    }, {
        "country": "China",
        "visits": 1882,
        "color": "#0097ff"
    }, {
        "country": "Japan",
        "visits": 1809,
        "color": "#0097ff"
    }, {
        "country": "Germany",
        "visits": 1322,
        "color": "#0097ff"
    }, {
        "country": "UK",
        "visits": 1122,
        "color": "#0097ff"
    }, {
        "country": "France",
        "visits": 1114,
        "color": "#0097ff"
    }, {
        "country": "India",
        "visits": 984,
        "color": "#0097ff"
    }, {
        "country": "Spain",
        "visits": 711,
        "color": "#0097ff"
    }, {
        "country": "Netherlands",
        "visits": 665,
        "color": "#0097ff"
    }, {
        "country": "Russia",
        "visits": 580,
        "color": "#0097ff"
    }, {
        "country": "South Korea",
        "visits": 443,
        "color": "#0097ff"
    }, {
        "country": "Canada",
        "visits": 441,
        "color": "#0097ff"
    }],
    "valueAxes": [{
        "position": "left",
        "title": "Visitors"
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "fillColorsField": "color",
        "fillAlphas": 1,
        "lineAlpha": 0.1,
        "type": "column",
        "valueField": "visits"
    }],
    "depth3D": 20,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "country",
    "categoryAxis": {
        "gridPosition": "left",
        "labelRotation": 90
    },
    "export": {
    	"enabled": true
     }
});




var chart = AmCharts.makeChart("transaction", {
    "theme": "dark",
    "color": "#9f9e9e",
    "type": "serial",
    "dataProvider": [{
        "country": "USA",
        "year2004": 3.5,
        "year2005": 4.2,
        "color": "#0394f8"
    }, {
        "country": "UK",
        "year2004": 1.7,
        "year2005": 3.1,
        "color": "#0394f8"
    }, {
        "country": "Canada",
        "year2004": 2.8,
        "year2005": 2.9,
        "color": "#0394f8"
    }, {
        "country": "Japan",
        "year2004": 2.6,
        "year2005": 2.3,
        "color": "#0394f8"
    }, {
        "country": "France",
        "year2004": 1.4,
        "year2005": 2.1,
        "color": "#0394f8"
    }, {
        "country": "Brazil",
        "year2004": 2.6,
        "year2005": 4.9,
        "color": "#0394f8"
    }, {
        "country": "Russia",
        "year2004": 6.4,
        "year2005": 7.2,
        "color": "#0394f8"
    }],
    "valueAxes": [{
        "stackType": "3d",
        "unit": "%",
        "position": "left",
        "title": "GDP growth rate",
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "GDP grow in [[category]] (2004): <b>[[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2004",
        "type": "column",
        "valueField": "year2004",
        "fillColorsField": "color",
    }, {
        "balloonText": "GDP grow in [[category]] (2005): <b>[[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2005",
        "type": "column",
        "valueField": "year2005"
    }],
    "plotAreaFillAlphas": 0.1,
    "depth3D": 60,
    "angle": 30,
    "categoryField": "country",
    "categoryAxis": {
        "gridPosition": "start"
    },
    "export": {
        "enabled": true
     }
});
jQuery('.chart-input').off().on('input change',function() {
    var property    = jQuery(this).data('property');
    var target      = chart;
    chart.startDuration = 0;

    if ( property == 'topRadius') {
        target = chart.graphs[0];
        if ( this.value == 0 ) {
          this.value = undefined;
        }
    }

    target[property] = this.value;
    chart.validateNow();
});

//charts end
$.ajaxSetup({
    beforeSend: function (xhr)
    {
       xhr.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
    }
});
function showAlert(title=null,content=null,type='blue') {
	$.alert({
		icon: 'fa fa-warning',
		title: title,
		content : content,
		type  : type,
		theme:'material',
		closeIcon: true,
		closeIconClass: 'fa fa-close',
		draggable: false,
	});
}
function showError(content) {
	$.alert({
		icon: 'fa fa-warning',
		title: "Error",
		content : content,
		type  : 'red',
		theme:'material',
		closeIcon: true,
		draggable: false,
		closeIconClass: 'fa fa-close'
	});
}
function showSuccess(content) {
	$.alert({
		icon: 'fa fa-check-circle',
		title: 'Success',
		content : content,
		type  : 'green',
		theme:'material',
		draggable: false,
		closeIcon: true,
		closeIconClass: 'fa fa-close'
	});
}
function showInfo(content) {
	$.alert({
		icon: 'fa fa-info-circle',
		title: 'Info',
		content : content,
		type  : 'blue',
		theme:'material',
		draggable: false,
		closeIcon: true,
		closeIconClass: 'fa fa-close'
	});
}
function loadJsPlugin() {
    $('.multiSelect_input').multiSelect('refresh');
    $('.show-tick').selectpicker();
    console.log('called');
}
function showAjaxFormValidation(errorData) {
    var path = $("#asset_path").html();
    var errors = '';
    var errorResp =	JSON.parse(errorData.responseText);
    for(datos in errorResp.errors){
        errors += ' <li><img src="'+path+'new_admin/images/danger.svg" class="icon_svg" /> ' + errorResp.errors[datos][0] + '</li>';
    }
    $(document.body).find('.form_validation_error').show();
    $(document.body).find( '.error_list' ).html( errors ); //appending to a <div id="form-errors"></div> inside form
    $('html, body').animate({scrollTop:0},'50');
}
var autocomplete = {};
function initialize() {
    name	= 'location';
        if ($('#location').length == 0) {
            return;
        }
        autocomplete[name] = new google.maps.places.Autocomplete($('#location')[0], {
            types: ['geocode'],
            componentRestrictions: {
                country: ['au', 'in']
            }
        });
        google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
            var place = autocomplete[name].getPlace();
            $(document.body).find('#latitude').val(place.geometry.location.lat());
            $(document.body).find('#longitude').val(place.geometry.location.lng());
        });
}
function generatePassword() {
    var length = 10,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()+-",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}
