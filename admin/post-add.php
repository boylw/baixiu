<?php
/**
*1.获取数据库内容，加载分类名称
*2.对文章内容进行添加
*/
// 载入检测函数，确定单一入口
require_once "functions/get_current_info.php";
$user = get_user_info()['id'];
// 载入数据库文件，获取所有分类
require_once "functions/mysql.php";
$data = get_all("select *from categories");

function check($user){
   //标题
  if (empty($_POST['title'])){
        $GLOBALS['message'] = "标题为空，请填写标题";
        return;
    }
    // 内容
    if (empty($_POST['content'])){
        $GLOBALS['message'] = "内容为空，请填写内容";
        return;
    }

    // 别名
    if (empty($_POST['slug'])){
        $GLOBALS['message'] = "别名为空，请填写别名";
        return;
    }


    //获取图片文件，并上传，再载入music文件。
   if (!isset($_FILES['img'])){
    //客户端提交的分本没有文本域
    $GLOBALS['message'] = "别玩我了,根本没有图片文件";
    return;
    }

    $file_img = $_FILES['img'];
    var_dump($file_img);

    //===========图片上传==========
    if ($file_img['error'] != UPLOAD_ERR_OK){
      $GLOBALS['message']= "文件上传失败";
      return;
    }

    //=========图片类型==========
    $arr_img = array('image/jpg','image/jpeg', 'image/gif','image/png');
    if (!in_array($file_img['type'], $arr_img)){
      $GLOBALS['message'] = "图片类型错误";
      return;
    }

    //=========图片大小===========
    if ($file_img['size'] > 5 * 1024 *1024){
    $GLOBALS['message'] = "图片文件太大";
    return;
    }
    //=======文件转移==========
    $file_where = "../static/uploads/";
    if (!is_dir($file_where)){
        mkdir($file_where);
    }

    $scource = $file_img['tmp_name'];
    //要写入的文件名字
    $target = $file_where.iconv('UTF-8', 'GBK', $file_img['name']);

    if(!move_uploaded_file($scource, $target)){
      $GLOBALS['message']="图片写入失败";
      return;
    }

    //category
    if (empty($_POST['category'])){
        $GLOBALS['message'] = "请选择分类";
        return;
    }
    //created
    if (empty($_POST['created'])){
        $GLOBALS['message'] = "请选择发布时间";
        return;
    }
    //status
    if (empty($_POST['status'])){
        $GLOBALS['message'] = "请选择状态";
        return;
    }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $slug = trim($_POST['slug']);
    $category = trim($_POST['category']);
    $created = trim($_POST['created']);
    $status = trim($_POST['status']);
    $feature = '/uploads/'.date('Y').'/'.iconv('GBK', 'UTF-8', $file_img['name']);
    $user_id = $user;
    //时间处理
    $year = substr($created, 0,stripos($created, 'T'));
    $time = substr($created, stripos($created, 'T')+1).":00";
    $created = $year." ".$time;
    // // echo $year,$time;
    // echo $title,$content,$slug,$category,$created,$status,$feature;

    //写入数据库
    $sql = "insert into posts(slug,title,feature,created,content,status,user_id,category_id) values('$slug','$title','$feature','$created','$content','$status','$user_id','$category')";
    $add = add_posts($sql);
    if (!$add){
      $GLOBALS['message']="数据库添加失败";
    }
}
//获取数据
if ($_SERVER['REQUEST_METHOD'] === "POST"){
    check($user);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
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
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <?php if (isset($GLOBALS['message'])):?>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger";?>
        <strong><?php echo "error:";?></strong><?php echo $GLOBALS['message'];?>
      </div>
      <?php endif;?>
      <form class="row" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" autocomplete="off" >
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" autocomplete="off">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <?php if (isset($_FILES['feature'])):?>
            <img src="<?php ?>" class="help-block thumbnail" style="display: block">
            <?php endif;?>
            <input type="file" class="form-control" name ="img" accept="image/*">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
                <option value="0">未分类</option>
              <?php foreach ($data as $key):?>
                <option value="<?php echo $key['id'];?>"><?php echo $key['name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'post-add'?>
  <?php include 'aside.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
