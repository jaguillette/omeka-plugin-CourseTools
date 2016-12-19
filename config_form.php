<h2><?php echo __("Disable deletion of content by type"); ?></h2>
<p class="explanation"><?php echo __("For each type of content, checking the box will hide the interface elements for deleting that kind of content. It is still technically possible for a user to delete that content, but it is not possible to do it accidentally.") ?></p>
<div class="field">
  <div class="two columns alpha">
    <label for="course_tool_disable_item_delete"><?php echo __("Disable Item Deletion"); ?></label>
  </div>
  <div class="inputs five columns omega">
    <?php echo get_view()->formCheckbox('course_tool_disable_item_delete',null,array('checked' => get_option('course_tool_disable_item_delete'))); ?>
  </div>
</div>
<div class="field">
  <div class="two columns alpha">
    <label for="course_tool_disable_collection_delete"><?php echo __("Disable Collection Deletion"); ?></label>
  </div>
  <div class="inputs five columns omega">
    <?php echo get_view()->formCheckbox('course_tool_disable_collection_delete',null,array('checked' => get_option('course_tool_disable_collection_delete'))); ?>
  </div>
</div>
<div class="field">
  <div class="two columns alpha">
    <label for="course_tool_disable_exhibit_delete"><?php echo __("Disable Exhibit Deletion"); ?></label>
  </div>
  <div class="inputs five columns omega">
    <?php echo get_view()->formCheckbox('course_tool_disable_exhibit_delete',null,array('checked' => get_option('course_tool_disable_exhibit_delete'))); ?>
  </div>
</div>
<div class="field">
  <div class="two columns alpha">
    <label for="course_tool_disable_neatline_delete"><?php echo __("Disable Neatline Exhibit Deletion"); ?></label>
  </div>
  <div class="inputs five columns omega">
    <?php echo get_view()->formCheckbox('course_tool_disable_neatline_delete',null,array('checked' => get_option('course_tool_disable_neatline_delete'))); ?>
  </div>
</div>
