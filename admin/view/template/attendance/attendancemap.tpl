<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
    <i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
    </div>
  </div>
  <div    class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Attendance Report-Map</h3>        
      </div>
	<div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">User Name <div style="font-size: 10px;">(First name Only)</div></label>
                <input type="text" name="filter_username" value="<?php echo $filter_username; ?>" placeholder="User Name" id="input-name" class="form-control" />
                <input type="hidden" name="filter_userid"  value="<?php echo $filter_userid; ?>" id="filter_userid"/>
              </div>
			  
               <div class="form-group">
                <label class="control-label" for="input-date-start">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div> 
            </div>
            <div class="col-sm-6">
              
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date<br/><br/></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
      <div id="map" class="panel-body" style="height: 500px !important;" >

          </div>
    </div>
  </div>
  </div>
 
  <style>
.labels{
    color: #fff;
    font-weight: bold;
    font-size: 14px;
    opacity: 1;
    pointer-events: none;
    text-align: center;
    width: 60px;
    white-space: nowrap;
}
</style>
<script>
function pinSymbol(color) {
    return {
        path: 'M31.5,0C14.1,0,0,14,0,31.2C0,53.1,31.5,80,31.5,80S63,52.3,63,31.2C63,14,48.9,0,31.5,0z M31.5,52.3 c-11.8,0-21.4-9.5-21.4-21.2c0-11.7,9.6-21.2,21.4-21.2s21.4,9.5,21.4,21.2C52.9,42.8,43.3,52.3,31.5,52.3z',
        fillColor: color,
        fillOpacity: 1,
        strokeColor: '#000',
        strokeWeight: 0,
        scale: 1,
   };
}
      var customLabel = {
        in: {
          label: ''        
        },
        out: {
          label: ''
        }
      };

        function initMap() { 

try{

        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng('<?php echo $average_lattitude; ?>', '<?php echo $average_longtitude; ?>'),
          zoom: 8
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('https://unnati.world/shop/admin/index.php?route=attendance/attendance/map_data&token=<?php echo $token; ?>&filter_userid=<?php echo $filter_userid; ?>&filter_username=<?php echo $filter_username; ?>&filter_date_start=<?php echo $filter_date_start; ?>&filter_date_end=<?php echo $filter_date_end; ?>', function(data) {
            var xml = data.responseXML;
	
            var markers = xml.documentElement.getElementsByTagName('marker');
	
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};

              var marker = new google.maps.Marker({
                map: map,
                position: point,
	  label: icon.label,
              });
	if (type == 'in') {
 	marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green.png');
	}
	else{
 	marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red.png');
	}
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
}catch(e){alert(e);}
        }



      function downloadUrl(url, callback) {
	//alert(url);
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZHnyQY8mmMWgUXI-R4JHTsi326HkXxms&callback=initMap">
    </script>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=attendance/attendance/map&token=<?php echo $token; ?>';
	
    var filter_userid = $('#filter_userid').val();
	
	
	var filter_username = $('#input-name').val();
	
	if (filter_username!="") {
		url += '&filter_username=' + encodeURIComponent(filter_username);
		if (filter_userid!="") 
		{
		url += '&filter_userid=' + encodeURIComponent(filter_userid);
		}
	}
	var filter_date_start = $('#input-date-start').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
              //alert(url);
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=attendance/attendance/map&token=<?php echo $token; ?>';
	
    var filter_userid = $('#filter_userid').val();
	
	if (filter_userid!="") {
		url += '&filter_userid=' + encodeURIComponent(filter_userid);
	}
	var filter_username = $('#input-name').val();
	
	if (filter_username!="") {
		url += '&filter_username=' + encodeURIComponent(filter_username);
	}
	var filter_date_start = $('#input-date-end').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
    
       
    //location = url;
        window.open(url, '_blank');
});
//-->

</script> 

<script type="text/javascript">
$('input[name=\'filter_username\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=attendance/attendance/autocomplete&token=<?php echo $token; ?>&filter_username=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_username\']').val(item['label']);
                $('input[name=\'filter_userid\']').val(item['value']);
    }
});
</script>

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>

<?php //echo $footer; ?>