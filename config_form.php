<style>
.content-type.permission {display: inline-block;}
.debug {position: absolute; top:45px; left: 20px; z-index: 10; background-color: white;}
.permission input {margin: 0 45%;}
.permission label {width: 33%; display: inline-block;}
.permission label.row-label {width: 100%;}
.permission-label {text-align: center; width: 32.5%; display: inline-block;}
.permission-labels {float: right;}
</style>
<div class="plugin-description">
  <h2>Plugin Description</h2>
  <p>This plugin adds two roles to Omeka, the "student" role and the "reviewer"
    role.</p>
  <p>Student permissions are defined on this page, and are updated as soon as
    they are saved here.</p>
  <p>Reviewer permissions are simply set to more permissive researcher
    permissions. Reviewers can view, but not edit, any content on the site,
    including Exhibits and Neatline exhibits.</p>
</div>
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
            $keyValue = (get_option($keytype)!=null)?get_option($keytype):"own";
            echo get_view()->formRadio($keytype, $keyValue, array('class'=>'columns omega'), array('all'=>'','own'=>'','none'=>''),"");
          ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <?php if (plugin_is_active("SimplePages")): ?>
  <div class="simple-page-switch">
    <h3>Simple Pages</h3>
    <p>Allow students to have access to Simple Pages. If enabled, students will have access to view, edit, and create all Simple Pages.</p>
    <div class="three columns alpha"><label for="simple-page-access">Students can edit all Simple Pages:</label></div>
    <div class="four columns omega">
      <?php echo get_view()->formCheckbox('simple-page-access', null, array('checked'=>get_option('simple-page-access'))); ?>
    </div>
  </div>
  <?php endif; ?>
</div>
