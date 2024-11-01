<?php //SVN: $Id: analytics-report.php 118972 2009-05-19 20:52:39Z imthiaz $ 
?>
<div class="wrap"><script type='text/javascript'
	src='http://www.google.com/jsapi'></script>
<h2>Google Analytics Reports</h2>
<script type='text/javascript'>
/* <![CDATA[ */
<?php
$startTime = strtotime ( '-1 month' );
$endEnd = strtotime ( "now" );
$records = $this->getAnalyticRecords ( date ( 'Y-m-d', $startTime ), date ( 'Y-m-d', $endEnd ), 'ga:date', 'ga:visitors,ga:newVisits,ga:visits,ga:pageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits' );
?>
	google.load('visualization', '1', {packages:['annotatedtimeline','geomap','table']});
	google.setOnLoadCallback(gaChartTimeline);
	function gaChartTimeline() {
	    var gaData = new google.visualization.DataTable();
	    gaData.addColumn('date', 'Date');
	    gaData.addColumn('number', 'Visits');
	    gaData.addColumn('number', 'Pageviews');
	    gaData.addColumn('number', 'Visitors');
	    gaData.addColumn('number', 'New Visits');
	    gaData.addRows(<?php echo count ( $records ['entry'] );?>);
		<?php
		if (! empty ( $records ['entry'] )) {
			$row = 0;
			$script = '';
			foreach ( $records ['entry'] as $record ) {
				$date = date ( 'Y,m-1,d', strtotime ( $record ['dimension'] ['ga:date'] ) );
				$script .= "gaData.setValue({$row}, 0, new Date({$date}));gaData.setValue({$row}, 1, {$record['metric']['ga:visits']});gaData.setValue({$row}, 2, {$record['metric']['ga:pageviews']});gaData.setValue({$row}, 3, {$record['metric']['ga:visitors']});gaData.setValue({$row}, 4, {$record['metric']['ga:newVisits']});";
				$row ++;
			}
		}
		echo $script;
		?>	  
	    var gaVisitsPageviewsChart = new google.visualization.AnnotatedTimeLine(document.getElementById('chartTimeline'));
	    gaVisitsPageviewsChart.draw(gaData, {
	        wmode: 'transparent',
	        displayZoomButtons: false,
	        displayAnnotations: true
	    });	
	}
<?php 
$mapRecords = $this->getAnalyticRecords( date ( 'Y-m-d', $startTime ), date ( 'Y-m-d', $endEnd ) , 'ga:country' , 'ga:visits');
?>
	google.setOnLoadCallback(gaChartMapOverlay);
	function gaChartMapOverlay(){
	    var gaData = new google.visualization.DataTable();
	    gaData.addColumn('string', 'Country');
	    gaData.addColumn('number', 'Visits');
	    gaData.addRows(<?php echo count ( $mapRecords ['entry'] );?>);
		<?php
		if (! empty ( $mapRecords ['entry'] )) {
			$row = 0;
			$script = '';
			foreach ( $mapRecords ['entry'] as $record ) {
				$script .= "gaData.setValue({$row}, 0, \"".js_escape($record['dimension']['ga:country'])."\" );gaData.setValue({$row}, 1, {$record['metric']['ga:visits']});";
				$row ++;
			}
		}
		echo $script;
		?>	
		var chartOptions = {};
		chartOptions['dataMode'] = 'regions';
		chartOptions['region'] = 'world';
		var chartMap = new google.visualization.GeoMap(document.getElementById('chartWorldMap'));
		chartMap.draw(gaData,chartOptions);
	}
<?php 
$keywordsRecords = $this->getAnalyticRecords( date ( 'Y-m-d', $startTime ), date ( 'Y-m-d', $endEnd ) , 'ga:keyword' , 'ga:visits', '-ga:visits', '50');
?>
	google.setOnLoadCallback(gaTableKeywords);
	function gaTableKeywords(){
	    var gaData = new google.visualization.DataTable();
	    gaData.addColumn('string', 'Keywords');
	    gaData.addColumn('number', 'Visits');
	    gaData.addRows(<?php echo count ( $keywordsRecords ['entry'] );?>);
		<?php
		if (! empty ( $keywordsRecords ['entry'] )) {
			$row = 0;
			$script = '';
			foreach ( $keywordsRecords ['entry'] as $record ) {
				$script .= "gaData.setValue({$row}, 0, \"".js_escape($record['dimension']['ga:keyword'])."\" );gaData.setValue({$row}, 1, {$record['metric']['ga:visits']});";
				$row ++;
			}
		}
		echo $script;
		?>	
		var table = new google.visualization.Table(document.getElementById('tableKeywords'));
		table.draw(gaData, {pageSize:10,page:'enable',showRowNumber: true});
	}
<?php 
$sourceRecords = $this->getAnalyticRecords( date ( 'Y-m-d', $startTime ), date ( 'Y-m-d', $endEnd ) , 'ga:source' , 'ga:visits', '-ga:visits', '50');
?>
	google.setOnLoadCallback(gaTableSource);
	function gaTableSource(){
	    var gaData = new google.visualization.DataTable();
	    gaData.addColumn('string', 'Source');
	    gaData.addColumn('number', 'Visits');
	    gaData.addRows(<?php echo count ( $keywordsRecords ['entry'] );?>);
		<?php
		if (! empty ( $sourceRecords ['entry'] )) {
			$row = 0;
			$script = '';
			foreach ( $sourceRecords ['entry'] as $record ) {
				$script .= "gaData.setValue({$row}, 0, \"".js_escape($record['dimension']['ga:source'])."\" );gaData.setValue({$row}, 1, {$record['metric']['ga:visits']});";
				$row ++;
			}
		}
		echo $script;
		?>	
		var table = new google.visualization.Table(document.getElementById('tableSource'));
		table.draw(gaData, {pageSize:10,page:'enable',showRowNumber: true});
	}
<?php 
$browserRecords = $this->getAnalyticRecords( date ( 'Y-m-d', $startTime ), date ( 'Y-m-d', $endEnd ) , 'ga:browser' , 'ga:visits', '-ga:visits', '50');
?>
	google.setOnLoadCallback(gaTableBrowser);
	function gaTableBrowser(){
	    var gaData = new google.visualization.DataTable();
	    gaData.addColumn('string', 'Browser');
	    gaData.addColumn('number', 'Visits');
	    gaData.addRows(<?php echo count ( $keywordsRecords ['entry'] );?>);
		<?php
		if (! empty ( $browserRecords ['entry'] )) {
			$row = 0;
			$script = '';
			foreach ( $browserRecords ['entry'] as $record ) {
				$script .= "gaData.setValue({$row}, 0, \"".js_escape($record['dimension']['ga:browser'])."\" );gaData.setValue({$row}, 1, {$record['metric']['ga:visits']});";
				$row ++;
			}
		}
		echo $script;
		?>	
		var table = new google.visualization.Table(document.getElementById('tableBrowser'));
		table.draw(gaData, {pageSize:10,page:'enable',showRowNumber: true});
	}	
/* ]]> */
</script>
<style>
table.reportHolder {
	border: 1px solid #999;
}

table.reportHolder th {
	border: 1px solid #ccc;
	background-color: #ccc;
	padding: 2px;
}

table.reportHolder td.chart {
	border: 1px dashed #ccc;
	height: 300px;
	vertical-align: top;
}

table.summary {
	margin: 0;
	padding: 0;
}

table.summary th {
	text-align: right;
	background-color: #ccc;
	width: 15%;
	padding: 2px;
	border: 1px solid #ccc;
}

table.summary td {
	width: 35%;
	padding: 2px;
	border: 1px solid #ccc;
}

div.chartholder {
	height: 98%;
	width: 98%;
	text-align: center;
	margin: 5px;
}
</style>
<table class="reportHolder" width="100%">
	<tr>
		<td colspan="2">
		<table class="summary" width="100%">
			<tr>
				<th>From:</th>
				<td><?php
				echo date ( 'dS F Y', $startTime );
				?></td>
				<th>To:</th>
				<td><?php
				echo date ( 'dS F Y', $endEnd );
				?></td>
			</tr>
			<tr>
				<th>Visits:</th>
				<td><?php
				echo number_format ( $records ['aggregates'] ['metric'] ['ga:visits'] );
				?></td>
				<th>Bounce Rate:</th>
				<td><?php
				echo number_format ( $records ['aggregates'] ['metric'] ['ga:bounces'] / $records ['aggregates'] ['metric'] ['ga:entrances'] * 100, 2 );
				?> %</td>
			</tr>
			<tr>
				<th>Pageviews:</th>
				<td><?php
				echo number_format ( $records ['aggregates'] ['metric'] ['ga:pageviews'] );
				?></td>
				<th>New Visits:</th>
				<td><?php
				echo number_format ( $records ['aggregates'] ['metric'] ['ga:newVisits'] );
				?></td>
			</tr>
			<tr>
				<th>Pages/Visit:</th>
				<td><?php
				echo number_format ( $records ['aggregates'] ['metric'] ['ga:pageviews'] / $records ['aggregates'] ['metric'] ['ga:visits'], 2 );
				?></td>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<th width="100%">Timeline</th>
	</tr>
	<tr>
		<td width="100%" class="chart">
		<div id="chartTimeline" class="chartholder"></div>
		</td>
	</tr>
	<tr>
		<th width="100%">Map Overlay</th>
	</tr>
	<tr>
		<td width="100%" class="chart" style="height:350px;">
		<div id="chartWorldMap" class="chartholder"></div>
		</td>
	</tr>
	<tr>
		<th width="100%">Top Search Keywords</th>
	</tr>
	<tr>
		<td width="100%" class="chart" style="height:300px;" >
		<div id="tableKeywords" class="chartholder"></div>
		</td>
	</tr>	
	<tr>
		<th width="100%">Traffic Source</th>
	</tr>
	<tr>
		<td width="100%" class="chart" style="height:300px;" >
		<div id="tableSource" class="chartholder"></div>
		</td>
	</tr>
	<tr>
		<th width="100%">Browser</th>
	</tr>
	<tr>
		<td width="100%" class="chart" style="height:300px;" >
		<div id="tableBrowser" class="chartholder"></div>
		</td>
	</tr>		
</table>


</div>