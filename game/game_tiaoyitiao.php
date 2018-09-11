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
$sql = "SELECT score FROM tiaoyitiao where name='$name'";
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
<html>
<head>
<meta charset="utf-8">
<title>跳一跳</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
	

    * {

      margin: 0;
      padding: 0;
    }
    html,body {

      width: 100%;
      height: 100%;
    }
     canvas {

       display: block;
     }
     #help {

       top: 0;
       left: 0;
       z-index: 3;
       width: 100%;
       height: 100%;
       padding: 10%;
       font-size: 30px;
       font-weight: 600;
       display: none;
       cursor: pointer;
       position: absolute;
       text-align: center;
       box-sizing: border-box;
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

<canvas id="canvas-game" user-select:none;></canvas>
	
<div id="help" -webkit-user-select:none;>
    <!-- 存放说明文字的div -->
  </div> 

  <script src="../index/main.js"></script>
  <script src="components/background.js"></script>
  <script src="roles/lead.js"></script>
  <script src="jumper/jumperBlock.js"></script>
  <script>
	  (function() {
  var jumperTimer = null;
  var jumperInterval = 5; // 定时器间隔
  var standBlockObj = {
    'position': game.block.positionArr[0].x1,
    'isFirstFall': false,
    'before': game.block.positionArr[0].x1,
    'current': game.block.positionArr[0].x1
  };

  var score = window.score;
  var canvas =  window.canvas;
  var ctx = window.ctx;
  var leadSize = game.lead.leadInfo.size;
  var leadPosition = game.lead.leadInfo.position;

  var isFirstFall = true;
  var isFirstPush = true;
  var canGetScore = true;
  var isToLeft = false;
  var isRecordAureole = false;
  var textColor = '#000';       // 文本颜色
  var fontSize = 40;            // 字体大小
  var HighScore = 0;            // 历史最高分记录值
  var startTime = 0;            // 蓄力开始时间存储值
  var aureoleShrinkSpeed = 0;   // 光圈收缩速度存储值
  var horizontalDirection = 1;  // 水平移动方向
  var maxYAddSpeed = 1500;      // 向下加速度
  var leftLine = canvas.width / 10;// 向左对齐水平位置
  var leftSpeed = - 600;        // 向左对齐速度
  var touchDB = 360;            // 触摸灵敏度，越小反应越大

  var currentChooseOption = ''; // 记录当前悬浮的操作选项
  var helpContent = '<p>操作: 电脑 按下空格蓄力，松开起跳 手机 按下开始蓄力,左右滑动的越远手指松开时跳的越远</p><p>得分: 每次踩中一个方块得一分,越过方块不得分</p><p>注意: 掉下缝隙或飞过屏幕右侧则判负,加油吧</p><hr><p>点击任意处关闭说明</p>';  
	
var HighScore=<?php echo "$score3"?>;
  var textInfoArr = [{    
	  // 存储菜单的文本信息
    'text': '最高分:' + HighScore,
    'x1': 0,
    'x2': 0,
    'y': 0
  },{
    'text': '开始游戏',
    'x1': 0,
    'x2': 0,
    'y': 0
  },{
    'text': '手指向右滑动起跳',
    'x1': 0,
    'x2': 0,
    'y': 0
  },{
    'text': '游戏说明',
    'x1': 0,
    'x2': 0,
    'y': 0
  }];
  
  game.help.changeHelpContent(helpContent);
  img.imgMethod.getImage();
  img.imgMethod.checkImageIsAllReady(function() {

    endGameHandle();

  });

  function runGame() {
    setAureoleAutoAdd();
    clearRect('#fff');
    while (!game.block.checkBlockIsEnough()) {
      game.block.createBlock();
    }
    game.block.draw();
    game.lead.changeMouseState('circle');
    if (game.lead.sportInfo.speed.y < 0) {
      game.lead.changeEyeState('top');
    } else if (game.lead.sportInfo.speed.y > 0) {
      game.lead.changeEyeState('bottom');
    } else {
      game.lead.changeEyeState('center');
      game.lead.changeMouseState('square');
    }
    var leadPositionObj = game.lead.computeLeadPosition(jumperInterval);
    var blockPositionArr = game.block.positionArr;
    if (!isToLeft) {
      blockPositionArr.forEach(function (blockPosition) {
        var beforePosition = {
          'x1': leadPositionObj.x.before,
          'x2': leadPositionObj.x.before + leadSize.x,
          'y': leadPositionObj.y.before
        };
        var afterPosition = {
          'x1': leadPositionObj.x.after,
          'x2': leadPositionObj.x.after + leadSize.x,
          'y': leadPositionObj.y.after
        };
        if ((beforePosition.y < blockPosition.y - leadSize.y && afterPosition.y > blockPosition.y - leadSize.y) || leadPositionObj.y.after === blockPosition.y - leadSize.y) {
          var rate = (blockPosition.y - leadSize.y - afterPosition.y) / (afterPosition.y - beforePosition.y - leadSize.y);
          var deviationX = beforePosition.x1 + (afterPosition.x1 - beforePosition.x1) * rate;
          if (deviationX > blockPosition.x1 - leadSize.x && deviationX < blockPosition.x2) {
            leadPositionObj.y.speed = 0;
            leadPositionObj.x.after = deviationX;
            leadPositionObj.y.after = blockPosition.y - leadSize.y;
            game.lead.changeAddSpeed('x', 0);
            game.lead.changeAddSpeed('y', 0);
            isFirstFall = false;
            standBlockObj.current = blockPosition.x1;
            if (standBlockObj.current !== standBlockObj.before) {
              standBlockObj.isFirstFall = true;
              canGetScore = true;
              if (standBlockObj.isFirstFall) {
                standBlockObj.before = blockPosition.x1;
                standBlockObj.isFirstFall = false;
                getScoreHandle();
              }
            }
          }
        };
        if (afterPosition.y + leadSize.y > blockPosition.y) {
          if ((beforePosition.x2 < blockPosition.x1 && afterPosition.x2 > blockPosition.x1) || afterPosition.x2 === blockPosition.x1) {
            var rate = (blockPosition.x1 - beforePosition.x2) / (afterPosition.x2 - beforePosition.x2);
            var deviationY = beforePosition.y + (afterPosition.y - beforePosition.y) * rate;
            leadPositionObj.y.after = deviationY;
            leadPositionObj.x.after = blockPosition.x1 - leadSize.x;
            horizontalDirection = -1;
          } else if (beforePosition.x1 > blockPosition.x2 && afterPosition.x1 < blockPosition.x2 || afterPosition.x1 === blockPosition.x2) {
            var rate = (blockPosition.x2 - beforePosition.x1) / (afterPosition.x1 - beforePosition.x1);
            var deviationY = beforePosition.y + (afterPosition.y - beforePosition.y) * rate;
            leadPositionObj.y.after = deviationY;
            leadPositionObj.x.after = blockPosition.x2;
            horizontalDirection = 1;
          }
        }
      });
      if (leadPositionObj.y.speed === 0 || isFirstFall) {
        leadPositionObj.x.speed = 0;
      } else {
        leadPositionObj.x.speed = game.lead.sportInfo.maxSpeed.x * horizontalDirection;
      }
    } else {
      var distance = leadPosition.x;
      var preDistance = leftSpeed * jumperInterval / 1000;
      leadPositionObj.x.speed = leftSpeed;
      blockPositionArr.forEach(function (blockPosition) {
        blockPosition.x1 = blockPosition.x1 + preDistance;
        blockPosition.x2 = blockPosition.x2 + preDistance;
      });
      standBlockObj.before = standBlockObj.before + preDistance; //方块归位时需要更新之前踩过方块的记录值
      if (leadPositionObj.x.after <= leftLine) {
        blockPositionArr.forEach(function (blockPosition) {
          if (blockPosition.x2 < 0) {
            blockPositionArr.shift();
          }
        });
        isToLeft = false;
      }
    }
    game.lead.updateLeadPositionAndSpeed(leadPositionObj);
    score.draw();
    game.lead.draw();
    if (checkLeadIsDie()) {
      endGameHandle();
    };
  }

  function getScoreHandle() {
    if (canGetScore && !isFirstFall) {
      score.computeScore(1);
    };
    canGetScore = false;
    if (leadPosition.x > leftLine) {
      isToLeft = true;
    }
  }

  function startGameHandle() {
    currentChooseOption = '';

    game.controller.coverFullScreen(false, function() {
      clearInterval(jumperTimer);
      jumperTimer = null;

      leadPosition = game.lead.leadInfo.position = {
        'x': canvas.width / 10,
        'y': canvas.height / 10 * 9 - 48,
      };

      standBlockObj = {
        'position': game.block.positionArr[0].x1,
        'isFirstFall': false,
        'before': game.block.positionArr[0].x1,
        'current': game.block.positionArr[0].x1
      };
      score.resetScore();

      game.block.resetBlockInfo();
      jumperTimer = setInterval(runGame, jumperInterval);
      clearChooseGameEvent();
      setGameControl();
    });

  }

  function endGameHandle() {
    var currentSocre = score.computeScore();
    clearInterval(jumperTimer);
    jumperTimer = null;
    HighScore = HighScore > currentSocre ? HighScore : currentSocre;
    textInfoArr[0].text = '最高分:' + HighScore;
	  		  $.ajax({url:'post.php?id=tiaoyitiao&score='+HighScore,type:'get',data:'',async: false,success:''})
    game.controller.coverFullScreen(true, endGameCallback);

    function endGameCallback() {
      ctx.beginPath();
      ctx.fillStyle = textColor;
      ctx.font = fontSize + 'px Arial';
      ctx.textBaseline = 'top';
      textInfoArr.forEach(function (textInfo, index) {
        var textSizeInfo = getTextSizeInfo(textInfo.text);
        textInfo.x1 = textSizeInfo.centerPoint;
        textInfo.x2 = textInfo.x1 + textSizeInfo.textWidth;
        textInfo.y = canvas.height / textInfoArr.length * index + canvas.height / textInfoArr.length / 2 - 20;
        ctx.fillText(textInfo.text, textInfo.x1, textInfo.y);
      });
      clearGameControl();
      setChooseGameEvent();
    }

    function getTextSizeInfo(text) {
      var textWidth = ctx.measureText(text).width;
      return {
        'textWidth': textWidth,
        'centerPoint': Math.round(canvas.width / 2 - textWidth / 2)
      }
    }
  }

  function checkLeadIsDie() {
    if (leadPosition.y > canvas.height || leadPosition.x > canvas.width) {
      return true;
    }
    return false;
  }

  function startJump(deviationTime) {
    if (deviationTime) {
      game.lead.accunulateJump(deviationTime);
      game.lead.changeAddSpeed('y', maxYAddSpeed);
    };
  }

  function setChooseGameEvent() {
    window.onmousemove = function (event) {
      var flag = false;
      canvas.style.cursor = 'default';
      currentChooseOption = '';
      textInfoArr.forEach(function (textInfo, index) {
        if (index !== 0) {
          if (event.clientX >= textInfo.x1 && event.clientX <= textInfo.x2 && event.clientY >= textInfo.y &&  event.clientY <= textInfo.y + fontSize) {
            flag = true;
            switch (index) {
              case 1: currentChooseOption = 'start';
              break;
              case 2: currentChooseOption = 'mode';
              break;
              case 3: currentChooseOption = 'help';
              break;
            }
          };
        };
      });
      if (flag) {
        canvas.style.cursor = 'pointer';
      }
    }

    window.onclick = function () {
      if (currentChooseOption) {
        currentChooseOption === 'start' ? startGameHandle() : '';
        currentChooseOption === 'mode' ? (function(){console.log('mode')})() : '';
        currentChooseOption === 'help' ? changeHelpShowState(true) : '';
      }
    }
  }

  function clearChooseGameEvent() {
    canvas.style.cursor = 'default';
    window.onmousemove = null;
    window.onclick = null;
  }

  function setAureoleAutoAdd() {
    if (isRecordAureole) {
      aureoleShrinkSpeed = aureoleShrinkSpeed + 0.6;
      game.lead.changeAureoleShrinkSpeed(aureoleShrinkSpeed, jumperInterval);
    };
  }

  function setGameControl() {
    window.onkeydown = function (event) {
      if (event.keyCode === 32 && isFirstPush && game.lead.sportInfo.speed.y === 0) {
        aureoleShrinkSpeed = 0;
        isRecordAureole = true;
        game.lead.changeAureoleIsShow(true);
        isFirstPush = false;
        startTime = new Date().getTime();
      }
    }

    window.onkeyup = function (event) {
      var endTime = 0;
      if (event.keyCode === 32 && game.lead.sportInfo.speed.y === 0 && !isToLeft) {
        isRecordAureole = false;
        game.lead.changeAureoleIsShow(false);
        isFirstPush = true;
        endTime = new Date().getTime();
        var deviationTime = (endTime - startTime) / 1000;
        horizontalDirection = 1;
        startJump(deviationTime);
      }
    }

    window.ontouchstart = function (event) {
      if (game.lead.sportInfo.speed.y === 0 && !isToLeft) {
        game.lead.changeAureoleIsShow(true);
        var startX = event.changedTouches[0].pageX;
        isFirstPush = false;
      };

      window.ontouchmove = function (event) {
        var moveY = event.changedTouches[0].pageX;
        var speed = Math.abs(moveY - startX) / 5;
        game.lead.changeAureoleShrinkSpeed(speed, jumperInterval);
      }

      window.ontouchend = function (event) {
        if (game.lead.sportInfo.speed.y === 0 && !isToLeft) {
          game.lead.changeAureoleIsShow(false);
          var endX = event.changedTouches[0].pageX;
          var distance = Math.abs(endX - startX);
          var deviationTime = distance / touchDB;
          horizontalDirection = 1;
          startJump(deviationTime);
        }
      }
    }
  }

  function clearGameControl() {
    window.ontouchstart = null;
    window.onkeydown = null;
    window.onkeyup = null;
  }

  function clearRect(color) {
    var color = color || '#ddd';
    ctx.beginPath();
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  }

  function changeHelpShowState(state) {
    clearChooseGameEvent();
    clearRect();
    game.help.changeHelpShowState(state);
    setCloseHelpEvent();
  }

  function setCloseHelpEvent() {
    if(game.help.showState) {
      window.onclick = function() {
        game.help.changeHelpShowState(false);
        endGameHandle();
      }
    }
  }
})();</script>
	
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
