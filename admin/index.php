<?php

  // 确定用户登录
  require_once "functions/get_current_info.php";
  get_user_info();

  // 这里需求:我们要获取文章数目，草稿数目，分类数目，评论数目，待审核数目
  require_once "functions/mysql.php";
  $content = get_one("select count(1) as num from comments","num");
  $content_approved = get_one("select count(1) as num from comments where status='approved'","num");
  $categories = get_one("select count(1) as num from categories","num");
  $posts = get_one("select count(1) as num from posts","num");
  $posts_approved = get_one("select count(1) as num from posts where status='drafted'","num");
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/chart/chart.min.js"></script>
</head>
<body>

  <script>NProgress.start()</script>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts;?></strong>篇文章（<strong><?php echo $posts_approved;?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories;?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $content;?></strong>条评论（<strong><?php echo $content_approved;?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
           <canvas id="myChart" width="400" height="200"></canvas>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php $current_page = 'index';?>
  <?php include 'aside.php'; ?>

  <script type="text/javascript">
  var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
        data: {
            datasets: [{
                data: [<?php echo $posts;?>, <?php echo $categories;?>, <?php echo $content;?>],
                backgroundColor:[
                "#38a79d",
                "#84645d",
                "#546464"
                ]
            }],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                '文章',
                '分类',
                '评论'
            ]
        }
  });
  </script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
