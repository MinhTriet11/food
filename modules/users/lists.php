<?php
if (!defined('_INCODE')) die('Access Deined...');
//file nay hien thi danh sach nguoi dung

$data=[
    'pageTitle'=>'Quản lý người dùng'
];
layout('header', $data );

//xu ly loc du lieu
$filter='';
if (isGet()){
    $body=getBody();
    if (!empty($body['status'])){
        $status=$body['status'];
        if ($status==2){
            $statusSql=0;
        }else{
            $statusSql=$status;
        }
    if (!empty($filter) && strpos($filter, 'WHERE')>=0)
        {
            $operator='AND';
        }else{
            $operator='WHERE';
        }

    $filter.="$operator status=$statusSql";
    }
   
    //xu ly loc theo tu khoa

    if (!empty($body['keyword'])){
        $keyword=$body['keyword'];

        if (!empty($filter) && strpos($filter, 'WHERE')>=0)
            {
                $operator='AND';
            }else{
                $operator='WHERE';
            }
        $filter.=" $operator fullname LIKE '%$keyword%'";
    }
    
}

$allUser=getRows("SELECT id FROM users $filter ");
$perPage=_PER_PAGE;

$maxPage=ceil($allUser/$perPage);

if (!empty(getBody()['page'])){
    $page=getBody()['page'];
    if ($page<1 || $page>$maxPage){
        $page=1;
    }
}else{
    $page=1;
}

//tinh toan offset trong limit bang bien $page
/*
*   $page=1 => offset = 0 offset=($page-1)* $perPage=0
*   $page=2 => offset = 3 offset=($page-1)* $perPage=3
*   $page=3 => offset = 6 offset=($page-1)* $perPage=6
*/

$offset=($page-1)*$perPage;

//truy van lay tat ca
$listAll=getRaw("SELECT * FROM users $filter ORDER BY createAt DESC LIMIT $offset, $perPage "); // limit (index, so luong user)
//xu ly query string tim kiem voi phan trang

if (!empty($_SERVER['QUERY_STRING'])){
    $queryString=$_SERVER['QUERY_STRING'];
    $queryString=str_replace('module=users','', $queryString);
    $queryString=str_replace('&page='.$page,'', $queryString);
    $queryString=trim($queryString,'&');
    $queryString='&'.$queryString;
}
$msg = getFalshData('msg');
$msgType = getFalshData('msg_type');
?>
<div class="container">
    <hr/>
    <h3><?php echo $data['pageTitle']; ?></h3>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm">Thêm người dùng <i class="fa fa-plus"></i></a>
    </p>
    <?php
                getMsg($msg, $msgType);
        ?>
    <form action="" method="get">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <select class="form-control" name="status">
                        <option value="0">Chọn trạng thái</option>
                        <option value="1" <?php echo (!empty($status) && $status==1)?'selected':false; ?>>Kích hoạt</option>
                        <option value="2" <?php echo (!empty($status) && $status==2)?'selected':false; ?>>Chưa kích hoạt</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <input type="search" class="form-control" name="keyword" value="<?php echo (!empty($keyword))?$keyword:false; ?>" id="" placeholder="Nhập tên tìm kiếm...">
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary btn-block" >Tìm kiếm</button>
            </div>
            <input type="hidden" name="module" value="users">
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Trạng thái</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listAll)):
            $count=0;
                foreach ($listAll as $value) :
                    $count++;
                
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $value['fullname'] ;?></td>
                <td><?php echo $value['email'] ;?></td>
                <td><?php echo $value['phone'] ;?></td>
                <td><?php echo $value['status']==1?'<button type="button" class="btn btn-success btn-sm">Đã kích hoạt</button>':'<button type="button" class="btn btn-warning btn-sm">Chưa kích hoạt</button>' ;?></td>
                <td><a href="<?php echo _WEB_HOST_ROOT.'?module=users&action=edit&id='.$value['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                <td><a href="<?php echo _WEB_HOST_ROOT.'?module=users&action=delete&id='.$value['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa không?')" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a></td>
            </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger text-center">Không có người dùng</div>
                    </td>
                </tr>
                <?php endif; ?>
        </tbody>

    </table>
    <nav aria-label="Page navigation example">
  <ul class="pagination">
    <?php if ($page>1){
        $prevPage=$page-1;
        echo '<li class="page-item"><a class="page-link" href="'. _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$prevPage.'">Trước</a></li>';
    }?>
    
    <?php
    $begin=$page-2;
    if ($begin<1){
        $begin=1;
    };
    $end=$page+2;
    if ($end>$maxPage){
        $end=$maxPage;
    }
    for ($i=$begin; $i<=$end  ; $i++) {  ?>
    <li class="page-item <?php echo ($i==$page)?'active':false; ?> "><a class="page-link" href="<?php echo _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$i; ?>"><?php echo $i ;?> </a></li>
    <?php } ?>
    <?php if ($page<$maxPage){
        $nextPage=$page+1;
        echo '<li class="page-item"><a class="page-link" href="'. _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$nextPage.'">Sau</a></li>';
    } ?>
  </ul>
</nav>
    
  
    <hr/>
</div>
<?php

layout('footer');