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

<?php //events信息获取

///从数据库调取最近一次的event信息
$sql = "SELECT * FROM events where id=(SELECT max(id) FROM events)";

$result = $conn->query($sql);

$row = $result->fetch_assoc();
//将信息调入php变量，以便引用
 $name1= $row['name'];
 $date=$row['date'];
 $img1=$row['content'];
 $abstract=$row['abstract'];
 $location= $row['location'];
 $text1= $row['text1'];
 $text2= $row['text2'];
 ?>

<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Home</title>
	
<link rel="shortcut icon"type="image/x-icon" href="img/favicon.ico"media="screen" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" >
<!-- Icon -->
<link rel="stylesheet" type="text/css" href="assets/fonts/line-icons.css">
<!-- Slicknav -->
<link rel="stylesheet" type="text/css" href="assets/css/slicknav.css">
<!-- Nivo Lightbox -->
<link rel="stylesheet" type="text/css" href="assets/css/nivo-lightbox.css" >
<!-- Animate -->
<link rel="stylesheet" type="text/css" href="assets/css/animate.css">
<!-- Main Style -->
<link rel="stylesheet" type="text/css" href="assets/css/main.css">
<!-- Responsive Style -->
<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
	
<?php ////获取ip
$ip=getip();
//以下调用ip定位数据库提供商提供的函数
function getip() 
{
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
	{
		$ip = getenv("HTTP_CLIENT_IP");
	} 
	else
		if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		else
			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
			{
				$ip = getenv("REMOTE_ADDR");
			} 
			else
				if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
				{
					$ip = $_SERVER['REMOTE_ADDR'];
				} 
				else 
				{
					$ip = "unknown";
				}
return ($ip);
}
?>
	
<?php ///查询对应地址
///从数据库获取本段ip对应信息
$sql = "SELECT * FROM `ip` WHERE minip <= INET_ATON('$ip') ORDER BY minip DESC LIMIT 1;";
	
//$result = $conn->query($sql);
//$row = $result->fetch_assoc();
//定义位置变量
$city= '3';//$row['city'];
$province= '3';//$row['province'];
$country= '3';//$row['country'];
//生成位置信息
$position="$city,$province,$country";
?>
	
<?php //更新数据库ip，访问次数，时间，ip位置，，插入新的日志
$time=date('Y-m-d H:i:s',time());///获取时间
//访问次数+1
$count=$count+1;
//定义数据库指令信息
$sql="UPDATE login set ip='$ip',count='$count',position='$position',time='$time' where code='$code' ";
//实施数据库更新指令，失败报错
if ($conn->query($sql) === TRUE) {} else {echo "Error: " . $sql . "<br>" . $conn->error;}
///生产数据库日志表格信标
$sign="$name$count";
///插入新的日志
$sql="INSERT INTO log VALUES ('$sign','$name','$ip','$position','$time','') ";
	
if ($conn->query($sql) === TRUE) {} else { echo "Error: " . $sql . "<br>" . $conn->error;}
	
mysqli_close($conn); ///关闭数据库 
?>  

<script src="js/simpleCanvas.js"></script>
	
<script>//判断用户端特征信息是否一致
if(simpleCanvas=='<?php echo $key?>'){}
else{alert('您的身份信息有误！');setTimeout(function(){top.location='../index/logout.php';},0)}
</script>

<script>//定位gps

  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition);
    }
 
 ///调用h5函数
function showPosition(position)
  {
	  //定义经纬度变量并设置偏移纠正
	  var longitude1=position.coords.longitude+0.0043;
	  var latitude1=position.coords.latitude-0.0022;
	  //ajax向后端传递定位信息，不返回状态信息
	 $.ajax({
		 url:'post1.php?sign=<?php echo "$sign"?>&gps='+longitude1+','+latitude1,
		 type:'get',
		 data:'',
		 async: false,
		 success:''
	 		})
  }
</script>
	
</head>
<body>

<!-- Header Area wrapper Starts -->
<header id="header-wrap">
  <!-- Navbar Start -->
  <nav class="navbar navbar-expand-lg fixed-top scrolling-navbar">
	<div class="container">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
		  <span class="navbar-toggler-icon"></span>
		  <span class="icon-menu"></span>
		  <span class="icon-menu"></span>
		  <span class="icon-menu"></span>
		</button>
		<a href="index.php" class="navbar-brand"><img src="assets/img/logo.png" alt="Steel15"></a>
	  </div>
	  <div class="collapse navbar-collapse" id="main-navbar">
		<ul class="navbar-nav mr-auto w-100 justify-content-end">
		  <li class="nav-item active">
			<a class="nav-link" href="index.php#header-wrap">
			  Home
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="../page/">
			  我的主页
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="pan.php">
			  云盘
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="../game/game.php">
			  小游戏
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="news.php">
			  事件
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="../class/">
			  同学信息
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="../fee/">
			  财务公开
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#contact-map">
			  联系管理员
			</a>
		  </li>
<?php if($dev=='1'){echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"../index/dev.php\">开发者</a></li>";} ?>
		  <li class="nav-item">
			<a class="nav-link" href="logout.php">
			  退出登录
			</a>
		  </li>
		</ul>
	  </div>
	</div>

	<!-- Mobile Menu Start -->
	<ul class="mobile-menu">
	  <li>
		<a class="page-scrool" href="index.php#header-wrap">Home</a>
	  </li>
	  <li>
		 <a class="page-scroll" href="../page/">我的主页</a>
	  </li>
	  <li>
		<a class="page-scroll" href="pan.php">云盘</a>
	  </li>
	  <li>
		<a class="page-scroll" href="../game/game.php">小游戏</a>
	  </li>
	  <li>
		<a class="page-scroll" href="news.php">事件</a>
	  </li>
	  <li>
		<a class="page-scroll" href="../class/">同学信息</a>
	  </li>
	  <li>
		<a class="page-scroll" href="../fee/">财务公开</a>
	  </li>
	  <li>
		<a class="page-scroll" href="index.php#contact-map">联系管理员</a>
	  </li>
		<?php if($dev=='1'){echo "<li class=\"page-scroll\"><a class=\"nav-link\" href=\"../index/dev.php\">开发者</a></li>";} ?>
	  <li class="nav-item">
		<a class="nav-link" href="logout.php">退出登录</a>
	  </li>
	</ul>
	<!-- Mobile Menu End -->

  </nav>
  <!-- Navbar End -->

  <!-- Main Carousel Section Start -->
  <div id="main-slide" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
	  <li data-target="#main-slide" data-slide-to="0" class="active"></li>
	  <li data-target="#main-slide" data-slide-to="1"></li>
	  <li data-target="#main-slide" data-slide-to="2"></li>
	</ol>
	<div class="carousel-inner">
	  <div class="carousel-item active">
		<img class="d-block w-100" src="assets/img/slider/slide1.jpg" alt="First slide">
		<div class="carousel-caption d-md-block">
		  <p class="fadeInUp wow" data-wow-delay=".6s">新推出网盘功能</p>
		  <h1 class="wow fadeInDown heading" data-wow-delay=".4s"> 快来尝试吧！</h1>
		  <a href="pan.php" class="fadeInRight wow btn btn-border btn-lg" data-wow-delay=".6s">了解更多</a>
		</div>
	  </div>
	  <div class="carousel-item">
		<img class="d-block w-100" src="assets/img/slider/slide2.jpg" alt="Second slide">
		<div class="carousel-caption d-md-block">
		  <p class="fadeInUp wow" data-wow-delay=".6s">Global Grand Event on Digital Design</p>
		  <h1 class="wow bounceIn heading" data-wow-delay=".7s">22 Amazing Speakers</h1>
		  <a href="#" class="fadeInUp wow btn btn-border btn-lg" data-wow-delay=".8s">Learn More</a>
		</div>
	  </div>
	  <div class="carousel-item">
		<img class="d-block w-100" src="assets/img/slider/slide3.jpg" alt="Third slide">
		<div class="carousel-caption d-md-block">
		  <p class="fadeInUp wow" data-wow-delay=".6s">Global Grand Event on Digital Design</p>
		  <p>&nbsp;</p>
		  <h1 class="wow fadeInUp heading" data-wow-delay=".6s">Book Your Seat Now!</h1>
		  <a href="#" class="fadeInUp wow btn btn-common btn-lg" data-wow-delay=".8s">Explore</a>
		</div>
	  </div>
	</div>
	<a class="carousel-control-prev" href="#main-slide" role="button" data-slide="prev">
	  <span class="carousel-control" aria-hidden="true"><i class="lni-chevron-left"></i></span>
	  <span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#main-slide" role="button" data-slide="next">
	  <span class="carousel-control" aria-hidden="true"><i class="lni-chevron-right"></i></span>
	  <span class="sr-only">Next</span>
	</a>
  </div>
  <!-- Main Carousel Section End -->

</header>
<!-- Header Area wrapper End -->

<!-- Coundown Section Start -->
<section class="countdown-timer section-padding">
  <div class="container">
	<div class="row text-center">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="heading-count">
		  <h2 class="wow fadeInDown" data-wow-delay="0.2s">距离<?php echo "$name1";?>还有</h2>
		</div>
	  </div>
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row time-countdown justify-content-center wow fadeInUp" data-wow-delay="0.2s">
		  <div id="clock" class="time-count"></div>
		</div>
		<a href="download-ics.php" class="btn btn-common wow fadeInUp" data-wow-delay="0.3s">插入到日历</a>
	  </div>
	</div>
  </div>
</section>
<!-- Coundown Section End -->

 <!-- Services Section Start -->
<section id="services" class="services section-padding">
  <div class="container">
	<div class="row">
	  <div class="col-12">
		<div class="section-title-header text-center">
		  <h1 class="section-title wow fadeInUp" data-wow-delay="0.2s">访问功能</h1>
		  <p class="wow fadeInDown" data-wow-delay="0.2s">Find the function you like.</p>
		</div>
	  </div>
	</div>
	<div class="row services-wrapper">
	  <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="0.2s">
		  <div class="icon">
			<i class="lni-heart"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="../page/">我的主页</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	  <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="0.4s">
		  <div class="icon">
			<i class="lni-gallery"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="pan.php">我的网盘</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	  <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="0.6s">
		  <div class="icon">
			<i class="lni-envelope"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="mail.php">我的邮箱</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	  <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="0.8s">
		  <div class="icon">
			<i class="lni-cup"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="../game/game.php">小游戏</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	   <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="1s">
		  <div class="icon">
			<i class="lni-user"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="../class/">同学信息</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	   <!-- Services item -->
	  <div class="col-md-6 col-lg-4 col-xs-12 padding-none">
		<div class="services-item wow fadeInDown" data-wow-delay="1.2s">
		  <div class="icon">
			<i class="lni-bubble"></i>
		  </div>
		  <div class="services-content">
			<h3><a href="index.php#contact-map">出谋划策</a></h3>
			<p>Lorem ipsum dolor sit amet, consectetuer commodo ligula eget dolor.</p>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</section>
<!-- Services Section End -->

<!-- Event Slides Section Start -->
<section id="event-slides" class="section-padding">
  <div class="container">
	<div class="row">
	  <div class="col-12">
		<div class="section-title-header text-center">
		  <h1 class="section-title wow fadeInUp" data-wow-delay="0.2s">事件预告</h1>
		  <p class="wow fadeInDown" data-wow-delay="0.2s">Events Notice</p>
		</div>
	  </div>
	  <div class="col-md-6 col-lg-6 col-xs-12 wow fadeInRight" data-wow-delay="0.3s">
		<div class="video">
		  <img class="img-fluid" src="assets/img/about/events/<?php echo "$img1"?>" alt="" height="360" width="540">
		</div>
	  </div>
	  <div class="col-md-6 col-lg-6 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
		<p class="intro-desc"><?php echo "$abstract";?>
		</p>
		<h2 class="intro-title">Relevant Info</h2>
		<ul class="list-specification">
		  <li><i class="lni-check-mark-circle"></i>时间： <?php echo "$date";?></li>
		  <li><i class="lni-check-mark-circle"></i>地址： <?php echo "$location";?></li>
		  <li><i class="lni-check-mark-circle"></i>负责人： <?php echo "$text1";?></li>
		  <li><i class="lni-check-mark-circle"></i> <?php echo "$text2";?></li> 
		</ul>
	  </div>
	</div>
  </div>
</section>
<!-- Event Slides Section End -->

<!-- Blog Section Start -->
<section id="blog" class="section-padding">
  <div class="container">
	<div class="row">
	  <div class="col-12">
		<div class="section-title-header text-center">
		  <h1 class="section-title wow fadeInUp" data-wow-delay="0.2s">近期消息</h1>
		  <p class="wow fadeInDown" data-wow-delay="0.2s">Latest News & Articles</p>
		</div>
	  </div>
	  <div class="col-lg-4 col-md-6 col-xs-12">
		<div class="blog-item">
		  <div class="blog-image">
			<a href="#">
			  <img class="img-fluid" src="assets/img/blog/img-1.jpg" alt="">
			</a>
		  </div>
		  <div class="descr">
			<div class="tag">Design</div>
			<h3 class="title">
			  <a href="single-blog.html">
				The 9 Design Trends You Need to Know
			  </a>
			</h3>
			<div class="meta-tags">
			  <span class="date">Jan 20, 2018</span>
			  <span class="comments">| <a href="#"> by Cindy Jefferson</a></span>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="col-lg-4 col-md-6 col-xs-12">
		<div class="blog-item">
		  <div class="blog-image">
			<a href="#">
			  <img class="img-fluid" src="assets/img/blog/img-2.jpg" alt="">
			</a>
		  </div>
		  <div class="descr">
			<div class="tag">Design</div>
			<h3 class="title">
			  <a href="single-blog.html">
				The 9 Design Trends You Need to Know
			  </a>
			</h3>
			<div class="meta-tags">
			  <span class="date">Jan 20, 2018 </span>
			  <span class="comments">| <a href="#"> by Cindy Jefferson</a></span>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="col-lg-4 col-md-6 col-xs-12">
		<div class="blog-item">
		  <div class="blog-image">
			<a href="#">
			  <img class="img-fluid" src="assets/img/blog/img-3.jpg" alt="">
			</a>
		  </div>
		  <div class="descr">
			<div class="tag">Design</div>
			<h3 class="title">
			  <a href="single-blog.html">
				The 9 Design Trends You Need to Know
			  </a>
			</h3>
			<div class="meta-tags">
			  <span class="date">Jan 20, 2018</span> 
			  <span class="comments">| <a href="#"> by Cindy Jefferson</a></span>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="col-12 text-center">
		<a href="#" class="btn btn-common">Read More News</a>
	  </div>
	</div>
  </div>
</section>
<!-- Blog Section End -->

<!-- Contact Us Section -->
<section id="contact-map" class="section-padding">
  <div class="container">
	<div class="row justify-content-center">
	  <div class="col-12">
		<div class="section-title-header text-center">
		  <h1 class="section-title wow fadeInUp" data-wow-delay="0.2s">给管理员留言</h1>
		  <p class="wow fadeInDown" data-wow-delay="0.2s">Drop a Message</p>
		</div>
	  </div>
	  <div class="col-lg-7 col-md-12 col-xs-12">
		<div class="container-form wow fadeInLeft" data-wow-delay="0.2s">
		  <div class="form-wrapper">
			<form role="form" method="post" id="contactForm" name="contact-form" data-toggle="validator">
			  <div class="row">
				<div class="col-md-6 form-line">
				  <div class="form-group">
					<input type="text" class="form-control" id="name" name="email" value="<?php echo "$name"?>" placeholder="Name" required data-error="请输入姓名">
					<div class="help-block with-errors"></div>
				  </div>
				</div>
				<div class="col-md-6 form-line">
				  <div class="form-group">
					<input type="email" class="form-control" id="email" name="email" value="<?php echo "$user"?>@steel15.com" placeholder="Email" required data-error="请输入正确的电子邮件地址">
					<div class="help-block with-errors"></div>
				  </div> 
				</div>
				<div class="col-md-12 form-line">
				  <div class="form-group">
					<input type="tel" class="form-control" id="msg_subject" name="subject" placeholder="Subject" required data-error="请输入主题">
					<div class="help-block with-errors"></div>
				  </div>
				</div>
				<div class="col-md-12">
				  <div class="form-group">
					<textarea class="form-control" rows="4" id="message" name="message" required data-error="请输入内容"></textarea>
				  </div>
				  <div class="form-submit">
					<button type="submit" class="btn btn-common" id="form-submit"><i class="fa fa-paper-plane" aria-hidden="true"></i>  Send Us Now</button>
					<div id="msgSubmit" class="h3 text-center hidden"></div>
				  </div>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</section>
<!-- Contact Us Section End -->

<!-- Footer Section Start -->
<footer class="footer-area section-padding">
  <div class="container">
	<div class="row">
	  <div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="0.2s">
		<h3><img src="assets/img/logo.png" alt=""></h3>
		<p>
		  Aorem ipsum dolor sit amet elit sed lum tempor incididunt ut labore el dolore alg minim veniam quis nostrud ncididunt.
		</p>
	  </div>
	  <div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="0.4s">
		<h3>QUICK LINKS</h3>
		<ul>
		  <li><a href="../page/">我的主页</a></li>
		  <li><a href="pan.php">云盘</a></li>
		  <li><a href="../game/game.php">小游戏</a></li>
		  <li><a href="../class/">同学信息</a></li>
		  <li><a href="../fee/">财务公开</a></li>
		</ul>
	  </div>
	  <div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="0.6s">
		<h3>事件预告</h3>
		<ul class="image-list">
		  <li>
			<figure class="overlay">
			  <img class="img-fluid" src="assets/img/about/events/<?php echo "$img1"?>" alt="">
			</figure>
			<div class="post-content">
			  <h6 class="post-title"> <a href="index.php#event-slides"><?php echo "$name1"?></a> </h6>
			  <div class="meta"><span class="date"><?php echo "$date"?></span></div>
			</div>
		  </li>
		</ul>
	  </div>
	  <div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="0.8s">
		<h3>申请成为网站管理员</h3>
		<div class="widget">
		  <div class="newsletter-wrapper">
			<form method="post" id="subscribe-form" name="subscribe-form" class="validate">
			  <div class="form-group is-empty">
				<input type="text" value="<?php echo "$name"?>" name="apply" class="form-control" id="apply" placeholder="Your name" required="">
				<button type="submit" name="subscribe" id="subscribes" class="btn btn-common sub-btn"><i class="lni-pointer"></i></button>
				<div class="clearfix"></div>
			  </div>
			</form>
		  </div>
		</div>
		<!-- /.widget -->
		<div class="widget">
		  <h5 class="widget-title">FOLLOW US ON</h5>
		  <ul class="footer-social">
			<li><a class="facebook" href="#"><i class="lni-facebook-filled"></i></a></li>
			<li><a class="twitter" href="#"><i class="lni-twitter-filled"></i></a></li>
			<li><a class="linkedin" href="#"><i class="lni-linkedin-filled"></i></a></li>
			<li><a class="google-plus" href="#"><i class="lni-google-plus"></i></a></li>
		  </ul>
		</div>
	  </div>
	</div>
  </div>
</footer>
<!-- Footer Section End -->

<div id="copyright">
  <div class="container">
	<div class="row">
	  <div class="col-md-12">
		<div class="site-info">
		  <p>Copyright &copy; 2018.Steel15 All rights reserved.</p>
		</div>      
	  </div>
	</div>
  </div>
</div>

<!-- Go to Top Link -->
<a href="#" class="back-to-top">
	<i class="lni-chevron-up"></i>
</a>

<div id="preloader">
  <div class="sk-circle">
	<div class="sk-circle1 sk-child"></div>
	<div class="sk-circle2 sk-child"></div>
	<div class="sk-circle3 sk-child"></div>
	<div class="sk-circle4 sk-child"></div>
	<div class="sk-circle5 sk-child"></div>
	<div class="sk-circle6 sk-child"></div>
	<div class="sk-circle7 sk-child"></div>
	<div class="sk-circle8 sk-child"></div>
	<div class="sk-circle9 sk-child"></div>
	<div class="sk-circle10 sk-child"></div>
	<div class="sk-circle11 sk-child"></div>
	<div class="sk-circle12 sk-child"></div>
  </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="assets/js/jquery-min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.countdown.min.js"></script>
<script src="assets/js/jquery.nav.js"></script>
<script src="assets/js/jquery.easing.min.js"></script>
<script src="assets/js/wow.js"></script>
<script src="assets/js/jquery.slicknav.js"></script>
<script src="assets/js/nivo-lightbox.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-validator.min.js"></script>
<script src="assets/js/contact-form-script.min.js"></script>
<div id='date' ids='<?php echo "$date"?>'></div>
<script>var date=$("#date").attr("ids");
	jQuery('#clock').countdown(date,function(event)
	{
      var $this=jQuery(this).html(event.strftime(''
      +'<div class="time-entry days"><span>%-D</span> Days</div> '
      +'<div class="time-entry hours"><span>%H</span> Hours</div> '
      +'<div class="time-entry minutes"><span>%M</span> Minutes</div> '
      +'<div class="time-entry seconds"><span>%S</span> Seconds</div> '));
    });</script>

<!---<script src="assets/js/map.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCsa2Mi2HqyEcEnM1urFSIGEpvualYjwwM"></script>--->
  
</body>
</html>
