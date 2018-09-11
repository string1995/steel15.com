<?php //验证是否登录
header("content-type:text/html;charset=utf-8"); ///规定php字符集为utf-8

if(!isset($_COOKIE['login']))///从cookie读取login值，判断是否存在
{
	echo "<script>setTimeout(function(){top.location='../index/logout.php';},0)</script>";
}

if($_COOKIE["login"]==15){}//判断login是否为15，否则服务器中断网页加载并提示500错误
//提取cookie中用户特征信息
$code=$_COOKIE['code'];
//用户特征信息解密，并分解为code（数据库端调用的用户特征）与key（用户设备特征信息，用于判断cookie是否被移植）
$key=base64_decode($code);
$code= base64_decode(substr($key,0,8));
$key= substr($key,8,8);
?>

<?php //用户基础信息获取
$servername = "localhost";
$username = "steel";
$password = "151515";
$dbname = "steel";
 
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) 
{
    die("连接失败: " . $conn->connect_error);
} 
///根据code用户特征调取指定用户信息
$sql = "SELECT * FROM login where code=$code";

$result = $conn->query($sql);
///禁止非法访问
if ($result->num_rows > 0) {}else{echo "<script>alert('非法访问！');setTimeout(function(){top.location='../index/logout.php';},0)</script>";}

$row = $result->fetch_assoc();
///将用户信息导入php变量，以方便下文引用
 $name= $row['name'];
 $user=$row['username'];
 $count=$row['count'];
 $tel=$row['tel'];
 $qq= $row['qq'];
 $ip= $row['ip'];
 $dev=$row['dev'];
 $position= $row['position'];
?>

<?php
$sql2 = "SELECT * FROM datecount order by date desc LIMIT 8";
$result = $conn->query($sql2);
$date=array();
$count=array();
$i=0;
while($row = $result->fetch_assoc()){
 $date[$i]= date("m月d日",strtotime($row['date']));
 $count[$i]= $row['count'];
$i++;}
	$conn->close();
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
	<meta id="viewport" name="viewport" content="width=540,user-scalable=no,target-densitydpi=high-dpi" >
<title>财务公开</title>

<link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
<script src="js/jquery-1.7.1.js" type="text/javascript"></script>
<script src="js/amcharts.js" type="text/javascript"></script>
<script src="js/serial.js" type="text/javascript"></script>
<script src="js/pie.js" type="text/javascript"></script>

<style type="text/css">
.main
{
	width: 700px;
	height: 400px;
	border: 1px solid #ccc;
	margin: 0 auto;
	margin-top: 100px;
	overflow: hidden;
}
#cylindrical
{
	width: 700px;
	height: 400px;
	margin-top: -15px;
}
#line
{
	width: 700px;
	height: 400px;
	margin-top: -15px;
}
#pie
{
	width: 700px;
	height: 400px;
	margin-top: -15px;
}
</style>
</head>

<body>

<div class="charts--container">
  <ul>
    <li class="chart">
      <h3 class="chart--subHeadline">Chart 1</h3>
      <h2 class="chart--headline">网站经营费用来源</h2>
      <div id="pieChart">
        <svg id="pieChartSVG">
          <defs>
            <filter id='pieChartInsetShadow'>
              <feOffset dx='0' dy='0'/>
              <feGaussianBlur stdDeviation='3' result='offset-blur' />
              <feComposite operator='out' in='SourceGraphic' in2='offset-blur' result='inverse' />
              <feFlood flood-color='black' flood-opacity='1' result='color' />
              <feComposite operator='in' in='color' in2='inverse' result='shadow' />
              <feComposite operator='over' in='shadow' in2='SourceGraphic' />
            </filter>
            <filter id="pieChartDropShadow">
              <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur" />
              <feOffset in="blur" dx="0" dy="3" result="offsetBlur" />
              <feMerge>
                <feMergeNode />
                <feMergeNode in="SourceGraphic" />
              </feMerge>
            </filter>
          </defs>
        </svg>
      </div>
    </li>
	
    <li class="chart">
      <h3 class="chart--subHeadline">Chart 2</h3>
      <h2 class="chart--headline">站点经营总成本</h2>
      <div id="lineChart">
        <svg id="lineChartSVG" class="lineChart--svg">
          <defs>
            <linearGradient id="lineChart--gradientBackgroundArea" x1="0" x2="0" y1="0" y2="1">
              <stop class="lineChart--gradientBackgroundArea--top" offset="0%" />
              <stop class="lineChart--gradientBackgroundArea--bottom" offset="100%" />
            </linearGradient>
          </defs>
        </svg>
		  </br></br>
		  	<h3 class="chart--subHeadline">Chart 3</h3>
		    <h2 class="chart--headline">站点访问量统计</h2>
		  <div class="main" zoom:40% >
    <div id="line"  >
    </div>
</div>
<br />
      </div>
    </li>
  </ul>
</div>

  <script src='js/d3.v3.min.js'></script>

  <script src="js/index.js"></script>

	<script type="text/javascript">
    $(document).ready(function (e) {
        GetSerialChart();
        MakeChart(json);
    });
    var json = [
  { "name": "<?php echo $date[7]?>", "value": "<?php echo $count[7]?>" },
  { "name": "<?php echo $date[6]?>", "value": "<?php echo $count[6]?>" },
  { "name": "<?php echo $date[5]?>", "value": "<?php echo $count[5]?>" },
  { "name": "<?php echo $date[4]?>", "value": "<?php echo $count[4]?>" },
  { "name": "<?php echo $date[3]?>", "value": "<?php echo $count[3]?>" },
  { "name": "<?php echo $date[2]?>", "value": "<?php echo $count[2]?>" },
  { "name": "<?php echo $date[1]?>", "value": "<?php echo $count[1]?>" },
  { "name": "<?php echo $date[0]?>", "value": "<?php echo $count[0]?>" }
  ]
    //柱状图  
    function GetSerialChart() {

        chart = new AmCharts.AmSerialChart();
        chart.dataProvider = json;
        //json数据的key  
        chart.categoryField = "name";
        //不选择      
        chart.rotate = false;
        //值越大柱状图面积越大  
        chart.depth3D = 20;
        //柱子旋转角度角度
        chart.angle = 30;
        var mCtCategoryAxis = chart.categoryAxis;
        mCtCategoryAxis.axisColor = "#efefef";
        //背景颜色透明度
        mCtCategoryAxis.fillAlpha = 0.5;
        //背景边框线透明度
        mCtCategoryAxis.gridAlpha = 0;
        mCtCategoryAxis.fillColor = "#efefef";
        var valueAxis = new AmCharts.ValueAxis();
        //左边刻度线颜色  
        valueAxis.axisColor = "#ccc";
        //标题
        valueAxis.title = "3D柱状图Demo";
        //刻度线透明度
        valueAxis.gridAlpha = 0.2;
        chart.addValueAxis(valueAxis);
        var graph = new AmCharts.AmGraph();
        graph.title = "value";
        graph.valueField = "value";
        graph.type = "column";
        //鼠标移入提示信息
        graph.balloonText = "测试数据[[category]] [[value]]";
        //边框透明度
        graph.lineAlpha = 0.3;
        //填充颜色 
        graph.fillColors = "#b9121b";
        graph.fillAlphas = 1;

        chart.addGraph(graph);

        // CURSOR
        var chartCursor = new AmCharts.ChartCursor();
        chartCursor.cursorAlpha = 0;
        chartCursor.zoomable = false;
        chartCursor.categoryBalloonEnabled = false;
        chart.addChartCursor(chartCursor);

        chart.creditsPosition = "top-right";

        //显示在Main div中
        chart.write("cylindrical");
    }
    //折线图
    AmCharts.ready(function () {
        var chart = new AmCharts.AmSerialChart();
        chart.dataProvider = json;
        chart.categoryField = "name";
        chart.angle = 30;
        chart.depth3D = 20;
        //标题
        //chart.addTitle("站点访问量统计", 15);  
        var graph = new AmCharts.AmGraph();
        chart.addGraph(graph);
        graph.valueField = "value";
        //背景颜色透明度
        graph.fillAlphas = 0.3;
        //类型
        graph.type = "line";
        //圆角
        graph.bullet = "round";
        //线颜色
        graph.lineColor = "#8e3e1f";
        //提示信息
        graph.balloonText = "[[name]]: [[value]]";
        var categoryAxis = chart.categoryAxis;
        categoryAxis.autoGridCount = false;
        categoryAxis.gridCount = json.length;
        categoryAxis.gridPosition = "start";
        chart.write("line");
		if(navigator.userAgent.indexOf("MSIE")>0){alert('sdfa')}
    });
    //饼图
    //根据json数据生成饼状图，并将饼状图显示到div中
    function MakeChart(value) {
        chartData = eval(value);
        //饼状图
        chart = new AmCharts.AmPieChart();
        chart.dataProvider = chartData;
        //标题数据
        chart.titleField = "name";
        //值数据
        chart.valueField = "value";
        //边框线颜色
        chart.outlineColor = "#fff";
        //边框线的透明度
        chart.outlineAlpha = .8;
        //边框线的狂宽度
        chart.outlineThickness = 1;
        chart.depth3D = 20;
        chart.angle = 30;
        chart.write("pie");
    }
</script>
</body>

</html>