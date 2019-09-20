<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- form start -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <?php echo form_open('import_admin_controller/imports_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('imports'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php
                $message = $this->session->flashdata('submit');
                if (!empty($message) && $message == "email") {
                    $this->load->view('admin/includes/_messages');
                }
                ?>

                <div class="form-group">
                    <label class="control-label">Database Name</label>
                    <input type="text" class="form-control" name="db_name" onchange="window.location.href = '<?php echo admin_url(); ?>imports?db_name='+this.value;"
                        placeholder="Database Name" value="<?php echo html_escape($imports['current']['db_name']); ?>" />
                </div>

                <div class="form-group">
                    <label class="control-label">Table</label>
                    <select name="table" class="form-control" onchange="window.location.href = '<?php echo admin_url(); ?>imports?db_name=<?php echo $imports['current']['db_name']; ?>&table='+this.value;">
                        <?php foreach ($imports['tables'] as $table): ?>
                            <option value="<?php echo html_escape($table['name']) ?>" <?php echo ($imports['current']['table'] == $table['name']) ? "selected" : ""; ?>><?php echo html_escape($table['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="mt-5 mb-5">

                <div class="box-header with-border">
                    <h3 class="box-title">Users</h3>
                </div>

                <div class="form-group">
                    <label class="control-label">User Name</label>
                    <select name="users-username" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-username'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">User Email</label>
                    <select name="users-email" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-email'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Phone Number</label>
                    <select name="users-phone_number" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-phone_number'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">User Logo</label>
                    <select name="users-avatar" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-avatar'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">User Url</label>
                    <select name="users-ext_url" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-ext_url'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">User City</label>
                    <select name="users-city" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-city'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">User Location Or Address</label>
                    <select name="users-ext_location" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['users-ext_location'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="mt-5 mb-5">

                <div class="box-header with-border">
                    <h3 class="box-title">Products</h3>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Title</label>
                    <select name="products-title" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-title'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Category ID</label>
                    <select name="products-category_id" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['categories'] as $category): ?>
                            <option value="<?php echo html_escape($category->id) ?>" <?php echo ($imports['current']['products-category_id'] == $category->id) ? "selected" : ""; ?>><?php echo html_escape($category->description); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Description</label>
                    <select name="products-description" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-description'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Price</label>
                    <select name="products-price" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-price'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product City</label>
                    <select name="products-city" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-city'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Location</label>
                    <select name="products-ext_location" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-ext_location'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Address</label>
                    <select name="products-address" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-address'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product ZipCode</label>
                    <select name="products-zip_code" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-zip_code'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Url</label>
                    <select name="products-external_link" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-external_link'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="mt-5 mb-5">

                <div class="box-header with-border">
                    <h3 class="box-title">Product Images</h3>
                </div>

                <div class="form-group">
                    <label class="control-label">Main Image</label>
                    <select name="products-ext_main_image" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-ext_main_image'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Images</label>
                    <select name="products-ext_images" class="form-control">
                        <option value="">[ Null ]</option>
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo ($imports['current']['products-ext_images'] == $column->Field) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="mt-5 mb-5">

                <div class="box-header with-border">
                    <h3 class="box-title">Product Additional Fields</h3>
                </div>

                <div class="form-group">
                    <label class="control-label">Product Additional Description</label>
                    <select name="products-ext_addtional_description[]" class="selectpicker form-control" multiple data-style="bg-white rounded-pill px-4 py-3 shadow-sm">
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo (in_array($column->Field, explode(",", $imports['current']['products-ext_addtional_description']))) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="control-label">Product Additional Fields</label>
                    <select name="products-ext_addtional_fields[]" class="selectpicker form-control" multiple data-style="bg-white rounded-pill px-4 py-3 shadow-sm">
                        <?php foreach ($imports['columns'] as $column): ?>
                            <option value="<?php echo html_escape($column->Field) ?>" <?php echo (in_array($column->Field, explode(",", $imports['current']['products-ext_addtional_fields']))) ? "selected" : ""; ?>><?php echo html_escape($column->Field); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="imports" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
        </div>
        <?php echo form_close(); ?><!-- form end -->
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Progress</h3>
            </div>
            <div class="box-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="">0% Complete</span>
                    </div>
                </div>
                <ul class="scroll-list list-unstyled" id="scroll_list">
                
                </ul>
                <div class="ajax-load text-center" style="display:none">
                    <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary pull-right" id="start_btn" onclick="start=1;loadMoreData(glo_upload_id);this.disabled=true;clearList()">Start Upload</button>
            </div>
        </div>
    </div>
</div>


<style>
    h4 {
        color: #0d6aad;
        font-weight: 600;
        margin-bottom: 15px;
        margin-top: 30px;
    }

    .col-option {
        margin-top: 5px;
    }

    .bg-white {
        background: white;
    }
    .rounded-pill {
        border: 1px solid #ced4da;
        border-radius: 0;
    }
    .scroll-list li {
        padding: 0 5px;
    }
    .scroll-list li.progressing {
        background: url('<?php echo base_url(); ?>/assets/img/loading.gif') right no-repeat;
        background-size: contain;
    }
    .scroll-list li:hover {
        background-color: #dedede;
    }
</style>

<script>
    $('.selectpicker').selectpicker();

    var number = 11;
    var start = 0;
    var glo_upload_id = 0;
    // $(window).scroll(function() {
    //     if($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         var last_id = $("#scroll_list li:last").attr("id");
    //         loadMoreData(last_id);
    //     }
    // });
    function clearList() {
        number -= 10;
        $("#scroll_list li").remove();
    }

    function loadMoreData(last_id){
        $.ajax({
            url: '<?php echo base_url("admin/imports/load_more_data"); ?>',
            data: {
                db_name: '<?php echo $imports['current']['db_name']; ?>',
                table: '<?php echo $imports['current']['table']; ?>',
                last_id: last_id,
            },
            datatype: 'json',
            type: "get",
            beforeSend: function()
            {
                $('.ajax-load').show();
            }
        })
        .done(function(data)
        {
            data = JSON.parse(data);
            $('.ajax-load').hide();
            if(data.length == 0) {
                alert('complete'); 
                return;
            }
            var name = '<?php echo $imports['current']['products-title']; ?>';
            var html = '';
            if (name) {
                for (var key in data)
                {
                    html += '<li id="' + data[key].id + '">' + number + ".  " + data[key][name] + '</li>';
                    number++;
                }
            }
            $("#scroll_list").append(html);
            if(start) {
                start_upload(data[0].id);
            }
            if($('#scroll_list li').length > 30) {
                $("#scroll_list li").slice(0,10).remove();
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
            alert('server not responding...');
        });
    }
    function start_upload(upload_id){
        $.ajax({
            url: '<?php echo base_url("admin/imports/upload_object"); ?>',
            data: {
                db_name: '<?php echo $imports['current']['db_name']; ?>',
                table: '<?php echo $imports['current']['table']; ?>',
                upload_id: upload_id,
            },
            datatype: 'json',
            type: "get",
            beforeSend: function()
            {
                $('#'+upload_id).addClass('progressing');
            }
        })
        .done(function(data)
        {
            data = JSON.parse(data);

            if(data.success) {
                if(data.progress)
                    $(".progress-bar").css("width", data.progress);

                if(data.success) 
                    $('#'+upload_id).removeClass('progressing').append('<span class="badge badge-success pull-right">uploaded</span>');
                    glo_upload_id = upload_id;
                    var next_id = $('#'+upload_id).next().eq(0).attr('id');
                    if(next_id) 
                        start_upload(next_id);
                    else
                        loadMoreData(upload_id);
            } else {
                $('#'+upload_id).removeClass('progressing').append('<span class="badge badge-danger pull-right">failed</span>');
                document.getElementById('start_btn').disabled = false;
            }

        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
            $('#'+upload_id).removeClass('progressing').append('<span class="badge badge-danger pull-right">failed</span>');
            document.getElementById('start_btn').disabled = false;
            alert('server not responding...');
        });
    }
</script>