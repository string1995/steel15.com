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

<?php //获取游戏历史数据
$servername = "localhost";
$username = "steel";
$password = "151515";
$dbname = "steel";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
$sql = "SELECT score FROM dajimu where name='$name'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$score3= $row['score'];

$sql = "SELECT * FROM dajimu order by score desc LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$score4= $row['score'];
$first= $row['name'];
	$conn->close();
		  ?>

<!doctype html>
<html><head>
<meta charset="utf-8">
<title>塔块游戏</title>

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
	
<style>
@import url("https://fonts.googleapis.com/css?family=Comfortaa");
html, body {
  margin: 0;
  overflow: hidden;
  height: 100%;
  width: 100%;
  position: relative;
  font-family: 'Comfortaa', cursive;
}

#container {
  width: 100%;
  height: 100%;
}
#container #score {
  position: absolute;
  top: 20px;
  width: 100%;
  text-align: center;
  font-size: 10vh;
  -webkit-transition: -webkit-transform 0.5s ease;
  transition: -webkit-transform 0.5s ease;
  transition: transform 0.5s ease;
  transition: transform 0.5s ease, -webkit-transform 0.5s ease;
  color: #333344;
  -webkit-transform: translatey(-200px) scale(1);
          transform: translatey(-200px) scale(1);
}
#container #game {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
#container .game-over {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 85%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
}
#container .game-over * {
  -webkit-transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease, -webkit-transform 0.5s ease;
  opacity: 0;
  -webkit-transform: translatey(-50px);
          transform: translatey(-50px);
  color: #333344;
}
#container .game-over h2 {
  margin: 0;
  padding: 0;
  font-size: 40px;
}
#container .game-ready {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -ms-flex-pack: distribute;
      justify-content: space-around;
}
#container .game-ready #start-button {
  -webkit-transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease, -webkit-transform 0.5s ease;
  opacity: 0;
  -webkit-transform: translatey(-50px);
          transform: translatey(-50px);
  border: 3px solid #333344;
  padding: 10px 20px;
  background-color: transparent;
  color: #333344;
  font-size: 30px;
}
#container #instructions {
  position: absolute;
  width: 100%;
  top: 16vh;
  left: 0;
  text-align: center;
  -webkit-transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease;
  transition: opacity 0.5s ease, transform 0.5s ease, -webkit-transform 0.5s ease;
  opacity: 0;
}
#container #instructions.hide {
  opacity: 0 !important;
}
#container.playing #score, #container.resetting #score {
  -webkit-transform: translatey(0px) scale(1);
          transform: translatey(0px) scale(1);
}
#container.playing #instructions {
  opacity: 1;
}
#container.ready .game-ready #start-button {
  opacity: 1;
  -webkit-transform: translatey(0);
          transform: translatey(0);
}
#container.ended #score {
  -webkit-transform: translatey(6vh) scale(1.5);
          transform: translatey(6vh) scale(1.5);
}
#container.ended .game-over * {
  opacity: 1;
  -webkit-transform: translatey(0);
          transform: translatey(0);
}
#container.ended .game-over p {
  -webkit-transition-delay: 0.3s;
          transition-delay: 0.3s;
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
	
  <meta name="viewport" content="width=device-width,user-scalable=no" id="body">

<div id="container">
	<div id="game"></div>
	<div id="score">0</div>
	<div id="instructions">我的最高分<?php echo "$score3"?>分<br/>距离小伙伴<?php echo "$first"?>仅差<?php $i=$score4-$score3; echo "$i"?>分 加油！</div>
	<div class="game-over">
		<h2>Game Over</h2>
		<p>You did great, you're the best.</p>
		<p>Click or spacebar to start again</p>
	</div>
	<div class="game-ready">
		<div id="start-button">Start</div>
		<div></div>
	</div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/three.js/r83/three.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js'></script>

<script>
console.clear();
var i=0;//新建统计变量
var a=0;
var Stage = /** @class */ (function () {
    function Stage() {
        // container
        var _this = this;
        this.render = function () {
            this.renderer.render(this.scene, this.camera);
        };
        this.add = function (elem) {
            this.scene.add(elem);
        };
        this.remove = function (elem) {
            this.scene.remove(elem);
        };
        this.container = document.getElementById('game');
        // renderer
        this.renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: false
        });
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.renderer.setClearColor('#D0CBC7', 1);
        this.container.appendChild(this.renderer.domElement);
        // scene
        this.scene = new THREE.Scene();
        // camera
        var aspect = window.innerWidth / window.innerHeight;
        var d = 20;
        this.camera = new THREE.OrthographicCamera(-d * aspect, d * aspect, d, -d, -100, 1000);
        this.camera.position.x = 2;
        this.camera.position.y = 2;
        this.camera.position.z = 2;
        this.camera.lookAt(new THREE.Vector3(0, 0, 0));
        //light
        this.light = new THREE.DirectionalLight(0xffffff, 0.5);
        this.light.position.set(0, 499, 0);
        this.scene.add(this.light);
        this.softLight = new THREE.AmbientLight(0xffffff, 0.4);
        this.scene.add(this.softLight);
        window.addEventListener('resize', function () { return _this.onResize(); });
        this.onResize();
    }
    Stage.prototype.setCamera = function (y, speed) {
        if (speed === void 0) { speed = 0.3; }
        TweenLite.to(this.camera.position, speed, { y: y + 4, ease: Power1.easeInOut });
        TweenLite.to(this.camera.lookAt, speed, { y: y, ease: Power1.easeInOut });
    };
    Stage.prototype.onResize = function () {
        var viewSize = 30;
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.camera.left = window.innerWidth / -viewSize;
        this.camera.right = window.innerWidth / viewSize;
        this.camera.top = window.innerHeight / viewSize;
        this.camera.bottom = window.innerHeight / -viewSize;
        this.camera.updateProjectionMatrix();
    };
    return Stage;
}());
var Block = /** @class */ (function () {
    function Block(block) {
        // set size and position
        this.STATES = { ACTIVE: 'active', STOPPED: 'stopped', MISSED: 'missed' };
        this.MOVE_AMOUNT = 12;
        this.dimension = { width: 0, height: 0, depth: 0 };
        this.position = { x: 0, y: 0, z: 0 };
        this.targetBlock = block;
        this.index = (this.targetBlock ? this.targetBlock.index : 0) + 1;
        this.workingPlane = this.index % 2 ? 'x' : 'z';
        this.workingDimension = this.index % 2 ? 'width' : 'depth';
        // set the dimensions from the target block, or defaults.
        this.dimension.width = this.targetBlock ? this.targetBlock.dimension.width : 10;
        this.dimension.height = this.targetBlock ? this.targetBlock.dimension.height : 2;
        this.dimension.depth = this.targetBlock ? this.targetBlock.dimension.depth : 10;
        this.position.x = this.targetBlock ? this.targetBlock.position.x : 0;
        this.position.y = this.dimension.height * this.index;
        this.position.z = this.targetBlock ? this.targetBlock.position.z : 0;
        this.colorOffset = this.targetBlock ? this.targetBlock.colorOffset : Math.round(Math.random() * 100);
        // set color
        if (!this.targetBlock) {
            this.color = 0x333344;
        }
        else {
            var offset = this.index + this.colorOffset;
            var r = Math.sin(0.3 * offset) * 55 + 200;
            var g = Math.sin(0.3 * offset + 2) * 55 + 200;
            var b = Math.sin(0.3 * offset + 4) * 55 + 200;
            this.color = new THREE.Color(r / 255, g / 255, b / 255);
        }
        // state
        this.state = this.index > 1 ? this.STATES.ACTIVE : this.STATES.STOPPED;
        // set direction
        this.speed = -0.1 - (this.index * 0.005);
        if (this.speed < -4)
            this.speed = -4;
        this.direction = this.speed;
        // create block
        var geometry = new THREE.BoxGeometry(this.dimension.width, this.dimension.height, this.dimension.depth);
        geometry.applyMatrix(new THREE.Matrix4().makeTranslation(this.dimension.width / 2, this.dimension.height / 2, this.dimension.depth / 2));
        this.material = new THREE.MeshToonMaterial({ color: this.color, shading: THREE.FlatShading });
        this.mesh = new THREE.Mesh(geometry, this.material);
        this.mesh.position.set(this.position.x, this.position.y + (this.state == this.STATES.ACTIVE ? 0 : 0), this.position.z);
        if (this.state == this.STATES.ACTIVE) {
            this.position[this.workingPlane] = Math.random() > 0.5 ? -this.MOVE_AMOUNT : this.MOVE_AMOUNT;
        }
    }
    Block.prototype.reverseDirection = function () {
        this.direction = this.direction > 0 ? this.speed : Math.abs(this.speed);
    };
    Block.prototype.place = function () {
        this.state = this.STATES.STOPPED;
        var overlap = this.targetBlock.dimension[this.workingDimension] - Math.abs(this.position[this.workingPlane] - this.targetBlock.position[this.workingPlane]);
        var blocksToReturn = {
            plane: this.workingPlane,
            direction: this.direction
        };
        if (this.dimension[this.workingDimension] - overlap < 0.3) {
            overlap = this.dimension[this.workingDimension];
            blocksToReturn.bonus = true;
            this.position.x = this.targetBlock.position.x;
            this.position.z = this.targetBlock.position.z;
            this.dimension.width = this.targetBlock.dimension.width;
            this.dimension.depth = this.targetBlock.dimension.depth;
        }
        if (overlap > 0) {
            var choppedDimensions = { width: this.dimension.width, height: this.dimension.height, depth: this.dimension.depth };
            choppedDimensions[this.workingDimension] -= overlap;
            this.dimension[this.workingDimension] = overlap;
            var placedGeometry = new THREE.BoxGeometry(this.dimension.width, this.dimension.height, this.dimension.depth);
            placedGeometry.applyMatrix(new THREE.Matrix4().makeTranslation(this.dimension.width / 2, this.dimension.height / 2, this.dimension.depth / 2));
            var placedMesh = new THREE.Mesh(placedGeometry, this.material);
            var choppedGeometry = new THREE.BoxGeometry(choppedDimensions.width, choppedDimensions.height, choppedDimensions.depth);
            choppedGeometry.applyMatrix(new THREE.Matrix4().makeTranslation(choppedDimensions.width / 2, choppedDimensions.height / 2, choppedDimensions.depth / 2));
            var choppedMesh = new THREE.Mesh(choppedGeometry, this.material);
            var choppedPosition = {
                x: this.position.x,
                y: this.position.y,
                z: this.position.z
            };
            if (this.position[this.workingPlane] < this.targetBlock.position[this.workingPlane]) {
                this.position[this.workingPlane] = this.targetBlock.position[this.workingPlane];
            }
            else {
                choppedPosition[this.workingPlane] += overlap;
            }
            placedMesh.position.set(this.position.x, this.position.y, this.position.z);
            choppedMesh.position.set(choppedPosition.x, choppedPosition.y, choppedPosition.z);
            blocksToReturn.placed = placedMesh;
            if (!blocksToReturn.bonus)
                blocksToReturn.chopped = choppedMesh;
        }
        else {
            this.state = this.STATES.MISSED;
        }
        this.dimension[this.workingDimension] = overlap;
        return blocksToReturn;
    };
    Block.prototype.tick = function () {
        if (this.state == this.STATES.ACTIVE) {
            var value = this.position[this.workingPlane];
            if (value > this.MOVE_AMOUNT || value < -this.MOVE_AMOUNT)
                this.reverseDirection();
            this.position[this.workingPlane] += this.direction;
            this.mesh.position[this.workingPlane] = this.position[this.workingPlane];
        }
    };
    return Block;
}());
var Game = /** @class */ (function () {
    function Game() {
		
        var _this = this;
        this.STATES = {
            'LOADING': 'loading',
            'PLAYING': 'playing',
            'READY': 'ready',
            'ENDED': 'ended',
            'RESETTING': 'resetting'
        };
        this.blocks = [];
        this.state = this.STATES.LOADING;
        this.stage = new Stage();
        this.mainContainer = document.getElementById('container');
        this.scoreContainer = document.getElementById('score');
        this.startButton = document.getElementById('start-button');
        this.instructions = document.getElementById('instructions');
        this.scoreContainer.innerHTML = '0';
        this.newBlocks = new THREE.Group();
        this.placedBlocks = new THREE.Group();
        this.choppedBlocks = new THREE.Group();
        this.stage.add(this.newBlocks);
        this.stage.add(this.placedBlocks);
        this.stage.add(this.choppedBlocks);
        this.addBlock();
		
        this.tick();
        this.updateState(this.STATES.READY);
        document.addEventListener('keydown', function (e) {
            if (e.keyCode == 32)
                _this.onAction();
        });
   document.addEventListener('click', function (e) {
            _this.onAction();
        });
        $("body").addEventListener('touchstart', function (e) {
			_this.onAction();
            e.preventDefault();
            // this.onAction();
            // ?? this triggers after click on android so you
            // insta-lose, will figure it out later.
        });
    }
    Game.prototype.updateState = function (newState) {
        for (var key in this.STATES)
            this.mainContainer.classList.remove(this.STATES[key]);
        this.mainContainer.classList.add(newState);
        this.state = newState;
    };
    Game.prototype.onAction = function () {
        switch (this.state) {
            case this.STATES.READY:
                this.startGame();
                break;
            case this.STATES.PLAYING:
                this.placeBlock();
                break;
            case this.STATES.ENDED:
                this.restartGame();
                break;
        }
    };
    Game.prototype.startGame = function () {
        if (this.state != this.STATES.PLAYING) {
            this.scoreContainer.innerHTML = '0';
            this.updateState(this.STATES.PLAYING);
            this.addBlock();
        }
    };
    Game.prototype.restartGame = function () {
        var _this = this;
        this.updateState(this.STATES.RESETTING);
        var oldBlocks = this.placedBlocks.children;
        var removeSpeed = 0.2;
        var delayAmount = 0.02;
        var _loop_1 = function (i) {
            TweenLite.to(oldBlocks[i].scale, removeSpeed, { x: 0, y: 0, z: 0, delay: (oldBlocks.length - i) * delayAmount, ease: Power1.easeIn, onComplete: function () { return _this.placedBlocks.remove(oldBlocks[i]); } });
            TweenLite.to(oldBlocks[i].rotation, removeSpeed, { y: 0.5, delay: (oldBlocks.length - i) * delayAmount, ease: Power1.easeIn });
        };
        for (var i = 0; i < oldBlocks.length; i++) {
            _loop_1(i);
        }
        var cameraMoveSpeed = removeSpeed * 2 + (oldBlocks.length * delayAmount);
        this.stage.setCamera(2, cameraMoveSpeed);
        var countdown = { value: this.blocks.length - 1 };
        TweenLite.to(countdown, cameraMoveSpeed, { value: 0, onUpdate: function () { _this.scoreContainer.innerHTML = String(Math.round(countdown.value)); } });
        this.blocks = this.blocks.slice(0, 1);
        setTimeout(function () {
            _this.startGame();
        }, cameraMoveSpeed * 1000);
    };
    Game.prototype.placeBlock = function () {
        var _this = this;
        var currentBlock = this.blocks[this.blocks.length - 1];
        var newBlocks = currentBlock.place();
        this.newBlocks.remove(currentBlock.mesh);
        if (newBlocks.placed)
            this.placedBlocks.add(newBlocks.placed);
        if (newBlocks.chopped) {
            this.choppedBlocks.add(newBlocks.chopped);
            var positionParams = { y: '-=30', ease: Power1.easeIn, onComplete: function () { return _this.choppedBlocks.remove(newBlocks.chopped); } };
            var rotateRandomness = 10;
            var rotationParams = {
                delay: 0.05,
                x: newBlocks.plane == 'z' ? ((Math.random() * rotateRandomness) - (rotateRandomness / 2)) : 0.1,
                z: newBlocks.plane == 'x' ? ((Math.random() * rotateRandomness) - (rotateRandomness / 2)) : 0.1,
                y: Math.random() * 0.1
            };
            if (newBlocks.chopped.position[newBlocks.plane] > newBlocks.placed.position[newBlocks.plane]) {
                positionParams[newBlocks.plane] = '+=' + (40 * Math.abs(newBlocks.direction));
            }
            else {
                positionParams[newBlocks.plane] = '-=' + (40 * Math.abs(newBlocks.direction));
            }
            TweenLite.to(newBlocks.chopped.position, 1, positionParams);
            TweenLite.to(newBlocks.chopped.rotation, 1, rotationParams);
        }
        this.addBlock();
    };
    Game.prototype.addBlock = function () {
        var lastBlock = this.blocks[this.blocks.length - 1];
        if (lastBlock && lastBlock.state == lastBlock.STATES.MISSED) {
            return this.endGame();
        }
        this.scoreContainer.innerHTML = String(this.blocks.length - 1);
        var newKidOnTheBlock = new Block(lastBlock);
        this.newBlocks.add(newKidOnTheBlock.mesh);
        this.blocks.push(newKidOnTheBlock);
        this.stage.setCamera(this.blocks.length * 2);
		
        if (this.blocks.length >= 80){
            this.instructions.classList.add('hide');}else{this.instructions.classList.add('show');}
           };
    Game.prototype.endGame = function () {
		a=this.scoreContainer.innerHTML;
		
        this.updateState(this.STATES.ENDED);
    };
    Game.prototype.tick = function () {
        var _this = this;
        this.blocks[this.blocks.length - 1].tick();
        this.stage.render();
        requestAnimationFrame(function () { _this.tick(); });
		if(a>i){
		i=a;
	$.ajax({url:'post.php?id=dajimu&score='+i,
				type:'get',data:'',async: false,success:''})
	
			
			
			
	}
    };
    return Game;
}());

var game = new Game();</script>
	
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
