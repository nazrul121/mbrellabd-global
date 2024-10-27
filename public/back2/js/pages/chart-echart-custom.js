'use strict';$(document).ready(function(){setTimeout(function(){var dom=document.getElementById("chart-echart-line-basic");var myChart=echarts.init(dom);var app={};var option=null;option={tooltip:{trigger:'axis'},xAxis:{type:'category',data:['Mon','Tue','Wed','Thu','Fri','Sat','Sun']},yAxis:{type:'value'},color:"#04a9f5",series:[{data:[1,5,3,6,4,8,10],type:'line',}]};myChart.setOption(option,true);var dom=document.getElementById("chart-echart-line-area");var myChart=echarts.init(dom);var app={};var option=null;option={tooltip:{trigger:'axis'},legend:{data:['abc','def','pqr']},toolbox:{show:false,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['line','bar','stack','tiled']},restore:{show:true},saveAsImage:{show:true}}},calculable:true,xAxis:[{type:'category',splitLine:{show:false},boundaryGap:false,data:['Monday','Tuesday','Wednesday','Thrusday','Friday','Saturday','Sunday']}],color:["rgba(163, 137, 212, 0.5)","rgba(4, 169, 246, 0.5)","rgba(28, 233, 181, 0.5)"],yAxis:[{type:'value',splitLine:{show:false}}],series:[{name:'abc',type:'line',smooth:true,itemStyle:{normal:{areaStyle:{type:'macarons'}}},data:[10,12,21,54,260,830,710]},{name:'def',type:'line',smooth:true,itemStyle:{normal:{areaStyle:{type:'macarons'}}},data:[30,182,434,791,390,30,10]},{name:'pqr',type:'line',smooth:true,itemStyle:{normal:{areaStyle:{type:'macarons'}}},data:[1320,1132,601,234,120,90,20]}]};myChart.setOption(option,true);var dom=document.getElementById("chart-Bar-besic-column");var myChart=echarts.init(dom);var app={};option=null;option={title:{text:'Bar Chart',subtext:'Basic Column Chart'},tooltip:{trigger:'axis'},legend:{data:['Data1','Data2']},toolbox:{show:true,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['line','bar']},restore:{show:true},saveAsImage:{show:true}}},calculable:true,color:["rgba(163, 137, 212, 1)","rgba(28, 233, 181, 1)"],xAxis:[{type:'category',boundaryGap:false,data:['Monday','Tuesday','Wednesday','Thrusday','Friday','Saturday','Sunday']}],yAxis:[{type:'value',axisLabel:{formatter:'{value} °C'}}],series:[{name:'Data1',type:'line',smooth:true,data:[11,11,15,13,12,13,10],markPoint:{data:[{type:'max',name:'Maximum'},{type:'min',name:'Minimum'}]},markLine:{data:[{type:'average',name:'100'}]}},{name:'Data2',type:'line',smooth:true,data:[1,-2,2,5,3,2,0],markPoint:{data:[{name:'Week Minimum',value:-2,xAxis:1,yAxis:-1.5}]},markLine:{data:[{type:'average',name:'100'}]}}]};myChart.setOption(option,true);var dom=document.getElementById("chart-Bar-besic-bar");var myChart=echarts.init(dom);var app={};option=null;option={title:{text:'Bar',subtext:'Besic Bar Chart'},tooltip:{trigger:'axis'},legend:{data:['2017','2018']},color:["rgba(163, 137, 212, 1)","rgba(28, 233, 181, 1)"],toolbox:{show:true,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['line','bar']},restore:{show:true},saveAsImage:{show:true}}},calculable:true,xAxis:[{type:'value',boundaryGap:[0,0.01]}],yAxis:[{type:'category',data:['Mon','Tue','Wed','Thu','Fri','Sat','Sun']}],series:[{name:'2017',type:'bar',data:[18203,23489,29034,104970,131744,130230,29034]},{name:'2018',type:'bar',data:[19325,23438,31000,121594,134141,181807,31000]}]};myChart.setOption(option,true);$(function(){var dom=document.getElementById("chart-Bar-timeline");var myChart=echarts.init(dom);option={title:{text:'dynamic data'},tooltip:{trigger:'axis',axisPointer:{type:'cross',label:{backgroundColor:'#04a9f5'}}},legend:{data:['Latest transaction price','Pre-order queue']},color:['#A389D4','#1de9b6'],toolbox:{show:true,feature:{dataView:{readOnly:false},restore:{},saveAsImage:{}}},dataZoom:{show:false,start:0,end:100},xAxis:[{type:'category',boundaryGap:true,data:(function(){var now=new Date();var res=[];var len=10;while(len--){res.unshift(now.toLocaleTimeString().replace(/^\D*/,''));now=new Date(now-2000);}
return res;})()},{type:'category',boundaryGap:true,data:(function(){var res=[];var len=10;while(len--){res.push(10-len-1);}
return res;})()}],yAxis:[{type:'value',scale:true,name:'price',max:30,min:0,boundaryGap:[0.2,0.2]},{type:'value',scale:true,name:'Pre-order',max:1200,min:0,boundaryGap:[0.2,0.2]}],series:[{name:'Pre-order queue',type:'bar',xAxisIndex:1,yAxisIndex:1,data:(function(){var res=[];var len=10;while(len--){res.push(Math.round(Math.random()*1000));}
return res;})()},{name:'Latest transaction price',type:'line',data:(function(){var res=[];var len=0;while(len<10){res.push((Math.random()*10+5).toFixed(1)-0);len++;}
return res;})()}]};app.count=11;setInterval(function(){var axisData=(new Date()).toLocaleTimeString().replace(/^\D*/,'');var data0=option.series[0].data;var data1=option.series[1].data;data0.shift();data0.push(Math.round(Math.random()*1000));data1.shift();data1.push((Math.random()*10+5).toFixed(1)-0);option.xAxis[0].data.shift();option.xAxis[0].data.push(axisData);option.xAxis[1].data.shift();option.xAxis[1].data.push(app.count++);myChart.setOption(option);},2100);});var dom=document.getElementById("chart-pie-basic");var myChart=echarts.init(dom);var app={};option=null;option={title:{text:'Pie',subtext:'Basic Pie Chart',x:'center'},tooltip:{trigger:'item',formatter:"{a} <br/>{b} : {c} ({d}%)"},legend:{orient:'vertical',x:'left',data:['HTML','SCSS','JS','Images','Icons']},color:['#f4c22b','#A389D4','#3ebfea','#04a9f5','#1de9b6'],toolbox:{show:true,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['pie','funnel'],option:{funnel:{x:'25%',width:'50%',funnelAlign:'left',max:1548}}},restore:{show:true},saveAsImage:{show:true}}},calculable:true,series:[{name:'Webpage',type:'pie',radius:'55%',center:['50%','60%'],data:[{value:335,name:'HTML'},{value:310,name:'SCSS'},{value:234,name:'JS'},{value:135,name:'Images'},{value:1548,name:'Icons'}]}]};myChart.setOption(option,true);var dom=document.getElementById("chart-pie-doughnut");var myChart=echarts.init(dom);var app={};option=null;option={title:{text:'Pie',subtext:'Doughnut Chart',x:'center'},tooltip:{trigger:'item',formatter:"{a} <br/>{b} : {c} ({d}%)"},legend:{orient:'vertical',x:'left',data:['HTML','SCSS','JS','Images','Icons']},color:['#f4c22b','#A389D4','#3ebfea','#04a9f5','#1de9b6'],toolbox:{show:true,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['pie','funnel'],option:{funnel:{x:'25%',width:'50%',funnelAlign:'left',max:1548}}},restore:{show:true},saveAsImage:{show:true}}},calculable:true,series:[{name:'Webpage',type:'pie',radius:['50%','70%'],itemStyle:{normal:{label:{show:false},labelLine:{show:false}},emphasis:{label:{show:true,position:'center',textStyle:{fontSize:'15',fontWeight:'bold'}}}},data:[{value:335,name:'HTML'},{value:310,name:'SCSS'},{value:234,name:'JS'},{value:135,name:'Images'},{value:1548,name:'Icons'}]}]};myChart.setOption(option,true);var dom=document.getElementById("chart-pie-timeline");var myChart=echarts.init(dom);var app={};option=null;var idx=1;option={timeline:{data:['2013-01-01','2013-02-01','2013-03-01','2013-04-01','2013-05-01',{name:'2013-06-01',symbol:'emptyStar6',symbolSize:8},'2013-07-01','2013-08-01','2013-09-01','2013-10-01','2013-11-01',{name:'2013-12-01',symbol:'star6',symbolSize:8}],},options:[{title:{text:'PieChart',subtext:'Pie Timeline Chart'},tooltip:{trigger:'item',formatter:"{a} <br/>{b} : {c} ({d}%)"},legend:{data:['Chrome','Firefox','Safari','IE9+','IE8-']},color:['#f4c22b','#A389D4','#3ebfea','#04a9f5','#1de9b6'],toolbox:{show:true,feature:{mark:{show:true},dataView:{show:true,readOnly:false},magicType:{show:true,type:['pie','funnel'],option:{funnel:{x:'25%',width:'50%',funnelAlign:'left',max:1700}}},restore:{show:true},saveAsImage:{show:true}}},series:[{name:'Browser (data is purely fictitious)',type:'pie',center:['50%','45%'],radius:'50%',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]},{series:[{name:'Browser (data is purely fictitious)',type:'pie',data:[{value:idx*128+80,name:'Chrome'},{value:idx*64+160,name:'Firefox'},{value:idx*32+320,name:'Safari'},{value:idx*16+640,name:'IE9+'},{value:idx++*8+1280,name:'IE8-'}]}]}]};myChart.setOption(option,true);var dom=document.getElementById("chart-Gauge");var myChart=echarts.init(dom);var app={};option=null;option={tooltip:{formatter:"{a} <br/>{b} : {c}%"},toolbox:{feature:{restore:{},saveAsImage:{}}},series:[{name:'gauge Chart',type:'gauge',axisLine:{show:true,lineStyle:{color:[[0.2,'#1de9b6'],[0.8,'#04a9f5'],[1,'#A389D4']],width:10}},detail:{formatter:'{value}%'},data:[{value:50,name:''}]}]};if(option&&typeof option==="object"){myChart.setOption(option,true);}
var domdynamic=document.getElementById("chart-DynamicLineBar");var myChartdynamic=echarts.init(domdynamic);var app={};var optiondyn=null;optiondyn={title:{text:'Dynamic',subtext:'Bar & Line'},tooltip:{trigger:'axis',axisPointer:{type:'cross',label:{backgroundColor:'#283b56'}}},legend:{data:['Data 1','Data 2']},color:['#A389D4','#1de9b6'],toolbox:{show:true,feature:{dataView:{readOnly:false},restore:{},saveAsImage:{}}},dataZoom:{show:false,start:0,end:100},xAxis:[{type:'category',boundaryGap:true,data:(function(){var now=new Date();var res=[];var len=10;while(len--){res.unshift(now.toLocaleTimeString().replace(/^\D*/,''));now=new Date(now-2000);}
return res;})()},{type:'category',boundaryGap:true,data:(function(){var res=[];var len=10;while(len--){res.push(10-len-1);}
return res;})()}],yAxis:[{type:'value',scale:true,name:'Start',max:30,min:0,boundaryGap:[0.2,0.2]},{type:'value',scale:true,name:'End',max:1200,min:0,boundaryGap:[0.2,0.2]}],series:[{name:'Data 2',type:'bar',itemStyle:{barBorderRadius:[15,15,0,0],},xAxisIndex:1,yAxisIndex:1,data:(function(){var res=[];var len=10;while(len--){res.push(Math.round(Math.random()*1000));}
return res;})()},{name:'Data 1',type:'line',smooth:true,data:(function(){var res=[];var len=0;while(len<10){res.push((Math.random()*10+5).toFixed(1)-0);len++;}
return res;})()}]};app.count=11;if(optiondyn&&typeof optiondyn==="object"){myChartdynamic.setOption(optiondyn,true);}},700);});