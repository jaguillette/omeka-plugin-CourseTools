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
    $active_content_types = $this->active_content_types;
    $content_types = $this->content_types;
    $permissions = $this->permissions;
    include "config_form.php";
  }

  public function hookConfig($args)
  {
    $this->setOptions($args['post']);
  }

  public function hookAdminHead($args)
  {
    $user = current_user();
    if ($user && $user->getRoleId() == 'student') {
      queue_css_string($this->make_disabling_css());
    }
  }

  public function hookDefineAcl($args)
  {
    $acl = $args['acl'];

    # Define available content_types
    $this->active_content_types = array('Items','Collections');
    if ($acl->has('ExhibitBuilder_Exhibits')) {
      array_push($this->active_content_types, 'ExhibitBuilder_Exhibits');
    }
    if ($acl->has('Neatline_Exhibits')) {
      array_push($this->active_content_types, 'Neatline_Exhibits');
    }

    # Add role, no inherited permissions other than global.
    $acl->addRole('student');

    # Allow students to edit own files, autocomplete tags, access additional elements.
    # These permissions don't fit into the defined permissions, but are needed.
    $acl->allow('student','Files','editSelf');
    $acl->allow('student','Tags',array('autocomplete'));
    $acl->allow('student', 'Elements', 'element-form');

    # Set student permissions on Simple Pages
    if (plugin_is_active("SimplePages")) {
      if (get_option('simple-page-access')) {
        $acl->allow('student', array('SimplePages_Index', 'SimplePages_Page'));
      }
    }

    # Iterate through available content types
    foreach ($this->active_content_types as $content_type) {
      $all_permissions = array();
      $own_permissions = array();
      foreach ($this->permissions as $permission_key => $permission_value) {
        $the_option = "$permission_key|$content_type";
        switch (get_option($the_option)) {
          case 'all':
            if (array_key_exists($content_type,$permission_value['permissions'])) {
              # If there are more specific permissions that need to be applied for this content type, apply them.
              foreach ($permission_value['permissions'][$content_type]['all'] as $resource_permissions) {
                if (array_key_exists('assertion',$resource_permissions)) {
                  $acl->allow('student',$resource_permissions['resource'],$resource_permissions['permissions'],new $resource_permissions['assertion']);
                } else {
                  $acl->allow('student',$resource_permissions['resource'],$resource_permissions['permissions']);
                }
              }
            } else {
              # Otherwise apply simpler default permissions
              $acl->allow('student',$content_type,$permission_value['permissions']['default']['all']);
            }
            break;
          case 'own':
            if (array_key_exists($content_type,$permission_value['permissions'])) {
              # If there are more specific permissions that need to be applied for this content type, apply them.
              foreach ($permission_value['permissions'][$content_type]['own'] as $resource_permissions) {
                if (array_key_exists('assertion',$resource_permissions)) {
                  $acl->allow('student',$resource_permissions['resource'],$resource_permissions['permissions'],new $resource_permissions['assertion']);
                } else {
                  $acl->allow('student',$resource_permissions['resource'],$resource_permissions['permissions']);
                }
              }
            } else {
              # Otherwise apply simpler default permissions
              $acl->allow('student',$content_type,$permission_value['permissions']['default']['own']);
            }
            break;
          default:
            # code...
            break;
        }
      }
    }

    # Add a new role, reviewer, which has more permissions to view non-public content
    $acl->addRole('reviewer','researcher');
    foreach ($this->active_content_types as $active_type) {
      $acl->allow('reviewer', $this->content_types[$active_type]['resources'], 'showNotPublic');
    }

    $acl->allow(array('student','reviewer'), 'Users', null, new Omeka_Acl_Assert_User);
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
      "delete|Items" => "body.items .delete-confirm { display:none; }",
      "delete|Collections" => "body.collections .delete-confirm { display:none; }",
      "delete|ExhibitBuilder_Exhibits" => "body.exhibits .delete-confirm { display:none; }",
      "delete|Neatline_Exhibits" => "table.neatline .delete-confirm { display:none; }",
      "create-edit|Neatline_Exhibits" => "a.add[href$='neatline/add'] { display:none; }",
    );
    $style = "";
    foreach ($disable_array as $key => $value) {
      if (get_option($key)=='none') {
        $style .= $value;
      }
    }
    return $style;
  }

  public $content_types = array(
    'Items'=>array(
      'resources'=>array('Items'),
      'label'=>'Items'
    ),
    'Collections'=>array(
      'resources'=>array('Collections'),
      'label'=>'Collections'
    ),
    'ExhibitBuilder_Exhibits'=>array(
      'resources'=>array('ExhibitBuilder_Exhibits'),
      'label'=>'Exhibit Builder Exhibits'
    ),
    'Neatline_Exhibits'=>array(
      'resources'=>array('Neatline_Exhibits','Neatline_Records'),
      'label'=>'Neatline Exhibits'
    )
  );

  public $active_content_types = array();

  public $permissions = array(
      'view-non-public'=>array(
        'title'=>'View Non-Public...',
        'description'=>"View the resource type, even if its creator hasn't made it publicly accessible.
                        Anyone can always view content that has been made public.",
        'permissions'=>array(
          'default'=>array(
            'all'=>array('showNotPublic'),
            'own'=>array('showSelfNotPublic')
          )
        )
      ),
      'create-edit'=>array(
        'title'=>'Create/Edit...',
        'description'=>"Allow students to create and edit content of this resource type.
                        Granting edit access also grants view access, overriding view permissions.
                        Edit access also grants students the ability to make their work public.",
        'permissions'=>array(
          'Neatline_Exhibits'=>array(
            'all'=>array(
              array(
                'resource'=>'Neatline_Exhibits',
                'permissions'=>array('add','edit','editor','put','import','showNotPublic')
              ),
              array(
                'resource'=>'Neatline_Records',
                'permissions'=>array('post','put','delete','delete-confirm')
              )
            ),
            'own'=>array(
              array(
                'resource'=>'Neatline_Exhibits',
                'permissions'=>array('edit','editor','put','import','showNotPublic'),
                'assertion'=>'Omeka_Acl_Assert_Ownership'
              ),
              array(
                'resource'=>'Neatline_Records',
                'permissions'=>array('post','put'),
                // 'assertion'=>'Neatline_Acl_Assert_RecordOwnership'
                // Assertion above seems to be part of Neatline's permissions, but bugs out when implemented
              ),
              array(
                'resource'=>'Neatline_Exhibits',
                'permissions'=>array('add','showSelfNotPublic','editSelf','editorSelf','putSelf','importSelf','deleteSelf')
              ),
              array(
                'resource'=>'Neatline_Records',
                'permissions'=>array('postSelf','putSelf','delete','delete-confirm')
              )
            )
          ),
          'default'=>array(
            'all'=>array(
              'add',
              'tag',
              'batch-edit',
              'batch-edit-save',
              'change-type',
              'edit',
              'showSelfNotPublic',
              'showNotPublic',
              'makePublic',
              'add-page',               # From Exhibit builder
              'edit-page',              # From Exhibit builder
              'attachment',             # From Exhibit builder
              'attachment-item-options',# From Exhibit builder
              'theme-config',           # From Exhibit builder
              'block-form',             # From Exhibit builder
              'editor',                 # From Neatline
              'put',                    # From Neatline
              'import',                 # From Neatline
              'post',                   # From Neatline
            ),
            'own'=>array(
              'add',
              'tag',
              'batch-edit',
              'batch-edit-save',
              'change-type',
              'editSelf',
              'showSelfNotPublic',
              'makePublic',
              'add-page',               # From Exhibit builder
              'edit-page',              # From Exhibit builder
              'attachment',             # From Exhibit builder
              'attachment-item-options',# From Exhibit builder
              'theme-config',           # From Exhibit builder
              'block-form',             # From Exhibit builder
              'editorSelf',             # From Neatline
              'putSelf',                # From Neatline
              'importSelf',             # From Neatline
              'post',                   # From Neatline
            )
          )
        )
      ),
      'delete'=>array(
        'title'=>"Delete...",
        'description'=>"Allow users to delete content of this resource type.
                        If students are given create/edit access to content,
                        but not delete access, they can create that kind of
                        content, but not delete it. This may be useful in
                        preventing accidental deletion of projects close to
                        deadlines.",
        'permissions'=>array(
          'Neatline_Exhibits'=>array(
            'all'=>array(
              array(
                'resource'=>'Neatline_Exhibits',
                'permissions'=>array('delete','delete-confirm')
              ),
              array(
                'resource'=>'Neatline_Records',
                'permissions'=>array('delete','delete-confirm')
              )
            ),
            'own'=>array(
              array(
                'resource'=>'Neatline_Exhibits',
                'permissions'=>array('delete','delete-confirm'),
                'assertion'=>'Omeka_Acl_Assert_Ownership'
              ),
              array(
                'resource'=>'Neatline_Records',
                'permissions'=>array('delete','delete-confirm'),
                // 'assertion'=>'Neatline_Acl_Assert_RecordOwnership'
                // Assertion above seems to be part of Neatline's permissions, but bugs out when implemented
              )
            )
          ),
          'default'=>array(
            'all'=>array('delete','delete-confirm'),
            'own'=>array('deleteSelf','delete-confirm')
          )
        )
      )
    );
}

?>
