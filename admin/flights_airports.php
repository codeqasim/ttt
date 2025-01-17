<?php 

use Medoo\Medoo;
require_once '_config.php';
auth_check();

$title = T::airports;
include "_header.php";

?>

<div class="page_head bg-transparent">
    <div class="panel-heading">
        <div class="float-start">
            <p class="m-0 page_title"><?=T::airports?></p>
        </div>
        <div class="float-end">
        </div>
    </div>
</div>

<div class="container mt-4 mb-4">

<?php 
include('./xcrud/xcrud.php');
$xcrud = Xcrud::get_instance();
$xcrud->table('flights_airports');
$xcrud->order_by('id','desc');
$xcrud->columns('status,code,airport,city,country');
$xcrud->fields('status,code,airport,city,country');

// USER PERMISSIONS
if (!isset($permission_delete)){ $xcrud->unset_remove(); }
if (!isset($permission_edit)){ $xcrud->unset_edit(); } else { }

$xcrud->column_callback('status', 'create_status_icon');
$xcrud->field_callback('status','Enable_Disable');
if (!isset($permission_add)){ $xcrud->unset_add(); }

$xcrud->relation('country','countries','nicename','nicename');
// $xcrud->relation('city','locations','city','city');

// $xcrud->label(array('status' =>  T::status, 'country_id' => T::country, 'type' => T::type ));

$xcrud->after_insert('create_lang');
$xcrud->before_remove('remove_lang');

$xcrud->unset_title();
$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->column_width('code','50px');
$xcrud->column_width('airport','260px');
$xcrud->column_width('country','250px');
$xcrud->column_width('city','250px');
$xcrud->column_width('status','5%');
echo $xcrud->render();

?>

</div>

<?php include "_footer.php"; ?>