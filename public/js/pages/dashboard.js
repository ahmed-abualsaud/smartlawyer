//[Dashboard Javascript]

//Project:	Alfa Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';



  // bootstrap WYSIHTML5 - text editor
  $('.textarea').wysihtml5();

  $('.daterange').daterangepicker({
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
  });

	
//ANALYSIS

	$("#baranl").sparkline([1,4,3,7,6,4,8,9,6,8,12,6,7,9,4,8,5,7,9,13,10,9,9,8], {
			type: 'bar',
			height: '70',
			barColor: '#ef5350',
			barWidth: 5,
    		barSpacing: 5,
		});

  // SLIMSCROLL FOR CHAT WIDGET
  $('#direct-chat').slimScroll({
    height: '300px'
  });
	
		
// donut chart
		$('.donut').peity('donut');

// chart
	$("#linechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'line',
			width: '100',
			height: '38',
			lineColor: '#06d79c',
			fillColor: '#ffffff',
			lineWidth: 2,
			minSpotColor: '#0fc491',
			maxSpotColor: '#0fc491',
		});
		
		$("#barchart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'bar',
			height: '38',
			barWidth: 6,
			barSpacing: 4,
			barColor: '#e9ab2e',
		});
		$("#discretechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'discrete',
			width: '50',
			height: '38',
			lineColor: '#745af2',
		});
	
	
//sparkline charts
$(document).ready(function() {
   var sparklineLogin = function() { 
       
  
        $("#sparkline8").sparkline([2,4,4,6,8,5,6,4,8,6,6,2 ], {
            type: 'line',
            width: '100%',
            height: '55',
            lineColor: '#fb9678',
            fillColor: '#fb9678',
            maxSpotColor: '#fb9678',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#fb9678'
        });
        
   }
    var sparkResize;
 
        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();

});
	
	
// AREA CHART

	
	
 var area = new Morris.Area({
        element: 'revenue-chart',
        data: [{
            period: '2010',
            iMac: 0,
            iPhone: 0,
            
        }, {
            period: '2011',
            iMac: 130,
            iPhone: 100,
            
        }, {
            period: '2012',
            iMac: 30,
            iPhone: 60,
            
        }, {
            period: '2013',
            iMac: 30,
            iPhone: 200,
            
        }, {
            period: '2014',
            iMac: 200,
            iPhone: 150,
            
        }, {
            period: '2015',
            iMac: 105,
            iPhone: 90,
            
        },
         {
            period: '2016',
            iMac: 250,
            iPhone: 150,
           
        }],
        xkey: 'period',
        ykeys: ['iMac', 'iPhone'],
        labels: ['iMac', 'iPhone'],
        pointSize: 0,
        fillOpacity: 0.4,
        pointStrokeColors:['#b4becb', '#01c0c8'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 0,
        smooth: true,
        hideHover: 'auto',
        lineColors: ['#b4becb', '#01c0c8'],
        resize: true
        
    });


}); // End of use strict

// easypie chart
	$(function() {
		'use strict'
		$('.easypie').easyPieChart({
			easing: 'easeOutBounce',
			onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			}
		});
		var chart = window.chart = $('.easypie').data('easyPieChart');
		$('.js_update').on('click', function() {
			chart.update(Math.random()*200-100);
		});
	});// End of use strict
