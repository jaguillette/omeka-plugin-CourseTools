<style>
  .content-type.permission {
    display: inline-block;
  }
  .debug {
    position: absolute; 
    top:45px; left: 20px; 
    z-index: 10; 
    background-color: white;
  }
  .permission label {
    width: 33%; 
    display: inline-block;
  }
  .permission label.row-label {
    width: 100%;
  }
  .permission-label {
    text-align: center; 
    width: 32.5%; 
    display: inline-block;
  }
  .permission-labels {
    float: right;
  }
  .permission-input {
    width: 32.5%;
    display: inline-block;
    text-align: center;
  }
  .permission-input input {
    display: inline-block;
    float: none;
  }
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
  foreach ($permissions as $permissionName => $permissionProperties): ?>
    <h3><?php echo $permissionProperties['title']; ?></h3>
    <p class="explanation"><?php echo $permissionProperties['description']; ?></p>
    <div class="four columns alpha omega permission-labels">
      <div class="permission-label">All</div>
      <div class="permission-label">Own</div>
      <div class="permission-label">None</div>
    </div>
    <?php foreach ($active_content_types as $contentType):
      $permissionForContentType = "$permissionName|$contentType";
      $label = $content_types[$contentType]['label']; ?>
      <div class="content-type permission">
        <div class="three columns alpha">
          <label class="row-label" for="<?php echo $permissionForContentType; ?>"><?php echo $label; ?></label>
        </div>
        <div class="four columns omega">
          <?php
            $activePermissionForContentType = (get_option($permissionForContentType)!=null) ? get_option($permissionForContentType) : "own";
            foreach(array('all', 'own', 'none') as $permissionOption) {
              $checked = ($activePermissionForContentType == $permissionOption) ? ' checked="checked" ' : '';
              if ($permissionOption == 'none') {
                $ariaLabel = "Do not allow " . $permissionName . " on " . $contentType;
              } else {
                $ariaLabel = "Allow " . $permissionName . " on " . $permissionOption . " " . $contentType;
              }
              echo('<div class="permission-input">
              <input type="radio" name="' . $permissionForContentType . '" value="' . $permissionOption . '" aria-label="' . $ariaLabel . '"' . $checked . '>
              </div>');
            }
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
