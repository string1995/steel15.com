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

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Games</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<script src="js/jquery.min.js"></script>
<style type="text/css"> 
a:link,a:visited{
 text-decoration:none;  /*超链接无下划线*/
}
a:hover{
 text-decoration:underline;  /*鼠标放上去有下划线*/
}
</style>

<style>
html,
body {
  height: 100%;
}

* {
  box-sizing: border-box;
}

body {
  font-family: Raleway, sans-serif;
  line-height: 1.7;
  -webkit-perspective-origin: 0% 50%;
          perspective-origin: 0% 50%;
  -webkit-perspective: 800px;
          perspective: 800px;
  background: #21212D;
}

nav,
main {
  transition: -webkit-transform 150ms ease-out;
  transition: transform 150ms ease-out;
  transition: transform 150ms ease-out, -webkit-transform 150ms ease-out;
}

nav {
  z-index: 100;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 16em;
  background-color: #353441;
  -webkit-transform: translateX(-16em);
          transform: translateX(-16em);
}
nav.menu-active {
  -webkit-transform: translateX(0);
          transform: translateX(0);
}
nav.menu-hover {
  -webkit-transform: translateX(-15em);
          transform: translateX(-15em);
}
nav h1 {
  z-index: 100;
  display: block;
  position: absolute;
  top: 0;
  right: -60px;
  height: 60px;
  width: 60px;
  line-height: 60px;
  font-size: .8em;
  font-weight: 800;
  letter-spacing: 1px;
  color: #9DC6D1;
  text-transform: uppercase;
  text-align: center;
  background-color: #353441;
  cursor: pointer;
}
nav h1:hover {
  color: #353441;
  background: #fff;
}
nav ul {
  margin: 0;
  padding: 0;
}
nav li {
  display: inline-block;
  padding: 0 1em;
  width: 100%;
  height: 60px;
  color: #9DC6D1;
  line-height: 60px;
  background-color: #353441;
}
nav li:nth-of-type(2n) {
  background-color: #3a3947;
}
nav li:hover {
  background: #fff;
}

main {
  z-index: 0;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: flex;
  align-items: center;
  overflow: hidden;
  background-color: #9DC6D1;
  -webkit-transform-origin: 0% 50%;
          transform-origin: 0% 50%;
}
main:after {
  content: '';
  display: block;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(33, 33, 45, 0.5));
  visibility: hidden;
  opacity: 0;
  transition: opacity 150ms ease-out, visibility 0s 150ms;
}
main.menu-active {
  border-radius: .001px;
  -webkit-transform: translateX(16em) rotateY(15deg);
          transform: translateX(16em) rotateY(15deg);
}
main.menu-active:after {
  visibility: visible;
  opacity: 1;
  transition: opacity 150ms ease-out, visibility 0s;
}
main.menu-hover {
  border-radius: .001px;
  -webkit-transform: translateX(1em) rotateY(1deg);
          transform: translateX(1em) rotateY(1deg);
}
main section {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  margin: auto;
  padding: 1em 4em;
  max-width: 680px;
  overflow: auto;
  background-color: rgba(255, 255, 255, 0.5);
}

section h1 {
  font-weight: 800;
  text-transform: uppercase;
  font-size: 2em;
}
section p {
  display: inline-block;
  margin: 16px 0;
}
</style>
</head>
<body>

<nav class="menu-activea">
  <h1>Menu</h1>
  <ul>
    <li><a href="game.php" ><font color="#81C0C0">导航页</font></a></li>
	  <li><a href="game_dajimu.php"><font color="#A3D1D1">搭积木</font></a></li>
    <li><a href="game_jianfengchazhen.php"><font color="#A3D1D1">见缝插针</font></a></li>
    <li><a href="game_saolei.php"><font color="#A3D1D1">扫雷</font></a></li>
    <li><a href="game_tanchishe.php"><font color="#A3D1D1">贪吃蛇</font></a></li>
    <li><a href="game_tiaoyitiao.php"><font color="#A3D1D1">跳一跳</font></a></li>
    <li><a href="game_busi.php"><font color="#A3D1D1">一个不能死</font></a></li>
	<li><a href="../index/index.php" ><font color="#81C0C0">返回主页</font></a></li>
  </ul>
</nav>
<main>
  <section>
    <h1>← 点击左侧Menu查看效果</h1>
    <p>Lorn n culpa qui officia deserunt mollit anim id est laborum.</p>
    <p>Quis um.</p>
    <p>Exce et dolore magna aliqua. Ut enim ad minim veniam</p>
    <p>Quis nostrud exercitation ullamco </p>
    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id d minim veniam</p>
  </section>
</main>

<script>
(function() {

  var nav = $('nav'),
    menu = $('nav h1'),
    main = $('main'),
  	open = false,
  	hover = false;

  menu.on('click', function() {
		open = !open ? true : false;
    nav.toggleClass('menu-active');
    main.toggleClass('menu-active');
    nav.removeClass('menu-hover');
    main.removeClass('menu-hover');
    console.log(open);
  });
  menu.hover( 
    function() {
      if (!open) {
      	nav.addClass('menu-hover');
      	main.addClass('menu-hover');
      }
    }, function() {
      nav.removeClass('menu-hover');
      main.removeClass('menu-hover');
    }
  );

})();</script>

</body>
</html>
