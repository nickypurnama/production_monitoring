<!-- BEGIN PAGE -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#qrcode-text').keyup(function(){
            if(this.value.length == 12){
                $('#qrcode-submit').click();
            }
        });

        $('#modal-scan').on('shown.bs.modal', function() {
            $('#qrcode-text').trigger('focus');
        });

        function reset() {
          document.getElementById("form-scan").reset();
        }
    });
</script>

<div class="page-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">
                <br>&nbsp;<br>&nbsp;
                <?php
                    $msg=$this->session->flashdata('msg');
                    echo $msg;
                ?>
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> GR Panel</div>
                    </div>
                    <div id="div_search" class="portlet-body table-bottom" style="display: block;">
                        <form id="form-scan" action="<?php echo site_url('transaction/gr_panel_process'); ?>" method="post" class="form-horizontal">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">QR Code</label>
                                        <div class="controls">
                                            <input id="qrcode-text" name="qrcode-text" type="text" required="true" autocomplete="off" autofocus="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <span class="pull-right">
                                    <button class="btn btn-default" onclick="reset()" style="margin-top: 3px;">Reset</button>
                                    <button type="submit" id="qrcode-submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>