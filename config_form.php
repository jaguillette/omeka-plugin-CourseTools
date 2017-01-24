<style>
.content-type.permission {display: inline-block;}
.debug {position: absolute; top:45px; left: 20px; z-index: 10; background-color: white;}
.permission input {margin: 0 45%;}
.permission label {width: 33%; display: inline-block;}
.permission label.row-label {width: 100%;}
.permission-label {text-align: center; width: 32.5%; display: inline-block;}
.permission-labels {float: right;}
</style>
<div class="set-permissions" style="display:inline-block;">
  <h2><?php echo __("Set Permissions for Students"); ?></h2>
  <p class="explanation"><?php echo __("Select what permissions students should have for each type of content."); ?></p>
  <?php
  foreach ($permissions as $key => $value): ?>
    <h3><?php echo $value['title']; ?></h3>
    <p class="explanation"><?php echo $value['description']; ?></p>
    <div class="four columns alpha omega permission-labels">
      <div class="permission-label">All</div>
      <div class="permission-label">Own</div>
      <div class="permission-label">None</div>
    </div>
    <?php foreach ($active_content_types as $type):
      $keytype = "$key|$type";
      $label = $content_types[$type]['label']; ?>
      <div class="content-type permission">
        <div class="three columns alpha">
          <label class="row-label" for="<?php echo $keytype; ?>"><?php echo $label; ?></label>
        </div>
        <div class="four columns omega">
          <?php
            // echo get_view()->formRadio($id, $value, $attribs, $options, $listsep);
            echo get_view()->formRadio($keytype, get_option($keytype), array('class'=>'columns omega'), array('all'=>'','own'=>'','none'=>''),"");
          ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
</div>
