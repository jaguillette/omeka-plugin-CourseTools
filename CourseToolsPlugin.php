<?php

class CourseToolsPlugin extends Omeka_Plugin_AbstractPlugin
{
  protected $_hooks = array(
    'admin_head',
    'config_form',
    'config',
    'define_acl'
  );

  public function hookConfigForm($args)
  {
    include "config_form.php";
  }

  public function hookConfig($args)
  {
    $this->setOptions($args['post']);
  }

  public function hookAdminHead($args)
  {
    queue_css_string($this->make_disabling_css());
  }

  public function hookDefineAcl($args)
  {
    $acl = $args['acl'];

    # Define available content_types
    $content_types = array('Items','Collections');
    if ($acl->has('ExhibitBuilder_Exhibits')) {
      array_push($content_types, 'ExhibitBuilder_Exhibits');
    }
    if ($acl->has('Neatline_Exhibits')) {
      array_push($content_types, 'Neatline_Exhibits');
    }

    # Add new role, student, which inherits from contributor, and allow the role
    # to make own content public.
    $acl->addRole('student','contributor');
    $acl->allow('student', $content_types, 'makePublic', new Omeka_Acl_Assert_Ownership);

    # Add a new role, reviewer, which has more permissions to view non-public content
    $acl->addRole('reviewer','researcher');
    $acl->allow('reviewer', $content_types, 'showNotPublic');
  }

  public function setOptions($options)
  {
    foreach ($options as $key => $value) {
      set_option($key,$value);
    }
  }

  public function make_disabling_css()
  {
    $disable_array = array(
      "course_tool_disable_item_delete" => "body.items .delete-confirm { display:none; }",
      "course_tool_disable_collection_delete" => "body.collections .delete-confirm { display:none; }",
      "course_tool_disable_exhibit_delete" => "body.exhibits .delete-confirm { display:none; }",
      "course_tool_disable_neatline_delete" => "table.neatline .delete-confirm { display:none; }"
    );
    $style = "";
    foreach ($disable_array as $key => $value) {
      if (get_option($key)) {
        $style .= $value;
      }
    }
    return $style;
  }
}

?>
