<?php
  require_once "index.php";
  while($row = mysqli_fetch_array($lastrecord)){
    $time = $row['datetime'];
  }
  while($row = mysqli_fetch_array($firstrecord)){
    $onedayback = $row['datetime'];
  }  
  if (isset($_POST['searchbtn'])) {      
    $dateFrom = date('Y, m, d', strtotime($_POST['start'] . '-1 month'));
    $dateTo = date('Y, m, d', strtotime($_POST['end'] . ' +1 day'. '-1 month'));
  }
  else
  {
    if(empty($firstrecord)) {
      $dateFrom = date('Y, m, d, H, i, s', strtotime($onedayback . '-1 month'));
      $dateTo = date('Y, m, d', strtotime($time . ' +1 day'. '-1 month'));
    }
    else{
      $dateFrom = date('Y, m, d, H, i, s', strtotime($time . '-1 month'));
      $dateTo = date('Y, m, d', strtotime($time . ' +1 day'. '-1 month'));
    }
    
  }
?>
<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart', 'controls']});
      google.charts.setOnLoadCallback(drawDashboard);      

      function drawDashboard() {
        
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Pulse', 'Oxygen'],
<?php
          while($row = mysqli_fetch_array($chartrecords)) {
            $time = $row['datetime']; 
            $pulse = $row['pulse']; 
            $oxygen = $row['oxygen'];
            $formatedDate = date_create($time);
?>
          [{v: new Date('<?php echo $time;?>')}, <?php echo $pulse;?>, <?php echo $oxygen;?>],
<?php    
          }
?>
        ]);

        var dashboard = new google.visualization.Dashboard(
          document.getElementById('programmatic_dashboard_div'));

        var programmaticSlider = new google.visualization.ControlWrapper({
          'controlType': 'ChartRangeFilter',
          'containerId': 'programmatic_control_div',
          'options': {
            'filterColumnLabel': 'Time',
            'ui': {
              chartType: 'LineChart', 
              chartOptions: {pointSize: 10},
              'minRangeSize': 86400000
            }
          },   
          'state': {'range': {'start': new Date(<?php echo $dateFrom; ?>), 'end': new Date(<?php echo $dateTo; ?>)}}
        });

        var programmaticChart  = new google.visualization.ChartWrapper({
          'chartType': 'LineChart',
          'containerId': 'programmatic_chart_div',
          'options': {'title':'Patient data for pulse and oxygen', 
            'focusTarget':'category',
            'legend': {
                'position': 'top',
            },
            'pointSize': 5,
            'pieSliceText': 'value',
            'colors': ['red', 'blue'], 
            'vAxis':{
              'title': 'Pulse and oxygen values',
              'viewWindow':{
                'max':200
              },
              'ticks': [40, 60, 80, 100, 120, 140, 160, 180, 200]
            }, 
            'ui': {
              'chartOptions': {
                'chartArea': {'height': '100%'},
                'vAxis': {
                  'textPosition': 'in',
                  'title': 'test title',
                  'viewWindow': {
                    'min': 0,
                    'max': 100
                  },
                },
              }
            }          
          }            
        });
        dashboard.bind(programmaticSlider, programmaticChart);
        dashboard.draw(data);
        programmaticChart.draw();
      }
    </script>
  </head>
  <body>
    <div id="programmatic_dashboard_div" style="border: 1px solid #ccc">
      <table class="columns">
        <tr>
        <div id="programmatic_chart_div"></div>    
        <div id="programmatic_control_div"></div>  
        </tr>
      </table>
    </div>
  </body>
</html>